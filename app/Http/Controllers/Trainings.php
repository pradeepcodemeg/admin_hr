<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Submit_training;
use App\Certificate;
use App\Training;
use App\Question;
use App\User;
use Validator, Redirect, Response;
use DataTables;
use ZipArchive;
use Auth;
use PDF;
use File;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class Trainings extends Controller
{

  public function index()
  {
    return view('backend/trainings-and-tests');
  }

  // public function fetch_data(Request $request)
  // {
  //   if (request()->ajax()) {
  //     if ($request->status != '') {
  //       $data = DB::table('trainings')
  //         ->where('status', $request->status)
  //         ->get();
  //     } else {
  //       $data = DB::table('trainings')->get();
  //     }
  //     echo json_encode($data);
  //   }
  // }

  public function fetch_data(Request $request)
  {
    $user_role = auth()->user()->role;
    if (request()->ajax()) {
      if ($request->status != '') {
        if ($user_role == 'Preservice' || $user_role == 'User') {
          $data = DB::table('trainings')
            ->where('status', $request->status)
            ->where(function ($query) use ($user_role) {
              $query->where('assign_role', $user_role)
                ->orWhere('assign_role', 'All');
            })
            ->get();
        } else {
          $data = DB::table('trainings')
            ->where('status', $request->status)
            ->get();
        }
      } else {
        if ($user_role == 'Preservice' || $user_role == 'User') {
          $data = DB::table('trainings')
            ->where(function ($query) use ($user_role) {
              $query->where('assign_role', $user_role)
                ->orWhere('assign_role', 'All');
            })
            ->get();
        } else {
          $data = DB::table('trainings')->get();
        }
      }
      foreach ($data as $row) {
        if ($row->assign_role == 'Supervisor') {
          $row->color   =   'light_pink';
        }
      }
      return response()->json($data);
    }
  }



  public function addTraining(Request $request)
  {
    ini_set('max_execution_time', 0);
    ini_set("memory_limit", "640M");
    ini_set("upload_max_size", "128M");
    ini_set("post_max_size", "128M");
    ini_set("upload_max_filesize", "128M");
    ini_set("max_input_time", "1000");

    request()->validate([
      'training_name' => 'required',
      'training_deadline' => 'required',
      'assign_role' => 'required',
    ]);
    $file = $request->file('file');
    $title = $file->getClientOriginalName();
    $title = time() . "_" . $title;
    $file->move('public/assets/admin/pdf', strtolower(str_replace(" ", "_", $title)));

    $training_data = $request->all();
    $only_users = DB::table('users')->where(['role' => 'User', 'status' => 'Active'])->get();

    foreach ($only_users as $user) {
      $data['to'] = $user->email;
      $data['cc'] = '';
      $data['bcc'] = '';
      $data['subject'] = "New training added to Portal RGUS.";
      $data['attachment'] = array();
      $data['from_email'] = env('MAIL_USERNAME');
      $data['from_name'] = Auth::user()->firstname . " " . Auth::user()->lastname;
      $data['id'] = Auth::user()->id;
      $data['user'] = $user;
      $data['training_data'] = $training_data;
      $data['email_template'] = 'email/template3';
      //send_mail($data);
    }

    if (!empty($training_data['video_file'])) {
      $video_file = $request->file('video_file');
      $video_title = $video_file->getClientOriginalName();
      $video_title = time() . "_" . $video_title;
      $video_file->move('public/assets/admin/video', strtolower(str_replace(" ", "_", $video_title)));
    }

    $training = new Training();

    $training->training_name = $training_data['training_name'];
    $training->training_deadline = $training_data['training_deadline'];
    $training->file = strtolower(str_replace(" ", "_", $title));
    $training->status = !empty($training_data['status']) ? $training_data['status'] : 'Inactive';
    if (!empty($training_data['status']) && $training_data['status'] == 'on') {
      $training->status = "Active";
    }
    $training->youtube_link = !empty($training_data['youtube_link']) ? $training_data['youtube_link'] : "";
    $training->video_file = !empty($training_data['video_file']) ? strtolower(str_replace(" ", "_", $video_title)) : "";
    $training->slide = !empty($training_data['slide']) ? $training_data['slide'] : "";
    $training->minimun_time = $training_data['minimun_time'];
    $training->credit_hours = $training_data['credit_hours'];
    $training->created_by = Auth::user()->id;
    $training->assign_role = $training_data['assign_role'];
    $created = $training->save();

    if ($created) {
      $file = DB::table('trainings')->where('training_name', $request->training_name)->orderBy('created_at', 'desc')->first();
      $create_path = 'public/certificates/' . $file->id;
      if (!is_dir($create_path)) {
        File::makeDirectory($create_path);
      }
      for ($i = 0; $i <= 19; $i++) {
        if (!empty($training_data['question'][$i]) && $training_data['question'][$i] != '') {
          $arr = array(
            'training_id' => $file->id,
            'question' => $training_data['question'][$i],
            'option_one' => $training_data['option_one'][$i],
            'option_two' => $training_data['option_two'][$i],
            'option_three' => $training_data['option_three'][$i],
            'option_four' => $training_data['option_four'][$i],
            'correct_option' => $training_data['correct_option' . $i]
          );
          Question::create($arr);
        }
      }
      return Redirect::to("trainings-and-tests")->with('training-add', 'New Training Added');
    } else {
      return Redirect::to("add-training");
    }
  }

  public function editTraining($id)
  {
    $training_list = Training::find($id);
    return view('backend/edit-training', ['training_list' => $training_list]);
  }

  public function duplicateTraining($id)
  {
    $training_list = Training::find($id);
    $question_list = DB::table('questions')->where('training_id', $id)->get();
    return view('backend/start-training', ['training_list' => $training_list, 'question_list' => $question_list]);
  }

  public function updateDuplicate(Request $request, $id)
  {
    $training_data = $request->except(['_token']);
    // $title = Training::find($id);

    ini_set("memory_limit", "64M");
    ini_set("upload_max_size", "64M");
    ini_set("post_max_size", "64M");
    ini_set("upload_max_filesize", "64M");
    ini_set("max_execution_time", "300");
    ini_set("max_input_time", "1000");

    $file = $request->file('file');
    if (!empty($file)) {
      $title = $file->getClientOriginalName();
      $title = time() . "_" . $title;
      $file->move('public/assets/admin/pdf', strtolower(str_replace(" ", "_", $title)));
    }

    if (!empty($training_data['video_file'])) {
      $video_file = $request->file('video_file');
      $video_title = $video_file->getClientOriginalName();
      $video_title = time() . "_" . $video_title;
      $video_file->move('public/assets/admin/video', strtolower(str_replace(" ", "_", $video_title)));
    }

    if (!empty($training_data['youtube_link'])) {
      $yt = $training_data['youtube_link'];
    } elseif (!empty($title['youtube_link'])) {
      $yt = $title['youtube_link'];
    } else {
      $yt = "";
    }

    if (!empty($training_data['slide'])) {
      $slide = $training_data['slide'];
    } elseif (!empty($title['slide'])) {
      $slide = $title['slide'];
    } else {
      $slide = "";
    }

    if (!empty($training_data['video_file'])) {
      $video_file = strtolower(str_replace(" ", "_", $video_title));
    } elseif (!empty($title['video_file'])) {
      $video_file = $title['video_file'];
    } else {
      $video_file = "";
    }

    if (!empty($title['file'])) {
      $ff = $title['file'];
    } else {
      $ff = "";
    }

    $new_id = DB::table('trainings')->insertGetId(
      array(
        'training_name' => $training_data['training_name'],
        'training_deadline' => $training_data['training_deadline'],
        'file' => !empty($file) ? strtolower(str_replace(" ", "_", $title)) : $ff,
        'status' => $training_data['status'],
        'youtube_link' => $yt,
        'video_file' => $video_file,
        'slide' => $slide,
        'minimun_time' => $training_data['minimun_time'],
        'credit_hours' => $training_data['credit_hours'],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
        'created_by' => Auth::user()->id,
        'assign_role' => $training_data['assign_role']
      )
    );

    if ($new_id) {
      // $file = DB::table('trainings')->where('training_name', $new_id)->first();
      for ($i = 0; $i <= 19; $i++) {
        if (!empty($training_data['question'][$i]) && $training_data['question'][$i] != '') {
          $arr = array(
            'training_id' => $new_id,
            'question' => $training_data['question'][$i],
            'option_one' => $training_data['option_one'][$i],
            'option_two' => $training_data['option_two'][$i],
            'option_three' => $training_data['option_three'][$i],
            'option_four' => $training_data['option_four'][$i],
            'correct_option' => $training_data['correct_option' . $i]
          );
          Question::create($arr);
        }
      }
      return Redirect::to("trainings-and-tests")->with('training-duplicate', 'Duplicate training created successfully');
    } else {
      return Redirect::to("start-training");
    }
  }

  public function updateTraining(Request $request, $id)
  {
    $data = $request->except(['_token']);
    $updated = Training::whereId($id)->update($data);
    if ($updated) {
      DB::table('submit_trainings')
        ->where('training_id', $id)
        ->update(['training_name' => $data['training_name'], 'credit_hours' => $data['credit_hours']]);
      return Redirect::to("trainings-and-tests")->with('training-update', 'Training updated successfully');
    } else {
      return Redirect::to("edit-training/{id}");
    }
  }

  public function stopTraining(Request $request, $id)
  {
    $data = DB::update('UPDATE trainings SET status = "Archive" WHERE id = ?', [$id]);
    return redirect()->intended('trainings-and-tests')->with('training-stop', 'Training has stopped now');
  }

  public function statistics(Request $request)
  {
    ini_set("memory_limit", "256M");
    ini_set("upload_max_size", "256M");
    ini_set("post_max_size", "256M");
    ini_set("upload_max_filesize", "256M");
    ini_set("max_execution_time", "300");
    ini_set("max_input_time", "1000");
    if (request()->ajax()) {
      if (!empty($request->min) && !empty($request->max)) {
        if (!empty(\Session::get('is_trainig_sort'))) {
          $data = DB::table('submit_trainings')
            ->whereIn('training_id', \Session::get('is_trainig_sort'))
            ->whereBetween('passing_date', [$request->min, $request->max])->orderBy('id', 'desc')
            ->get();
        } else {
          $data = DB::table('submit_trainings')
            ->where('passing_date', '>=', $request->min)
            ->where('passing_date', '<=', $request->max)->orderBy('id', 'desc')
            ->get();
        }
      } else {
        if (!empty(\Session::get('is_trainig_sort'))) {
          $data = DB::table('submit_trainings')->whereIn('training_id', \Session::get('is_trainig_sort'))->orderBy('id', 'desc')
            ->get();
        } else {
          $data = DB::table('submit_trainings')->orderBy('id', 'desc')->limit(10)
            ->get();
        }
      }
      return DataTables::of($data)->make(true);
    }
    // echo "<pre>";
    // print_r($_REQUEST['training']);
    // die;
    if (!empty($_REQUEST['training'])) {
      \Session::put('is_trainig_sort', $_REQUEST['training']);
      $trainings = Training::all()->toArray();
      if (!empty($trainings)) {
        $trainings = array_values($trainings);
        $draw_table   =   true;
      }
      $t_id = $request->input('training');
    } else {
      \Session::put('is_trainig_sort', '');
      $trainings = Training::all()->toArray();
      $t_id = "";
      $draw_table   =   false;
    }
    return view('backend/statistics', ['trainings' => $trainings, 't_id' => $t_id, 'draw_table' => $draw_table]);
  }

  public function excelStatistics(Request $request)
  {
    if (!empty($_REQUEST['training'])) {
      $data = DB::table('submit_trainings')->select('firstname', 'lastname', 'training_name', 'passed', 'passing_date', 'credit_hours')->whereIn('training_id', explode(',', $_REQUEST['training']))->get();
    } else {
      $data = DB::table('submit_trainings')->select('firstname', 'lastname', 'training_name', 'passed', 'passing_date', 'credit_hours')->get();
    }
    //$data = $data;
    $timestamp = time();
    $filename = 'Learning management system_' . $timestamp . '.xls';
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $isPrintHeader = false;
    $header = array('First Name', 'Last Name', 'Training Name', 'Passed', 'Passing Date', 'Credit Hours (HH:MM)');
    foreach ($data as $row) {
      $r = (array)$row;
      if (!$isPrintHeader) {
        //echo implode("\t", array_keys($r)) . "\n";
        echo implode("\t", ($header)) . "\n";
        $isPrintHeader = true;
      }
      echo implode("\t", array_values($r)) . "\n";
    }
    //print_r($data);
    die;
  }

  public function manageTraining()
  {
    $passed_users = DB::table('submit_trainings')->where('passed', "Passed")->get();
    $users = User::all()->toArray();
    $trainings = Training::all()->toArray();
    return view('backend/manage-trainings', ['trainings' => $trainings, 'users' => $users, 'passed_users' => $passed_users]);
  }

  public function manageTraining2()
  {
    ini_set("memory_limit", "64M");
    ini_set("upload_max_size", "64M");
    ini_set("post_max_size", "64M");
    ini_set("upload_max_filesize", "64M");
    ini_set("max_execution_time", "300");
    ini_set("max_input_time", "1000");

    $passed_users = DB::table('submit_trainings')->select('firstname', 'lastname', 'training_name', 'passed', 'passing_date', 'credit_hours')->where('passed', "Passed")->get();
    $users = User::all()->toArray();

    $trainings = Training::all()->toArray();
    return view('backend/manage-trainings', ['trainings' => $trainings, 'users' => $users, 'passed_users' => $passed_users]);
  }

  public function destroy($id)
  {
    $file = Training::find($id);
    $file->delete();
    return redirect()->intended('trainings-and-tests');
  }

  public function updateResult($id)
  {
    $data = Submit_Training::find($id);
    $date = date('Y:m:d');
    $cData = DB::table('submit_trainings')->where('id', $id)->first();

    if ($data->passed == "No") {
      $crt_id = $cData->user_id . '_' . $id;

      DB::table('submit_trainings')
        ->where('id', $id)
        ->update(['certificate_id' => $crt_id, 'passing_date' => $date, 'passed' => "Passed"]);
    } elseif ($data->passed == "Passed") {
      DB::table('submit_trainings')
        ->where('id', $id)
        ->update(['certificate_id' => "", 'passing_date' => "", 'passed' => "No"]);
    }
    return redirect()->intended('statistics');
  }

  public function changeStatus(Request $request)
  {
    $data = $request->all();

    if (empty($data['training'])) {
      return Redirect::to('manage-trainings')->with('message', 'Please Select any training');
    }
    $id = $data['training'];
    $file = Training::find($id);

    if (!empty($file)) {
      if (file_exists('public/assets/admin/pdf/' . $file['file'])) {
        unlink('public/assets/admin/pdf/' . $file['file']);
      }
      DB::table('questions')->where('training_id', $id)->delete();
      DB::table('submit_trainings')->where('training_id', $id)->delete();

      $delete_training = $file->delete();

      return redirect()->intended('manage-trainings')->with('alert-delete-success', 'Successfully Deleted Training');
    }

    return redirect()->intended('manage-trainings')->with('alert-delete-success', 'Something went wrong');
  }

  public function getFailedUsers($id)
  {
    if ($id == 0) {
      die("No Users Found");
    }
    $usr = DB::table('submit_trainings')->where(['training_id' => $id, 'passed' => "Passed"])->get();
    $arr = [];
    foreach ($usr as $key => $value) {
      array_push($arr, $value->user_id);
    }
    if (sizeof($usr) == 0) {
      $users = DB::table('users')->where('role', 'User')->get();
    } else {
      $users = DB::table('users')->where('role', 'User')->whereNotIn('id', $arr)->get();
    }
    return view('backend/failed-users', ['users' => $users]);
  }

  public function getPassedUsers($id)
  {
    if ($id == 0) {
      die("No Users Found");
    }
    $users = DB::table('submit_trainings')->where(['training_id' => $id, 'passed' => "Passed"])->get();
    return view('backend/passed-users', ['users' => $users]);
  }

  public function getActiveUsers()
  {
    $users = DB::table('users')->where(['status' => "Active"])->where('id', '<>', 1)->get();
    if ($users->isEmpty()) {
      die("No Users Found");
    }
    return view('backend/passed-users-new', ['users' => $users]);
  }
  public function downloadMultipleNew(Request $request)
  {
    ini_set('max_execution_time', 60);
    ini_set("memory_limit", "640M");

    $req = $request->except(['_token']);

    if (empty($req['users'])) {
      return redirect()->intended('manage-trainings')->with('alert-warning-new', 'Please select user');
    }

    $usr_arr = array_values($req['users']);

    if (sizeof($usr_arr) == 0 || count($usr_arr) == 0) {
      return redirect()->intended('manage-trainings')->with('alert-warning-new', 'Select users first');
    }

    foreach ($usr_arr as $user_id) {
      $training = DB::table('submit_trainings')->where(['user_id' => $user_id, 'passed' => "Passed"])->first();

      if (empty($training)) {
        return redirect()->intended('manage-trainings')->with('alert-warning-new', 'user not submit any training');
      }

      $id = $training->id;
      $files = glob("public/certificates/$id" . '/*'); // get all file names
      foreach ($files as $file) { // iterate files
        if (is_file($file))
          unlink($file); // delete file
      }
      $create_path = 'public/certificates/' . $id;

      if (!is_dir($create_path)) {
        File::makeDirectory($create_path);
      }
    }

    $assets = [];
    $j = 0;
    for ($i = 0; $i < sizeof($usr_arr); $i++) {
      $sql = "SELECT submit_trainings.*,users.id as user_id,users.email from `submit_trainings` LEFT JOIN users on submit_trainings.user_id = users.id WHERE `training_id` = $id and `user_id` = $usr_arr[$i] GROUP BY users.id";
      $data = DB::select($sql);

      if (empty($data)) {
        return Redirect::to('manage-trainings')->with('alert-danger-new', 'Something went wrong');
      }

      $trn_srt_folder_url = "public/certificates";
      $trn_title = $data[0]->training_name;
      $ut_passing_date = $data[0]->passing_date;
      $trn_credit_hours = $data[0]->credit_hours;

      $im = imagecreatefrompng('public/images/certificate.png');
      $text_color = imagecolorresolve($im, 0, 0, 0);
      $black = imagecolorallocate($im, 0, 0, 0);
      $name = $data[0]->firstname . " " . $data[0]->lastname;
      $subject = 'Subject Material: ' . $trn_title;
      $year = date("Y");

      $trn_credit_hours = date('h:i', strtotime($trn_credit_hours));
      $passing_date = date('Y', strtotime($data[0]->passing_date));

      $credit_hours_row_text = "For completing {$trn_credit_hours} credit hour(s) of {$passing_date} In-Service Training";
      $date = 'Date: ' . date("m/d/Y", strtotime($ut_passing_date));
      $font_bold_italic = 'public/fonts/timesbi.ttf';
      $font_italic = 'public/fonts/timesi.ttf';
      $font_regular = 'public/fonts/times.ttf';

      $root_dir = str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);

      $fl_title = $data[0]->lastname . "_" . $data[0]->firstname . '_' . $trn_title . '_' . date("m_Y") . '_' . $i . ".jpg";

      $cut_folder_name = explode('/', $trn_srt_folder_url);
      $cut_folder_name = $cut_folder_name[1];
      $usr_srt_folder = $_SERVER['DOCUMENT_ROOT'] . $root_dir . 'public/' . $cut_folder_name . "/$id" . '/' . $fl_title; //this saves the image 
      $srt_folder = $_SERVER['DOCUMENT_ROOT'] . $root_dir . 'public/' . $cut_folder_name . "/$id" . '/' . $fl_title;

      imagettftext($im, 80, 0, 820, 880, $text_color, $font_bold_italic, $name);
      imagettftext($im, 30, 0, 610, 994, $text_color, $font_italic, $credit_hours_row_text);
      imagettftext($im, 28, 0, 610, 1053, $text_color, $font_italic, $subject);
      imagettftext($im, 28, 0, 975, 1110, $text_color, $font_regular, $date);

      imagejpeg($im, $usr_srt_folder, 9);
      imagejpeg($im, $srt_folder, 9);
      imagedestroy($im);

      if (file_exists($srt_folder)) {
        $assets[$j]['name'] = $fl_title;
        $assets[$j]['url'] = 'public/' . $cut_folder_name . "/$id" . '/' . $fl_title;
        $j++;
      } else {
        return 'Somthing went wrong!';
      }
    }

    $zip = new \ZipArchive();
    $fileName = time() . "_" . $id;

    if ($zip->open(public_path("certificates/" . $id . "/" . $fileName . ".zip"), ZipArchive::CREATE) === TRUE) {
      $files = File::files(public_path('certificates/' . $id));
      foreach ($files as $key => $value) {
        $relativeNameInZipFile = basename($value);
        $zip->addFile($value, $relativeNameInZipFile);
      }
      $zip->close();
    }

    DB::table('certificate_image')->insert(['name' => $fileName . '.zip', 'url' => 'public/certificates/' . $id . '/' . $fileName . '.zip']);

    if (file_exists('public/certificates/' . $id . '/' . $fl_title)) {
      unlink('public/certificates/' . $id . '/' . $fl_title);
    }

    echo "certificates/" . $id . "/" . $fileName . ".zip";

    // return response()->download(public_path("certificates/".$id."/".$fileName.".zip"));
  }
  public function passUsers(Request $request)
  {
    $data = $request->all();

    if (!empty($data['generate_dates_start']) || !empty($data['generate_dates_end'])) {
      $fMin = strtotime($data['generate_dates_start']);
      $fMax = strtotime($data['generate_dates_end']);
      if ($fMax < $fMin) {
        return redirect()->intended('manage-trainings')->with('alert-complete', 'Please choose correct dates');
      }
      $fVal = mt_rand($fMin, $fMax);
      $date = date('Y-m-d', $fVal);
    } else {
      $date = date('Y-m-d');
    }

    if (empty($data['training']) || empty($data['users'])) {
      return redirect()->intended('manage-trainings')->with('alert-complete', 'Please choose required parameters');
    }

    $training_id = $data['training'];
    $users = $data['users'];
    $temp = [];
    foreach ($users as $user_id) {
      $fVal = mt_rand($fMin, $fMax);
      $date = date('Y-m-d', $fVal);
      if (in_array($date, $temp)) {
        $fVal = mt_rand($fMin, $fMax);
        $date = date('Y-m-d', $fVal);
      }
      $temp[] = $date;
      $usr = DB::table('submit_trainings')->where('user_id', $user_id)->where('training_id', $training_id)->first();
      $crt_id = $user_id . '_' . $training_id;
      if (empty($usr) || $usr == '') {
        $u_name = DB::table('users')->where('id', $user_id)->first();
        $t_name = DB::table('trainings')->where('id', $training_id)->first();
        DB::table('submit_trainings')->insert(['user_id' => $user_id, 'training_id' => $training_id, 'certificate_id' => $crt_id, 'firstname' => $u_name->firstname, 'lastname' => $u_name->lastname, 'training_name' => $t_name->training_name, 'passed' => "Passed", 'passing_date' => $date, 'credit_hours' => $t_name->credit_hours, 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')]);
      } else {
        DB::table('submit_trainings')
          ->where('user_id', $user_id)
          ->where('training_id', $training_id)
          ->update(['certificate_id' => $crt_id, 'passing_date' => $date, 'passed' => "Passed"]);
      }
      //sleep(3);
    }

    return redirect()->intended('manage-trainings')->with('alert-complete-success', 'Success');
  }

  public function downloadMultiple(Request $request)
  {
    ini_set('max_execution_time', 0);
    ini_set("memory_limit", "640M");

    $certificates = $request->except(['_token']);

    if (empty($certificates['training']) || empty($certificates['users'])) {
      return redirect()->intended('manage-trainings')->with('alert-warning', 'Please select both training and users');
    }

    $id = $certificates['training'];
    $files = glob("public/certificates/$id" . '/*'); // get all file names
    foreach ($files as $file) { // iterate files
      if (is_file($file))
        unlink($file); // delete file
    }
    $create_path = 'public/certificates/' . $id;
    if (!is_dir($create_path)) {
      File::makeDirectory($create_path);
    }

    $usr_arr = $certificates['users'];
    if (sizeof($usr_arr) == 0 || count($usr_arr) == 0) {
      return redirect()->intended('manage-trainings')->with('alert-warning', 'Select users');
    }
    $usr_arr = array_values($usr_arr);
    $assets = [];
    $j = 0;
    for ($i = 0; $i < sizeof($usr_arr); $i++) {

      $sql = "SELECT submit_trainings.*,users.id as user_id,users.email from    `submit_trainings` LEFT JOIN users on submit_trainings.user_id = users.id WHERE `training_id` = $id and `user_id` = $usr_arr[$i] GROUP BY users.id";
      $data = DB::select($sql);

      if (empty($data)) {
        return Redirect::to('manage-trainings')->with('alert-danger', 'Something went wrong');
      }

      $trn_srt_folder_url = "public/certificates";
      $trn_title = $data[0]->training_name;
      $ut_passing_date = $data[0]->passing_date;
      $trn_credit_hours = $data[0]->credit_hours;

      $im = imagecreatefrompng('public/images/certificate.png');
      $text_color = imagecolorresolve($im, 0, 0, 0);
      $black = imagecolorallocate($im, 0, 0, 0);
      $name = $data[0]->firstname . " " . $data[0]->lastname;
      $subject = 'Subject Material: ' . $trn_title;
      $year = date("Y");

      $trn_credit_hours = date('h:i', strtotime($trn_credit_hours));
      $passing_date = date('Y', strtotime($data[0]->passing_date));

      $credit_hours_row_text = "For completing {$trn_credit_hours} credit hour(s) of {$passing_date} In-Service Training";
      $date = 'Date: ' . date("m/d/Y", strtotime($ut_passing_date));
      $font_bold_italic = 'public/fonts/timesbi.ttf';
      $font_italic = 'public/fonts/timesi.ttf';
      $font_regular = 'public/fonts/times.ttf';

      $root_dir = str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);

      $fl_title = $data[0]->lastname . "_" . $data[0]->firstname . '_' . $trn_title . '_' . date("m_Y") . '_' . $i . ".jpg";

      $cut_folder_name = explode('/', $trn_srt_folder_url);
      $cut_folder_name = $cut_folder_name[1];
      $usr_srt_folder = $_SERVER['DOCUMENT_ROOT'] . $root_dir . 'public/' . $cut_folder_name . "/$id" . '/' . $fl_title; //this saves the image 
      $srt_folder = $_SERVER['DOCUMENT_ROOT'] . $root_dir . 'public/' . $cut_folder_name . "/$id" . '/' . $fl_title;

      imagettftext($im, 80, 0, 820, 880, $text_color, $font_bold_italic, $name);
      imagettftext($im, 30, 0, 610, 994, $text_color, $font_italic, $credit_hours_row_text);
      imagettftext($im, 28, 0, 610, 1053, $text_color, $font_italic, $subject);
      imagettftext($im, 28, 0, 975, 1110, $text_color, $font_regular, $date);

      imagejpeg($im, $usr_srt_folder, 9);
      imagejpeg($im, $srt_folder, 9);
      imagedestroy($im);

      if (file_exists($srt_folder)) {
        $assets[$j]['name'] = $fl_title;
        $assets[$j]['url'] = 'public/' . $cut_folder_name . "/$id" . '/' . $fl_title;
        $j++;
      } else {
        return 'Somthing went wrong!';
      }
    }

    $zip = new \ZipArchive();
    $fileName = time() . "_" . $id;

    if ($zip->open(public_path("certificates/" . $id . "/" . $fileName . ".zip"), ZipArchive::CREATE) === TRUE) {
      $files = File::files(public_path('certificates/' . $id));
      foreach ($files as $key => $value) {
        $relativeNameInZipFile = basename($value);
        $zip->addFile($value, $relativeNameInZipFile);
      }
      $zip->close();
    }

    DB::table('certificate_image')->insert(['name' => $fileName . '.zip', 'url' => 'public/certificates/' . $id . '/' . $fileName . '.zip']);

    if (file_exists('public/certificates/' . $id . '/' . $fl_title)) {
      unlink('public/certificates/' . $id . '/' . $fl_title);
    }

    echo "certificates/" . $id . "/" . $fileName . ".zip";

    // return response()->download(public_path("certificates/".$id."/".$fileName.".zip"));
  }

  public function remindTraining($id)
  {
    $only_users = DB::table('users')->where(['role' => 'User', 'status' => 'Active'])->get();
    $trainings = Training::find($id);

    foreach ($only_users as $user) {
      $data['to'] = $user->email;
      $data['cc'] = '';
      $data['bcc'] = '';
      $data['subject'] = "Training Reminder Portal RGUS.";
      $data['attachment'] = array();
      $data['from_email'] = env('MAIL_USERNAME');
      $data['from_name'] = Auth::user()->firstname . " " . Auth::user()->lastname;
      $data['id'] = Auth::user()->id;
      $data['user'] = $user;
      $data['trainings'] = $trainings;
      $data['email_template'] = 'email/template4';
      send_mail($data);
    }
    return redirect()->intended('trainings-and-tests')->with('training-remind', 'Training reminder notification sent to users');
  }
}
