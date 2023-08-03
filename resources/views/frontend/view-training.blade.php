<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend/global/head')
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @php
                    $root_dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
                    
                    $image_name = time();
                    
                    $img = $_SERVER['DOCUMENT_ROOT'] . $root_dir . 'public/assets/admin/pdf/' . $image_name . '.png';
                    
                    $name = $image_name;
                    
                    exec('convert "public/assets/admin/pdf/' . $training->file . '" -colorspace RGB "' . $img . '"', $output, $return_var);
                    
                    $cmd = sprintf('identify %s', 'public/assets/admin/pdf/' . $training->file);
                    exec($cmd, $output);
                    $number = count($output);
                @endphp
                <div class="w3-content">
                    @if (!empty($training->video_file) && ($training->slide == 'first_slide' || $training->slide == ''))
                        <div class="mySlides text-center">
                            <video id="first_video" width="680" height="500" controls>
                                <source src="{{ asset('public/assets/admin/video') }}/{{ $training->video_file }}"
                                    type="video/mp4">
                            </video>
                        </div>
                    @elseif(!empty($training->youtube_link))
                        @php
                            $url = parse_url($training->youtube_link, PHP_URL_QUERY);
                            parse_str($url, $output);
                        @endphp
                        <div class="mySlides text-center">
                            <iframe class="youtube-video" id="player" width="680" height="500"
                                src="https://www.youtube.com/embed/{{ $output['v'] }}">
                            </iframe>
                        </div>
                    @endif
                    @for ($i = 0; $i < $number; $i++)
                        @php
                            DB::table('certificate_image')->insert(['name' => $name . '-' . $i . '.png', 'url' => 'public/assets/admin/pdf/' . $name . '-' . $i . '.png']);
                        @endphp
                        <div style="background: url(../public/assets/admin/pdf/{{ $name }}-{{ $i }}.png)center center no-repeat;background-size: contain;height: 33em;width: 100%;"
                            class="mySlides">
                        </div>
                    @endfor
                    @if (!empty($training->video_file) && $training->slide == 'last_slide')
                        <div class="mySlides text-center">
                            <video width="680" height="500" controls>
                                <source src="{{ asset('public/assets/admin/video') }}/{{ $training->video_file }}"
                                    type="video/mp4">
                            </video>
                        </div>
                    @endif
                    <div class="mySlides text-center test_button">
                        <button class="btn btn-lg bg-primary" data-toggle="modal" data-target="#testModal">
                            Take a test
                        </button>
                    </div>
                </div>
                <div class="w3-center paginate">
                    <h3><span id="timer"></span></h3>
                    <div class="w3-section">
                        <button class="btn btn-primary icon-left" onclick="plusDivs(-1)" id="prev">Prev</button>
                        <span class="demo"></span> of @if ($training->video_file)
                            @php echo $number+1; @endphp
                        @else
                            {{ $number }}
                        @endif
                        <button class="btn btn-primary icon-right" onclick="plusDivs(1)" id="next"
                            disabled>Next</button>
                    </div>
                </div>
            </div>
            <div id="testModal" class="modal fade" role="dialog">
                <div class="modal-dialog container">

                    <!-- Modal content-->
                    <div class="modal-content row">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Terms and Conditions</h4>
                        </div>
                        <div class="modal-body">
                            <p>
                                These are guidelines for taking Test. The Test has unlimited time. The dates and times
                                during which
                                you can take each Test will be announced via email. Up to 3 attempts are allowed to pass
                                the
                                test.Once you start the Test you are responsible to finished, otherwise it won’t save
                                the results.
                                You will have 3 attempts per each Test. Passing score is up to 3 wrong answers. Once
                                Test closes you
                                will not be able to take it, so be sure to pay attention to the times as noted in your
                                email.
                                <br><br>
                                A few additional suggestions:<br>
                            <ol type="a">
                                <li>Keep a secured login. Don't leave a computer without logging out.</li>
                                <li>Best if you will use the latest version of the WEB browser.</li>
                                <li>You have unlimited attempts to pass the test.</li>
                                <li>Passing score is up to 3 wrong answers.</li>
                                <li>Keep <u>ONLY ONE</u> window open - the Test. Close all other windows and
                                    applications.</li>
                            </ol>
                            <br>
                            <b>To prevent connection lose we are recommend:</b>
                            <ol type="1">
                                <li>To use a hardwire (not wireless)</li>
                                <li>Test is compatible with any cell phones (Smartphones), IPhone, or IPad but it’s
                                    always better to
                                    do it on a PC or Laptop.
                                </li>
                            </ol>
                            <br>
                            <small>If you need further assistance, contact us at 847-291-8404.</small>
                            <br><br>
                            <b>These Tests are mandatory to complete on time for all Agency’s employees</b> in order to
                            be in compliance
                            with IDoA Community Care Program Administrative Rules <b>(Section 240.1535 In-home Service
                                Staff Positions,
                                Qualifications, Training and Responsibilities).Failure to do so will lead to
                                Suspension.</b>
                            </p>
                            <hr>
                            <form method="post" enctype="multipart/form-data"
                                action="{{ url('take-test', $training['id']) }}">
                                {{ csrf_field() }}
                                <div class="form-inline hidden-xs">
                                    <div class="pull-right bottom">
                                        <button onclick="submit()" type="button" class="btn btn-primary bottom"
                                            data-dismiss="modal">
                                            Ok
                                        </button>
                                    </div>
                                </div>

                                <div class="form-inline visible-xs">
                                    <div class="bottom">
                                        <button onclick="submit()" type="button"
                                            class="btn btn-primary bottom col-xs-12" data-dismiss="modal">
                                            Ok
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
    @include('frontend/global/foot')
    <script>
        $(document).ready(function() {
            $('#timer').html('00:03');
            setTimeout(function() {
                $('#timer').html('00:02');
            }, 1000);
            setTimeout(function() {
                $('#timer').html('00:01');
            }, 2000);
            setTimeout(function() {
                $('#timer').html('00:00 ');
                $('#next').removeAttr('disabled');
            }, 3000);
        })

        var slideIndex = 1;
        showDivs(slideIndex);

        function plusDivs(n) {
            if (n == 1) {
                $('#first_video').trigger('pause');
                var youtube = $('iframe.youtube-video').attr('src');
                $('iframe.youtube-video').attr('src', youtube);

            }
            $('.demo').html('');
            $('#next').attr('disabled', 'disabled');
            $('#timer').html('00:03');
            setTimeout(function() {
                $('#timer').html('00:02');
            }, 1000);
            setTimeout(function() {
                $('#timer').html('00:01');
            }, 2000);
            setTimeout(function() {
                $('#timer').html('00:00 ');
                $('#next').removeAttr('disabled');
            }, 3000);
            showDivs(slideIndex += n);
        }

        function showDivs(n) {
            $('.demo').html('');
            var i;
            var x = document.getElementsByClassName("mySlides");
            console.log("This is:" + x.length);
            if (n > x.length) {
                slideIndex = 1
            }
            if (n < 1) {
                slideIndex = x.length
            }
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            $('.demo').append(slideIndex);
            x[slideIndex - 1].style.display = "block";
            if (x[0].style.display == "block") {
                $('#prev').attr('disabled', 'disabled');
            } else {
                $('#prev').removeAttr('disabled');
            }
            if (slideIndex == x.length) {
                $('#timer').html('');
                setTimeout(function() {
                    $('#timer').html('');
                }, 1000);
                setTimeout(function() {
                    $('#timer').html('');
                }, 2000);
                setTimeout(function() {
                    $('#timer').html('');
                    $('#next').attr('disabled', 'disabled');
                }, 3000);
            }
        }
    </script>
</body>

</html>
