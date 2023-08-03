<?php
 
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Personal;
use App\User;
use Validator,Redirect,Response;
use Carbon\Carbon;
use Session;
use Image;
use DB;
 
class AuthController extends Controller
{
     
    public function postLogin(Request $request)
    {
        $errors = new MessageBag;

        request()->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {      
                $user = DB::table('users')->where('email', $request->email)->first();

                if($user->status == 'Inactive'){
                    Auth::logout();
                    $errors = new MessageBag(['password' => ['Credentials are Inactive, please contact with administrator.']]);

                    return Redirect::back()->withErrors($errors);
                }

                if($user->role == 'Admin' || $user->role == 'Hr'){
                    return Redirect::to('personal');
                    // ->with('alert-success', 'You are now logged in as Admin');
                }else {
                    return redirect()->intended('my-files');
                    // ->with('alert-success', 'You are now logged in as a User');
                }
        }
        $errors = new MessageBag(['password' => ['Email and/or Password is invalid']]);

        return Redirect::back()->withErrors($errors);
    }

    public function addUser(Request $request)
    {  
        request()->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'role' => 'required',
            'email' => 'required|email',
            'passwordBig' => 'required',
        ]);
        
        $email = DB::table('users')->where('email', $request->email)->first();
        if ($email) {
           return redirect()->intended('add-user')->with('user-email', 'Email already exists');
        }

        $user_data = $request->all();
        if(!empty($user_data['photoBig'])){
        $filename = time() . '.' . $user_data['photoBig']->getClientOriginalExtension();
        Image::make($user_data['photoBig'])->resize(300, 300)->save( public_path('/images/' . $filename ) );        
        }
        $user = new User();

        $user->firstname = $user_data['firstname'];
        $user->lastname = $user_data['lastname'];
        $user->role = $user_data['role'];
        $user->image = !empty($user_data['photoBig'])?'/images/' . $filename:"/images/default_user_photo.png";
        $user->email = $user_data['email'];
        $user->password = bcrypt($user_data['passwordBig']);
        $user->status = !empty($user_data['status'])?$user_data['status']:'Inactive';
        if(!empty($user_data['status']) && $user_data['status'] == 'on'){
            $user->status = "Active";
        }
        $added = $user->save();

        $data['to'] = $user_data['email'];
        $data['cc'] = '';
        $data['bcc'] = '';
        $data['subject'] = "New User account created for you on rgus-portal.com."; 
        $data['attachment'] = array();
        $data['from_email'] = env('MAIL_USERNAME');
        $data['from_name'] = Auth::user()->firstname." ".Auth::user()->lastname;
        $data['id'] = Auth::user()->id;
        $data['user_data'] = $user_data;
        $data['email_template'] = 'email/template8';
        send_mail($data);

        if($added){
            $path = 'public/assets/admin/personal/'.strtolower($user->firstname)."_".strtolower($user->lastname);
            if (!file_exists($path)) {
                File::makeDirectory($path);
            }
            return Redirect::to("user-management")->with('user-add', 'New Record Added');
        }else {
            return Redirect::to("add-user");
        }
    }

    public function editUser($id)
    {
        $users = User::find($id);
        return view('backend/edit-user',['users'=> $users]);
    }

    public function updateUser(Request $request, $id)
    {          
        $data = $request->all();
        if(!empty($data['photoBig'])){
            $filename = time() . '.' . $data['photoBig']->getClientOriginalExtension();
        Image::make($data['photoBig'])->resize(300, 300)->save( public_path('/images/' . $filename ) );
        }
        $users = DB::table('users')->where('id', $id)->first();
        $password = $users->password;
        $user_data = array(
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'role' => !empty($data['role'])?$data['role']:'Admin',
            'image' => !empty($data['photoBig'])?'/images/' . $filename:$users->image,
            'status' => !empty($data['status'])?'Active':'Inactive',
            'email' => $request->email,
            'password' => !empty($data['passwordBig'])?bcrypt($data['passwordBig']):$password,
        );

        $updated = User::whereId($id)->update($user_data);
        if($updated){
            return Redirect::to("user-management")->with('user-edit', 'User profile updated');
        }else {
            return Redirect::to("edit-user/{id}");
        }
    }

    public function destroy($id) 
    {
        $member = User::find($id);
        $user = DB::table('users')->where('id', $id)->first();
       
        $data['to'] = $user->email;
        $data['cc'] = '';
        $data['bcc'] = '';
        $data['subject'] = "Your User account deleted on rgus-portal.com.";
        $data['attachment'] = array();
        $data['from_email'] = env('MAIL_USERNAME');
        $data['from_name'] = Auth::user()->firstname." ".Auth::user()->lastname;
        $data['id'] = Auth::user()->id;
        $data['user'] = $user;
        $data['email_template'] = 'email/template9';
        send_mail($data);

        $member->delete();        
        DB::table('personals')->where('user_id', $id)->delete();
        DB::table('submit_trainings')->where('user_id', $id)->delete();

        File::deleteDirectory(public_path('assets/admin/personal/'.strtolower($user->firstname)."_".strtolower($user->lastname)));
        return Redirect::to('user-management')->with('user-delete', 'User deleted');
    }

    public function logout() 
    {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }

    public function passwordReset(Request $request)
    {
        $user = DB::table('users')->where('email', $request->email)->first();
        if((!empty($user) || $user != "") && $user->status == "Active"){

            $updated = DB::table('users')->where('email', $request->email)->update(['password' => bcrypt($user->firstname.'@123')]);

            if($updated){
                $data['to'] = $request->email;
                $data['cc'] = '';
                $data['bcc'] = '';
                $data['subject'] = "Your User account password is reset on rgus-portal.com.";
                $data['attachment'] = array();
                $data['from_email'] = env('MAIL_USERNAME');
                $data['from_name'] = 'Portal RGUS Support team';
                $data['id'] = '';
                $data['user'] = $user;
                $data['email_template'] = 'email/template11';
                send_mail($data);                
            }     
            sleep(5);      
            return Redirect::to("login")->with('reset-success', 'New Password is sent to your email, please check');
        }

        return Redirect::to("login")->with('reset-error', 'Please enter registered email');
    }

    public function sendEmail(Request $request){
        $data['to'] = $email = 'ansari.ismael90@gmail.com';
        if($this->mxrecordValidate($email)) {
            echo('This MX records exists; I will accept this email as valid.');
        }
        else {
            echo('No MX record exists;  Invalid email.');
        }
            $data['cc'] = '';
            $data['bcc'] = '';
            $data['subject'] = "Test Message"; 
            $data['attachment'] = array();
            $data['from_email'] = env('MAIL_USERNAME');
            $data['from_name'] = "Ismael";//Auth::user()->firstname." ".Auth::user()->lastname;
            $data['id'] = 1;//Auth::user()->id;
            $data['user'] = "Test";
            $data['email_template'] = 'email/test';
            $data['firstname'] = 'Test22';
            // echo "<pre>";
            // print_r($data);
            // die;

            \Mail::send($data['email_template'], compact('data'), function ($message) use($data){    
        $message->from($data['from_email'],$data['from_name']);
        $message->to($data['to'])->subject($data['subject']);          
          if(!empty($files) && count($files) > 0) {
              
          }
      });
            print_r(\Mail::failures());

           // send_mail($data);
    }
    
    public function mxrecordValidate($email){
        list($user, $domain) = explode('@', $email);
        $arr= dns_get_record($domain,DNS_MX);
        if($arr[0]['host']==$domain&&!empty($arr[0]['target'])){
                return $arr[0]['target'];
        }
    }
 
}
