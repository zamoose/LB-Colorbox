jQuery(document).ready( function($) {
	$(".save a").click( function() {
		var data = {
			action: "",
			kuler: 'this will be echoed back'
		};
		$.post(the_ajax_script.ajaxurl, data, function(response) {
			alert(response);
		});
		return false;
	});
});