<ul class="nav nav-sidebar">
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
		<ul class="submenu" {{ Request::is('my-messages') ? 'style=display:block;' : (Request::is('my-outbox-messages') ? 'style=display:block;' : '') }}>
			<li>
				<a href="{{asset('my-messages')}}" {{ Request::is('my-messages') ? 'style=background-color:#ccc;':''}}>Inbox
					<b class="float-right">({{$nn}} unread)</b>
				</a>
			</li>
			<li>
				<a href="{{asset('my-outbox-messages')}}" {{ Request::is('my-outbox-messages') ? 'style=background-color:#ccc;':''}}>Outbox</a>
			</li>
		</ul>
	</li>
</ul>