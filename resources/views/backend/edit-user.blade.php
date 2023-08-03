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
            <h1 class="page-header">Edit User</h1>
            <!-- Display filename inside the button instead of its label -->
                                    <form id="editUser" class="row" enctype="multipart/form-data" action="{{ url('update-user', $users['id']) }}" method="post">
                                        {{ csrf_field() }}
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="hidden" name="usr_id" value="101">
                        <label class="top">First name</label>
                        <input type="text" class="form-control" name="firstname" value="{{$users->firstname}}" placeholder="Enter first name" required="">
                        <label class="top">Last name</label>
                        <input type="text" class="form-control" name="lastname" value="{{$users->lastname}}" placeholder="Enter last name" required="">
                       <label class="top">Role</label>
                       <div class="role-type">
                           @if($users->role =='Hr')         
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="role" value="Hr" checked>
                                        <span><i></i>HR</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="role" value="User">
                                        <span><i></i>User</span>
                                    </label>
                                </div>
                            @elseif($users->role =='User')
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="role" value="Hr">
                                            <span><i></i>HR</span>
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="role" value="User" checked>
                                            <span><i></i>User</span>
                                        </label>
                                    </div>    
                            @else
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="role" value="Admin" disabled>
                                            <span><i></i>HR</span>
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="role" value="Admin" disabled>
                                            <span><i></i>User</span>
                                        </label>
                                    </div>
                            @endif
                       </div>
                        <label class="top">Photo</label>
                        <div class="form-inline hidden-xs">
                            <img id="preview-foto" class="preview" src="{{asset('public')}}{{$users->image}}">
                           <span class="btn btn-primary btn-file form-control">
                            Browse <input onchange="loadFile(event)" name="photoBig" type="file" accept="image/*">
                           </span>
                        </div>
                        <div class="form-group visible-xs">
                            <img id="preview-foto-s" class="preview center-block" src="public/{{$users->image}}">
                           <span class="btn btn-primary btn-file form-control top">
                             Browse <input onchange="loadFile(event)" name="photoSmall" type="file" accept="image/*">
                           </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="top">Login</label>
                        <input type="text" class="form-control" name="email" value="{{$users->email}}" placeholder="Enter e-mail" required="">
                        <label class="top">Password</label>
                        <div class="form-inline hidden-xs">
                            <input id="password0" type="text" class="form-control" name="passwordBig" placeholder="Enter new password" value="">
                            <button type="button" class="icon-right btn btn-primary" onclick="pasGeneration()">
                                Generate
                            </button>
                        </div>
                        <div class="form-group visible-xs">
                            <input id="password1" type="text" name="passwordSmall" class="form-control password top center-block" placeholder="Enter new password">
                            <button type="button" class="btn btn-primary top center-block" onclick="pasGeneration()">
                                Generate
                            </button>
                        </div>
                               <!--                          <label class="top">Status</label>
                                <div class="toggle btn btn-primary" data-toggle="toggle" style="width: 127px; height: 34px;"><input class="form-control" name="is_active" type="checkbox" checked="" data-toggle="toggle" data-on="Active" data-off="Inactive"><div class="toggle-group"><label class="btn btn-primary toggle-on">Active</label><label class="btn btn-default active toggle-off">Inactive</label><span class="toggle-handle btn btn-default"></span></div></div> -->
                                <label class="top">Status</label>
                                @if($users->status == 'Active')         
                                <input class="form-control" id="checkbox" name="status" type="checkbox" data-toggle="toggle-groupgle-offggle" data-on="Active" data-off="Inactive" checked>         
                                @else
                                      <input class="form-control" id="checkbox" name="status" type="checkbox" data-toggle="toggle-groupgle-offggle" data-on="Active" data-off="Inactive">     
                                @endif
                   </div>
                </div>
                <div class="col-md-12">
                    <hr>
                    <button type="submit" class="right bottom right btn btn-primary">Apply</button>
                </div>
            </form>

        </div>
    </div>
</div>
@include('backend/global/foot')
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="{{asset('public/js/preview_foto.js')}}"></script>
<script src="{{asset('public/js/password.js')}}"></script>
</body>
</html>