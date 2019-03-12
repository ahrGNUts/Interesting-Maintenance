/*
	@since 0.4
	@author Patrick Strube
*/
jQuery( document ).ready( function( $ ) {
	$textarea = $('#message_body');
	$char_count = $('#char_count');
	
	$textarea.on('input', function() {
		$char_count.html($textarea.val().length);
		
		if($textarea.val().length < (1200 * 0.66)){
			$('#char_string').css('color', '#666');
		} else if($textarea.val().length > (1200 * 0.66) && $textarea.val().length < (1200 * 0.9)){
			$('#char_string').css('color', 'orange');
		} else {
			$('#char_string').css('color', 'red');
		}
	});
});