<ul class="nav navbar-nav navbar-right visible-xs">
  	<li class="{{ Request::is('personal') ? 'active' : '' }}"><a href="{{asset('personal')}}">Personal files<span class="sr-only">(current)</span></a></li>
	@if (auth()->user()->role != 'preservice')
		<li class="{{ Request::is('blanks') ? 'active' : '' }}"><a href="{{asset('blanks')}}">Blanks</a></li>
	@endif
	<li class="{{ Request::is('certificates') ? 'active' : '' }}"><a href="{{asset('certificates')}}">Certificates</a></li>
	<li class="{{ Request::is('trainings-and-tests') ? 'active' : (Request::is('edit-training') ? 'active' : (Request::is('add-training') ? 'active' : '')) }}"><a href="{{asset('trainings-and-tests')}}">Trainings and tests</a></li>
	<li class="{{ Request::is('user-management') ? 'active' : (Request::is('add-user') ? 'active' : (Request::is('edit-user') ? 'active' : '')) }}"><a href="{{asset('user-management')}}">User management</a></li>
	<li class="{{ Request::is('statistics') ? 'active' : '' }}"><a href="{{asset('statistics')}}">Statistics</a></li>
	<li class="{{ Request::is('manage-trainings') ? 'active' : '' }}"><a href="{{asset('manage-trainings')}}">Manage Trainings</a></li>
	@if (auth()->user()->role != 'preservice')
	<li class="submenu-parent {{ Request::is('messages') ? 'active' : (Request::is('outbox-messages') ? 'active' : '') }}">
		@php
			$id = Auth::user()->id;
			$read = DB::table('message_user_relation')->where('is_read', 0)->where('receiver_id', $id)->get();
			$nn = count($read);
		@endphp
			<a href="javascript:void(0);">Messages</a>
			<ul class="submenu">
				<li>
					<a href="{{asset('messages')}}">Inbox 
					<b class="float-right">({{$nn}} unread)</b></a>
				</li>
				<li>
					<a href="{{asset('outbox-messages')}}">Outbox</a>
				</li>
			</ul>
		</li>
	@endif
</ul>