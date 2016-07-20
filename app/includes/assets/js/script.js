$(document).on('ready', function(){
	$("#password_strength").on('input', function(){
		$("#password_length").text($("#password_strength").val());
	});
				
	$("#generate_button").click(function(e){
		var length = $("#password_strength").val();
		$.get("includes/functions/password_generator.php?length=" + length, function(response){
			$("#password_generated").html(response);
		});
	});

	$("#use_password_button").click(function(e){
		$("#password").val($("#password_generated").text());
		$("#password_repeat").val($("#password_generated").text());
	});

	/* Adapted from: http://jsfiddle.net/sVQwA/ */
	$("#select_all_checkboxes").click(function () {
		$(".checkboxes_approval").prop('checked', $(this).prop('checked'));
	});

	/*
	$("#collapse_comments").on('click', function(){
		if($(this).html() == "Show Comments"){
			$(this).html("Hide Comments");
		} else{
			$(this).html("Show Comments");
		}
	});
	*/

	$(".fancybox").fancybox({
    	padding: 20,
		openEffect : 'elastic',
		openSpeed  : 150,
		closeEffect : 'elastic',
		closeSpeed  : 150,
		closeClick : true,
    });

	$('textarea').wysihtml5();

});