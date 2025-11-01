<?php
namespace App\Http\Controllers\Web;

use Exception;
use Socialite;
use App\Models\User; 
use App\Mail\Registration;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller; 

class WebAuthController extends Controller
{    
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    } 

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $user = User::where('email', $googleUser->getEmail())->first();
    
            if ($user) {
                if (empty($user->google_id)) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
            }
             if ($user->is_verify == 0) {
                return redirect()->route('web.login')->withErrors([
                    'error' => 'Your account is de-activated.',
                ]);
            }
                Auth::login($user);
                return redirect('/dashboard')->with('success', 'Logged In Successfully');
            } else {
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'role' => 'recruiter',
                ]);
                Auth::login($newUser);
                return redirect('/dashboard')->with('success', 'Account Created and Logged In Successfully');
            }
        } catch (Exception $e) {
            return redirect()->route('web.login')->with('error', 'Something went wrong, please try again.');
        }
    }
    //Sign Up
    public function register(Request $request)
    {   

         // dd($request->all());
        $request->validate([
            'name'     => 'required|string|max:20',
            'phone'    => 'required|string|max:20',
            'email'    => [
             'required',
             'email',
                 Rule::unique('users')->where(function ($query) {
                    return $query->where('role', 'recruiter');
                }),
            ],
            'password' => 'required|min:8',
            'password_confirmation' => 'same:password',
        ]);
         
        // save data to users table with default recruiter role
        User::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'recruiter', 
        ]);
        Mail::to($request->email)->send(new Registration($user->name, $user->email, $user->phone));
        return redirect()->route('web.login')->with('success', 'Registered Successfully');
    }
        public function showLoginForm()
    {
        return view('web.login'); 
    }
    //Login
   public function login(Request $request)
{
    // Step 1: Validation
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|min:8',
    ]);

    // Step 2: Check if email exists
    $user = \App\Models\User::where('email', $request->email)
        ->where('role', 'recruiter') // sirf recruiter allow
        ->first();

    if (!$user) {
        return back()->withErrors([
            'email' => 'This email is not registered.',
        ])->onlyInput('email');
    }
    if ($user->is_verify == 0) {
        return back()->withErrors([
            'error' => 'Your account is de-activated.',
        ]);
    }

    // Step 3: Try login
    if (Auth::attempt([
        'email' => $request->email,
        'password' => $request->password,
        'role' => 'recruiter'
    ], $request->remember)) {
        $request->session()->regenerate();
        return redirect('/dashboard')->with('success', 'Logged In Successfully');
    }

    // Step 4: Wrong password
    return back()->withErrors([
        'password' => 'Incorrect Password',
    ])->onlyInput('email');
}


//Send OTP
public function sendOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    // recruiter email find
    $user = User::where('email', $request->email)
                ->where('role', 'recruiter')
                ->first();

    if (!$user) {
        return back()->withErrors(['email' => 'This email is not registered as recruiter']);
    }

    // 4 digit OTP generate
    $otp = rand(1000, 9999);

    // save OTP in password_resets
DB::table('password_resets')->updateOrInsert(
    ['email' => $request->email],
    [
        'otp' => $otp, 
        'token' => \Str::random(64), 
        'created_at' => now()
    ]
);

      session(['reset_email' => $request->email]);
    // redirect to otp page
    return redirect()->route('web.otp')->with('success', 'OTP Sent Successfully');
}


//Verify OTP
      public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|numeric'
    ]);

    $email = session('reset_email'); 
    $otp = $request->otp;

    $record = DB::table('password_resets')
        ->where('email', $email)
        ->where('otp', $otp)
        ->first();

    if ($record) {
        //  record delete kar do
        DB::table('password_resets')->where('email', $email)->delete();
          session(['reset_email' => $email]);
        return redirect()->route('web.setpassword')
            ->with('success', 'OTP Verified Successfully');
    }

    return back()->with('error', 'Invalid OTP, please try again');
}
   //Resend OTP 
public function resendOtp(Request $request)
{
    $email = $request->email;

    $otp = rand(1000, 9999);

    DB::table('password_resets')->updateOrInsert(
        ['email' => $email],
        [
            'otp' => $otp,
            'token' => \Str::random(64),
            'created_at' => now()
        ]
    );

    return back()->with('success', 'A new OTP has been sent to your email');
}

//Set Password
      public function setPassword(Request $request)
{
    $request->validate([
                    'password' => 'required|min:8',
            'password_confirmation' => 'same:password',
    ]);

    $email = session('reset_email'); 

    if (!$email) {
        return redirect()->route('web.forgotpassword')->with('error', 'Session expired, please try again');
    }

    // update password in users table
    User::where('email', $email)->update([
        'password' => Hash::make($request->password),
    ]);
    session()->forget('reset_email');

    return redirect()->route('web.login')->with('success', 'Password Reset Successfully');
}
   //Logout
   public function logout()
{
    Auth::logout(); 
 
    return redirect()->route('web.login')->with('success', 'Logged Out Successfully');
}


}
