<?php
/*
	Plugin Name: Interesting Maintenance
	Description: Display a more interesting maintenance or coming soon page with OpenProcessing.org sketches
	Version: 0.8
	Author: Patrick Strube
	Text Domain: int-maint
	License: GPLv2
	License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/
defined( 'ABSPATH' ) || exit;

define( 'INTMAINT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'INTMAINT_PLUGIN_ROOT', plugin_dir_path( __FILE__ ) );

if( !class_exists( 'Interesting_Maintenance' ) ){
	register_uninstall_hook( __FILE__, array( 'Interesting_Maintenance', 'on_uninstall' ) );
	
	final class Interesting_Maintenance {
		private static $instance = null;
		
		function __construct() {
			add_action( 'admin_menu', array( $this, 'admin_menu_item' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
			add_action( 'wp_handle_upload_prefilter', array( $this, 'prefilter_uploaded_files' ) );
			add_action( 'admin_post_process_intmaint_options', array( $this, 'process_intmaint_options' ) );
			
			if( get_option( '_int-maint_site_status' ) != 1 ){
				if( function_exists( 'bp_is_active' ) ){
					add_action( 'template_redirect', array( $this, 'render_template' ), 9 );
				} else {
					add_action( 'template_redirect', array( $this, 'render_template' ) );
				}
			}
			
			$current_WP_version = get_bloginfo('version');
            if ( version_compare( $current_WP_version, '4.7', '>=' ) ) {
                add_filter( 'rest_authentication_errors', array( &$this, 'only_allow_admin_rest_access' ) );
            }
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
				wp_enqueue_style( 'int-maint_admin_styles', INTMAINT_PLUGIN_URL . 'css/int-maint_admin_styles.css' );
				// media uploader
				wp_enqueue_script( 'int-maint_uploader', INTMAINT_PLUGIN_URL . 'js/int-maint_upload.js', array( 'jquery' ) );
				wp_enqueue_media();
				
				// character counter
				wp_enqueue_script( 'int-maint_char_counter', INTMAINT_PLUGIN_URL . 'js/int-maint_char_count.js', array( 'jquery' ) );
				
				// dynamic page behavior
				wp_enqueue_script( 'int-maint_dynamic_options', INTMAINT_PLUGIN_URL . 'js/int-maint_dynamic_options.js', array( 'jquery' ) );
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
				
				// site title
				if( isset( $_POST['seo_title'] ) ){
					update_option( '_int-maint_seo_title', sanitize_text_field( $_POST['seo_title'] ) );
				} else {
					update_option( '_int-maint_seo_title', '' );
				}
				
				// site description
				if( isset( $_POST['seo_desc'] ) ){
					update_option( '_int-maint_seo_desc', sanitize_text_field( $_POST['seo_desc'] ) );
				} else {
					update_option( '_int-maint_seo_desc', '' );
				}
				
				// sketch types
				if( isset( $_POST['sketch_type'] ) ){
					$type = $_POST['sketch_type'];
					update_option( '_int-maint_sketch_type', $type );
					
					if( $type === 'static' ){
						if( isset( $_POST['sketch_id'] ) && is_numeric( $_POST['sketch_id'] ) ){
							
							if( $_POST['sketch_width'] > 1 && $_POST['sketch_width'] < 5000 ) {
								$width = intval( $_POST['sketch_width'] );
							} else {
								$width = 650;
							}
							
							if( $_POST['sketch_height'] > 1 && $_POST['sketch_height'] < 5000){
								$height = intval( $_POST['sketch_height'] );
							} else {
								$height = 650;
							}
							
							update_option( '_int-maint_sketch_height', $height );
							update_option( '_int-maint_sketch_width', $width );
							update_option( '_int-maint_sketch_id', intval( $_POST['sketch_id'] ) );
						} else {
							update_option( '_int-maint_sketch_id', '' );
							update_option( '_int-maint_sketch_width', 0 );
							update_option( '_int-maint_sketch_height', 0 );
						}
					} else if( $type == 'multiple' ){
						$index = 0;
						
						foreach( $_POST['multi'] as $row ) {
							$write = array(
								'id' => intval( $row['id'] ),
								'width' => intval( $row['width'] ),
								'height' => intval( $row['height'] )
							);
							
							update_option( '_int-maint_multi_data_' . $index, serialize( $write ) );
							$index++;
						}
						
						update_option( '_int-maint_multi_count', $index );
						
						for( $i = $index + 1; $i < 15; $i++ ) {
							delete_option( '_int-maint_multi_data_' . $i );
						}
					}
					// TODO: more planning for random sketches
					/*else if( $type === 'pop_random' ) {
						// TODO
					} else if( $type === 'random' ) {
						// TODO
					}*/ 
				}
			}
			
			wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
			exit;
		}
		
		function render_template() {
			if( !current_user_can( 'administrator' ) ){
				require( 'template/int-maint_template.php' );
				exit;
			}
		}
		
		function only_allow_admin_rest_access( $access ) {
	        if( !current_user_can( 'administrator' ) ) {
	            return new WP_Error( 'admin_only_rest', __( 'Only administrators can access the REST API.', 'int-maint' ), array( 'status' => rest_authorization_required_code() ) );
	        }
	
	        return $access;
    		}
    		
    		public static function on_uninstall() {
	    		// security checks
	    		if( !current_user_can( 'activate_plugins' ) )
	    			return;
	    		
	    		check_admin_referer( 'bulk-plugins' );
	    		
	    		if ( __FILE__ != WP_UNINSTALL_PLUGIN )
				return;
	    		
	    		$keys = array(
		    		'site_status',
		    		'site_logo_id',
		    		'site_logo_path',
		    		'message_heading',
		    		'message_body',
		    		'seo_title',
		    		'seo_desc',
		    		'sketch_id',
		    		'sketch_width',
		    		'sketch_height',
		    		'multi_count',
		    		'multi_data_0',
		    		'multi_data_1',
		    		'multi_data_2',
		    		'multi_data_3',
		    		'multi_data_4',
		    		'multi_data_5',
		    		'multi_data_6',
		    		'multi_data_7',
		    		'multi_data_8',
		    		'multi_data_9',
		    		'multi_data_10',
		    		'multi_data_11',
		    		'multi_data_12',
		    		'multi_data_13',
		    		'multi_data_14'
	    		);
	    		
	    		// cleaning up site options
	    		foreach( $keys as $key ){
		    		delete_option( '_int-maint_' . $key );
	    		}
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
	