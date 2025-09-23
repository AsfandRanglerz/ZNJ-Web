<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UserLoginPassword;
use App\Models\EntertainerDetail;
use App\Models\EntertainerEventPhotos;
use App\Models\EntertainerFeatureAdsPackage;
use App\Models\EntertainerPricePackage;
use App\Models\TalentCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class EntertainerController extends Controller
{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()
    {

    }

    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()
    {

        return view('admin.entertainer.add');

    }

    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)
    {
        // return $request;

        $validator = $request->validate([

            'name' => 'required',

            'email' => 'required|unique:users,email|email',

            'phone' => 'required',

            'gender' => 'required',

            'dob' => 'required',

            'nationality' => 'required',

            'country' => 'required',

            'city' => 'required',
            'address' => 'required',

            // 'password'=>'required|confirmed',

            // 'password_confirmation'=>'required'

        ]);

        $password = random_int(10000000, 99999999);

        $data = $request->only(['name', 'email', 'role', 'phone', 'gender', 'dob', 'nationality', 'country', 'city' , 'address' , 'latitude' , 'longitude']);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // Get the file extension
            $filename = time() . '.' . $extension;
            $file->move(public_path('images/'), $filename);
            $data['image'] = 'public/images/' . $filename;
        }
        else{
            $data['image'] = 'public/images/1695640800.png';
        }

        $data['role'] = 'entertainer';

        $data['password'] = Hash::make($password);

        // dd($message);

        $user = User::create($data);

        $message['email'] = $request->email;

        $message['password'] = $password;

        try {

            Mail::to($request->email)->send(new UserLoginPassword($message));

            return redirect()->route('admin.user.index')->with(['status' => true, 'message' => 'Entertainer Created sucessfully']);

        } catch (\Throwable $th) {

            dd($th->getMessage());

            return back()

                ->with(['status' => false, 'message' => $th->getMessage()]);

        }

    }

    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    /** */

    public function show($user_id)
    {

        $data['entertainer'] = EntertainerDetail::with('talentCategory')->with('entertainerFeatureAdsPackage')->where('user_id', $user_id)->latest()->get();

        // dd($data['entertainer']);

        $data['user_id'] = $user_id;

        return view('admin.entertainer.Talent.index', compact('data'));

    }

    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($user_id)
    {

        $entertainer = User::find($user_id);

        return view('admin.entertainer.edit', compact('entertainer'));

    }

    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, $user_id)
    {
        // return $request;
        $request->validate([

            'name' => 'required',

            'email' => 'required|email',

            'phone' => 'required',

            'gender' => 'required',

            'dob' => 'required',

            'nationality' => 'required',

            'country' => 'required',

            'city' => 'required',
            'address' => 'required',

        ]);

        $entertainer = User::find($user_id);

        $entertainer->name = $request->input('name');

        $entertainer->email = $request->input('email');

        $entertainer->phone = $request->input('phone');

        $entertainer->gender = $request->input('gender');

        $entertainer->dob = $request->input('dob');

        $entertainer->nationality = $request->input('nationality');

        $entertainer->country = $request->input('country');

        $entertainer->city = $request->input('city');
        $entertainer->address = $request->input('address');

        $entertainer->latitude = $request->input('latitude');

        $entertainer->longitude = $request->input('longitude');
        if ($request->hasFile('image')) {
            $destination = 'public/images/' . $entertainer->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/images/', $filename);
            $image = 'public/images/' . $filename;
            $entertainer->image = $image;
        }

        $entertainer->update();

        return redirect()->route('admin.user.index')->with(['status' => true, 'message' => 'Entertainer Updated sucessfully']);

    }

    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($user_id)
    {

        User::destroy($user_id);

        return redirect()->back()->with(['status' => true, 'message' => 'Entertainer Deleted sucessfully']);

    }

    /**

     * Show the form for creating a new Talent for Specific Entertainer.

     *

     * @return \Illuminate\Http\Response

     */

    public function createTalentIndex($user_id)
    {

        $data['user_id'] = $user_id;

        $data['talent_categories'] = TalentCategory::select('id', 'category')->get();

        // dd($data['talent_categories']);

        $data['entertainer_feature_ads_packages'] = EntertainerFeatureAdsPackage::select('id', 'title', 'price', 'validity')->get();

        return view('admin.entertainer.Talent.add', compact('data'));

    }

    public function storeTalent(Request $request, $user_id)
    {

        if ($request->has('entertainer_feature_ads_packages_id')) {

            $validator = $request->validate([

                'category_id' => 'required',

                'price' => 'required',

                // 'images'=>'required',

                'entertainer_feature_ads_packages_id' => 'required',

            ], [

                'category_id.required' => 'Category field is required',

            ]);

            $data = $request->only(['user_id', 'price', 'entertainer_feature_ads_packages_id', 'height', 'weight', 'shoe_size', 'own_equipments', 'awards', 'bio', 'description', 'events_completed', 'category_id']);

            $data['feature_status'] = 1;

            $data['user_id'] = $user_id;

            $user = EntertainerDetail::create($data);

            if ($request->file('event_photos')) {

                foreach ($request->file('event_photos') as $data) {

                    $image = hexdec(uniqid()) . '.' . strtolower($data->getClientOriginalExtension());

                    $data->move('public/admin/assets/img/entertainer', $image);

                    EntertainerEventPhotos::create([

                        'event_photos' => '' . $image,

                        'entertainer_details_id' => $user->id,

                    ]);

                }

            }

            return redirect()->route('entertainer.show', $user_id)->with(['status' => true, 'message' => 'Talent Created sucessfully']);

        } else {

            $validator = $request->validate([

                'category_id' => 'required',

                'price' => 'required',

            ], [

                'category_id.required' => 'Category field is required',

            ]);

            $data = $request->only(['user_id', 'price', 'height', 'weight', 'shoe_size', 'own_equipments', 'awards', 'bio', 'description', 'events_completed', 'category_id']);

            $data['feature_status'] = 0;

            $data['user_id'] = $user_id;

            $user = EntertainerDetail::create($data);

            if ($request->file('event_photos')) {

                foreach ($request->file('event_photos') as $data) {

                    $image = hexdec(uniqid()) . '.' . strtolower($data->getClientOriginalExtension());

                    $data->move('public/admin/assets/img/entertainer', $image);

                    EntertainerEventPhotos::create([

                        'event_photos' => '' . $image,

                        'entertainer_details_id' => $user->id,

                    ]);

                }

            }

            return redirect()->route('entertainer.show', $user_id)->with(['status' => true, 'message' => 'Talent Created sucessfully']);

        }

        // return view('admin.entertainer.Talent.add');

    }

    public function destroyTalent($user_id)
    {

        EntertainerDetail::destroy($user_id);

        return redirect()->back()->with(['status' => true, 'message' => 'Talent Deleted sucessfully']);

    }

    public function editTalent($user_id, $entertainer_details_id)
    {

        //$data['user_id'] = EntertainerDetail::find($id);

        $data['entertainer_talent'] = EntertainerDetail::where('id', $entertainer_details_id)->with('talentCategory')->first();

        // dd($data['entertainer_talent']);

        $data['talent_categories'] = TalentCategory::select('id', 'category')->get();

        $data['entertainer_feature_ads_packages'] = EntertainerFeatureAdsPackage::select('id', 'title', 'price', 'validity')->get();

        // $data['user_id'] = $entertainer_details_id;

        $data['user_id'] = $user_id;

        return view('admin.entertainer.Talent.edit', compact('data'));

    }

    public function updateTalent(Request $request, $user_id, $entertainer_details_id)
    {

        // dd($request->input());

        if ($request->entertainer_feature_ads_packages_id !== null && $request->feature_ads === 'on') {

            $validator = $request->validate([

                'category_id' => 'required',

                'price' => 'required',

                // 'images'=>'required',

            ], [

                'category_id.required' => 'Category field is required',

            ]);

            $data = $request->only(['price', 'awards', 'height', 'weight', 'waist', 'shoe_size', 'bio', 'events_completed', 'entertainer_feature_ads_packages_id', 'category_id']);

            $data['feature_status'] = 1;

            $talent = EntertainerDetail::find($entertainer_details_id);

            $talent->update($data);

            return redirect()->route('entertainer.show', $user_id)->with(['status' => true, 'message' => 'Talent Updated successfully']);

        } else if ($request->entertainer_feature_ads_packages_id === null && $request->feature_ads === 'on') {

            // @dd($request->input());

            return redirect()->back()->with(['status' => false, 'message' => 'Feature Package Must Be Selected']);

        } else {

            // @dd($request->input());

            $validator = $request->validate([

                'category_id' => 'nullable',

                'price' => 'required',

                // 'images'=>'required',

            ], [

                'category_id.required' => 'Category field is required',

            ]);

            $data = $request->only(['price', 'awards', 'height', 'weight', 'waist', 'shoe_size', 'bio', 'events_completed', 'category_id']);

            $data['entertainer_feature_ads_packages_id'] = null;

            $data['feature_status'] = 0;

            $talent = EntertainerDetail::find($entertainer_details_id);

            $talent->update($data);

            return redirect()->route('entertainer.show', $user_id)->with(['status' => true, 'message' => 'Talent Updated successfully']);

        }

    }

    public function showPhoto($user_id, $entertainer_details_id)
    {

        //  Showing Entertainer Talent

        $data['photos'] = EntertainerEventPhotos::where('entertainer_details_id', $entertainer_details_id)->latest()->get();

        // dd($data['photos']);

        // dd($data['user_id']);

        $data['user_id'] = $user_id;

        $data['entertainer_details_id'] = $entertainer_details_id;

        // dd($data['user_id']);

        return view('admin.entertainer.Talent.Photo.index', compact('data'));

    }

    //Photo

    public function destroyTalentPhoto($photo_id)
    {

        // dd($photo_id);

        EntertainerEventPhotos::destroy($photo_id);

        return redirect()->back()->with(['status' => true, 'message' => 'Photo Deleted sucessfully']);

    }

    public function editPhoto($user_id, $entertainer_details_id, $photo_id)
    {

        //$data['user_id'] = EntertainerDetail::find($id);

        // dd('sa');

        $data['user_id'] = $user_id;

        $data['entertainer_details_id'] = $entertainer_details_id;

        $data['photo_id'] = $photo_id;

        $data['photo'] = EntertainerEventPhotos::find($photo_id);

        return view('admin.entertainer.Talent.Photo.edit', compact('data'));

    }

    public function updatePhoto(Request $request, $user_id, $entertainer_details_id, $photo_id)
    {

        $validator = $request->validate([

            'event_photos' => 'required',

            // 'description' => 'required',

            // 'images'=>'required',

        ]);

        $photo = EntertainerEventPhotos::find($photo_id);

        if ($request->hasfile('event_photos')) {

            $file = $request->file('event_photos');

            $extension = $file->getClientOriginalExtension(); // getting image extension

            $filename = time() . '.' . $extension;

            $file->move(public_path('admin/assets/img/entertainer/'), $filename);

            $photo->event_photos = '' . $filename;

        }

        $photo->update();

        return redirect()->route('entertainer.photo.show', ['user_id' => $user_id, 'entertainer_details_id' => $entertainer_details_id, 'photo_id' => $photo_id])->with(['status' => true, 'message' => 'Photo Updated sucessfully']);

    }

    /**

     * Talent Categories

     *

     *

     *

     */

    public function talentCategoriesIndex()
    {

        $data = TalentCategory::select('id', 'category')->latest()->get();

        return view('admin.Categories.Talent.index', compact('data'));

    }

    public function talentCategoryStore(Request $request)
    {

        $validator = $request->validate([

            'category' => 'required',

        ]);

        $data = $request->only(['category']);

        $data = TalentCategory::create($data);

        return redirect()->route('entertainer.talent.categories.index')->with(['status' => true, 'message' => 'Talent Category Created sucessfully']);

    }

    public function talentCategoryEditIndex($category_id)
    {

        $data = TalentCategory::select('id', 'category')->where('id', $category_id)->first();

        return view('admin.Categories.Talent.edit', compact('data'));

    }

    public function updateTalentCategory(Request $request, $category_id)
    {

        $validator = $request->validate([

            'category' => 'required',

        ]);

        $talent_category = TalentCategory::find($category_id);

        $talent_category->category = $request->category;

        $talent_category->update();

        return redirect()->route('entertainer.talent.categories.index')->with(['status' => true, 'message' => 'Talent Category Updated sucessfully']);

    }

    public function destroyTalentCategory($category_id)
    {

        TalentCategory::destroy($category_id);

        return redirect()->back()->with(['status' => true, 'message' => 'Category Deleted sucessfully']);

    }

    public function pricePackagesIndex($user_id, $entertainer_details_id)
    {

        $data['price_packages'] = EntertainerPricePackage::where('entertainer_details_id', $entertainer_details_id)->latest()->get();

        $data['entertainer_details_id'] = $entertainer_details_id;

        $data['user_id'] = $user_id;

        return view('admin.entertainer.Talent.Price_packages.index', compact('data'));

    }

    public function createPricePackageIndex($user_id, $entertainer_details_id)
    {

        $data['entertainer_details_id'] = $entertainer_details_id;

        $data['user_id'] = $user_id;

        return view('admin.entertainer.Talent.Price_packages.add', compact('data'));

    }

    public function storePricePackage(Request $request, $user_id, $entertainer_details_id)
    {

        $validator = $request->validate([

            'price_package' => 'required',

            'time' => 'required',

        ]);

        $data = $request->only(['price_package', 'time']);

        $data['entertainer_details_id'] = $entertainer_details_id;

        $user = EntertainerPricePackage::create($data);

        return redirect()->route('entertainer.talent.price_packages.index', ['user_id' => $user_id, 'entertainer_details_id' => $entertainer_details_id])->with(['status' => true, 'message' => 'Price Package Created Sucessfully']);

    }

    public function editPricePackageIndex($user_id, $price_package_id)
    {

        $data['price_package'] = EntertainerPricePackage::where('id', $price_package_id)->first();

        $data['user_id'] = $user_id;

        return view('admin.entertainer.Talent.Price_packages.edit', compact('data'));

    }

    public function updatePricePackage(Request $request, $user_id, $price_package_id)
    {

        $validator = $request->validate([

            'price_package' => 'required',

            'time' => 'required',

        ]);

        // dd($request->time);

        $price_package = EntertainerPricePackage::find($price_package_id);

        $price_package->price_package = $request->input('price_package');

        $price_package->time = $request->input('time');

        $price_package->update();

        return redirect()->route('entertainer.talent.price_packages.index', ['user_id' => $user_id, 'entertainer_details_id' => $price_package['entertainer_details_id']])->with(['status' => true, 'message' => 'Price Package Updated Sucessfully']);

    }

    public function destroyPricePackage($price_package_id)
    {

        EntertainerPricePackage::where('id', $price_package_id)->delete();

        return redirect()->back()->with(['status' => true, 'message' => 'Price Package Deleted Sucessfully']);

    }

}
