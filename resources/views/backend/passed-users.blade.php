<ul id="myUL1" style="list-style-type: none;">
    <div class="list-loader" id="pk_loader_pass" style="display: none" ><span class="glyphicon glyphicon-repeat slow-right-spinner"></span></div>
    @if (empty($users) || $users == '' || sizeof($users) == 0)
        <li>
            <span>No users found who have passed this training.</span>
        </li>
    @endif
    @foreach ($users as $user)
        <li>
            <span class="custom-checkbox">
                <label>
                    <input type="checkbox" name="users[{{ $user->id }}]" value="{{ $user->user_id }}"
                        class="users_list1" onclick="db_btn({{ $user->id }})" />
                    <span style="font-weight: normal;"><i></i> {{ $user->firstname }} {{ $user->lastname }}</span>
                </label>
            </span>
        </li>
    @endforeach
</ul>
