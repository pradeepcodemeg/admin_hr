<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Learning management system</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{asset('public/css/signin.css')}}" />
    <link rel="stylesheet" href="{{asset('public/css/bootstrap.min.css')}}" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="my-container">
    <div class="my-container-outer">
        <div class="my-container-inner">
            <div class="login-form">                
            @if ($error = $errors->first('password'))
              <div class="alert alert-danger" id="msg-div">
                {{ $error }}
              </div>
            @elseif ($alert = Session::get('reset-error'))
                <div class="alert alert-danger" id="msg-div">
                    {{ $alert }}
                </div>
            @elseif ($alert = Session::get('reset-success'))
            <div class="alert alert-success" id="msg-div">
                {{ $alert }}
            </div>
            @endif
                <form class="form-signin" role="form" method="post" action="{{url('post-login')}}">
                {{ csrf_field() }}  

                    <h2 class="form-signin-heading">Please sign in</h2>

                    <input type="email" class="form-control margin" id="username" name="email" placeholder="Email address"
                           required autofocus>

                    <input type="password" class="form-control margin" id="password" name="password" placeholder="Password"
                           required>

                    <label class="checkbox margin">
                        <input type="checkbox" name="rememberMe" value="Yes"> 
                        <span><i></i> Remember me</span>
                    </label>

                    <div class="btns">
                        <button class="btn btn-lg btn-primary btn-block" name="login" type="submit">Sign in</button>
                        <button type="button" class="btn btn-lg btn-block" data-toggle="modal" data-target="#myModal">Reset password </button>
                    </div>
                </form>
            </div>
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Reset password</h4>
                        </div>
                        <form role="form" action="{{ url('reset-password') }}" method="post">
                            {{ csrf_field() }}  
                            <div class="modal-body">
                                <p>After pressing "Reset" button the new generated password will be sent to your email</p>
                                <input type="email" name="email" class="form-control margin" placeholder="Email address"
                                       required autofocus>
                                <!--<button type="submit" class="btn btn-primary pull-right" data-dismiss="modal">Отправить</button>-->
                                <button type="submit" class="btn btn-primary">Reset</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <footer class="footer">
                <p class="text-muted text-center">&copy; All rights reserved.</p>
            </footer>
        </div>
    </div>
</div> <!-- /container -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{asset('public/js/bootstrap.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        setInterval(function(){$('#msg-div').hide(); }, 3000);
    });
</script>
</body>

</html>