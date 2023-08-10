<!DOCTYPE html>
<html lang="en">

<head>
    @include('backend/global/head')
    <!-- jQuery Datatables-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <style type="text/css">
        #fader {
            opacity: 0.5;
            z-index: 9999;
            background: black;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            display: none;
            background: url('public/images/pre.gif') 50% 50% no-repeat rgb(249, 249, 249);
        }

        body .select2-container {
            z-index: 0;
        }


        .well.well-wrap {
            padding: 0;
            position: relative;
            overflow: hidden;
            height: 268px;
        }

        .well.well-wrap p {
            padding: 15px;
        }

        .well.well-wrap ul {
            padding: 15px;
            max-height: 100%;
            overflow: auto;
        }

        .list-loader {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            display: flex;
            align-content: center;
            justify-content: center;
            align-items: center;
            background: #1083f61f;
            z-index: 1;
        }

        .list-loader span {
            display: block;
            color: #1083f6;
            font-size: 26px;
            border-radius: 50%;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        @include('backend/global/header')
    </nav>

    <div class="container-fluid manage-training">
        <div class="loader"></div>
        <div id="fader"></div>
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar collapse">
                @include('backend/global/sidebar')
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <h1 class="page-header">Manage Trainings</h1>
                <!-- Display filename inside the button instead of its label -->
                <div class="container-fluid">
                    <h2 class="section-heading">Delete trainings</h2>
                    @if ($alert = Session::get('message'))
                        <div class="alert alert-warning" id="msg-div">
                            {{ $alert }}
                        </div>
                    @elseif ($alert = Session::get('alert-delete-success'))
                        <div class="alert alert-success" id="msg-div">
                            {{ $alert }}
                        </div>
                    @endif
                    <form method="GET" action="{{ url('changeStatus') }}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Trainings:</label>
                                <div class="select-box">
                                    <select class="js-multi-select" id="del-train" name="training">
                                        <option value="0">--Select training--</option>
                                        @foreach ($trainings as $training)
                                            <option
                                                style="background-color: {{ $training['status'] == 'Active' ? 'lightgreen' : '#f5f5f5' }};"
                                                value="{{ $training['id'] }}">{{ $training['training_name'] }}
                                                ({{ $training['status'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input class="btn btn-lg btn-block btn-danger dlt-btn" type="submit" name="delete_button"
                                id="del-btn" value="Delete" disabled>
                            <!--                        <input class="left btn btn-danger" type="submit" name="complete_delete_button" value="Delete With Files" style="margin-left: 20px;">-->
                        </div>
                    </form>
                    <!--                -->
                </div>

                <div class="container-fluid tcm">
                    <h2 class="section-heading">Training completion management</h2>
                    @if ($alert = Session::get('alert-complete'))
                        <div class="alert alert-warning" id="msg-div">
                            {{ $alert }}
                        </div>
                    @elseif ($alert = Session::get('alert-complete-success'))
                        <div class="alert alert-success" id="msg-div">
                            {{ $alert }}
                        </div>
                    @endif
                    <form method="POST" action="{{ url('pass-users') }}">
                        {{ csrf_field() }}

                        <div class="sort-form col-md-5">
                            <div class="form-group">
                                <label>Trainings:</label>
                                <span style="margin-top: 40px;">
                                    <div class="select-box">
                                        <select class="js-multi-select" name="training" id="trn_cmp"
                                            onchange="showUser(this.value)">
                                            <option value="0">--Select training--</option>
                                            @foreach ($trainings as $training)
                                                <option value="{{ $training['id'] }}">{{ $training['training_name'] }}
                                                    ({{ $training['status'] }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </span>
                            </div>
                        </div>

                        <div class="sort-form col-md-5" style="padding-left: 15px;">
                            <div class="form-group">
                                <label>Users:</label>
                                <span class="custom-checkbox" style="margin-right: 15px">
                                    <label>
                                        <input type="checkbox" id="select_all_users">
                                        <span><i></i> All</span>
                                    </label>
                                </span>
                                <input type="text" class="form-control" id="myInput" onkeyup="myFunction()"
                                    placeholder="Search for names.." title="Type in a name"
                                    style="width: 50%;display: inline-block"><br>
                                <div class="well well-wrap" id="users_list">
                                    <p>Only users those are did not pass chosen training will be displayed</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2" id="generate_dates" style="margin-top: 40px;">
                            <label for="generate_dates_start">Start Date</label>
                            <input type="text" class="form-control" name="generate_dates_start"
                                id="generate_dates_start"><br>
                            <label for="generate_dates_end">End Date</label>
                            <input type="text" class="form-control" name="generate_dates_end"
                                id="generate_dates_end"><br>

                            <button class="btn btn-lg btn-primary" type="submit" name="generate_button"
                                id="generate_button" disabled>Generate</button>
                        </div>
                    </form>
                </div>

                {{-- Download Certificates by Training --}}
                <div class="container-fluid dc">
                    <h2 class="section-heading">Download Certificates by Training</h2>
                    @if ($alert = Session::get('alert-warning'))
                        <div class="alert alert-warning" id="msg-div">
                            {{ $alert }}
                        </div>
                    @elseif ($alert = Session::get('alert-danger'))
                        <div class="alert alert-danger" id="msg-div">
                            {{ $alert }}
                        </div>
                    @endif
                    <form method="POST" id="dwn_trn" action="{{ url('download-multiple') }}">{{ csrf_field() }}
                        <div class="clearfix">
                            <div class="sort-form col-md-5">
                                <div class="form-group">
                                    <label>Trainings:</label>
                                    <span style="margin-top: 40px;" id="trainings_download">
                                        <div class="select-box">
                                            <select class="js-multi-select" name="training" id="dwn_trn_list"
                                                onchange="showPassedUser(this.value)">
                                                <option value="0">--Select training--</option>
                                                @foreach ($trainings as $training)
                                                    <option
                                                        style="background-color: {{ $training['status'] == 'Active' ? 'lightgreen' : '#f5f5f5' }};"
                                                        value="{{ $training['id'] }}">
                                                        {{ $training['training_name'] }} ({{ $training['status'] }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </span>
                                </div>
                            </div>

                            <div class="sort-form col-md-5" style="padding-left: 15px;">
                                <div class="form-group">
                                    <h5 class="same-subh-heading" style="margin: 0px;">Users:</h5>
                                    <span class="custom-checkbox" style="margin-right: 15px">
                                        <label>
                                            <input type="checkbox" id="select_all_users1" disabled>
                                            <span><i></i> All</span>
                                        </label>
                                    </span>

                                    <input type="text" id="myInput1" style="display: inline-block;width: 70%;"
                                        class="form-control" onkeyup="myFunction1()" placeholder="Search for names.."
                                        title="Type in a name" style="width: 50%;">
                                    <div class="well well-wrap" id="users_list1">
                                        {{-- <div class="pk_loader">
                                            <span class="glyphicon glyphicon-repeat slow-right-spinner"></span>
                                        </div> --}}
                                        <p>Only users those have passed choosen training will be displayed</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div>
                            <!--  <label for="generate_dates_start">Start Date</label>
                        <input type="text" class="form-control" name="generate_dates_start" id="generate_dates_start"><br>
                        <label for="generate_dates_end">End Date</label>
                        <input type="text" class="form-control" name="generate_dates_end" id="generate_dates_end"><br> -->

                            <button class="btn btn-lg btn-success" type="submit" id="download_button"
                                disabled>Download</button>
                            <button class="btn-lg" type="button" id="downloading_button" hidden="true"
                                disabled><span class="glyphicon glyphicon-repeat slow-right-spinner"></span>
                                Downloading... </button>
                        </div>
                    </form>
                </div>

                {{-- Download by user --}}
                <div class="container-fluid tcm">
                    <h2 class="section-heading">Download Certificates by User</h2>
                </div>
                @if ($alert = Session::get('alert-warning-new'))
                    <div class="alert alert-warning" id="msg-div">
                        {{ $alert }}
                    </div>
                @elseif ($alert = Session::get('alert-danger-new'))
                    <div class="alert alert-danger" id="msg-div">
                        {{ $alert }}
                    </div>
                @endif
                <form method="POST" action="{{ url('download-multiple-new') }}">{{ csrf_field() }}
                    <div class="clearfix">
                        <div class="sort-form col-md-5" style="padding-left: 15px;">
                            <div class="form-group">
                                <h5 class="same-subh-heading" style="margin: 0px;">Users:</h5>
                                {{-- select all checkbox --}}
                                {{-- <span class="custom-checkbox" style="margin-right: 15px">
                                    <label>
                                        <input type="checkbox" id="select_all_users1_new">
                                        <span><i></i> All</span>
                                    </label>
                                </span> --}}

                                <input type="text" id="myInput1New" style="display: inline-block;width: 70%;"
                                    class="form-control" onkeyup="myFunction1New()" placeholder="Search for names.."
                                    title="Type in a name" style="width: 50%;">
                                <div class="well well-wrap" id="users_list1_new">
                                    <p>Only passed users </p>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-lg btn-success" type="submit" id="download_button_new"
                            disabled>Download all certificates</button>
                        <button class="btn-lg" type="button" id="downloading_button_new" hidden="true"
                            disabled><span class="glyphicon glyphicon-repeat slow-right-spinner"></span>
                            Downloading... </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    @include('backend/global/foot')

    {{-- Update code --}}
    <script type="text/javascript">
        showAllUsers();

        $('#download_button_new').on('click', function() {
            $('#fader').css('display', 'block');
            $('#download_button_new').hide();
            $('#downloading_button_new').show();
            // var training = $('#dwn_trn_list_new').val();
            var users = [];
            $(".users_list1_new:checked").each(function() {
                users.push(this.value);
            });
            if (users.length > 200) {
                alert("You can choose maximum 200 users at once");
                $('#fader').css('display', 'none');
                $('#download_button_new').show();
                $('#downloading_button_new').hide();
                return false;
            }
            $.ajax({
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                'type': 'POST',
                'url': 'download-multiple-new',
                'data': {
                    users: users
                },
                'success': function(response) {
                    // console.log("Success:: " + response);
                    $('#fader').css('display', 'none');
                    $('#download_button_new').show();
                    $('#downloading_button_new').hide();
                    // window.location = baseUrl + "/manage-trainings";
                    setTimeout(function() {
                        window.location = baseUrl + "/public/" + response;
                    }, 1000);
                },
                'error': function(error) {
                    console.log("Error:: " + error);
                    $('#fader').css('display', 'none');
                    $('#download_button_new').show();
                    $('#downloading_button_new').hide();
                },
                complete: function() {},
            });
            return false;
        });

        function myFunction1New() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("myInput1New");
            filter = input.value.toUpperCase();
            ul = document.getElementById("myUL1New");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("span")[0];
                console.log(a.value);
                if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";

                }
            }
        }

        function showAllUsers() {
            document.getElementById("users_list1_new").innerHTML = "<p class='text-center'>Loading...</p>";
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("users_list1_new").innerHTML = this.responseText;
                    $("#select_all_users1_new").prop('checked', false);
                }
            };
            xmlhttp.open("GET", "active-users-new", true);
            xmlhttp.send();
        }

        function db_btn_new(id) {
            $('#download_button_new').removeAttr('disabled');
        }
        // $("#dwn_trn_list_new").change(function() {
        //     $('#select_all_users1_new').removeAttr('disabled');
        //     if ($("#dwn_trn_list_new").val() == 0) {
        //         $('#download_button_new').attr('disabled', true);
        //         $('#select_all_users1_new').attr('disabled', true);
        //     }
        // });
        $('#select_all_users1_new').click(function() {
            $('#download_button_new').removeAttr('disabled');
        });
    </script>
    {{-- End update code --}}


    <script type="text/javascript">
        $('#download_button').on('click', function() {

            $('#fader').css('display', 'block');
            $('#download_button').hide();
            $('#downloading_button').show();
            var training = $('#dwn_trn_list').val();
            var users = [];

            $(".users_list1:checked").each(function() {
                users.push(this.value);
            });
            if (users.length > 200) {
                alert("You can choose maximum 200 users at once");
                $('#fader').css('display', 'none');
                $('#download_button').show();
                $('#downloading_button').hide();
                return false;
            }

            $.ajax({
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                'type': 'POST',
                'url': 'download-multiple',
                'data': {
                    training: training,
                    users: users
                },
                'success': function(response) {
                    console.log("Success:: " + response);
                    $('#fader').css('display', 'none');
                    $('#download_button').show();
                    $('#downloading_button').hide();
                    setTimeout(function() {
                        window.location = baseUrl + "/public/" + response;
                    }, 2000);
                },
                'error': function(error) {
                    console.log("Error:: " + error);
                    $('#fader').css('display', 'none');
                    $('#download_button').show();
                    $('#downloading_button').hide();
                },
                complete: function() {},
            });
            return false;
        });

        $(document).ready(function() {
            $('#fader').css('display', 'none');
        });
    </script>
    <script src="{{ asset('public/js/bootstrap-datepicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-datepicker3.min.css') }}">

    <script type="text/javascript">
        $('#del-train').change(function() {
            if ($('#del-train').val() != '') {
                $('#del-btn').removeAttr('disabled');
            }
        });
    </script>
    <script type="text/javascript">
        $('#generate_dates_start, #generate_dates_end').change(function() {
            if ($('#generate_dates_start').val() != '' && $('#generate_dates_end').val() != '') {
                $('#generate_button').removeAttr('disabled');
            }
        });
    </script>

    <script>
        $('#generate_dates input').datepicker({
            format: 'dd MM yyyy'
        });
        $('#select_all_users').on('change', function() {
            $("input:checkbox.user_founded").prop('checked', this.checked);
        });
        $('#select_all_users1').on('change', function() {
            $("input:checkbox.users_list1").prop('checked', this.checked);
        });
        $('#select_all_users1_new').on('change', function() {
            $("input:checkbox.users_list1_new").prop('checked', this.checked);
        });
    </script>

    <script>
        function myFunction() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            ul = document.getElementById("myUL");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("span")[0];
                console.log(a.value);
                if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";

                }
            }
        }

        function myFunction1() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("myInput1");
            filter = input.value.toUpperCase();
            ul = document.getElementById("myUL1");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("span")[0];
                console.log(a.value);
                if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";

                }
            }
        }
    </script>

    <script>
        function showUser(str) {
            $('#pk_loader').show();
            if (str == "") {
                document.getElementById("users_list").innerHTML = "";
                return;
            } else {
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        $('#pk_loader').hide();
                        document.getElementById("users_list").innerHTML = this.responseText;
                        $("#select_all_users").prop('checked', false);
                    }
                };
                xmlhttp.open("GET", "failed-users/" + str, true);
                xmlhttp.send();
            }
        }
    </script>

    <script>
        function showPassedUser(str) {
            $('#pk_loader_pass').show();

            if (str == "") {
                document.getElementById("users_list1").innerHTML = "";
                return;
            } else {
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        $('#pk_loader_pass').hide();
                        document.getElementById("users_list1").innerHTML = this.responseText;
                        $("#select_all_users1").prop('checked', false);
                    }
                };
                xmlhttp.open("GET", "passed-users/" + str, true);
                xmlhttp.send();
            }
        }
    </script>
    <script type="text/javascript">
        function db_btn(id) {
            $('#download_button').removeAttr('disabled');
        }
        $("#dwn_trn_list").change(function() {
            $('#select_all_users1').removeAttr('disabled');
            if ($("#dwn_trn_list").val() == 0) {
                $('#download_button').attr('disabled', true);
                $('#select_all_users1').attr('disabled', true);
            }
        });
        $('#select_all_users1').click(function() {
            $('#download_button').removeAttr('disabled');
        });
    </script>
    <style>
        .glyphicon.slow-right-spinner {
            -webkit-animation: glyphicon-spin-r 3s infinite linear;
            animation: glyphicon-spin-r 3s infinite linear;
        }


        @-webkit-keyframes glyphicon-spin-r {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(359deg);
                transform: rotate(359deg);
            }
        }

        @keyframes glyphicon-spin-r {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(359deg);
                transform: rotate(359deg);
            }
        }

        @-webkit-keyframes glyphicon-spin-l {
            0% {
                -webkit-transform: rotate(359deg);
                transform: rotate(359deg);
            }

            100% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
        }

        @keyframes glyphicon-spin-l {
            0% {
                -webkit-transform: rotate(359deg);
                transform: rotate(359deg);
            }

            100% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
        }
    </style>
</body>

</html>
