<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Submit_training;
use App\Personal;
use App\User;
use Imagick;
use View;
use File;
use DB;
use PDF;

class MyFiles extends Controller
{

    public function index(Request $request)
    {
        if(Auth::check()){     
            $id = Auth::user()->id;
            $users = User::all()->toArray();
            $user = DB::table('users')->where('id', $id)->first();
            $blank_files = DB::table('personals')->where('user_id', $id)->get();
            return view('frontend/my-files', ['user' => $user,'blank_files' => $blank_files, 'users' => $users]);
          }
            return Redirect::to("login")->withSuccess('You do not have access');
    }
    
    public function file_upload(Request $request, $id)
   {
        $mail_data = $request->all();
        
        foreach ($mail_data['users'] as $user) {

            $mail_users = User::find($user);
            $fullname = Auth::user()->firstname." ".Auth::user()->lastname;
            
            $data['to'] = $mail_users['email'];
            $data['cc'] = '';
            $data['bcc'] = '';
            $data['subject'] = "User uploaded new file for you."; 
            $data['attachment'] = array();
            $data['from_email'] = env('MAIL_USERNAME');
            $data['from_name'] = $fullname;
            $data['id'] = Auth::user()->id;
            $data['mail_users'] = $mail_users;
            $data['fullname'] = $fullname;
            $data['email_template'] = 'email/template1';
            send_mail($data);
        }        

        if($file = $request->file('personal_file')){
          $title = $file->getClientOriginalName();
          $user = User::find($id);
          $person = new Personal();        
          $file->move('public/assets/admin/personal/'.strtolower($user["firstname"])."_".strtolower($user["lastname"]),$title);
          $person->user_id = $id;
          $person->title = $title;
          $person->url = $file;
          $person->save();
          return redirect()->intended('my-files')->with('upload-success', 'File Uploaded Successfully');
        }

        return redirect()->intended('my-files')->with('upload-error', 'File upload unsuccessful');
    }

    public function destroy($id) 
    {
      $file = Personal::find($id);
      $user_id = Auth::user()->id;
      $personal_file = DB::table('personals')->where('id', $id)->first();
      $user = DB::table('users')->where('id', $user_id)->first();

      if (file_exists('public/assets/admin/personal/'.strtolower($user->firstname)."_".strtolower($user->lastname)."/".$personal_file->title)) {
          unlink('public/assets/admin/personal/'.strtolower($user->firstname)."_".strtolower($user->lastname)."/".$personal_file->title);
      }
      if($file){
         $file->delete();
      }

      return redirect()->intended('my-files')->with('delete-msg', 'File Deleted');
    }

    public function download($id)
    {
        $file = DB::table('personals')->where('id', $id)->first();
        $name = $file->title;
        $user = User::find($file->user_id);
        $path = public_path('assets/admin/personal/'.strtolower($user["firstname"]).'_'.strtolower($user["lastname"]).'/'.$name);
        if(file_exists($path)){
          return \Response::download($path);
        }
    }


    public function certificates(Request $request)
    {
        $id = Auth::user()->id;
        $user = DB::table('users')->where('id', $id)->first();
        $certificates = DB::table('submit_trainings')->where('user_id', $id)->where('passed', 'Passed')->orderBy('training_id', 'asc')->get();
        return view('frontend/my-certificates', ['user' => $user, 'certificates' => $certificates]);
    } 

    public function certificateDownload($id)
    {   
       // return redirect()->intended('my-certificates');
      ini_set('max_execution_time', 10000);
      ini_set("memory_limit", "6400M");
      
      $data = DB::table('submit_trainings')->where('certificate_id',$id)->first();
      $user = DB::table('users')->where('id', $data->user_id)->first();

      $usr_id = $data->user_id;
      $trn_id = $data->training_id;
      $usr_first_name = $data->firstname;
      $usr_last_name = $data->lastname;
      $usr_email = $user->email;
      $trn_srt_folder_url = "public/trn_cert";
      $trn_slides_timeout = "00:03";
      $trn_title = $data->training_name;
      $ut_passing_date = $data->passing_date;
      $trn_credit_hours = $data->credit_hours;

      $im = imagecreatefrompng('public/images/certificate.png');
      $text_color = imagecolorresolve($im, 0, 0, 0);
      $black = imagecolorallocate($im, 0, 0, 0);
      $name = $usr_first_name . " " . $usr_last_name;
      $subject = 'Subject Material: ' . $trn_title;
      $year = date("Y");

      $trn_credit_hours = date('h:i', strtotime($trn_credit_hours));

      $credit_hours_row_text = "For completing {$trn_credit_hours} credit hour(s) of {$year} In-Service Training";
      $date = 'Date: ' . date("m/d/Y", strtotime($ut_passing_date));
      $font_bold_italic = 'public/fonts/timesbi.ttf';
      $font_italic = 'public/fonts/timesi.ttf';
      $font_regular = 'public/fonts/times.ttf';

      $root_dir = str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

      $fl_title = $usr_last_name . "_" . $usr_first_name . '_' . $trn_title . '_' .date("m_Y") . ".jpg";

      $folder = $_SERVER['DOCUMENT_ROOT'].$root_dir.'public/trn_cert/' . $usr_id;
      if(!is_dir($folder)) {        
          mkdir($folder,0777,true);
      }
      
      $fl_url = url('/') . 'public/trn_cert/' . $usr_id . '/' . $fl_title;
      $cut_folder_name = explode('/', $trn_srt_folder_url);      
      $cut_folder_name = $cut_folder_name[1];
      $srt_url = url('/')  . $cut_folder_name;
      $usr_srt_folder = $_SERVER['DOCUMENT_ROOT'] . $root_dir. 'public/' . $cut_folder_name."/$usr_id".'/'. $fl_title; //this saves the image 
      $srt_folder = $_SERVER['DOCUMENT_ROOT'] . $root_dir. 'public/' . $cut_folder_name."/$usr_id".'/'. $fl_title;

      imagettftext($im, 80, 0, 820, 880, $text_color, $font_bold_italic, $name);
      imagettftext($im, 30, 0, 610, 994, $text_color, $font_italic, $credit_hours_row_text);
      imagettftext($im, 28, 0, 610, 1053, $text_color, $font_italic, $subject);
      imagettftext($im, 28, 0, 975, 1110, $text_color, $font_regular, $date);

      imagejpeg($im,$usr_srt_folder,9);
      imagejpeg($im,$srt_folder,9);
      imagedestroy($im);

      if(file_exists($srt_folder)){
        DB::table('certificate_image')->insert(['name' => $fl_title, 'url' => 'public/' . $cut_folder_name."/$usr_id".'/'. $fl_title]);
        return \Response::download($srt_folder);
      }else{
        return 'Somthing went wrong!';
      }

     } 

}