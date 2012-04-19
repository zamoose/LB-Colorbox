jQuery(document).ready( function($) {
	$(".save a").click( function() {
		//alert($(this));
		var data = {
			action: 'kuler_response',
			kuler: $(this).prop('href')
		};
		$.post(the_ajax_script.ajaxurl, data, function(response) {
			alert(response);
		});
		$(this).closest('tr').addClass('kuler-saved');
		return false;
	});
});