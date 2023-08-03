<!DOCTYPE html>
<html lang="en">
<head>
    @include('backend/global/head')
    <link href="{{asset('public/css/fileinput.css')}}" media="all" rel="stylesheet" type="text/css"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="{{asset('public/js/fileinput.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/js/fileinput_locale_ru.js')}}" type="text/javascript"></script>
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
    @include('backend/global/header')
</nav>

<div class="container-fluid">
    <div class="loader"></div>
      <div id="fader"></div>
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar collapse">
            @include('backend/global/sidebar')
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Blanks</h1>
            @if ($alert = Session::get('upload-success'))
            <div class="alert alert-success" id="msg-div">
                {{ $alert }}
            </div>
            @elseif ($alert = Session::get('delete-msg'))
            <div class="alert alert-success" id="msg-div">
                {{ $alert }}
            </div>
            @endif
            <!-- Display filename inside the button instead of its label -->
            <div class="clearfix">
                <form enctype="multipart/form-data"
                      action="{{url('blanks/upload')}}"
                      method="POST">
                {!! csrf_field() !!}
                <!--                    <input type="hidden" name="MAX_FILE_SIZE" value="300000">-->
                    <input class="btn btn-primary bottom right" type="submit" value="Upload" id="upload" disabled>
                    <span class="btn btn-primary btn-file bottom right right2">
					<i class="icon-left glyphicon glyphicon-file"></i>Choose file<input name="blank_file" type="file" id="file"
                                required>
				</span>
                <span class="right text-info mr-4 filename" id="filename"></span>
                </form>
            </div>
            <div class="clearfix blanks-list">
                <div class="list-group">
                    @if(empty($blank_files))
                    <li class="list-group-item ficha">
                           No Records Found
                    </li>
                    @endif
                    @foreach($blank_files as $list)
                        <li class="list-group-item ficha">
                            {{$list['title']}}
                            <span onclick="deleteUser({{$list['id']}})" data-title="Delete" data-toggle="modal" data-target="#delete">
                                <i class="right glyphicon glyphicon-trash"></i>
                            </span>
                            <a href="blanks/download/{{$list['id']}}" class="a" target="_blank">
                                <i class="right icon-left glyphicon glyphicon-download-alt"></i>
                            </a>
                        </li>
                    @endforeach
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
                <!-- /.modal-dialog -->
            </div>
    </div>
</div>
</div>

@include('backend/global/foot')
<script src="{{asset('public/js/delete_blanks.js')}}"></script>
<script>
    $('input[type="file"]').change(function(e){
        $('#upload').removeAttr('disabled');
        var fileName = e.target.files[0].name;
        $('#filename').html(fileName);
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
