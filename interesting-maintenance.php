<?php
/*
	Plugin Name: Interesting Maintenance
	Description: Display a more interesting maintenance or coming soon page with OpenProcessing.org sketches
	Version: 0.2
	Author: Patrick Strube
	Text Domain: int-maint
	License: GPLv2
	License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/
defined( 'ABSPATH' ) || exit;

if( !class_exists( 'Interesting_Maintenance' ) ){
	final class Interesting_Maintenance {
		private static $instance = null;
		
		function __construct() {
			add_action( 'admin_menu', array( $this, 'admin_menu_item' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
			add_action( 'wp_handle_upload_prefilter', array( $this, 'prefilter_uploaded_files' ) );
			add_action( 'admin_footer', array( $this, 'help_modal_content' ) );
		}
		
		function admin_menu_item() {
			add_menu_page(
				'Interesting Maintenance Settings',
				'Int Maint Settings',
				'manage_options',
				'interesting_maintenance_settings',
				array( $this, 'settings_page_content' ),
				'dashicons-art'
			);
		}
		
		function settings_page_content() {
			require( 'views/int-maint_admin_settings.php' );
		}
		
		function enqueue_admin_scripts( $hook ) {
			if( $hook === "toplevel_page_interesting_maintenance_settings" ){
				wp_enqueue_style( 'int-maint_admin_styles', plugin_dir_url( __FILE__ ) . 'css/int-maint_admin_styles.css' );
				// media uploader
				wp_enqueue_script( 'int-maint_uploader', plugin_dir_url( __FILE__ ) . 'js/int-maint_upload.js', array( 'jquery' ) );
				wp_enqueue_media();
				
				// for help modal
				wp_enqueue_script( 'int-maint_help_modal', plugin_dir_url( __FILE__ ) . 'js/int-maint_help_modal.js', array( 'jquery-ui-core', 'jquery-ui-dialog' ) );
				wp_enqueue_style( 'wp-jquery-ui-dialog' );
			}	
		}
		
		/**
		 * Ensures non-image files can't be uploaded on the settings page.
		 *
		 * @return file object
		 */
		function prefilter_uploaded_files( $file ) {
			
			$settings_page = strpos( $_SERVER['HTTP_REFERER'], '?page=interesting_maintenance') !== false;
 
			if( $settings_page && is_admin() && current_user_can( 'administrator' ) ) {
			
				// bmp not in this list because the uploader doesn't seem to allow it anyway
				$extensions = array(
					'jpeg',
					'jpg',
					'gif',
					'png'
				);
			
				$ext = pathinfo( $file['name'], PATHINFO_EXTENSION );
			 
				if ( !in_array( $ext, $extensions ) ) {
					$file['error'] = "The uploaded .". $ext ." file is not supported. Please upload an image file.";
				}
			
			}

			return $file;
		}
		
		function help_modal_content() {
			require( 'views/int-maint_help_modal.php' );
		}
		
		/**
		 * Return class instance.
		 *
		 * @return static Instance of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
	
			return self::$instance;
		}
	}// class
	
	Interesting_Maintenance::get_instance();
}

	