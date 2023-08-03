<!DOCTYPE html>
<html lang="en">

<head>
    @include('backend/global/head')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
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
                <h1 class="page-header">Add user</h1>
                @if ($alert = Session::get('user-email'))
                    <div class="alert alert-warning" id="msg-div">
                        {{ $alert }}
                    </div>
                @endif
                <!-- Display filename inside the button instead of its label -->
                <form id="editUser" class="row" enctype="multipart/form-data" action="{{ url('add-user') }}"
                    method="post">
                    {{ csrf_field() }}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="top">First name</label>
                            <input type="text" class="form-control" name="firstname" placeholder="Enter first name"
                                required />
                            <label class="top">Last name</label>
                            <input type="text" class="form-control" name="lastname" placeholder="Enter last name"
                                required />
                            <label class="top">Role</label>
                            <div class="role-type">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="role" value="Hr" required>
                                        <span><i></i>HR</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="role" value="User" required>
                                        <span><i></i>User</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="role" value="preservice" required>
                                        <span><i></i>Pre-service</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="role" value="supervisor" required>
                                        <span><i></i>Supervisor</span>
                                    </label>
                                </div>
                            </div>
                            <label class="top">Photo</label>
                            <div class="form-inline hidden-xs">
                                <img id="preview-foto" class="preview"
                                    src="{{ asset('public/images/default_user_photo.png') }}" />
                                <span class="btn btn-primary btn-file form-control">
                                    Browse <input onchange="loadFile(event)" name="photoBig" type="file"
                                        accept="image/*">
                                </span>
                            </div>
                            <div class="form-group visible-xs">
                                <img id="preview-foto-s" class="preview center-block" />
                                <span class="btn btn-primary btn-file form-control top">
                                    Browse <input onchange="loadFile(event)" name="photoSmall" type="file"
                                        accept="image/*">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="top">Login</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter e-mail"
                                required />
                            <label class="top">Password</label>
                            <div class="form-inline hidden-xs">
                                <input id="password0" type="text" class="form-control" name="passwordBig"
                                    placeholder="Enter password" required /> <button type="button"
                                    class="icon-right btn btn-primary" onclick="pasGeneration()">Generate</button>
                            </div>
                            <div class="form-group visible-xs">
                                <input id="password1" type="text" name="passwordSmall"
                                    class="form-control password top center-block" placeholder="Enter password" />
                                <button type="button" class="btn btn-primary top center-block"
                                    onclick="pasGeneration()">Generate</button>
                            </div>
                            <label class="top">Status</label>
                            <input class="form-control" id="checkbox" name="status" type="checkbox"
                                data-toggle="toggle" data-on="Active" data-off="Inactive" checked>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                        <button type="submit" class="right bottom right btn btn-primary">Add user</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    </div>
    <!-- Bootstrap core JavaScript
================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="{{ asset('public/js/bootstrap.js') }}"></script>
    <script src="{{ asset('public/js/preview_foto.js') }}"></script>
    <script src="{{ asset('public/js/password.js') }}"></script>
</body>

</html>
