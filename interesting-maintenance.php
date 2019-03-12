<?php
/*
	Plugin Name: Interesting Maintenance
	Description: Display a more interesting maintenance or coming soon page with OpenProcessing.org sketches
	Version: 0.4
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
			add_action( 'admin_post_process_intmaint_options', array( $this, 'process_intmaint_options' ) );
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
				
				// character counter
				wp_enqueue_script( 'int-maint_char_counter', plugin_dir_url( __FILE__ ) . 'js/int-maint_char_count.js', array( 'jquery' ) );
				
				// dynamic page behavior
				wp_enqueue_script( 'int-maint_dynamic_options', plugin_dir_url( __FILE__ ) . 'js/int-maint_dynamic_options.js', array( 'jquery' ) );
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
		
		function process_intmaint_options() {
			if( !wp_verify_nonce( $_POST['_int-maint_settings_nonce'], 'process_intmaint_options' ) ){
				wp_die( 
					__( 'Invalid nonce.', 'int-maint' ), 
					__( 'Error', 'int-maint' ), 
					array(
						'response' => 403,
						'back_link' => 'admin.php?page=interesting-maintenance-settings'
					)
				);
			} else {
				// site status
				if( isset( $_POST['site_status'] ) ){
					update_option( '_int-maint_site_status', $_POST['site_status'] );
				} else {
					update_option( '_int-maint_site_status', '1' );
				}
				
				// logo
				if( isset( $_POST['logo_path'] ) && isset( $_POST['image_attachment_id'] ) ){
					update_option( '_int-maint_site_logo_id', sanitize_text_field( $_POST['image_attachment_id'] ) );
					update_option( '_int-maint_site_logo_path', sanitize_text_field( $_POST['logo_path'] ) );
				} else {
					update_option( '_int-maint_site_logo_id', 0 );
					update_option( '_int-maint_site_logo_path', '' );
				}
				
				// message heading
				if( isset( $_POST['message_heading'] ) ){
					update_option( '_int-maint_message_heading', sanitize_text_field( $_POST['message_heading'] ) );
				} else {
					if( $_POST['site_status'] == 2 ){
						update_option( '_int-maint_message_heading', 'Coming Soon!' );
					} else {
						update_option( '_int-maint_message_heading', 'Down For Maintenance' );
					}
				}
				
				// message body
				if( isset( $_POST['message_body'] ) ){
					update_option( '_int-maint_message_body', sanitize_text_field( $_POST['message_body'] ) );
				} else {
					update_option( '_int-maint_message_body', '' );
				}
				
				// sketch types
				if( isset( $_POST['sketch_type'] ) ){
					$type = $_POST['sketch_type'];
					update_option( '_int-maint_sketch_type', $type );
					
					if( $type === 'static' ){
						if( isset( $_POST['sketch_id'] ) && is_numeric( $_POST['sketch_id'] ) ){
							update_option( '_int-maint_sketch_id', $_POST['sketch_id'] );
						} else {
							update_option( '_int-maint_sketch_id', '' );
						}
					} else if( $type === 'pop_random' ) {
						// TODO
					} else if( $type === 'random' ) {
						// TODO
					}
				}
			}
			
			wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
			exit;
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

	