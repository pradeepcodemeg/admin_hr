<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Submit_training;
use App\Certificate;
use App\Training;
use App\Question;
use Imagick;
use Redirect;
use Session;
use DB;

class Trainings extends Controller
{

    public function index(Request $request)
    {
        $id = Auth::user()->id;
        $user_role  = Auth::user()->role;
        $user = DB::table('users')->where('id', $id)->first();
        $trainings = Training::where('assign_role', $user_role)->orWhere('assign_role', 'All')->get()->toArray();
        return view('frontend/user-trainings', ['trainings' => $trainings, 'user' => $user]);
    }

    public function viewTraining(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $user = DB::table('users')->where('id', $user_id)->first();
        $questions = DB::table('questions')->where('training_id', $id)->get();
        $training = Training::find($id);
        return view('frontend/view-training', ['training' => $training, 'user' => $user, 'questions' => $questions]);
    }

    public function submitTraining(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $user = DB::table('users')->where('id', $user_id)->first();
        $training = DB::table('trainings')->where('id', $id)->first();
        $result = DB::table('submit_trainings')
            ->where('user_id', $user_id)
            ->where('training_id', $id)->first();
        $fullname = Auth::user()->firstname . " " . Auth::user()->lastname;

        $data['to'] = Auth::user()->email;
        $data['cc'] = '';
        $data['bcc'] = '';
        $data['subject'] = "Training Completed Successfully.";
        $data['attachment'] = array();
        $data['from_email'] = env('MAIL_USERNAME');
        $data['from_name'] = 'Portal RGUS Support team';
        $data['id'] = Auth::user()->id;
        $data['training'] = $training;
        $data['fullname'] = $fullname;
        $data['email_template'] = 'email/template2';
        send_mail($data);

        if (empty($result->user_id) || $result->user_id == "" || $result->user_id != $user_id) {
            $crt_id = $user_id . '_' . $id;

            $submit = new Submit_training();

            $submit->user_id = $user_id;
            $submit->training_id = $id;
            $submit->certificate_id = $crt_id;
            $submit->firstname = $user->firstname;
            $submit->lastname = $user->lastname;
            $submit->training_name = $training->training_name;
            $submit->passed = "Passed";
            $submit->passing_date = date('Y-m-d');
            $submit->credit_hours = $training->credit_hours;
            $submit->save();
        }
        if (!empty($result) && $result->user_id == $user_id && $result->passed == "No") {
            $crt_id = $user_id . '_' . $id;
            $date = date('Y:m:d');
            DB::table('submit_trainings')
                ->where('user_id', $user_id)
                ->where('training_id', $id)
                ->update(['certificate_id' => $crt_id, 'passing_date' => $date, 'passed' => "Passed"]);
        } else {
            return redirect()->intended('my-files');
        }

        return redirect()->intended('my-files');
    }

    public function test(Request $request)
    {
        $id = Auth::user()->id;
        $user = DB::table('users')->where('id', $id)->first();
        $questions = DB::table('questions')->where('training_id', $request->id)->get();
        $training = Training::find($request->id);
        return view('frontend/take-test', ['training' => $training, 'user' => $user, 'questions' => $questions]);
    }

    public function repeatTest(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $user = DB::table('users')->where('id', $user_id)->first();
        $training = Training::find($request->id);
        $result = DB::table('submit_trainings')->where('user_id', $user_id)->first();

        $questions = DB::table('questions')->where('training_id', $request->id)->get();

        if (empty($result->user_id) || $result->user_id == "" || $result->user_id != $user_id) {
            $submit = new Submit_training();

            $submit->user_id = $user_id;
            $submit->training_id = $request->id;
            $submit->certificate_id = 0;
            $submit->firstname = $user->firstname;
            $submit->lastname = $user->lastname;
            $submit->training_name = $training->training_name;
            $submit->passed = "No";
            $submit->passing_date = "";
            $submit->credit_hours = $training->credit_hours;
            $submit->save();
        } else {
            return view('frontend/take-test', ['training' => $training, 'user' => $user, 'questions' => $questions]);
        }

        return view('frontend/take-test', ['training' => $training, 'user' => $user, 'questions' => $questions]);
    }

    public function showResult(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $data = $request->all();
        Session::put('test_result', []);
        Session::put('correct_result', []);
        $arr = [];
        for ($i = 0; $i <= 19; $i++) {
            if (!empty($data['question'][$i]) && $data['question'][$i] != '') {
                $ques = DB::table('questions')
                    ->where('question', $data['question'][$i])
                    ->where('training_id', $data['id'])
                    ->first();
                $q_id = $ques->id;
                $arr = array(
                    'question_id' => $q_id,
                    'question' => $ques->question,
                    'options' => array(
                        'option_one' => $ques->option_one,
                        'option_two' => $ques->option_two,
                        'option_three' => $ques->option_three,
                        'option_four' => $ques->option_four
                    ),
                    'selected_option' => !empty($data['selected_option'][$q_id]) ? $data['selected_option'][$q_id] : '',
                    'correct_option' => $ques->correct_option
                );
                Session::push('test_result', $arr);
            }
        }
        $results = Session::get('test_result');
        foreach ($results as $q) {
            foreach ($q['options'] as $key => $op) {
                if (!empty($op)) {
                    if (!empty($q['selected_option'])) {
                        if (in_array($key, array_values($q['selected_option']))) {
                            if ($key == $q['correct_option']) {
                                Session::push('correct_result', $q['correct_option']);
                            }
                        }
                    }
                }
            }
        }
        $corr = Session::get('correct_result');
        $questions = Question::all();
        $training = Training::find($id);
        $percentage = (count($corr) / count($results)) * 100;
        if ($percentage < 75) {
            return view('frontend/failed', ['training' => $training]);
        } else {
            return view('frontend/passed', ['training' => $training]);
        }
    }

    public function showResults($id)
    {
        $results = Session::get('test_result');
        $corr = Session::get('correct_result');
        $questions = Question::all();
        $training = Training::find($id);
        $percentage = (count($corr) / count($results)) * 100;
        return view('frontend/show-result', ['training' => $training, 'results' => $results, 'questions' => $questions, 'corr' => $corr]);
    }
}
