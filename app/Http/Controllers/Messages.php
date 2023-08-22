<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\MessageUserRelation;
use Auth;
use DB;
use App\Mail\sendSMs;
use Illuminate\Support\Facades\Mail;

class Messages extends Controller
{
    public function index()
    {
        $id = Auth::user()->id;
        $users = User::where('status', 'Active')->get()->toArray();
        $msg_data = DB::table('message_user_relation')
            ->join('users as sender', 'message_user_relation.sender_id', '=', 'sender.id')
            ->join('users as receiver', 'message_user_relation.receiver_id', '=', 'receiver.id')
            ->orderBy('message_user_relation.created_at', 'desc')
            ->where('message_user_relation.receiver_id', $id)
            ->distinct()
            ->paginate(20);
        // dd($msg_data);
        $messages = DB::table('messages')->get();
        return view('backend/messages', ['users' => $users, 'messages' => $messages, 'msg_data' => $msg_data]);
    }

    public function deleteInboxMessage(Request $request)
    {
        $id = decrypt($request->id);
        $user_id = \Auth::user()->id;
        DB::table('message_user_relation')
            ->where('message_id', $id)->where('receiver_id', $user_id)
            ->update(['receiver_id' => 0]);
        return redirect()->intended('messages')->with('sent', 'Message Deleted Successfully!');
    }

    public function deleteOutMessage(Request $request)
    {
        $id = decrypt($request->id);
        $user_id = \Auth::user()->id;
        DB::table('message_user_relation')
            ->where('message_id', $id)->where('sender_id', $user_id)
            ->update(['sender_id' => 0]);
        return redirect()->intended('outbox-messages')->with('sent', 'Message Deleted Successfully!');
    }

    public function outbox()
    {
        $id = Auth::user()->id;
        $users = User::where('status', 'Active')->get()->toArray();
        $msg_data = DB::table('message_user_relation')
            ->orderBy('created_at', 'desc')
            ->select('message_id')
            ->where('sender_id', $id)
            ->groupBy('message_id')
            ->get();
        return view('backend/outbox-messages', ['users' => $users, 'msg_data' => $msg_data]);
    }

    public function sendMessage(Request $request)
    {
        $send_data = $request->all();
        
        if (empty($send_data['message'])) {
            return response(['fail' => 'The message field is required.']);
        }

        $user_id = Auth::user()->id;
        if (!empty($send_data['message_id'])) {
            $msg = DB::table('messages')->where('message_id', $send_data['message_id'])->first();
        }
        $message_id = DB::table('messages')->insertGetId([
            'subject' => !empty($send_data['subject']) ? $send_data['subject'] : $msg->subject,
            'message' => $send_data['message'],
            "created_at" =>  \Carbon\Carbon::now(),
            "updated_at" =>  \Carbon\Carbon::now()
        ]);

        if (!empty($send_data['users'])) {
            foreach ($send_data['users'] as $receiver_id) {
                if ($receiver_id != 0) {
                    $user = DB::table('users')->where('id', $receiver_id)->first();

                    $data['to'] = $user->email;
                    $data['cc'] = '';
                    $data['bcc'] = '';
                    $data['subject'] = "New message on Portal RGUS.";
                    $data['attachment'] = array();
                    $data['from_email'] = env('MAIL_USERNAME');
                    $data['from_name'] = Auth::user()->firstname . " " . Auth::user()->lastname;
                    $data['id'] = Auth::user()->id;
                    $data['user'] = $user;
                    $data['email_template'] = 'email/template6';

                    send_mail($data);

                    // Mail::to($data['to'])->send(new sendSMs($data));

                    DB::table('message_user_relation')->insert(
                        ['message_id' => $message_id, 'sender_id' => $user_id, 'receiver_id' => $receiver_id, "created_at" =>  \Carbon\Carbon::now(), "updated_at" =>  \Carbon\Carbon::now()]
                    );
                }
            }
        }

        if (!empty($send_data['attachment'])) {
            foreach ($send_data['attachment'] as $file) {
                $title = $file->getClientOriginalName();
                $file->move('public/assets', $title);
                DB::table('message_attachment')->insert(
                    ['message_id' => $message_id, 'attachment' => 'public/assets/' . $title, "created_at" =>  \Carbon\Carbon::now(), "updated_at" =>  \Carbon\Carbon::now()]
                );
            }
        }
        return redirect()->intended('messages')->with('sent', 'Message Sent');
    }

    public function viewMessage($id)
    {
        $users = User::get()->toArray();
        $messages = DB::table('messages')->where('message_id', $id)->first();
        $data = DB::table('message_user_relation')->where('message_id', $id)->first();
        $user = DB::table('users')->where('id', $data->sender_id)->first();
        $attachment = DB::table('message_attachment')->where('message_id', $id)->get();
        return view('backend/view-message', ['messages' => $messages, 'data' => $data, 'user' => $user, 'users' => $users, 'attachment' => $attachment]);
    }

    public function viewOutboxMessage($id)
    {
        $reply = DB::table('message_reply')->where('message_id', $id)->first();
        if (!empty($reply)) {
            $reply_msg = DB::table('messages')->where('message_id', $reply->reply_id)->first();
        } else {
            $reply_msg = "";
        }
        $users = User::where('status', 'Active')->get()->toArray();
        $messages = DB::table('messages')->where('message_id', $id)->first();
        $data = DB::table('message_user_relation')->where('message_id', $id)->first();
        $user = DB::table('users')->where('id', $data->sender_id)->first();
        // echo "<pre>";
        // print_r($user);
        // die;
        $attachment = DB::table('message_attachment')->where('message_id', $id)->get();
        return view('backend/view-outbox-message', ['messages' => $messages, 'data' => $data, 'user' => $user, 'users' => $users, 'attachment' => $attachment, 'reply_msg' => $reply_msg]);
    }

    public function download($id)
    {
        $file = DB::table('message_attachment')->where('id', $id)->first();
        return \Response::download($file->attachment);
    }
}
