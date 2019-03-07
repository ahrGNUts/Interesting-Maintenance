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

	