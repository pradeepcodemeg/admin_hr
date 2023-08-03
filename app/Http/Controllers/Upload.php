<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Upload extends Controller
{
    function personal(Request $request)
    {    	
    	$file = $request->file('file');
        $destinationPath = 'assets/admin/personal/Mariia Purtova';
        $originalFile = $file->getClientOriginalName();
        $filename=strtotime(date('Y-m-d-H:isa')).$originalFile;
        $uploaded = $file->move($destinationPath, $filename);

		if($uploaded){
    		return redirect()->intended('personal');   	
    	}
    }

    function blanks(Request $request)
    {     	
    	$file = $request->file('blank_file');
        $destinationPath = 'assets/admin/blanks';
        $originalFile = $file->getClientOriginalName();
        $filename=strtotime(date('Y-m-d-H:isa')).$originalFile;
        $uploaded = $file->move($destinationPath, $filename);
		if($uploaded){
    		return redirect()->intended('blanks');   	
    	}
    }

}
