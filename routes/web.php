<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
  if (\Auth::check()) {
    return Redirect::to('blanks');
  } else {
    return view('login');
  }
});
Route::get('/login', function () {
  if (\Auth::check()) {
    return Redirect::to('blanks');
  } else {
    return view('login');
  }
});
Route::post('post-login', 'AuthController@postLogin');
Route::get('delete-certificate', 'Certificates@certificateCron');
Route::get('test_email', 'AuthController@sendEmail');


Route::group(['middleware' => ['auth']], function () {
  Route::get('my-files', 'User\MyFiles@index');
  Route::post('file-upload/{id}', 'User\MyFiles@file_upload');
  Route::get('download/{id}', 'User\MyFiles@download');
  Route::get('delete/{id}', 'User\MyFiles@destroy');

  Route::get('user-blanks', 'User\Blanks@index');
  Route::get('user-blanks/download/{id}', 'User\Blanks@download');

  Route::get('user-trainings', 'User\Trainings@index');
  Route::post('view-training/{id}', 'User\Trainings@viewTraining');
  Route::post('take-test/{id}', 'User\Trainings@test');
  Route::get('repeat-test/{id}', 'User\Trainings@repeatTest');
  Route::post('show-result/{id}', 'User\Trainings@showResult');
  Route::post('show-results/{id}', 'User\Trainings@showResults');
  Route::post('submit-test/{id}', 'User\Trainings@submitTraining');

  Route::get('my-certificates', 'User\MyFiles@certificates');
  Route::get('download-certificate/{id}', 'User\MyFiles@certificateDownload');

  Route::get('my-outbox-messages', 'User\Messages@outbox');
  Route::get('my-messages', 'User\Messages@index');

  Route::post('send-my-message', 'User\Messages@sendMessage');
  Route::post('send-my-message-again', 'User\Messages@sendMessageAgain');
  Route::get('view-my-message/{id}', 'User\Messages@viewMessage');
  Route::get('view-my-outbox-message/{id}', 'User\Messages@viewOutboxMessage');

  Route::get('delete-my-message/{id}', 'User\Messages@deleteInboxMessage');
  Route::get('delete-my-outbox-message/{id}', 'User\Messages@deleteOutMessage');
  Route::get('email', 'User\MyFiles@template_one');
});


Route::get('messages', 'Messages@index');
Route::get('outbox-messages', 'Messages@outbox');

Route::post('send-message', 'Messages@sendMessage');
Route::post('send-message-again', 'Messages@sendMessageAgain');

Route::get('view-message/{id}', 'Messages@viewMessage');

Route::get('view-outbox-message/{id}', 'Messages@viewOutboxMessage');


Route::get('delete-message/{id}', 'Messages@deleteInboxMessage');
Route::get('delete-outbox-message/{id}', 'Messages@deleteOutMessage');






Route::post('multiple-upload', 'Messages@upload');
Route::get('download-attachment/{id}', 'Messages@download');


Route::group(['middleware' => ['auth', 'checkUser']], function () {

  Route::get('personal', 'Personals@index');
  Route::post('personal-upload/{id}', 'Personals@personal_upload');
  Route::get('personal/download/{id}', 'Personals@download');
  Route::get('personal/delete/{id}', 'Personals@destroy');

  Route::get('blanks', 'Blanks@index');
  Route::post('blanks/upload', 'Blanks@upload');
  Route::get('blanks/download/{id}', 'Blanks@download');
  Route::get('blanks/delete/{id}', 'Blanks@destroy');

  Route::get('certification', 'Certificates@index');
  Route::get('certificates1', 'Certificates@index');
  Route::post('download-zip', 'Certificates@downloadZip');
  //if(Auth::check() && Auth::user()->role != "User"){
  Route::get('trainings-and-tests', 'Trainings@index');
  Route::get('trainings-filter', 'Trainings@fetch_data');
  Route::get('add-training', function () {
    return view('backend/add-training');
  });
  //   }else{
  //       return Redirect::to('blanks');
  //   }
  Route::post('add-training', 'Trainings@addTraining');
  Route::get('edit-training/{id}', 'Trainings@editTraining');
  Route::post('update-training/{id}', 'Trainings@updateTraining');
  Route::get('duplicate-training/{id}', 'Trainings@duplicateTraining');
  Route::post('add-duplicate/{id}', 'Trainings@updateDuplicate');
  Route::get('stop-training/{id}', 'Trainings@stopTraining');
  Route::get('remind-training/{id}', 'Trainings@remindTraining');
  Route::get('trainings-and-tests/delete/{id}', 'Trainings@destroy');

  Route::get('user-management', 'Users@index');
  Route::get('add-user', function () {
    return view('backend/add-user');
  });
  Route::post('add-user', 'AuthController@addUser');
  Route::get('edit-user/{id}', 'AuthController@editUser');
  Route::post('update-user/{id}', 'AuthController@updateUser');
  Route::get('user-management/delete/{id}', 'AuthController@destroy');

  Route::get('statistics', 'Trainings@statistics');
  Route::get('statistics1', 'Trainings@statistics11');
  Route::get('excel-statistics', 'Trainings@excelStatistics');
  Route::get('manage-trainings11', 'Trainings@manageTraining');
  Route::get('manage-trainings', 'Trainings@manageTraining2');
  Route::get('update-result/{id}', 'Trainings@updateResult');
  Route::get('changeStatus', 'Trainings@changeStatus');
  Route::post('pass-users', 'Trainings@passUsers');
  Route::post('download-multiple', 'Trainings@downloadMultiple');
  Route::post('download-multiple-new', 'Trainings@downloadMultipleNew');
  Route::get('failed-users/{id}', 'Trainings@getFailedUsers');
  Route::get('passed-users/{id}', 'Trainings@getPassedUsers');
  Route::get('active-users-new', 'Trainings@getActiveUsers');



  Route::get('certificate', 'Certificates@certificate');
});
Route::get('logout', 'AuthController@logout');
Route::post('reset-password', 'AuthController@passwordReset');
Route::post('set-password', 'AuthController@resetPassword');
