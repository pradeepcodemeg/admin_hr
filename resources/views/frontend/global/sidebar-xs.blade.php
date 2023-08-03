<ul class="nav navbar-nav navbar-right visible-xs">
 	<li class="{{ Request::is('my-files') ? 'active' : '' }}"><a href="{{asset('my-files')}}">My files <span class="sr-only">(current)</span></a></li>
    <li class="{{ Request::is('user-blanks') ? 'active' : '' }}"><a href="{{asset('user-blanks')}}">Blanks</a></li>
    <li class="{{ Request::is('user-trainings') ? 'active' : '' }}"><a href="{{asset('user-trainings')}}">Trainings</a></li>
    <li class="{{ Request::is('my-certificates') ? 'active' : '' }}"><a href="{{asset('my-certificates')}}">My Certificates</a></li>
	<li class="submenu-parent {{ Request::is('my-messages') ? 'active' : (Request::is('my-outbox-messages') ? 'active' : '') }}">
	@php
		$id = Auth::user()->id;
		$read = DB::table('message_user_relation')->where('is_read', 0)->where('receiver_id', $id)->get();
		$nn = count($read);
	@endphp
		<a href="javascript:void(0);">My Messages</a>
		<ul class="submenu">
			<li>
				<a href="{{asset('my-messages')}}">Inbox
					<b class="float-right">({{$nn}} unread)</b>
				</a>
			</li>
			<li>
				<a href="{{asset('my-outbox-messages')}}">Outbox</a>
			</li>
		</ul>
	</li>
</ul>