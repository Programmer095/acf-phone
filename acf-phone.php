<?php
/*
Plugin Name: Advanced Custom Fields: Phone
Plugin URI: https://gitlab.ledevsimple.ca/wordpress/acf-phone
Description: Phone number field for ACF.
Version: 0.1.0
Author: Pascal Martineau <pascal@lewebsimple.ca>
Author URI: https://lewebsimple.ca
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'acf_plugin_phone' ) ) :

	class acf_plugin_phone {

		function __construct() {
			$this->settings = array(
				'version' => '0.1.0',
				'url'     => plugin_dir_url( __FILE__ ),
				'path'    => plugin_dir_path( __FILE__ )
			);

			load_plugin_textdomain( 'acf-phone', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

			add_action( 'acf/include_field_types', array( $this, 'include_field_types' ) ); // v5
			add_action( 'acf/register_fields', array( $this, 'include_field_types' ) ); // v4
		}

		function include_field_types( $version = false ) {
			if ( ! $version ) {
				$version = 4;
			}
			include_once( 'fields/acf-phone-v' . $version . '.php' );
		}

	}

	new acf_plugin_phone();

endif;
