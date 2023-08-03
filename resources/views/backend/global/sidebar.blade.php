<ul class="nav nav-sidebar">
	<li class="{{ Request::is('personal') ? 'active' : '' }}"><a href="{{asset('personal')}}">Personal files<span class="sr-only">(current)</span></a></li>
	@if (auth()->user()->role != 'preservice')
		<li class="{{ Request::is('blanks') ? 'active' : '' }}"><a href="{{asset('blanks')}}">Blanks</a></li>
	@endif
	<li class="{{ Request::is('certification') ? 'active' : '' }}"><a href="{{asset('certification')}}">Certificates</a></li>
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
			<ul class="submenu" {{ Request::is('messages') ? 'style=display:block;' : (Request::is('outbox-messages') ? 'style=display:block;' : '') }}>
				<li>
					<a href="{{asset('messages')}}" {{ Request::is('messages') ? 'style=background-color:#ccc;':''}}>Inbox 
					<b class="float-right">({{$nn}} unread)</b></a>
				</li>
				<li>
					<a href="{{asset('outbox-messages')}}" {{ Request::is('outbox-messages') ? 'style=background-color:#ccc;':''}}>Outbox</a>
				</li>
			</ul>
		</li>
	@endif
</ul>