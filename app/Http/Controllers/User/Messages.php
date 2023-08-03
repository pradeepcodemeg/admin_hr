<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use DB;

class Messages extends Controller
{
    public function index(){
    	$id = Auth::user()->id;
      	$user = User::find($id);
        $msg_data = DB::table('message_user_relation')->orderBy('created_at', 'desc')->where('receiver_id', $id)->get();
        $users = DB::table('users')->get();
    	return view('frontend/my-messages',['user' => $user, 'users' => $users, 'msg_data' => $msg_data]);
    }

     public function deleteInboxMessage(Request $request){
        $id = decrypt($request->id);
        $user_id = \Auth::user()->id;
        DB::table('message_user_relation')
                ->where('message_id', $id)->where('receiver_id', $user_id)
                ->update(['receiver_id' => 0]);
        return redirect()->intended('my-messages')->with('sent', 'Message Deleted Successfully!');
    }

    public function deleteOutMessage(Request $request){
        $id = decrypt($request->id);
        $user_id = \Auth::user()->id;        
        DB::table('message_user_relation')
                ->where('message_id', $id)->where('sender_id', $user_id)
                ->update(['sender_id' => 0]);
        return redirect()->intended('my-outbox-messages')->with('sent', 'Message Deleted Successfully!');
    }
    
    public function outbox(){ 
        $id = Auth::user()->id;
      	$user = User::find($id);
        $msg_data = DB::table('message_user_relation')
                    ->orderBy('created_at', 'desc')
                     ->select('message_id')
                     ->where('sender_id', $id)
                     ->groupBy('message_id')
                     ->get();
        $users = DB::table('users')->get();
    	return view('frontend/my-outbox-messages',['user' => $user, 'users' => $users, 'msg_data' => $msg_data]);
    }

    public function viewMessage($id){ 
        $user_id = Auth::user()->id;
        $user = DB::table('users')->where('id', $user_id)->first();
        $all_user = User::all()->toArray();
        $messages = DB::table('messages')->where('message_id', $id)->first();
        $data = DB::table('message_user_relation')->where('message_id', $id)->first();
        $send_user = DB::table('users')->where('id', $data->sender_id)->first();
        $attachment = DB::table('message_attachment')->where('message_id', $id)->get();
        return view('frontend/view-my-message',['messages' => $messages, 'data' => $data, 'send_user' => $send_user, 'user' => $user, 'all_user' => $all_user, 'attachment' => $attachment]);
    }

    public function viewOutboxMessage($id){ 
        $reply = DB::table('message_reply')->where('message_id', $id)->first();
        if(!empty($reply)){
            $reply_msg = DB::table('messages')->where('message_id',$reply->reply_id)->first();
        }else{
            $reply_msg = "";
        }
        $user_id = Auth::user()->id;
        $user = DB::table('users')->where('id', $user_id)->first();
        $all_user = User::all()->toArray();
        $messages = DB::table('messages')->where('message_id', $id)->first();
        $data = DB::table('message_user_relation')->where('message_id', $id)->first();
        $send_user = DB::table('users')->where('id', $data->sender_id)->first();
        $attachment = DB::table('message_attachment')->where('message_id', $id)->get();
        return view('frontend/view-my-outbox-message',['messages' => $messages, 'data' => $data, 'send_user' => $send_user, 'user' => $user, 'all_user' => $all_user, 'attachment' => $attachment, 'reply_msg' => $reply_msg]);
    }

    public function sendMessage(Request $request){
        $user_id = Auth::user()->id;
        $send_data = $request->all();
        if (!empty($send_data['message_id'])) {
            $msg = DB::table('messages')->where('message_id', $send_data['message_id'])->first();
        }       
        $message_id = DB::table('messages')->insertGetId(
            array('subject' => !empty($send_data['subject'])?$send_data['subject']:$msg->subject, 'message' => $send_data['message'], "created_at" =>  \Carbon\Carbon::now(), "updated_at" =>  \Carbon\Carbon::now())
        );
        if(!empty($send_data['users'])){
            foreach ($send_data['users'] as $receiver_id) {  
                if ($receiver_id != 0) {
                    $user = DB::table('users')->where('id', $receiver_id)->first();
                    $fullname = Auth::user()->firstname." ".Auth::user()->lastname;

                    $data['to'] = $user->email;
                    $data['cc'] = '';
                    $data['bcc'] = '';
                    $data['subject'] = "New message from the user."; 
                    $data['attachment'] = array();
                    $data['from_email'] = env('MAIL_USERNAME');
                    $data['from_name'] = $fullname;
                    $data['id'] = Auth::user()->id;
                    $data['user'] = $user;
                    $data['fullname'] = $fullname;
                    $data['email_template'] = 'email/template5';
                    send_mail($data);

                    DB::table('message_user_relation')->insert(
                        ['message_id' => $message_id, 'sender_id' => Auth::user()->id, 'receiver_id' => $receiver_id, "created_at" =>  \Carbon\Carbon::now(), "updated_at" =>  \Carbon\Carbon::now()]
                    );  
                }
            }  
        }
        if(!empty($send_data['attachment'])){
            foreach ($send_data['attachment'] as $file) {
                $title = $file->getClientOriginalName();
                $file->move('public/assets',$title);
                DB::table('message_attachment')->insert(
                    ['message_id' => $message_id, 'attachment' => 'public/assets/'.$title, "created_at" =>  \Carbon\Carbon::now(), "updated_at" =>  \Carbon\Carbon::now()]
                ); 
            } 
        }   
        return redirect()->intended('my-messages')->with('sent', 'Message Sent');
    }

}
