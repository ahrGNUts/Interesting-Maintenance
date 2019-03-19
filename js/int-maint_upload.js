/*
	@since 0.2
	@author Nitin Yawalkar (tweaked from code found here: https://nitinyawalkar.com/wordpress-custom-media-uploader-filters/55
*/

jQuery( document ).ready( function( $ ) {
	var file_frame;
	var wp_media_post_id = wp.media.model.settings.post.id;
	var set_to_post_id;

	jQuery('#logo_upload_image_button').on('click', function( event ){

		event.preventDefault();

		if ( file_frame ) {
			file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
			file_frame.open();
			return;
		} else {
			wp.media.model.settings.post.id = set_to_post_id;
		}

		file_frame = wp.media.frames.file_frame = wp.media({
			title: 'Select an image to upload',
			button: {
				text: 'Use this image',
			},
			library: {
				type: [ 'image' ]
				
			},
			multiple: false
		});

		file_frame.on( 'select', function() {
			attachment = file_frame.state().get('selection').first().toJSON();

			$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
			$( '#image_attachment_id' ).val( attachment.id );
			$( '#logo_path' ).val( attachment.url );

			wp.media.model.settings.post.id = wp_media_post_id;
		});

			file_frame.open();
	});

	jQuery( 'a.add_media' ).on( 'click', function() {
		wp.media.model.settings.post.id = wp_media_post_id;
	});
});
