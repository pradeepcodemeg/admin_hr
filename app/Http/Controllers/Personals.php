<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Personal;
use App\User;
use DB;

class Personals extends Controller
{

    public function index(Request $request)
    {  
        if(Auth::check()){        
            $users = DB::table('users')->get();
            $blank_files = Personal::all()->toArray();
            return view('backend/personal',['users' => $users],compact('blank_files'));
       }
    }
    
   public function personal_upload(Request $request, $id)
   {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "640M");
        ini_set("upload_max_size","64M");
        ini_set("post_max_size","64M");
        ini_set("upload_max_filesize","64M");
        ini_set("max_input_time","1000");

        if($file = $request->file('personal_file')){
          $title = $file->getClientOriginalName();
          $user = User::find($id);
          $data = $request->all();

          $person = new Personal();        
          $file->move('public/assets/admin/personal/'.strtolower($user["firstname"])."_".strtolower($user["lastname"]),$title);
          $person->user_id = $id;
          $person->title = $title;
          $person->url = $file;
          $person->save();
          
          return redirect()->intended('personal')->with('upload-success', 'File Uploaded Successfully');
      }

      return redirect()->intended('personal')->with('upload-error', 'File upload unsuccessful');
    }

    public function destroy($id) 
    {
      $file = Personal::find($id);
      $users = DB::table('personals')->where('id', $id)->first();
      $personal_file = DB::table('personals')->where('id', $id)->first();
      $user = DB::table('users')->where('id', $users->user_id)->first();
      if ($file) {
        $file->delete();
      }
      if (file_exists('public/assets/admin/personal/'.strtolower($user->firstname)."_".strtolower($user->lastname)."/".$personal_file->title)) {
        unlink('public/assets/admin/personal/'.strtolower($user->firstname)."_".strtolower($user->lastname)."/".$personal_file->title);
      }    
      return redirect()->intended('personal')->with('delete-msg', 'File Deleted');
    }


  	public function download($id)
    {
        $file = DB::table('personals')->where('id', $id)->first();
        $name = $file->title;
        $user = User::find($file->user_id);
        $path = public_path('assets/admin/personal/'.strtolower($user["firstname"]).'_'.strtolower($user["lastname"]).'/'.$name);
        if (file_exists($path)) {
          return \Response::download($path);
        }        
    }

}