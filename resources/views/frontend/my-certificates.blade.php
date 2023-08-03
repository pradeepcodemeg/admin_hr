<!DOCTYPE html>
<html lang="en">
  <head>
    @include('frontend/global/head')
    <script src= 
"https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"> 
    </script> 
      
    <script src= 
"https://files.codepedia.info/files/uploads/iScripts/html2canvas.js"> 
    </script>
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
     @include('frontend/global/header')
    </nav>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar collapse">
          @include('frontend/global/sidebar')
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">My Certificates</h1>
          <!-- Display filename inside the button instead of its label -->
		  <div class="clearfix">
			@foreach($certificates as $certificate)
				<li href="#" class="list-group-item ficha"><span class="list-right a2">Certificate_{{$certificate->training_name}}_{{$certificate->passing_date}}_{{$certificate->training_id}}</span>
                <a href="{{url('download-certificate', $certificate->certificate_id)}}" class="a" target="_blank">
             <i class="right glyphicon glyphicon-download-alt"></i>
                </a>
                </li>
			@endforeach
		  </div>
         </div>
        </div>
      </div>
      @include('frontend/global/foot')
  </body>
</html>
