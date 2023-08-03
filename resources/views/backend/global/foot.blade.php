<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="{{asset('public/js/bootstrap.js')}}"></script>
<script type="text/javascript" src="{{asset('public/js/select2.full.js')}}"></script>
<script type="text/javascript" src="{{asset('public/js/custom.js')}}"></script>
<script type="text/javascript">
	var baseUrl = "{{url('/')}}";
</script>
<script type="text/javascript">
	$('.delete-btn').click(function(){		
		var msg_id = $(this).attr('data-id');
		if($(this).hasClass('outbox')){
			window.location = baseUrl+'/delete-outbox-message/'+msg_id;
		}else{
			window.location = baseUrl+'/delete-message/'+msg_id;
		}		
		return false;
	});
	$('*[data-href]').on("click",function(){
	  window.location = $(this).data('href');
	  return false;
	});
</script>
<script type="text/javascript">
	$(document).ready(function(){
		setInterval(function(){$('#msg-div').hide(); }, 3000);
	});
</script>