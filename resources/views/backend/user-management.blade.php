<!DOCTYPE html>
<html lang="en">
<head>
    @include('backend/global/head')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/t/bs/jq-2.2.0,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.11,af-2.1.1,b-1.1.2,b-colvis-1.1.2,b-html5-1.1.2,b-print-1.1.2,cr-1.3.1,kt-2.1.1,r-2.0.2,rr-1.1.1,sc-1.4.1,se-1.1.2/datatables.min.css"/>
    <script type="text/javascript"
            src="https://cdn.datatables.net/t/bs/jq-2.2.0,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.11,af-2.1.1,b-1.1.2,b-colvis-1.1.2,b-html5-1.1.2,b-print-1.1.2,cr-1.3.1,kt-2.1.1,r-2.0.2,rr-1.1.1,sc-1.4.1,se-1.1.2/datatables.min.js">                
    </script>
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
            <h1 class="page-header">User management</h1>
            @if ($alert = Session::get('user-add'))
                <div class="alert alert-success" id="msg-div">
                    {{ $alert }}
                </div>
                @elseif ($alert = Session::get('user-edit'))
                <div class="alert alert-success" id="msg-div">
                    {{ $alert }}
                </div>
                @elseif ($alert = Session::get('user-delete'))
                <div class="alert alert-success" id="msg-div">
                    {{ $alert }}
                </div>
                @endif
            <!-- Display filename inside the button instead of its label -->
            <div class="clearfix">
                <button id="active" class="left right2 btn btn-primary form-button" value="Active">Active
                </button>
                <button id="inactive" class="left right2 btn btn-primary form-button" value="Inactive">Inactive
                </button>
                <button id="all" class="left right2 btn btn-primary form-button" value="All">All
                </button>
                <button class="bottom right right2 btn btn-primary" onclick="document.location.href='add-user'"><i
                        class="icon-left glyphicon glyphicon-user"></i>Add user
                </button>
            </div>
            <div class="row">
                <div class="col-md-12">
                        <div class="table-responsive">
                        <table id="myTable" class="table table-bordred table-striped">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Add date</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                        </table>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span
                                    class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                            <h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> Are
                                you sure you want to delete this user?
                            </div>
                        </div>
                        <div class="modal-footer ">
                            <button id="yes-delete" type="button" class="btn btn-danger" onclick=""><span
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
@include('backend/global/foot')
<script src="{{asset('public/js/preview_foto.js')}}"></script>
<script src="{{asset('public/js/password.js')}}"></script>
<script src="{{asset('public/js/delete.js')}}"></script>
<script src="{{asset('public/js/filter_user.js')}}"></script>
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        baseUrl = '{{url("/")}}';
    </script>
</body>
</html>