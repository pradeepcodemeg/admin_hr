<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend/global/head')
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="w3-content"> 
                   <form enctype="multipart/form-data" action="{{url('show-result', $training['id'])}}" method="POST">
                    <input type="text" value="{{$training['id']}}" name="id" hidden>
                              {{ csrf_field() }}
                    @php
                      $i = 1;
                    @endphp
                   @foreach($questions as $question)
                  <div class="queSlide">
                    <input type="text" name="question[]" value="{{$question->question}}" hidden>
                    <h2 class="list-right"><b>{{$question->question}}</b></h2>
                    <ul type="none">
                    <li>
                      @if(!empty($question->option_one))
                      <input type="checkbox" name="selected_option[{{$question->id}}][{{$i++}}]" value="option_one"> {{$question->option_one}}
                      @endif
                    </li>
                    <li>                        
                    @if(!empty($question->option_two))
                    <input type="checkbox" name="selected_option[{{$question->id}}][{{$i++}}]" value="option_two"> {{$question->option_two}}
                    @endif
                      </li>
                      <li>                        
                    @if(!empty($question->option_three))
                    <input type="checkbox" name="selected_option[{{$question->id}}][{{$i++}}]" value="option_three"> {{$question->option_three}}
                    @endif
                      </li>
                      <li>                        
                    @if(!empty($question->option_four))
                    <input type="checkbox" name="selected_option[{{$question->id}}][{{$i++}}]" value="option_four"> {{$question->option_four}}
                    @endif
                      </li>
                      @php
                        $i = 1;
                      @endphp
                    </ul>
                  </div>  
                  @endforeach
               @if(!empty($question))
                <div class="queSlide text-center test_button">
                   <button type="submit" class="btn btn-lg bg-primary">
                           Finish Test
                   </button>
               </div>
               @else
                  <a class="queSlide text-center test_button" href="../user-trainings">
                     <span class="btn btn-lg bg-primary">
                           Exit
                   </span>
                  </a>
               @endif 
                 </form>
            </div>
            <div class="w3-center paginate">
              <div class="w3-section">
                <button class="btn btn-primary icon-left" onclick="plusDivs(-1)" id="prev">Prev</button>
                <span class="demo"></span> of @php echo count($questions)@endphp
                <button class="btn btn-primary icon-right" onclick="plusDivs(1)" id="next">Next</button>
              </div>
            </div>
        </div>
    </div>
</div>
@include('frontend/global/foot')
<script>
var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) { 
    $('.demo').html(''); 
  showDivs(slideIndex += n);
}

function showDivs(n) {
  $('.demo').html('');
  var i;
  var x = document.getElementsByClassName("queSlide");
  console.log("This is:"+x.length);
  if (n > x.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = x.length}
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";
  }
  $('.demo').append(slideIndex);
  x[slideIndex-1].style.display = "block";
  if(x[0].style.display == "block"){
        $('#prev').attr('disabled','disabled');
  }else{
        $('#prev').removeAttr('disabled');
  }
  if(slideIndex == x.length){
        $('#next').attr('disabled', 'disabled');
  }else{
        $('#next').removeAttr('disabled');
  }
}
</script>
</body>
</html>
