/*
	@since 0.4
	@author Patrick Strube
*/
jQuery( document ).ready( function( $ ) {
	$('#sketch_type').on('change', function() {
		if($(this).val() != 'static'){
			$('#sketch_id').hide();
			$('#single_desc').hide();
		} else {
			$('#sketch_id').show();
			$('#single_desc').show();
		}
	});
});