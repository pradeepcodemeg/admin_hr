<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend/global/head')
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
            background: url('public/images/pre.gif') 50% 50% no-repeat rgb(249, 249, 249);
        }

        body .select2-container {
            z-index: 0;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        @include('frontend/global/header')
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar">
                @include('frontend/global/sidebar')
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <div class="loader"></div>
                <div id="fader"></div>
                <h1 class="page-header">Trainings</h1>
                <div id="trening-content" class="">
                    @if (empty($trainings))
                        <div class="col-md-12">
                            <h3 class="card-title" align="center">
                                No Trainings Scheduled
                            </h3>
                        </div>
                    @endif

                    @foreach ($trainings as $training)
                        @php
                            $d1 = Carbon\Carbon::parse($training['training_deadline']);
                            $d2 = Carbon\Carbon::parse(date('Y-m-d'));
                            $tt = $d1->diffInDays($d2);
                            
                            $res = DB::table('submit_trainings')
                                ->where(['user_id' => Auth::user()->id, 'training_id' => $training['id']])
                                ->first();
                        @endphp
                        @if ($training['status'] == 'Active')
                            @if (!empty($res) && $res->passed == 'Passed')
                                <div class="col-md-3">
                                    <div class="well yes not">
                                        <h3 class="card-title" align="center">{{ $training['training_name'] }}</h3>
                                        <p class="card-text" align="center">Duration:
                                            {{ \Carbon\Carbon::parse($training['credit_hours'])->format('h:m') }}</p>
                                        <p class="card-text" align="center">Deadline:
                                            {{ \Carbon\Carbon::parse($training['training_deadline'])->format('Y-m-d') }}
                                        </p>
                                        <div align="center" style="margin: 20px 0 5px;">
                                            <a href="" class="btn btn-success">Passed</a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-3">
                                    <div class="well {{ $tt < 30 ? 'bg-danger' : 'not' }}">
                                        <h3 class="card-title" align="center">{{ $training['training_name'] }}</h3>
                                        <p class="card-text" align="center">Duration:
                                            {{ \Carbon\Carbon::parse($training['credit_hours'])->format('h:m') }}</p>
                                        <p class="card-text" align="center">Deadline:
                                            {{ \Carbon\Carbon::parse($training['training_deadline'])->format('Y-m-d') }}
                                        </p>
                                        <div align="center" style="margin: 20px 0 5px;">
                                            <button class="btn btn-primary" onclick="passingTraining(225)"
                                                data-toggle="modal" data-target="#my{{ $training['id'] }}">Take a
                                                training</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div id="my{{ $training['id'] }}" class="modal fade" role="dialog">
                            <div class="modal-dialog container">

                                <!-- Modal content-->
                                <div class="modal-content row">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">License agreement</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>This Program is designed for educational purpose only and is not a substitute
                                            for professional care. The information provided should not be used for
                                            diagnosing or treatment of a medical problem.
                                            <br><br>The unauthorized reproduction or distribution of this copyrighted
                                            work is illegal.
                                            <br><br>Criminal copy right infringement without monetary gain is
                                            investigated by the FBI and is punishable by up to 5 years in federal prison
                                            and fine of $250,000.
                                            <br><br>All sources in the training are used from Medifecta. An Institute
                                            for professional Care Education Company. <a
                                                href="http://www.medifecta.com/">http://www.medifecta.com/</a> , Google
                                            Images, and other public sources.
                                            <br><br>BY CLICKING “I ACCEPT” BELOW, YOU AGREE THAT YOU HAVE READ AND
                                            UNDERSTAND THE INFORMATION ABOVE AND THAT YOU WILL BE BOUND BY AND COMPLY
                                            WITH ALL OF THE TERMS AND CONDITIONS. DO NOT CLICK THE “I ACCEPT” BUTTON IF
                                            YOU DO NOT AGREE TO BE BOUND BY THE TERMS AND CONDITIONS .
                                        </p>
                                        <hr>
                                        <form method="post" enctype="multipart/form-data"
                                            action="{{ url('view-training', $training['id']) }}">
                                            {{ csrf_field() }}
                                            <div class="form-inline hidden-xs">
                                                <div class="pull-right bottom">
                                                    <button type="button" class="btn btn-default bottom"
                                                        data-dismiss="modal">I DON'T ACCEPT
                                                    </button>
                                                    <button id="yes-pass" type="submit" class="btn btn-primary bottom"
                                                        data-dismiss="modal" onclick="submit()">
                                                        I ACCEPT <i class="fa fa-arrow-circle-right"
                                                            aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="form-inline visible-xs">
                                                <div class="bottom">
                                                    <button type="button" class="btn btn-default bottom"
                                                        data-dismiss="modal">I DON'T ACCEPT
                                                    </button>
                                                    <button id="yes-pass" type="submit" class="btn btn-primary bottom"
                                                        data-dismiss="modal" onclick="submit()">
                                                        I ACCEPT <i class="fa fa-arrow-circle-right"
                                                            aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
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

    @include('frontend/global/foot')
    <script src="{{ asset('public/js/passing_training.js') }}"></script>
    <script>
        //     $(document).ready(function(){        
        //     $('#fader').css('display', 'none');

        //     $('body').on('click', '#yes-pass', function() {
        //         $('#fader').css('display', 'block');
        //         window.location.href = "https://portal-rgus.com/"+$(this).attr('data-href');
        //     });

        //   });
    </script>
</body>

</html>
