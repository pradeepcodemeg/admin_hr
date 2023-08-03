<!DOCTYPE html>
<html lang="en">

<head>
    @include('backend/global/head')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <style>
        @media only screen and (max-width: 768px) {

            /* For mobile phones: */
            #button-on-desktop {
                margin-top: 0px !important;
                float: none !important;
            }

            #submit-button {
                width: 100% !important;
                margin-top: 70px !important;
                margin-bottom: 10px !important;
            }
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
                <h1 class="page-header">Edit training</h1>
                <form method="post" action="{{ url('update-training', $training_list['id']) }}">
                    {{ csrf_field() }}
                    <!-- <div class="col-md-12">
                   <div class="col-md-6 sort-form">
                    <label class="top">Training Id</label>
                     <input type="text" class="form-control" name="" value="{{ $training_list->id }}" disabled/>
                   </div>
                </div> -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Training name</label>
                                <input type="text" class="form-control" name="training_name"
                                    value="{{ $training_list->training_name }}" required />
                                <label class="top">Minimum time (HH:MM) (max - 23:59)</label>
                                <input type="text" class="form-control" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]"
                                    id="minTime" name="minimun_time"
                                    value="{{ Carbon\Carbon::parse($training_list->minimun_time)->format('h:m') }}"
                                    required />
                                <label class="top">Credit Hours (HH:MM)</label>
                                <input type="text" class="form-control" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]"
                                    id="creditTime" name="credit_hours"
                                    value="{{ Carbon\Carbon::parse($training_list->credit_hours)->format('h:m') }}"
                                    required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Training deadline</label>
                                <input type="text" id="generate_dates" class="form-control" name="training_deadline"
                                    value="{{ $training_list->training_deadline }}" />
                                <label class="top">Training status</label>
                                <select class="form-control" name="status">
                                    <option value="Active" @if ($training_list->status == 'Active') selected @endif>Active
                                    </option>
                                    <option value="Inactive" @if ($training_list->status == 'Inactive') selected @endif>Inactive
                                    </option>
                                    <option value="Archive" {{ $training_list->status == 'Archive' ? 'selected' : '' }}>
                                        Archive</option>
                                </select>

                                <label class="top">Select Training Avalibility Group</label>
                                <select class="form-control" name="assign_role">
                                    <option value="">Choose role</option>
                                    <option value="User" @if ($training_list->assign_role == 'User') selected @endif>Users
                                    </option>
                                    <option value="Preservice" @if ($training_list->assign_role == 'Preservice') selected @endif>
                                        Pre-service</option>
                                    <option value="Supervisor" @if ($training_list->assign_role == 'Supervisor') selected @endif>
                                        Supervisors</option>
                                    <option value="All" @if ($training_list->assign_role == 'All') selected @endif>Everyone
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <div id="button-on-desktop" class="form-inline">
                                <div id="button-on-desktop" class="pull-right top">
                                    <button id="submit-button" type="submit" type="button" class="btn btn-primary"
                                        data-dismiss="modal">
                                        Update </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    @include('backend/global/foot')
    <script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    <script src="{{ asset('public/js/bootstrap-datepicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-datepicker3.min.css') }}">
    <script type="text/javascript">
        $('#generate_dates').datepicker({
            format: 'yyyy-mm-dd'
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#minTime').inputmask(
                "hh:mm", {
                    placeholder: "HH:MM",
                    insertMode: false,
                    showMaskOnHover: false,
                    hourFormat: 12
                });
            $('#creditTime').inputmask(
                "hh:mm", {
                    placeholder: "HH:MM",
                    insertMode: false,
                    showMaskOnHover: false,
                    hourFormat: 12
                });
        })
    </script>
</body>

</html>
