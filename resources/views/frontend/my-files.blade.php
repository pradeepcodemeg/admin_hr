<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend/global/head')
    <link href="{{asset('public/css/fileinput.css')}}" media="all" rel="stylesheet" type="text/css"/>
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
        background: url('public/images/pre.gif') 50% 50% no-repeat rgb(249,249,249);
      }
    </style>
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    @include('frontend/global/header')
</nav>

<div class="container-fluid">
      <div id="fader"></div>
    <div class="row">
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">My files</h1>
            @if ($alert = Session::get('alert-success'))
                <div class="alert alert-warning" id="msg-div">
                    {{ $alert }}
                </div>
            @elseif ($alert = Session::get('upload-success'))
            <div class="alert alert-success" id="msg-div">
                {{ $alert }}
            </div>
            @elseif ($alert = Session::get('upload-error'))
            <div class="alert alert-danger" id="msg-div">
                {{ $alert }}
            </div>
            @elseif ($alert = Session::get('delete-msg'))
            <div class="alert alert-success" id="msg-div">
                {{ $alert }}
            </div>
            @endif
            <!-- Display filename inside the button instead of its label -->
            <div class="row">
                <div class="col-sm-4 "><!-- <span>Used space: 12.3 MB / 70 MB  </span> --></div>
                <div class="col-sm-8">
                    <form enctype="multipart/form-data" action="{{ url('file-upload', $user->id) }}" method="POST">
                      {{ csrf_field() }}
                        <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
                        <input class="btn btn-primary bottom right" type="submit" value="Upload" disabled id="upload">
                        <div id="select" class="form-group right" style="margin-right: 10px;" hidden>
                            <div class="select-box my-files">
                            <select name="users[]" class="js-multi-select" multiple required>
                        @foreach($users as $person)
                            @if($person['role'] == "Admin" || $person['role'] == "Hr")
                                <option value="{{$person['id']}}">{{$person['firstname']}} {{$person['lastname']}} </option>
                            @endif
                        @endforeach
                        </select>
                    </div>
                        </div>
                        <span class="btn btn-primary btn-file bottom right" style="margin-right: 10px;">
                            <i class="icon-left glyphicon glyphicon-file"></i>Choose file<input name="personal_file" type="file" id="file">     
                        </span> 
                        <input type="text" name="subject" value=" User uploaded new file for you." hidden>
                    </form>
                     <span class="right text-info mr-4 filename" id="filename"></span>
                </div>
            </div>
            <ul class="list-group">                   
                @foreach($blank_files as $list)
                    <li class="list-group-item ficha">
                        {{$list->title}}                 
                        <span onclick="deleteUser({{$list->id}})" data-title="Delete" data-toggle="modal" data-target="#delete">
                            <i class="right glyphicon glyphicon-trash"></i>
                         </span>
                        <a href="download/{{$list->id}}" class="a" target="_blank">
                        <i class="right icon-left glyphicon glyphicon-download-alt"></i></a>
                    </li>
                @endforeach
            </ul>
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
                                you sure you want to delete this file?
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
        </div>
        <div class="col-sm-3 col-md-2 sidebar">
            @include('frontend/global/sidebar')
        </div>
    </div>
</div>
@include('frontend/global/foot')
<script src="{{asset('public/js/delete_my_file.js')}}"></script>
<script src="{{asset('public/js/show_text.js')}}"></script>
<script>
    $('input[type="file"]').change(function(e){
        $('#upload').removeAttr('disabled');
        var fileName = e.target.files[0].name;
        $('#filename').html(fileName);
        $('#select').removeAttr('hidden');
    });
</script>
<script type="text/javascript">
      $('#upload').on('click', function (e) {
        $('#fader').css('display', 'block');
      });
      $(document).ready(function(){        
        $('#fader').css('display', 'none');
      });
</script>
</body>
</html>
