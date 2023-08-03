<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend/global/head')
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
            <h1 class="page-header">Blanks</h1>
            <!-- Display filename inside the button instead of its label -->
            <div class="clearfix">
                <div class="list-group">
                    @if(empty($blank_files))
                    <li class="list-group-item ficha">
                           No Records Found
                    </li>
                    @endif
                    @foreach($blank_files as $list)
                        <li class="list-group-item ficha">
                            {{$list['title']}}
                            <a href="user-blanks/download/{{$list['id']}}" class="a" target="_blank">
                                <i class="right icon-left glyphicon glyphicon-download-alt"></i>
                            </a>
                        </li>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@include('frontend/global/foot')
</body>
</html>
