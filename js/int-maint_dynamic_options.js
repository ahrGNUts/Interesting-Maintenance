/*
	@since 0.4
	@author Patrick Strube
*/
jQuery( document ).ready( function( $ ) {
	$('#sketch_type').on('change', function() {
		if($(this).val() != 'static'){
			$('.static_fields').hide();
			$('#multiple_fields').show();
		} else {
			$('.static_fields').show();
			$('#multiple_fields').hide();
		}
	});
});