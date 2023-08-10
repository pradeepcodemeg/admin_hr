<!DOCTYPE html>
<html lang="en">

<head>
    @include('backend/global/head')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/style.css') }}">
    <!--font awesome 4-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/font-awesome.min.css') }}">
    <!--MULTI SELECT-->
    <style type="text/css">
        .pointer:hover {
            cursor: pointer;
        }
    </style>
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
                    <div class="compose-mail-btn">
                        <button data-toggle="modal" data-target="#compose-mail"> <i
                                class="glyphicon glyphicon-plus"></i> Compose Mail</button>
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
                                                $my_msg = DB::table('messages')
                                                    ->orderBy('created_at')
                                                    ->where('message_id', $msg->message_id)
                                                    ->first();
                                                $send_data = DB::table('message_user_relation')
                                                    ->where('message_id', $msg->message_id)
                                                    ->first();
                                                $user = DB::table('users')
                                                    ->where('id', $send_data->sender_id)
                                                    ->first();
                                            @endphp
                                            <tr class="pointer {{ $send_data->is_read != 1 ? 'unseen' : '' }}"
                                                data-href="{{ asset('view-message/' . $msg->message_id) }}">
                                                <td class="sender"><span>
                                                        {{ $user->firstname }} {{ $user->lastname }}
                                                    </span></td>
                                                <td class="subject-mg">
                                                    <a>
                                                        <h5> {{ $my_msg->subject }} </h5>
                                                        <p> <span>&nbsp; - &nbsp;</span>{{ $my_msg->message }}</p>
                                                    </a>
                                                </td>
                                                <td class="date">
                                                    <span>{{ \Carbon\Carbon::parse($my_msg->created_at)->format('D: d M yy H:i') }}</span>
                                                </td>

                                                <td class="delete-btn" data-id="{{ encrypt($msg->message_id) }}">
                                                    <button><i class="glyphicon glyphicon-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $msg_data->links() }}
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
                        <form enctype="multipart/form-data" id="frm-send" action="{{ url('send-message') }}" method="post">
                            {{ csrf_field() }}
                            <div class="m-header">
                                <i class="glyphicon glyphicon-th-list"></i>
                                <div class="select-box">
                                    <span>Category</span>
                                    <select id="select" name="" class="js-multi-select" required>
                                        <option value="all">All</option>
                                        <option value="all_users">All Users</option>
                                        <option value="completed_training">Users who have completed all trainings
                                        </option>
                                        <option value="uncompleted_training">Users who has any incomplete training
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="m-header">
                                <i class="glyphicon glyphicon-share-alt"></i>
                                <div class="select-box">
                                    <span>To</span>
                                    <span id="space">
                                        <select id="all" class="js-multi-select" name="users[]"
                                            multiple="multiple">
                                            <option value="0">All</option>
                                            @foreach ($users as $person)
                                                <option value="{{ $person['id'] }}">{{ $person['firstname'] }}
                                                    {{ $person['lastname'] }} </option>
                                            @endforeach
                                        </select>
                                    </span>
                                    <span id="space1">
                                        <select id="all_users" class="js-multi-select" name="users[]"
                                            multiple="multiple">
                                            <option value="0">All</option>
                                            @foreach ($users as $person)
                                                @if ($person['role'] != 'Admin')
                                                    <option value="{{ $person['id'] }}">{{ $person['firstname'] }}
                                                        {{ $person['lastname'] }} </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </span>
                                    <span id="space2">
                                        <select id="completed_training" class="js-multi-select" name="users[]"
                                            multiple="multiple">
                                            <option value="0">All</option>
                                            @foreach ($users as $person)
                                                @if ($person['role'] != 'Admin')
                                                    @php
                                                        $complete = DB::table('submit_trainings')
                                                            ->where('user_id', $person['id'])
                                                            ->first();
                                                    @endphp
                                                    @if (!empty($complete) || $complete != '')
                                                        <option value="{{ $person['id'] }}">{{ $person['firstname'] }}
                                                            {{ $person['lastname'] }} </option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </span>
                                    <span id="space3">
                                        <select id="uncompleted_training" class="js-multi-select" name="users[]"
                                            multiple="multiple">
                                            <option value="0">All</option>
                                            @foreach ($users as $person)
                                                @if ($person['role'] != 'Admin')
                                                    @php
                                                        $incomplete = DB::table('submit_trainings')
                                                            ->where('user_id', $person['id'])
                                                            ->first();
                                                    @endphp
                                                    @if (empty($incomplete) || $incomplete == '')
                                                        <option value="{{ $person['id'] }}">{{ $person['firstname'] }}
                                                            {{ $person['lastname'] }} </option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </span>
                                </div>
                            </div>
                            <div class="m-body">
                                <input type="text" name="subject" class="form-control" placeholder="Add subject here"
                                    required>
                                <textarea class="form-control" name="message" placeholder="Write message here..." rows="4" required></textarea>
                                <div class="attch-img" id="preview">
                                    <!-- <a href="javascript:void(0);">
                                        <img src="{{ asset('public/images/attch.jpg') }}">
                                    </a> -->
                                </div>
                            </div>
                            <div class="foot clearfix">
                                <div class="left">
                                    <button class="send" id="frm_submit" type="submit"><i
                                            class="glyphicon glyphicon-send"></i>
                                        &nbsp;Send</button>
                                    <button class="attachment" type="button">
                                        <label>
                                            <input type="file" name="attachment[]" multiple id="img">
                                            <i class="glyphicon glyphicon-paperclip"></i>
                                        </label>
                                    </button>
                                </div>
                                <div class="right">
                                    <button data-dismiss="modal" class="mail-off"><i
                                            class="glyphicon glyphicon-trash"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend/global/foot')
    <script src="{{ asset('public/js/passing_training.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/js/preview_img.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/js/category_filter.js') }}"></script>

    <script>
        $('#frm_submit').click(function(){
            $('#frm_submit').attr('disabled', true);
            $('#frm_submit').css('cursor', 'not-allowed');
            $('#frm_submit').text('sending...');
            $("#frm-send").submit();
        });
        $('.mail-off').click(function(){
            $('#frm_submit').attr('disabled', false);
            $('#frm_submit').css('cursor', 'pointer');
            $('#frm_submit').text('Send');
        });
    </script>
</body>

</html>
