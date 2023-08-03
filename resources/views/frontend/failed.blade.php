<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend/global/head')
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div id="testModal" class="">
    <div class="modal-dialog container">

        <!-- Modal content-->
        <div class="modal-content row">
            <div class="modal-body">
                <p>
                	Unfortunately you did not pass this test.<br>
                	 To review your results and try again – click “Show Results” Or click “Exit” to complete this training later.
                </p>
           <form method="post" enctype="multipart/form-data" action="{{ url('show-results', $training['id']) }}">
                                        {{ csrf_field() }}
                <div class="form-inline hidden-xs">
                    <div class="pull-right bottom">
                        <button type="submit" class="btn btn-primary bottom">Show Results
                        </button>
                        <a href="../user-trainings">
                        	<button id="yes-pass" type="button" class="btn btn-primary bottom">Exit</button>
                        </a>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
@include('frontend/global/foot')
</body>
</html>
