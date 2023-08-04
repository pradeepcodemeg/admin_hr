<ul id="myUL1New" style="list-style-type: none;">
	@if(empty($users) || $users == '' || sizeof($users) == 0) 
	 <li>
    	<span>No users found !</span>
    </li>
	@endif
    @foreach($users as $user)  
        <li>
           <span class="custom-checkbox">
                <label>
                    <input type="checkbox" name="users[{{$user->id}}]" value="{{$user->id}}" class="users_list1_new" onclick="db_btn_new({{$user->id}})" />
                    <span style="font-weight: normal;"><i></i>  {{$user->firstname}} {{$user->lastname}}</span> 
                </label>
            </span>
        </li>
    @endforeach
</ul>