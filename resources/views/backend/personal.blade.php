<!DOCTYPE html>
<html lang="en">
<head>
    @include('backend/global/head')
    <link href="{{asset('public/css/fileinput.css')}}" media="all" rel="stylesheet" type="text/css"/>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    @include('backend/global/header')
</nav>
<div class="container-fluid"> 
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar collapse">
            @include('backend/global/sidebar')
        </div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Personal files</h1>
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
            <div class="clearfix personal-list">
            <input type="text" id="search" placeholder="Search folder">                
            <div class="list-group">

                @foreach($users as $item)
                    @if($item->role != "Admin")
                        <li href="#" class="list-group-item ficha content" data-toggle="collapse"
                    data-target="#{{$item->id}}">             
                    <span style="display:none;">{{strtolower($item->firstname)}}  {{strtolower($item->lastname)}}</span>       
                    <i class="icon-left glyphicon glyphicon-folder-close"></i>
                    {{$item->firstname}}  {{$item->lastname}}                                </li>
                <div id="{{$item->id}}" class="collapse">
                    <li class="list-group-item ficha">
                        <form enctype="multipart/form-data"
                              action="{{ url('personal-upload', $item->id) }}"
                              method="POST" class="zagristi">
                              {{ csrf_field() }}
                            <!--                            <input type="hidden" name="MAX_FILE_SIZE" value="300000">-->
                            <input type="hidden" name="user_id" value="">
                            <input class="btn btn-primary bottom right" type="submit" value="Upload" id="upload{{$item->id}}" disabled>
                            <span class="btn btn-primary btn-file bottom right right2">
                            <i class="icon-left glyphicon glyphicon-file"></i>Choose file<input name="personal_file" type="file" id="file" value="{{$item->id}}" required>
                            </span>                                
                            <span class="right text-info mr-4 filename" id="filename{{$item->id}}"></span>
                        </form>
                    </li>
                    @foreach($blank_files as $list)
                        @if($list['user_id']  ==  $item->id)
                            <li class="list-group-item ficha">
                                <span class="list-right">
                                    {{$list['title']}}
                                </span>
                                <span onclick="deleteUser({{$list['id']}})" data-title="Delete" data-toggle="modal" data-target="#delete">
                                    <i class="right glyphicon glyphicon-trash"></i>
                                </span>
                                <a href="personal/download/{{$list['id']}}" class="a" target="_blank">
                                    <i class="right icon-left glyphicon glyphicon-download-alt"></i>
                                </a>
                            </li>
                        @endif
                        @endforeach 
                    </div>   
                    @endif                     
                @endforeach    
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
                            <button id="yes-delete" type="button" class="btn btn-success"><span class="glyphicon glyphicon-ok-sign"></span> Yes
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
<script src="{{asset('public/js/delete_personal.js')}}"></script>
<script>
    $('input[type="file"]').change(function(e){
        var fileName = e.target.files[0].name;
        var id = $(this).attr('value');
        $('#filename'+id).html(fileName);
        $('#upload'+id).removeAttr('disabled');
    });

    $(document).ready(function(){
     $('#search').keyup(function(){
     
      // Search text
      var text = $(this).val();
     
      // Hide all content class element
      $('.content').hide();

      // Search and show
      $('.content:contains("'+text+'")').show();
     
     });
    });
</script>
</body>
</html>