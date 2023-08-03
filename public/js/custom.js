$(document).ready(function() {
    $('.js-multi-select').select2();
});

$(document).ready(function(){
	$(".reply").click(function() {
		$("body").toggleClass("reply-mail-popup");
	});

	$(".forward").click(function() {
		$("body").toggleClass("forward-mail-popup");
	});
});


$(document).ready(function(){
	$('.nav-sidebar .submenu-parent a').click(function() {
		$(this).next(".submenu").slideToggle("open");
	});
});