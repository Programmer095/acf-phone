<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'acf_field_phone' ) ) :

	class acf_field_phone extends acf_field {

		public $settings;

		/**
		 * acf_field_phone constructor.
		 *
		 * This function will setup the field type data
		 *
		 * @param $settings (array) The plugin settings
		 */
		function __construct( $settings ) {
			$this->name      = 'phone';
			$this->label     = __( 'Phone', 'acf-phone' );
			$this->category  = 'basic';
			$this->defaults  = array(
				'initial_country' => 'CA',
			);
			$this->settings  = $settings;
			$this->phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
			parent::__construct();
		}

		/**
		 * Create extra settings for your field. These are visible when editing a field
		 *
		 * @param $field (array) the $field being edited
		 */
		function render_field_settings( $field ) {
			acf_render_field_setting( $field, array(
				'label'        => __( 'Initial Country', 'acf-phone' ),
				'instructions' => __( 'Country code used for the initial phone number format.', 'acf-phone' ),
				'type'         => 'text',
				'name'         => 'initial_country',
			) );
		}

		/**
		 * Create the HTML interface for your field
		 *
		 * @param $field (array) the $field being rendered
		 */
		function render_field( $field ) {
			?>
            <div class="acf-input-wrap phone">
                <input type="tel" name="<?= esc_attr( $field['name'] ) ?>"
                       value="<?= esc_attr( $field['value'] ) ?>"
                       data-initial-country="<?= esc_attr( $field['initial_country'] ) ?>"/>
                <span class="error-msg hide"></span>
            </div>
			<?php
		}

		/**
		 *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
		 *  Use this action to add CSS + JavaScript to assist your render_field() action.
		 */
		function input_admin_enqueue_scripts() {
			$url     = $this->settings['url'];
			$version = $this->settings['version'];

			wp_register_script( 'intl-phone-input', "{$url}assets/js/intlTelInput.min.js", array( 'jquery' ), $version );
			wp_register_script( 'acf-input-phone', "{$url}assets/js/input.js", array(
				'acf-input',
				'intl-phone-input'
			), $version );
			wp_localize_script( 'acf-input-phone', 'acfPhoneSettings', $this->settings );
			wp_enqueue_script( 'acf-input-phone' );

			wp_register_style( 'intl-phone-input', "{$url}assets/css/intlTelInput.css", array( 'acf-input' ), $version );
			wp_enqueue_style( 'intl-phone-input' );
		}

		/**
		 *  This action is called in the admin_head action on the edit screen where your field is created.
		 *  Use this action to add CSS and JavaScript to assist your render_field() action.
		 */
		function input_admin_head() {
		}

		/**
		 *  This function is called once on the 'input' page between the head and footer
		 *  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and
		 *  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
		 *  seen on comments / user edit forms on the front end. This function will always be called, and includes
		 *  $args that related to the current screen such as $args['post_id']
		 *
		 * @param $args
		 */
		function input_form_data( $args ) {
		}


		/**
		 *  This action is called in the admin_footer action on the edit screen where your field is created.
		 *  Use this action to add CSS and JavaScript to assist your render_field() action.
		 */
		function input_admin_footer() {
		}

		/**
		 *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
		 *  Use this action to add CSS + JavaScript to assist your render_field_options() action.
		 */
		function field_group_admin_enqueue_scripts() {
		}

		/**
		 *  This action is called in the admin_head action on the edit screen where your field is edited.
		 *  Use this action to add CSS and JavaScript to assist your render_field_options() action.
		 */
		function field_group_admin_head() {
		}

		/**
		 * This filter is applied to the $value after it is loaded from the db
		 *
		 * @param  $value (mixed) the value found in the database
		 * @param  $post_id (mixed) the $post_id from which the value was loaded
		 * @param  $field (array) the field array holding all the field options
		 *
		 * @return $value
		 */
		function load_value( $value, $post_id, $field ) {
			return $value;
		}

		/**
		 * This filter is applied to the $value before it is saved in the db
		 *
		 * @param  $value (mixed) the value found in the database
		 * @param  $post_id (mixed) the $post_id from which the value was loaded
		 * @param  $field (array) the field array holding all the field options
		 *
		 * @return $value
		 */
		function update_value( $value, $post_id, $field ) {
			return $value;
		}

		/**
		 * This filter is appied to the $value after it is loaded from the db and before it is returned to the template
		 *
		 * @param  $value (mixed) the value which was loaded from the database
		 * @param  $post_id (mixed) the $post_id from which the value was loaded
		 * @param  $field (array) the field array holding all the field options
		 *
		 * @return $value (mixed) the formatted value
		 */
		function format_value( $value, $post_id, $field ) {
			if ( empty( $value ) ) {
				return $value;
			}

			return $value;
		}

		/**
		 *  This filter is used to perform validation on the value prior to saving.
		 *  All values are validated regardless of the field's required setting. This allows you to validate and return
		 *  messages to the user if the value is not correct
		 *
		 * @param  $valid (boolean) validation status based on the value and the field's required setting
		 * @param  $value (mixed) the $_POST value
		 * @param  $field (array) the field array holding all the field options
		 * @param  $input (string) the corresponding input name for $_POST value
		 *
		 * @return $valid
		 */
		function validate_value( $valid, $value, $field, $input ) {
			if ( empty( $value ) ) {
				return $valid;
			}
			try {
				$phone_number = $this->phoneUtil->parse( $value, $field['initial_country'] );
			} catch ( \libphonenumber\NumberParseException $e ) {
				return $e->getMessage();
			}
			if ( ! $this->phoneUtil->isValidNumber( $phone_number ) ) {
				$valid = __( "Phone number is invalid", 'acf-phone' );
			}

			return $valid;
		}

		/**
		 *  This action is fired after a value has been deleted from the db.
		 *  Please note that saving a blank value is treated as an update, not a delete
		 *
		 * @param  $post_id (mixed) the $post_id from which the value was deleted
		 * @param  $key (string) the $meta_key which the value was deleted
		 */
		function delete_value( $post_id, $key ) {
		}

		/**
		 *  This filter is applied to the $field after it is loaded from the database
		 *
		 * @param  $field (array) the field array holding all the field options
		 *
		 * @return  $field
		 */
		function load_field( $field ) {
			return $field;
		}

		/**
		 *  This filter is applied to the $field before it is saved to the database
		 *
		 * @param  $field (array) the field array holding all the field options
		 *
		 * @return  $field
		 */
		function update_field( $field ) {
			return $field;
		}

		/**
		 *  This action is fired after a field is deleted from the database
		 *
		 * @param  $field (array) the field array holding all the field options
		 */
		function delete_field( $field ) {
		}

	}

	new acf_field_phone( $this->settings );

endif;
