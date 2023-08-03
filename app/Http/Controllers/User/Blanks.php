<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Blank;
use DB;

class Blanks extends Controller
{
 
   public function index()
   {            
      $id = Auth::user()->id;
      $user = DB::table('users')->where('id', $id)->first();
      $blank_files = Blank::all()->toArray();
      return view('frontend/user-blanks',compact('blank_files'),['user' => $user]);
   }

   public function download($id)
    {
      $file = DB::table('blanks')->where('id', $id)->first();
      $name = $file->title;
      $path = public_path('assets/admin/blanks/'.$name);
      if(file_exists($path)){
        return \Response::download($path);
      }
    }
 
}
