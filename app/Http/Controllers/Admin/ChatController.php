<?php

namespace App\Http\Controllers\Admin;

use Pusher\Pusher;
use App\Models\User;
use App\Models\admin;
use App\Models\ChatMessage;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\ChatFavourite;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function index()
    {
        // dd('ss');
        $data['chatfavourites'] = ChatFavourite::where('admin_deleted', 0)->with('User')->latest()->get();
        return view('admin.Chat.index', compact('data'));
    }
    public function store(Request $request)
    {
        // return response()->json($request);
        $exists = ChatFavourite::where('user_id', $request->user_id)->exists();
        if (!$exists) {
            $chatfavourite = ChatFavourite::create([
                'user_id' => $request->user_id,
                'admin_id' => 1,
            ]);
            $data['chatfavourite'] = $chatfavourite;
            if ($request->hasFile('body')) {
                $filePath = $request->file('body')->store('uploads');

                $data['chatdata'] = ChatMessage::create([
                    'chat_favourites_id' => $chatfavourite->id,
                    'sender_type' => $request->sender_type,
                    'body' => $filePath,
                ]);
            } else {

                $data['chatdata'] = ChatMessage::create([
                    'chat_favourites_id' => $chatfavourite->id,
                    'sender_type' => $request->sender_type,
                    'body' => $request->body,
                ]);
            }
        } else {
            if ($request->hasFile('body')) {
                $filePath = $request->file('body')->store('uploads');

                $data['chatdata'] = ChatMessage::create([
                    'chat_favourites_id' => $request->chatfavourites_id,
                    'sender_type' => $request->sender_type,
                    'body' => $filePath,
                ]);
            } else {
                // return response($request->chat_favourites_id);

                $data['chatdata'] = ChatMessage::create([
                    'chat_favourites_id' => $request->chat_favourites_id,
                    'sender_type' => $request->sender_type,
                    'body' => $request->body,
                ]);

            }
        }

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'encrypted' => true,
            ]
        );
        $pusher->trigger('chat', 'new-message', [
            'message' => $data,
        ]);
        $user_data=User::find($request->user_id);
        $admin_data = admin::first();
        $notification = new Notification();
        $notification->user_id = $request->user_id;
        $notification->sender_id = $admin_data->id;
        $notification->title = 'New Message from Admin';
        $notification->type = 'message';
        $notification->body = $request->body;
        $notification->save();
        $body = array(
            'id' => $notification->id,
            'sender_id' => $admin_data->id,
            'user_id'  => $request->user_id,
            'type'     => 'message',
        );
        $data = [
            'to' => $user_data->fcm_token,
            'notification' => [
                'title' => "New Message from Admin",
                'body' =>  $request->body,
            ],
            'data' => [
                'RequestData' => $body,
            ],
            'content_available' => true,
        ];
        // $SERVER_API_KEY = 'AAAAGAYvVyg:APA91bHn703e-8w6gHludk4Wd8Uj1HjFXYp6933n-ZQx-a8qM_Hu86nJh-XlVv7CBUXikcOICEN1TW4sswuAjjeD7RWaCwttgE3R26ZvLGdwkIgHR9HigoxyZusqQucp-i5vdjyqWww8';
        $SERVER_API_KEY = 'AAAA1U9GgKM:APA91bE_zPJEiZ6IBj_RcFwN_xzOSB7v2osZC9DcWSoYi7nPaDUdPOZRndvEnBiq8U4RgcXaNTIQUUl6-jr5FsHRCWTXUEmbjkbk5myWI_7YYif7Rj9uCqdAwPUoAEmExXkeRVeemhNo';


        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        // dd($response);
        return response($request->body);

    }
    public function get_ChatMessages(Request $request)
    {

        ChatMessage::where('chat_favourites_id', $request->chatfavourite_id)->update(['seen' => 1]);
        $data['chat_favourite'] = ChatFavourite::where('id', $request->chatfavourite_id)->with('User')->first();
        $data['chat_messages'] = ChatMessage::where('chat_favourites_id', $request->chatfavourite_id)->where('admin_deleted', 0)->get();
        return response($data);
    }
// All message deleted by admin

    /** delete the favorite user */
    public function favouriteDeleted(Request $request)
    {
        $user = ChatFavourite::find($request->id);
        if ($user->user_deleted == 0) {
            /** if user not deleded then admin status will be updated */
            $user->update(['admin_deleted' => 1]);
            /** message deleted */
            $data = ChatMessage::where('chat_favourites_id', $request->id)->get();
            foreach ($data as $chat) {
                if ($chat->user_deleted == 0) {
                    $chat->update(['admin_deleted' => 1]);
                } else {
                    $chat->delete();
                }
            }
        } else {
            /** if user delete 1 then hole chat deleted */
            $user->delete();
        }

        return response()->json([
            'success' => 'user deleted successfully',
            'user' => $user,

        ]);
    }

// single message deleted
    public function MessageDeleted(Request $request)
    {
        $user = ChatMessage::find($request->id);
        // return response()->json($user);
        if ($user->user_deleted == 0) {
            $user->update(['admin_deleted' => 1]);
            //return response()->json($user);
        } else {
            $user->delete();
        }
        // $user = ChatMessage::where('admin_deleted',0)->get();
        return response()->json([
            'success' => 'user deleted successfully',
            'user' => $user,
        ]);
        //return redirect()->route('chat.index');
    }
    // all message deleted
    public function AllMessageDeleted(Request $request)
    {
        $users = ChatMessage::where('chat_favourites_id', $request->id)->get();
        //    alert($users);
        //return response()->json($users);
        foreach ($users as $user) {
            if ($user->user_deleted == 0) {
                $user->update(['admin_deleted' => 1]);
                //return response()->json($user);
            } else {
                $user->delete();
            }
        }

        //    $user = ChatMessage::where('admin_deleted',0)->get();

        return response()->json([
            'success' => 'messages deleted successfully',
            'user' => $user,
        ]);
        //return redirect()->route('chat.index');
    }
    //seen message
    public function unreadMessage()
    {

        $users = ChatFavourite::orderBy('created_at', 'desc')->get();
        $unread =[];
        foreach($users as $user){
           $message = ChatMessage::where([['chat_favourites_id',$user->id],['seen', 0], ['sender_type', '!=', 'Admin']])->count();
           $data['chatId']= $user->id;
           $data['message']=$message;
           array_push($unread,$data);
        }


         return response()->json([
              'success' =>'Successfully',
              'data'=>$unread
         ]);
    }

    public function unreadTop(){

    }

}
