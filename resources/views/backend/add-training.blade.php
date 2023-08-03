<!DOCTYPE html>
<html lang="en">
<head>
    @include('backend/global/head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <style type="text/css">
            .video-file li{
                display: inline-block;
                margin-right: 20px;
                padding: 10px 0;
                vertical-align: start;
            }
    </style> 
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
      body .select2-container {
            z-index: 0;
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
        <div class="col-sm-3 col-md-2 sidebar">
            @include('backend/global/sidebar')
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Create training</h1>
            <form id="post_training" class="row" enctype="multipart/form-data" action="{{url('add-training')}}" method="post">
                {{ csrf_field() }}
                <div class="clearfix">
                    <div class="col-md-6">
                        <input type="text" name="id" hidden>
                        <div class="form-group">
                            <label>Training name</label>
                            <input type="text" class="form-control" name="training_name" value="" placeholder="Enter training name" required />
                            <label class="top">Choose PDF training file</label>
                            <input type="file" title="Choose a PDF file" name="file" class="btn-primary" required accept="application/pdf">
                            <label class="top">Minimum time (HH:MM) (max - 23:59)</label>
                            <input type="text" class="form-control" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="minTime" name="minimun_time" placeholder="HH:MM" required/>
                            <label class="top">Credit Hours (HH:MM)</label>
                            <input type="text" class="form-control" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="creditTime" name="credit_hours" placeholder="HH:MM" required/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Training deadline (dd:mm:yyyy)</label>
                            <input type="text" id="generate_dates" class="form-control" name="training_deadline" value="" required/>
                            <div class="custom-checkbox top">
                                <label>
                                    <input type="checkbox" class="check-box-top" name="status" checked="checked" /> 
                                    <span><i></i>Active/Inactive</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="top">Video File</label>
                            <div class="video-file role-type" type="none" style="margin-bottom: 21px;">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="slide" value="first_slide"> 
                                        <span><i></i>At first slide</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="slide" value="last_slide">
                                        <span><i></i>At last slide</span>
                                    </label>
                                </div>                                
                            </div>
                            <input type="file" title="Choose a video file" name="video_file" class="btn-primary" id="video_file" accept="video/*">
                            
                            <label class="top">Or</label>
                            <input type="text" class="form-control" name="youtube_link" placeholder="Add youtube link here" id="Ulink">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <hr>
                    <h3 class="page-header ques">Add questions</h3>
                    @for($i=0;$i<=19;$i++)
                        <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>
                                                <label class="float-left">Question <?php echo $i+1 ?>:</label>
                                                <input type="text" name="question[]" class="form-control" placeholder="Add question here" id="que<?php echo $i ?>">
                                            </th>
                                            <th style="vertical-align: middle;">
                                                <label>Answer:</label>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span style="color: red; float: left;" id="req<?php echo $i ?>">*</span>
                                                <input type="text" name="option_one[]" class="form-control" id="option_one<?php echo $i;?>" placeholder="Add option 1 here">
                                            </td>
                                            <td>
                                                <div class="role-type">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="correct_option<?php echo $i ?>" value="option_one">
                                                            <span><i></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span style="color: red; float: left;" id="req<?php echo $i ?>">*</span>
                                                <input type="text" name="option_two[]" class="form-control" id="option_two<?php echo $i;?>" placeholder="Add option 2 here">
                                            </td>
                                            <td>
                                                <div class="role-type">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="correct_option<?php echo $i ?>" value="option_two">
                                                            <span><i></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="option_three[]" class="form-control" id="option_three<?php echo $i;?>" placeholder="Add option 3 here">
                                            </td>
                                            <td>
                                                <div class="role-type">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="correct_option<?php echo $i ?>" value="option_three">
                                                            <span><i></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="option_four[]" class="form-control" id="option_four<?php echo $i;?>" placeholder="Add option 4 here">
                                            </td>
                                            <td>
                                                <div class="role-type">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="correct_option<?php echo $i ?>" value="option_four">
                                                            <span><i></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                        </div>
                    @endfor
                </div>
                <div class="col-md-12">
                    <div class="form-inline hidden-xs">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary bottom top">Create</button>
                        </div>
                    </div>
                    <div class="form-inline visible-xs">
                        <button type="submit" class="btn btn-primary col-xs-12 bottom top">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@include('backend/global/foot')
<script src="{{asset('public/js/bootstrap.file-inputs.js')}}"></script>
<script src="{{asset('public/js/file.js')}}"></script>
<script src="{{asset('public/js/training_validate.js')}}"></script>
<script src="{{asset('public/js/bootstrap-datepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('public/css/bootstrap-datepicker3.min.css')}}">
<script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
<script>
    $(document).ready(function(){
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
    });
  </script>
  <script type="text/javascript">
      $('#video_file').change(function(){
            if($('#video_file').val() != ''){
                $('#Ulink').attr('disabled',true);
            }
      });
  </script>
  <script type="text/javascript">
      $('#generate_dates').datepicker({
        format: 'yyyy-mm-dd'
    });
      $(document).ready(function(){        
    $('#fader').css('display', 'none');
  });
  </script>
</body>
</html>
