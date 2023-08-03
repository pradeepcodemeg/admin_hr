<div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <img src="{{asset('public')}}{{Auth::user()->image}}" width="45px" class="img-circle" ;
                 style="vertical-align: middle;">
            <a class="navbar-brand" href="">{{Auth::user()->firstname}} {{Auth::user()->lastname}}</a>
        </div>
        <div class="collapse navbar-collapse">
            @include('backend/global/sidebar-xs')
            @include('logout')
        </div>
</div> 