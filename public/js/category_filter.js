$('#select').ready(function(){
		if($('#select').val() == "all"){
			$('#space').show();
			$('#space3').hide();
			$('#space2').hide();
			$('#space1').hide();
		}
});

$('#select').change(function(){
	if($('#select').val() == "all"){
		$('#space').show();
		$('#space3').hide();
		$('#space2').hide();
		$('#space1').hide();
	} 
	if($('#select').val() == "all_users"){
		$('#space1').show();
		$('#space3').hide();
		$('#space2').hide();
		$('#space').hide();
	} 
	if($('#select').val() == "completed_training"){
		$('#space2').show();
		$('#space3').hide();
		$('#space').hide();
		$('#space1').hide();
	} 
	if($('#select').val() == "uncompleted_training"){
		$('#space3').show();
		$('#space').hide();
		$('#space2').hide();
		$('#space1').hide();
	}
});

$("#all").change(function(){
var selectedCountry = $(this).children("option:selected").val();
if(selectedCountry == 0){
	$('#all option').each(function() {
		$(this).prop('selected',true);
	});
}
});

$("#all_users").change(function(){
var selectedCountry = $(this).children("option:selected").val();
if(selectedCountry == 0){
	$('#all_users option').each(function() {
		$(this).prop('selected',true);
	});
}
});

$("#completed_training").change(function(){
var selectedCountry = $(this).children("option:selected").val();
if(selectedCountry == 0){
	$('#completed_training option').each(function() {
		$(this).prop('selected',true);
	});
}
});

$("#uncompleted_training").change(function(){
var selectedCountry = $(this).children("option:selected").val();
if(selectedCountry == 0){
	$('#uncompleted_training option').each(function() {
		$(this).prop('selected',true);
	});
}
});
