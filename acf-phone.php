<?php
/*
Plugin Name: Advanced Custom Fields: Phone
Plugin URI: https://gitlab.ledevsimple.ca/wordpress/acf-phone
Description: Phone number field for ACF.
Version: 0.2.0
Author: Pascal Martineau <pascal@lewebsimple.ca>
Author URI: https://lewebsimple.ca
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/inc/countries.php';

if ( ! class_exists( 'acf_plugin_phone' ) ) :

	class acf_plugin_phone {

		function __construct() {
			$this->settings = array(
				'version' => '0.2.0',
				'url'     => plugin_dir_url( __FILE__ ),
				'path'    => plugin_dir_path( __FILE__ ),
				'errors'  => array(
					0 => __( "Valid phone number", 'acf-phone' ),
					1 => __( "Invalid country code", 'acf-phone' ),
					2 => __( "Phone number is too short", 'acf-phone' ),
					3 => __( "Phone number is too long", 'acf-phone' ),
					4 => __( "Invalid phone number", 'acf-phone' )
				),
			);

			load_plugin_textdomain( 'acf-phone', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

			add_action( 'acf/include_field_types', array( $this, 'include_field_types' ) );
		}

		function include_field_types( $version = false ) {
			if ( ! $version ) {
				$version = 5;
			}
			include_once( 'fields/acf-phone-v' . $version . '.php' );
		}

	}

	new acf_plugin_phone();

endif;
