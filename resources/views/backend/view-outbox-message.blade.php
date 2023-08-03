<!DOCTYPE html>
<html lang="en">
<head>
    @include('backend/global/head')
	<link rel="stylesheet" type="text/css" href="{{asset('public/css/style.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('public/css/responsive.css')}}">
	<!--font awesome 4-->
	<link rel="stylesheet" type="text/css" href="{{asset('public/css/font-awesome.min.css')}}">
	<!--MULTI SELECT-->
	<link rel="stylesheet" href="{{asset('public/css/select2.css')}}" />
</head>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    @include('backend/global/header')
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            @include('backend/global/sidebar')
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
							<a href="../outbox-messages"><i class="glyphicon glyphicon-arrow-left"></i></a>
						</div>
						<h2> {{$messages->subject}} </h2>
						@php
							$usr = DB::table('message_user_relation')->where(['message_id' => $messages->message_id, 'sender_id' => $user->id])->get();
						@endphp
						<div class="sender-d clearfix">
							<div class="profile-txt clearfix">
								<div class="img">
									<img src="{{asset('public/')}}{{$user->image}}" class="img-circle">
								</div>
								<div class="txt">
									<p> <label> {{$user->firstname}} {{$user->lastname}} </label> <span>{{$user->email}} to 
									@foreach($usr as $send_usr)						
										@php 
											$each = DB::table('users')->where('id', $send_usr->receiver_id)->first();
										@endphp
										@if(!empty($each->firstname) && !empty($each->lastname))
											{{$each->firstname}} {{$each->lastname}},
											@endif
									@endforeach
									</span> </p>
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
						@if($reply_msg != "")
						<div class="mail-details-body">
					<div class="header">
						<div class="sender-d clearfix">
							<div class="profile-txt clearfix">
								<div class="img">
									<img src="{{asset('public/')}}{{$user->image}}" class="img-circle" style="width: 100%;">
								</div>
								<div class="txt top">
									<p> <label> {{$user->firstname}} {{$user->lastname}} </label> <span>{{$user->email}} to me</span> </p>
								</div>
							</div>
						</div>
					</div>

					<div class="inner-body">
						<div class="formate-mail">
							<p>{{$reply_msg->message}}</p>
						</div>
					</div>	
				</div>
				@endif
						<div class="top">
							<div class="formate-mail">
							<p>{{$messages->message}}</p>
						</div>
						<br>
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
					</div>	
				</div>
			</section>
    	</div>
    </div>
</div>
@include('backend/global/foot')
<script src="{{asset('public/js/passing_training.js')}}"></script>
<script type="text/javascript" src="{{asset('public/js/preview_img.js')}}"></script>
</body>
</html>
