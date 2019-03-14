<?php
/*
	Template helper functions
	
	@since 0.5
	@author Patrick Strube
*/
defined( 'ABSPATH' ) || exit;

function intmaint_build_sketch_url() {
	$base_url = 'https://openprocessing.org/sketch/';
	$type = get_option( '_int-maint_sketch_type' );
	
	switch( $type ){
		case 'static':
			return $base_url . get_option( '_int-maint_sketch_id' ) . '/embed';
			break;
		case 'pop_random':
			//TODO
			break;
		case 'random':
			//TODO
			break;
		default:
			return 'https://openprocessing.org';
			break;
	}
}

function intmaint_get_message_heading() {
	$message = get_option( '_int-maint_message_heading' );
	
	if( empty( $message ) ){
		$status = (int)get_option( '_int-maint_site_status' );
		
		if( $status == 2 ) {
			echo 'Coming Soon!';
		} else {
			echo 'Maintenance Mode';
		}
	} else {
		echo $message;
	}
}

function intmaint_get_message_body() {
	echo get_option( '_int-maint_message_body' );
}

function intmaint_echo_styles_scripts() {
	echo '<link rel="stylesheet" href="' . INTMAINT_PLUGIN_URL . 'template/css/bootstrap.min.css">' . "\n";
	echo '<link rel="stylesheet" href="' . INTMAINT_PLUGIN_URL . 'template/css/int-maint_styles.css">' . "\n";
	echo '<script href="' . INTMAINT_PLUGIN_URL . 'template/js/bootstrap.min.js"></script>' . "\n";
}

function intmaint_get_sketch_height() {
	echo get_option( '_int-maint_sketch_height' );
}

function intmaint_get_sketch_width() {
	echo get_option( '_int-maint_sketch_width' );
}

function intmaint_get_logo_path() {
	echo get_option( '_int-maint_site_logo_path' );
}

/*
	@param element -- string
	
	$element should be either 'title' or 'desc' and will output data accordingly
*/
function intmaint_get_seo_data( $element ) {
	$custom_data = get_option( '_int-maint_seo_' . $element );
	
	if( !empty( $custom_data ) ){
		echo $custom_data;
	} else {
		// if $element == title, get current blog name (title) else get current blog description
		echo get_bloginfo( $element == 'title' ? 'name' : 'description' );	
	}
}
