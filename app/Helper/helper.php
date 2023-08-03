<?php
//use DB;

if(!function_exists('send_mail')) {  
  function send_mail($data) {
    try{
        $files = $data['attachment'];        
        $from_email = 'do-not-reply@portal-rgus.com';
        if($data['cc'] == '' && $data['bcc'] == ''){
          \Mail::send($data['email_template'], compact('data'), function ($message) use($data, $files){    
            $message->from($data['from_email'],$data['from_name']);
            $message->to($data['to'])->subject($data['subject']);          
              if(!empty($files) && count($files) > 0) {
                  foreach($files as $file) {
                      $message->attach($file, array(
                          //'as' => 'abc.jpg', // If you want you can chnage original name to custom name      
                          )
                      );
                  }
              }
          });
        }else if($data['cc'] != '' && $data['bcc'] == ''){
          \Mail::send($data['email_template'], compact('data'), function ($message) use($data, $files){    
            $message->from($data['from_email'],$data['from_name']);
            $message->to($data['to'])->cc($data['cc'])->subject($data['subject']);
    
              if(!empty($files) && count($files) > 0) {
                  foreach($files as $file) {
                      $message->attach($file, array(
                         // 'as' => 'abc.jpg', // If you want you can chnage original name to custom name      
                          )
                      );
                  }
              }
          });
        }else if($data['cc'] == '' && $data['bcc'] != ''){
          \Mail::send($data['email_template'], compact('data'), function ($message) use($data, $files){    
            $message->from($data['from_email'],$data['from_name']);
            $message->to($data['to'])->bcc($data['bcc'])->subject($data['subject']);
    
              if(!empty($files) && count($files) > 0) {
                  foreach($files as $file) {
                      $message->attach($file, array(
                          //'as' => 'abc.jpg', // If you want you can chnage original name to custom name      
                          )
                      );
                  }
              }
          });
        }else if($data['cc'] != '' && $data['bcc'] != ''){
          \Mail::send($data['email_template'], compact('data'), function ($message) use($data, $files){    
            $message->from($data['from_email'],$data['from_name']);
            $message->to($data['to'])->cc($data['cc'])->bcc($data['bcc'])->subject($data['subject']);
    
              if(!empty($files) && count($files) > 0) {
                  foreach($files as $file) {
                      $message->attach($file, array(
                          //'as' => 'abc.jpg', // If you want you can chnage original name to custom name      
                          )
                      );
                  }
              }
          });
        }
    }catch(Exception $e){
        
        $values = array('email' => $data['to'],'logs' => $e->getMessage());
        \DB::table('email_failed_logs')->insert($values);
        \Log::error($e->getMessage());
    }
      //return true;
  }
}

?>