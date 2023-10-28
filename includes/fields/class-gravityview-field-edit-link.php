<?php
/**
 * @file class-gravityview-field-edit-link.php
 * @package GravityView
 * @subpackage includes\fields
 */

/**
 * Add custom options for entry_link fields
 */
class GravityView_Field_Edit_Link extends GravityView_Field {

	var $name = 'edit_link';

	var $contexts = array( 'single', 'multiple' );

	/**
	 * @var bool
	 * @since 1.15.3
	 */
	var $is_sortable = false;

	/**
	 * @var bool
	 * @since 1.15.3
	 */
	var $is_searchable = false;

	var $group = 'gravityview';

	var $icon = 'dashicons-welcome-write-blog';

	public function __construct() {
		$this->label = esc_html__( 'Link to Edit Entry', 'gk-gravityview' );
		$this->description = esc_html__('A link to edit the entry. Visible based on View settings.', 'gk-gravityview');
		parent::__construct();
	}

	/**
	 * Add as a default field, outside those set in the Gravity Form form
	 *
	 * @since 2.10 Moved here from GravityView_Admin_Views::get_entry_default_fields
	 *
	 * @param array $entry_default_fields Existing fields
	 * @param string|array $form form_ID or form object
	 * @param string $zone Either 'single', 'directory', 'edit', 'header', 'footer'
	 *
	 * @return array
	 */
	public function add_default_field( $entry_default_fields, $form = array(), $zone = '' ) {

		if ( 'directory' !== $zone ) {
			return $entry_default_fields;
		}

		$entry_default_fields[ $this->name ] = array(
			'label' => $this->label,
			'type'  => $this->name,
			'desc'  => $this->description,
			'icon'  => $this->icon,
		);

		return $entry_default_fields;
	}

	public function field_options( $field_options, $template_id, $field_id, $context, $input_type, $form_id ) {

		if( 'edit' === $context ) {
			return $field_options;
		}

		// Always a link, never a filter
		unset( $field_options['show_as_link'], $field_options['search_filter'] );

		// Edit Entry link should only appear to visitors capable of editing entries
		unset( $field_options['only_loggedin'], $field_options['only_loggedin_cap'] );

		$add_option['edit_link'] = array(
			'type' => 'text',
			'label' => __( 'Edit Link Text', 'gk-gravityview' ),
			'desc' => NULL,
			'value' => __('Edit Entry', 'gk-gravityview'),
			'merge_tags' => true,
		);

		$add_option['new_window'] = array(
			'type' => 'checkbox',
			'label' => __( 'Open link in a new tab or window?', 'gk-gravityview' ),
			'value' => false,
			'group' => 'display',
			'priority' => 1300,
		);

		return array_merge( $add_option, $field_options );
	}

}

new GravityView_Field_Edit_Link;
