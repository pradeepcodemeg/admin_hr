<ul id="myUL" style="list-style-type: none;">
    <div style="text-align: center"><span id="pk_loader" style="display: none" class="glyphicon glyphicon-repeat slow-right-spinner"></span></div>
	@if(empty($users) || $users == '' || sizeof($users) == 0) 
	 <li>
    	<span>No users found who have failed in this training.</span>
    </li>
	@endif
    @foreach($users as $user)    
        <li>
            <span class="custom-checkbox">
                <label>
                    <input type="checkbox" name="users[{{$user->id}}]" value="{{$user->id}}" class="user_founded"/>
                    <span style="font-weight: normal;"><i></i>  {{$user->firstname}} {{$user->lastname}}</span>
                </label>
            </span>

        </li>
    @endforeach
</ul>