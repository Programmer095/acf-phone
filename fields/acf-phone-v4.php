<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'acf_field_phone' ) ) :

	class acf_field_phone extends acf_field {

		var $settings,
			$defaults;

		/**
		 * This function will setup the field type data
		 *
		 * @param $settings (array) The plugin settings
		 */
		function __construct( $settings ) {
			$this->name     = 'phone';
			$this->label    = __( 'Phone' );
			$this->category = __( "Basic", 'acf' ); // Basic, Content, Choice, etc
			$this->defaults = array();
			$this->settings = $settings;
			parent::__construct();
		}

		/**
		 *  Create extra options for your field. This is rendered when editing a field.
		 *  The value of $field['name'] can be used (like below) to save extra data to the $field
		 *
		 * @param  $field - an array holding all the field's data
		 */
		function create_options( $field ) {
			$field = array_merge( $this->defaults, $field );
			$key   = $field['name'];
			?>
          <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
              <label><?php _e( "Preview Size", 'acf' ); ?></label>
              <p class="description"><?php _e( "Thumbnail is advised", 'acf' ); ?></p>
            </td>
            <td>
				<?php
				do_action( 'acf/create_field', array(
					'type'    => 'radio',
					'name'    => 'fields[' . $key . '][preview_size]',
					'value'   => $field['preview_size'],
					'layout'  => 'horizontal',
					'choices' => array(
						'thumbnail'      => __( 'Thumbnail' ),
						'something_else' => __( 'Something Else' ),
					)
				) );
				?>
            </td>
          </tr>
			<?php
		}

		/**
		 *  Create the HTML interface for your field
		 *
		 * @param  $field - an array holding all the field's data
		 */
		function create_field( $field ) {
			$field = array_merge( $this->defaults, $field );
			?>
          <div>
          </div>
			<?php
		}

		/**
		 *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
		 *  Use this action to add CSS + JavaScript to assist your create_field() action.
		 */
		function input_admin_enqueue_scripts() {
			$url     = $this->settings['url'];
			$version = $this->settings['version'];
			wp_register_script( 'acf-input-phone', "{$url}assets/js/input.js", array( 'acf-input' ), $version );
			wp_enqueue_script( 'acf-input-phone' );
		}

		/**
		 *  This action is called in the admin_head action on the edit screen where your field is created.
		 *  Use this action to add CSS and JavaScript to assist your create_field() action.
		 */
		function input_admin_head() {
		}

		/**
		 *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
		 *  Use this action to add CSS + JavaScript to assist your create_field_options() action.
		 */
		function field_group_admin_enqueue_scripts() {
		}

		/**
		 *  This action is called in the admin_head action on the edit screen where your field is edited.
		 *  Use this action to add CSS and JavaScript to assist your create_field_options() action.
		 */
		function field_group_admin_head() {
		}

		/**
		 *  This filter is applied to the $value after it is loaded from the db
		 *
		 * @param  $value - the value found in the database
		 * @param  $post_id - the $post_id from which the value was loaded
		 * @param  $field - the field array holding all the field options
		 *
		 * @return  $value - the value to be saved in the database
		 */
		function load_value( $value, $post_id, $field ) {
			return $value;
		}

		/**
		 *  This filter is applied to the $value before it is updated in the db
		 *
		 * @param  $value - the value which will be saved in the database
		 * @param  $post_id - the $post_id of which the value will be saved
		 * @param  $field - the field array holding all the field options
		 *
		 * @return  $value - the modified value
		 */
		function update_value( $value, $post_id, $field ) {
			return $value;
		}

		/**
		 *  This filter is applied to the $value after it is loaded from the db and before it is passed to the create_field action
		 *
		 * @param  $value - the value which was loaded from the database
		 * @param  $post_id - the $post_id from which the value was loaded
		 * @param  $field - the field array holding all the field options
		 *
		 * @return  $value  - the modified value
		 */
		function format_value( $value, $post_id, $field ) {
			$field = array_merge( $this->defaults, $field );

			return $value;
		}

		/**
		 *  This filter is applied to the $value after it is loaded from the db and before it is passed back to the API functions such as the_field
		 *
		 * @param  $value - the value which was loaded from the database
		 * @param  $post_id - the $post_id from which the value was loaded
		 * @param  $field - the field array holding all the field options
		 *
		 * @return  $value  - the modified value
		 */
		function format_value_for_api( $value, $post_id, $field ) {
			$field = array_merge( $this->defaults, $field );

			return $value;
		}

		/**
		 *  This filter is applied to the $field after it is loaded from the database
		 *
		 * @param  $field - the field array holding all the field options
		 *
		 * @return  $field - the field array holding all the field options
		 */
		function load_field( $field ) {
			return $field;
		}

		/**
		 *  This filter is applied to the $field before it is saved to the database
		 *
		 * @param  $field - the field array holding all the field options
		 * @param  $post_id - the field group ID (post_type = acf)
		 *
		 * @return  $field - the modified field
		 */
		function update_field( $field, $post_id ) {
			return $field;
		}

	}

	new acf_field_phone( $this->settings );

endif;
