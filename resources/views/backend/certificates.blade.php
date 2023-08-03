<!DOCTYPE html>
<html lang="en">
  <head>
    @include('backend/global/head')
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
       .modal-scr{
         max-height: 500px;
         overflow-y: scroll;
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
          <h1 class="page-header">Certificates</h1>
          <!-- Display filename inside the button instead of its label -->
    		  <div class="clearfix">
          <div class="list-group">
                @foreach($trainings as $training)
                @php $crt = DB::table('submit_trainings')->where('training_id', $training->id)->where('passed', 'Passed')->get(); @endphp
                <li href="#" class="list-group-item ficha ccg" data-toggle="collapse"
                data-target="#demo{{$training->id}}">
                <i class="icon-left glyphicon glyphicon-folder-close"></i>
                Certificates_{{$training->training_name}}_{{$training->created_at}} 
                @if(count($crt) > 0 && count($crt) <= 100)
                <a id="{{$training->id}}" class="d ttg" value="0">
                  <i class="right glyphicon glyphicon-download-alt"></i>
                </a>
                @elseif(count($crt) > 100)
                <a data-toggle="modal" data-target="#my{{$training->id}}" data-backdrop="static" data-keyboard="false" class="ttg">
                  <i class="right glyphicon glyphicon-download-alt"></i>
                </a>
                @endif
                </li>
                    <div id="demo{{$training->id}}" class="collapse">
                @foreach($certificates as $certificate)
                  @if($certificate->training_id == $training->id && $certificate->passed == "Passed")
                      <li href="#" class="list-group-item ficha"><span class="list-right a2">{{$certificate->firstname}}_{{$certificate->lastname}}_{{$certificate->passing_date}}.jpg</span>
                      <a href="{{url('download-certificate', $certificate->certificate_id)}}" id="zip-d" target="_blank"><i class="right glyphicon glyphicon-download-alt"></i></a></li> 
                  @endif
                @endforeach                            
                    </div>
                <div id="my{{$training->id}}" class="modal fade" role="dialog">
                  <div class="modal-dialog container">
                    <div class="modal-content row">
                        <div class="modal-header">                        
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Download Certificates Zip</h4>
                        </div>
                        <div class="modal-body">
                        <p>
                          This training  has more than 100 certificates which we have split into multiple folders and each zip will have 100 certificates, You can download them as per your need.
                        </p>
                        <ul class="list-group top">
                         @switch(count($crt))
                              @case(count($crt) <= 200)
                                @for($i=0;$i<=1;$i++)
                                  @php $nm = $i + 1; @endphp
                                  <li href="#" class="list-group-item ficha">
                                  <i class="icon-left glyphicon glyphicon-folder-close"></i>{{$nm}}. Download zip {{$nm}} 
                                  <a id="{{$training->id}}" class="d" value="{{$i}}00">
                                  <i class="right glyphicon glyphicon-download-alt"></i>
                                  </a>
                                  </li>
                                @endfor
                              @break
                              @case(count($crt) <= 300)
                                @for($i=0;$i<=2;$i++)
                                  @php $nm = $i + 1; @endphp
                                  <li href="#" class="list-group-item ficha">
                                  <i class="icon-left glyphicon glyphicon-folder-close"></i>{{$nm}}. Download zip {{$nm}} 
                                  <a id="{{$training->id}}" class="d" value="{{$i}}00">
                                  <i class="right glyphicon glyphicon-download-alt"></i>
                                  </a>
                                  </li>
                                @endfor
                              @break
                              @case(count($crt) <= 400)
                                @for($i=0;$i<=3;$i++)
                                  @php $nm = $i + 1; @endphp
                                  <li href="#" class="list-group-item ficha">
                                  <i class="icon-left glyphicon glyphicon-folder-close"></i>{{$nm}}. Download zip {{$nm}} 
                                  <a id="{{$training->id}}" class="d" value="{{$i}}00">
                                  <i class="right glyphicon glyphicon-download-alt"></i>
                                  </a>
                                  </li>
                                @endfor
                              @break
                              @case(count($crt) <= 500)
                                @for($i=0;$i<=4;$i++)
                                  @php $nm = $i + 1; @endphp
                                  <li href="#" class="list-group-item ficha">
                                  <i class="icon-left glyphicon glyphicon-folder-close"></i>{{$nm}}. Download zip {{$nm}} 
                                  <a id="{{$training->id}}" class="d" value="{{$i}}00">
                                  <i class="right glyphicon glyphicon-download-alt"></i>
                                  </a>
                                  </li>
                                @endfor
                              @break
                              @case(count($crt) <= 600)
                                @for($i=0;$i<=5;$i++)
                                  @php $nm = $i + 1; @endphp
                                  <li href="#" class="list-group-item ficha">
                                  <i class="icon-left glyphicon glyphicon-folder-close"></i>{{$nm}}. Download zip {{$nm}} 
                                  <a id="{{$training->id}}" class="d" value="{{$i}}00">
                                  <i class="right glyphicon glyphicon-download-alt"></i>
                                  </a>
                                  </li>
                                @endfor
                              @break
                              @default
                              <ul class="">
                                  <li href="#" class="list-group-item ficha">
                                  <i class="icon-left glyphicon glyphicon-folder-close"></i>1. Download zip 1 
                                  <a id="{{$training->id}}" class="d" value="0">
                                  <i class="right glyphicon glyphicon-download-alt"></i>
                                  </a>
                                  </li> 
                                  </ul>                             
                            @endswitch
                        </ul>
                        </div>
                    </div>
                  </div>
                </div>
                @endforeach
            </div>
         </div>
        </div>
      </div>
    </div>

    @include('backend/global/foot')
  
<script type="text/javascript">
  $('.d').on('click', function () {
    var id = $(this).attr('id');
    var offset = $(this).attr('value');
    $('#fader').css('display', 'block');
    $.ajax({
          "headers":{
          'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
          },
          'type':'POST',
          'url' : 'download-zip',
          'data': {offset:offset, id:id},
          'success' : function(response){
            console.log("Success:: "+response);
            $('#fader').css('display', 'none');
            setTimeout(function(){
            window.location = baseUrl + "/public/" + response;
            },500);
          },
          'error' : function(error){
            console.log("Error:: "+error);
            $('#fader').css('display', 'none');
          },
          complete: function() {               
          },
      });   
    return false;
  });
  $(document).ready(function(){        
    $('#fader').css('display', 'none');
  });
</script>   
<script type="text/javascript">
  $('.ttg').click(function(){
    $('.ccg').attr('data-toggle', '');
  });
  $('.ccg').hover(function(){
    $('.ccg').attr('data-toggle', 'collapse');
  });
</script>
  </body>
</html>
