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
                	Congratulations!<br> You’ve successfully passed the test!<br>
                	 You can find your certificate of completion in “My Certificates” tab under your account.
                </p>
            <form method="post" enctype="multipart/form-data" action="{{ url('submit-test', $training['id']) }}">
                                        {{ csrf_field() }}
                <div class="form-inline hidden-xs">
                    <div class="pull-right bottom">
                        <button type="submit" class="btn btn-primary float-right icon-left  bottom">Done</button>
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
