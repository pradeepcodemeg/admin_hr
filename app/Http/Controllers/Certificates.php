<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Submit_training;
use App\User;
use ZipArchive;
use File;
use DB;
use PDF;

class Certificates extends Controller
{

    public function certificate(){
        $data = DB::table('submit_trainings')->where('passed', "Passed")->first();
        return view('certificate', ['data' => $data]);
    }

    public function index(Request $request)
    {        
      ini_set('memory_limit', '-1');
     	$trainings = DB::table('trainings')->get();
	    $certificates = DB::table('submit_trainings')->orderBy('id','desc')
                   ->get();      
      ini_set('memory_limit', '32M');  
	    return view('backend/certificates',['trainings' => $trainings, 'certificates' => $certificates]);
    }
 
    public function downloadZip(Request $request)
    {           
        ini_set('memory_limit', '-1');
        $files = glob("public/certificates/$request->id".'/*'); 
        foreach($files as $file){ // iterate files
          if(is_file($file))
            unlink($file); // delete file
        }
        //rereturn '../certification';
        $create_path = 'public/certificates/'.$request->id;
        if (!is_dir($create_path)) {
            File::makeDirectory($create_path);
        }
        if(!empty($request->offset)){
          $offset = $request->offset;
        }else{
          $offset = 0;
        }
        $sql = "SELECT submit_trainings.*,users.id as user_id,users.email FROM `submit_trainings` LEFT JOIN users ON submit_trainings.user_id = users.id WHERE `training_id` = $request->id AND `passed` = Passed LIMIT 100 OFFSET $offset";
        $data = DB::select($sql);        
        if(count($data) == 0){   
            ini_set('memory_limit', '32M'); 
            return redirect()->intended('certificates');
        }
       
        $data_size =  sizeof($data);
        if($data_size != 0){
          for($i=0;$i<$data_size;$i++) 
          { 
          $crt_name = $i + $offset + 1;
          $trn_title = $data[$i]->training_name;
          $im = imagecreatefrompng('public/images/certificate.png');
          $text_color = imagecolorresolve($im, 0, 0, 0);
          $name = $data[$i]->firstname . " " . $data[$i]->lastname;
          $subject = 'Subject Material: ' . $trn_title;
          $year = date("Y");

          

          $hour = date('h:i', strtotime($data[$i]->credit_hours));

          $passing_date = date('Y', strtotime($data[$i]->passing_date));
         // $passing_date = $data[$i]->passing_date;

          $credit_hours_row_text = "For completing {$hour} credit hour(s) of {$passing_date} In-Service Training";
          $date = 'Date: ' . date("m/d/Y", strtotime($data[$i]->passing_date));
          $font_bold_italic = 'public/fonts/timesbi.ttf';
          $font_italic = 'public/fonts/timesi.ttf';
          $font_regular = 'public/fonts/times.ttf';

          $root_dir = str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

          $fl_title = $data[$i]->lastname . "_" . $data[$i]->firstname . '_' . $trn_title . '_' .date("m_Y") . '_' . $crt_name .".jpg";
          
          $cut_folder_name = explode('/','public/certificates');      
          $cut_folder_name = $cut_folder_name[1];
          $usr_srt_folder = $_SERVER['DOCUMENT_ROOT'] . $root_dir. 'public/' . $cut_folder_name."/$request->id".'/'. $fl_title; //this saves the image 
          $srt_folder = $_SERVER['DOCUMENT_ROOT'] . $root_dir. 'public/' . $cut_folder_name."/$request->id".'/'. $fl_title;

          imagettftext($im, 80, 0, 820, 880, $text_color, $font_bold_italic, $name);
          imagettftext($im, 30, 0, 610, 994, $text_color, $font_italic, $credit_hours_row_text);
          imagettftext($im, 28, 0, 610, 1053, $text_color, $font_italic, $subject);
          imagettftext($im, 28, 0, 975, 1110, $text_color, $font_regular, $date);

          imagejpeg($im,$usr_srt_folder,9);
          imagejpeg($im,$srt_folder,9);
          imagedestroy($im);
          }
        }
        $zip = new \ZipArchive();
        $fileName = time()."_".$request->id;   
        if ($zip->open(public_path("certificates/".$request->id."/".$fileName.".zip"), ZipArchive::CREATE) === TRUE)
        {
          $files = File::files(public_path('certificates/'.$request->id));    
            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }
          $zip->close();

          DB::table('certificate_image')->insert(['name' => $fileName.'.zip', 'url' => 'public/certificates/'.$request->id.'/'.$fileName.'.zip']);

          ini_set('memory_limit', '32M'); 
          echo "certificates/".$request->id."/".$fileName.".zip";
        }
    }

    public function certificateCron(){
        $certificates = DB::table('certificate_image')->whereRaw('created_at < DATE_SUB(NOW(),INTERVAL 1 HOUR)')->get()->toArray();
        //$certificates = DB::table('certificate_image')->get()->toArray();
        foreach ($certificates as $img) {
            // if(file_exists($img->url)){
            //     unlink($img->url);                
            // }
           $path = pathinfo($img->url);
          
            $files = glob($path['dirname'].'/*'); // get all file names
           
            foreach($files as $file){ // iterate files
              if(is_file($file))
              $ext = pathinfo($file, PATHINFO_EXTENSION);
              if($ext != 'pdf'){
                  unlink($file); // delete file
              }
              
              //  
            }


        DB::table('certificate_image')->whereRaw('created_at < DATE_SUB(NOW(),INTERVAL 1 HOUR)')->delete($img->id);
        }
    }

}