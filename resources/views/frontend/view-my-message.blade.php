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
</head>

<body>	
@php
	$id = Auth::user()->id;
	DB::table('message_user_relation')
                ->where('message_id', $messages->message_id) 
                ->where('receiver_id', $id) 
                ->update(['is_read' => 1]);
@endphp
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
				<div class="mail-details-body">
					@php 
						$d1 = Carbon\Carbon::parse($messages->created_at);
	                    $d2 = Carbon\Carbon::parse(date('h:i:s'));
	                    $mm = $d1->diffInMinutes($d2);
	                    $hh = $d1->diffInHours($d2);
	                    $dd = $d1->diffInDays($d2);
					@endphp
					<div class="header">
						<div class="back-btn">
							<a href="../my-messages"><i class="glyphicon glyphicon-arrow-left"></i></a>
						</div>
						<h2> {{$messages->subject}} </h2>

						<div class="sender-d clearfix">
							<div class="profile-txt clearfix">
								<div class="img">
									<img src="{{asset('public/')}}{{$send_user->image}}" class="img-circle">
								</div>
								<div class="txt">
									<p> <label> {{$send_user->firstname}} {{$send_user->lastname}} </label> <span>{{$send_user->email}} to me</span> </p>
								</div>
							</div>
							<label class="time-date">
								{{\Carbon\Carbon::parse($messages->created_at)->format('H:i')}}
								@if($mm < 60)
									({{$mm}} minutes ago)
								@elseif($hh < 24)
									({{$hh}} hours ago)	
								@else
									({{$dd}} days ago)
								@endif
							</label>
						</div>
					</div>
					<div class="inner-body">
						<div class="formate-mail">
							<p>{{$messages->message}}</p>
						</div>
						<div class="attch-img">
							@foreach($attachment as $src)
							@php $pth = pathinfo($src->attachment)['extension']; @endphp
							@if(($pth == 'jpg') || ($pth == 'png') || ($pth == 'jpeg') || ($pth == 'gif') || ($pth == 'webp'))
								<a href="{{url('download-attachment', $src->id)}}">
								<img src="{{asset('')}}{{$src->attachment}}">
								</a>
							@elseif(($pth == 'pdf'))
								<a href="{{url('download-attachment', $src->id)}}">
								<img src="{{asset('public/images/pdf.png')}}">
								</a>
							@elseif(($pth == 'xlsx'))
								<a href="{{url('download-attachment', $src->id)}}">
								<img src="{{asset('public/images/xlsx.png')}}">
								</a>
							@elseif(($pth == 'docx') || ($pth == 'doc'))
								<a href="{{url('download-attachment', $src->id)}}">
								<img src="{{asset('public/images/docx.png')}}">
								</a>
							@else
								<a href="{{url('download-attachment', $src->id)}}">
								<img src="{{asset('public/images/txt.png')}}">
								</a>
							@endif
							@endforeach
						</div>
					</div>
					<div class="footer">
						<button data-toggle="modal" data-target="#reply-mail"> <i class="glyphicon glyphicon-plus"></i>Reply</button>
						<button data-toggle="modal" data-target="#forward-mail"> <i class="glyphicon glyphicon-plus"></i>Forward</button>
					</div>
<div class="modal fade" id="reply-mail" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <div class="compose-mail">
      	<form enctype="multipart/form-data" action="{{url('send-message')}}" method="post">
                			{{ csrf_field() }}
                			<input type="text" name="message_id" value="{{$messages->message_id}}" hidden>
								<div class="m-header">
									<i class="glyphicon glyphicon-share-alt"></i>
									<div class="select-box">
										<span>To</span>
										<select class="js-multi-select" name="users[]" multiple="multiple">
								@foreach($all_user as $person)
										@if($person['role'] == "Admin" || $person['role'] == "Hr")
											<option value="{{$person['id']}}" {{($person['id'] == $send_user->id)?'selected':''}}>{{$person['firstname']}} {{$person['lastname']}} 
										</option>
										@endif
								@endforeach
										</select>
									</div>
								</div>

								<div class="m-body">
									<div class="sender-d clearfix">
										<div class="profile-txt clearfix">
											<div class="img">
												<img src="{{asset('public/')}}{{$user->image}}" class="img-circle">
											</div>
											<div class="txt">
												<p> <label>{{$user->firstname}} {{$user->lastname}}</label> <span>{{$user->email}}</span> </p>
											</div>
										</div>
										<label class="time-date"></label>
									</div>
									<textarea class="form-control" name="message" placeholder="Write message here..." rows="4" required></textarea>
									<div class="attch-img" id="preview"></div>
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
  <div class="modal fade" id="forward-mail" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <div class="compose-mail">
      	<form enctype="multipart/form-data" action="{{url('send-message')}}" method="post">
                			{{ csrf_field() }}
                			<input type="text" name="message_id" value="{{$messages->message_id}}" hidden>
								<div class="m-header">
									<i class="glyphicon glyphicon-share-alt"></i>
									<div class="select-box">
										<span>To</span>
										<select class="js-multi-select" name="users[]" multiple="multiple">
								@foreach($all_user as $person)
										@if($person['role'] == "Admin")
											<option value="{{$person['id']}}">{{$person['firstname']}} {{$person['lastname']}} 
										</option>
										@endif
								@endforeach
										</select>
									</div>
								</div>

								<div class="m-body">
									<div class="sender-d clearfix">
										<div class="profile-txt clearfix">
											<div class="img">
												<img src="{{asset('public/')}}{{$user->image}}" class="img-circle">
											</div>
											<div class="txt">
												<p> <label>{{$user->firstname}} {{$user->lastname}}</label> <span>{{$user->email}}</span> </p>
											</div>
										</div>
										<label class="time-date"></label>
									</div>
									<textarea class="form-control" name="message" placeholder="Write message here..." rows="4" required></textarea>
									<div class="attch-img" id="preview"></div>
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
				</div>
			</section>
    	</div>
    </div>
</div>
@include('frontend/global/foot')
<script src="{{asset('public/js/passing_training.js')}}"></script>
<script type="text/javascript" src="{{asset('public/js/preview_img.js')}}"></script>
</body>
</html>
