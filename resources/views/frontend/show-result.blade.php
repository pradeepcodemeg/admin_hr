<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend/global/head')
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>  
<div class="container">
    <div class="row bottom">
        <div class="col-md-12">
            <form enctype="multipart/form-data" action="{{url('submit-test', $training['id'])}}" method="POST">
                              {{ csrf_field() }}
          @foreach($results as $q)               
            <div class="top">
              <h6 class="list-right"><b>{!! ($q['question']) !!}</b></h6>
              <ul type="none">
                @foreach($q['options'] as $key=>$op)   
                  @if(!empty($op))                 
                    @if(!empty($q['selected_option']))   
                        @if(in_array($key,array_values($q['selected_option'])))
                          <li>
                            <input type="checkbox" checked="checked">
                            @if($key == $q['correct_option'])
                                <b class="text-success">{{$op}} (correct answer)</b>
                            @else
                              <b class="text-danger">{{$op}}</b>
                            @endif
                          </li>
                        @elseif($key == $q['correct_option'])
                          <li><input type="checkbox"> <b>{{$op}} (correct answer)</b></li>
                        @else
                          <li><input type="checkbox"> <b>{{$op}}</b></li>
                        @endif
                    @else
                          <li>
                            <input type="checkbox">
                            @if($key == $q['correct_option'])
                              <b>{{$op}} (correct answer)</b>
                            @else
                              <b>{{$op}}</b>
                            @endif
                          </li>
                    @endif
                  @endif
                @endforeach
              </ul> 
            </div>           
          @endforeach
        </div>
        <div class="col-md-12 top">
          <p class="list-right">Incorrect answers:  @php 
            echo count($results) - count($corr);
           @endphp of @php echo count($results) @endphp</p>
            <a href="../repeat-test/{{$training['id']}}">
            <span class="btn btn-primary float-right icon-left  bottom">Try Again</span>
            </a>
            </form>
        </div>
    </div>
</div>
@include('frontend/global/foot')
</body>
</html>
