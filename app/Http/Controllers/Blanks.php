<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Blank;
use Auth;
use File; 
use DB;

class Blanks extends Controller
{

   public function index()
   {
        $blank_files = Blank::all()->toArray();
        return view('backend/blanks',compact('blank_files'));
   }

   public function upload(Request $request)
   {         
        ini_set('max_execution_time', 300);
        ini_set("memory_limit", "640M");
        ini_set("upload_max_size","64M");
        ini_set("post_max_size","64M");
        ini_set("upload_max_filesize","64M");
        ini_set("max_input_time","1000");

        if($file = $request->file('blank_file')){
          $title = $file->getClientOriginalName(); 
        if($file->move('public/assets/admin/blanks',$title)){
              $blank = new Blank();
              $blank->title = $title;
              $blank->url = $file;
              $blank->save();
        $only_users = DB::table('users')->where(['role' => 'User'])->get();
        foreach ($only_users as $user) {            
            $data['to'] = $user->email;
            $data['cc'] = '';
            $data['bcc'] = '';
            $data['subject'] = "New Blanks Available on Portal-RGUS.com."; 
            $data['attachment'] = array();
            $data['from_email'] = env('MAIL_USERNAME');
            $data['from_name'] = Auth::user()->firstname." ".Auth::user()->lastname;
            $data['id'] = Auth::user()->id;
            $data['user'] = $user;
            $data['email_template'] = 'email/template7';
            send_mail($data);
        }
        return redirect()->intended('blanks')->with('upload-success', 'File Uploaded Successfully');
        } 
        }
    }

    public function destroy($id) 
    {
        $file = Blank::find($id);
        $blank_file = DB::table('blanks')->where('id', $id)->first();
        if (file_exists('public/assets/admin/blanks/'.$blank_file->title)) {
          unlink('public/assets/admin/blanks/'.$blank_file->title);
        }
        if($file){
           $file->delete();
        }               
        return redirect()->intended('blanks')->with('delete-msg', 'File Deleted');
    }

   public function download($id)
    {
        $file = DB::table('blanks')->where('id', $id)->first();
        $name = $file->title;
        $path = public_path('assets/admin/blanks/'.$name);
        if (file_exists($path)) {
         return \Response::download($path);
        }        
    }
 
}
