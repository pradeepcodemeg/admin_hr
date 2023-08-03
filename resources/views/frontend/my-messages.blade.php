<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend/global/head')
	<link rel="stylesheet" type="text/css" href="{{asset('public/css/style.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('public/css/responsive.css')}}">
	<!--font awesome 4-->
	<link rel="stylesheet" type="text/css" href="{{asset('public/css/font-awesome.min.css')}}">
	<!--MULTI SELECT-->
	<link rel="stylesheet" href="{{asset('public/css/select2.css')}}" />
	<style type="text/css">
		.pointer:hover{
			cursor: pointer;
		}
	</style>
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    @include('frontend/global/header')
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            @include('frontend/global/sidebar')
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <section class="all-mails-list">
            	<div class="compose-mail-btn">
					<button data-toggle="modal" data-target="#compose-mail"> <i class="glyphicon glyphicon-plus"></i> Compose Mail</button>
				</div>
				@if ($alert = Session::get('sent'))
                <div class="alert alert-success" id="msg-div">
                    {{ $alert }}
                </div>
                @endif
				<div class="mail-list-body">
					<div class="mail-header">
						<h1>Primary</h1>
					</div>
					<div class="mail-body">
						<div class="">
							<table class="table">
								<tbody>
					@foreach ($msg_data as $msg)
						@php 
							$message = DB::table('messages')->where('message_id', $msg->message_id)->first();
							$send_data = DB::table('message_user_relation')->where('message_id',$msg->message_id)->where('receiver_id',Auth::user()->id)->first();
							$user = DB::table('users')->where('id', $send_data->sender_id)->first();
						@endphp
						<tr class="pointer {{($send_data->is_read != 1)?'unseen':''}}" data-href="{{asset('view-my-message/'.$msg->message_id)}}">
							<td class="sender"><span>
								{{$user->firstname}} {{$user->lastname}}
							</span></td>
							<td  class="subject-mg">
								<a>
									<h5> {{$message->subject}} </h5> 
									<p> <span>&nbsp; - &nbsp;</span>{{$message->message}}</p>
								</a>
							</td>
							<td class="date">
								<span>{{\Carbon\Carbon::parse($message->created_at)->format('D: d M yy H:i')}}</span>
							</td>
							<td class="delete-btn" data-id="{{encrypt($msg->message_id)}}">
								<button><i class="glyphicon glyphicon-trash"></i></button>
							</td>
						</tr>
						@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</section>
    	</div>
    </div>
</div>
  <div class="modal fade" id="compose-mail" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <div class="compose-mail">
      	<form enctype="multipart/form-data" action="{{url('send-my-message')}}" method="post">
                {{ csrf_field() }}
			<div class="m-header">
				<i class="glyphicon glyphicon-share-alt"></i>
				<div class="select-box">
					<span>To</span>
					<select class="js-multi-select" name="users[]" multiple="multiple" required>
					@foreach($users as $person)
						@if($person->role == "Admin" || $person->role == "Hr")
							<option value="{{$person->id}}">{{$person->firstname}} {{$person->lastname}} </option>
						@endif
					@endforeach
					</select>
				</div>
			</div>
			<div class="m-body">
				<input type="text" name="subject" class="form-control" placeholder="Add subject here" required>
				<textarea class="form-control" name="message" placeholder="Write message here..." rows="4" required></textarea>
				<div class="attch-img" id="preview">
				</div>
			</div>
			<div class="foot clearfix">
				<div class="left">
					<button class="send" type="submit">Send</button>
					<button class="attachment" type="button">
						<label>
							<input type="file" name="attachment[]" multiple id="img">
							<i class="glyphicon glyphicon-paperclip"></i> 
						</label>
					</button>
				</div>
				<div class="right">
					<button data-dismiss="modal" class="mail-off"><i class="glyphicon glyphicon-trash"></i></button>
				</div>
			</div>
		</form>
		</div>
        </div>
      </div>      
    </div>
  </div>
@include('frontend/global/foot')
<script src="{{asset('public/js/passing_training.js')}}"></script>
<script type="text/javascript" src="{{asset('public/js/preview_img.js')}}"></script>
<script type="text/javascript">
	if($('#all').val() == 'all'){
		console.log("Yess");
	}
</script>
</body>
</html>
