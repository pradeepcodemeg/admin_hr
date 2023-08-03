<!DOCTYPE html>
<html lang="en">

<head>
    @include('backend/global/head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
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
    </style>
</head>

<body>
    <div id="myModal3" class="modal fade" role="dialog">
        <div class="modal-dialog container">

            <!-- Modal content-->
            <div class="modal-content row">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Trainings and tests</h4>
                </div>
                <div class="modal-body">
                    <p>Данный тренинг остановлен и перешёл в архивное состояние.</p>
                </div>
            </div>

        </div>
    </div>

    <div id="myModal4" class="modal fade" role="dialog">
        <div class="modal-dialog container">

            <!-- Modal content-->
            <div class="modal-content row">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Start training</h4>
                </div>
                <div class="modal-body">
                    <p>Данный тренинг был запущен и перешёл в активное состояние.</p>
                </div>
            </div>

        </div>
    </div>

    @include('backend/global/foot')

    <script src="js/training-id.js"></script>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        @include('backend/global/header')
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar">
                @include('backend/global/sidebar')
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <div class="top-buttons all-buttons">
                    <h1 class="page-header">
                        Trainings and tests
                        <span class="all-buttons-right">
                            <button id="all" class="btn btn-primary bottom" style="margin-right: 10px;">All
                            </button>
                            <button id="active" class="btn btn-primary bottom" style="margin-right: 10px;"
                                value="Active">Active
                            </button>
                            <button id="inactive" class="btn btn-primary bottom" style="margin-right: 10px;"
                                value="Inactive">Inactive
                            </button>
                            <button id="archive" class="btn btn-primary bottom" style="margin-right: 10px;"
                                value="Archive">Archive
                            </button>
                            <button onclick="window.location.href='{{ url('add-training') }}'"
                                class="btn btn-primary bottom visible-xs"><i
                                    class="glyphicon glyphicon-plus"></i></i></button>
                            <button onclick="window.location.href='{{ url('add-training') }}'"
                                class="btn btn-primary bottom hidden-xs"><i class="glyphicon glyphicon-plus"></i> Add
                                training</button>
                        </span>
                    </h1>
                    @if ($alert = Session::get('training-add'))
                        <div class="alert alert-success" id="msg-div">
                            {{ $alert }}
                        </div>
                    @elseif ($alert = Session::get('training-duplicate'))
                        <div class="alert alert-success" id="msg-div">
                            {{ $alert }}
                        </div>
                    @elseif ($alert = Session::get('training-update'))
                        <div class="alert alert-success" id="msg-div">
                            {{ $alert }}
                        </div>
                    @elseif ($alert = Session::get('training-remind'))
                        <div class="alert alert-success" id="msg-div">
                            {{ $alert }}
                        </div>
                    @elseif ($alert = Session::get('training-stop'))
                        <div class="alert alert-success" id="msg-div">
                            {{ $alert }}
                        </div>
                    @endif
                </div>


                <div class="loader"></div>
                <div id="fader"></div>
                <ul id="training-content" class="my-nav clearfix">
                </ul>
                <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span
                                        class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                                <h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span>
                                    Are
                                    you sure you want to delete this training?
                                </div>
                            </div>
                            <div class="modal-footer ">
                                <button id="yes-delete" type="button" class="btn btn-success" onclick=""><span
                                        class="glyphicon glyphicon-ok-sign"></span> Yes
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal"><span
                                        class="glyphicon glyphicon-remove"></span> No
                                </button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="{{ asset('public/js/delete_training.js') }}"></script>
    <script src="{{ asset('public/js/filter_training.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#fader').css('display', 'none');

            $('body').on('click', 'a.remindcls', function() {
                $('#fader').css('display', 'block');
                window.location.href = "{{ url('/') }}/" + $(this).attr('data-href');
            });

        });
    </script>
</body>

</html>
