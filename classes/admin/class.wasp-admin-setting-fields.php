<?php
/**
 * Setting Fields
 *
 * @since WASP 1.0.0
 */
abstract class WASP_Setting_Fields
{

	/**
	 * HTML section id
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $section_id;

	/**
	 * Section title
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $section_title;

	/**
	 * HTML field id
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $field_id;

	/**
	 * Field Title
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $field_title;

	/**
	 * Page slug
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $slug;

	/**
	 * Option group
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $option_group;

	/**
	 * Option name
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $option_name;

	/**
	 * WPML Filter. Name of the filter returned by method fields()
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $wpml_field;


	/**
	 * Construct
	 *
	 * @since WASP 1.0.0
	 */
	function __construct()
	{
		add_action( 'admin_menu', array( $this, 'register_setting' ) );
		add_filter( 'wasp_options_input', array( $this, 'validate' ) );
		add_filter( $this->wpml_field, array( $this, 'wpml_field' ) );
	}

	/**
	 * Register Setting
	 *
	 * @since WASP 1.0.0
	 */
	function register_setting()
	{
		register_setting(
			$this->option_group,
			$this->option_name,
			array( $this, 'sanitize_options' )
		);

		add_settings_section(
			$this->slug .'-'. $this->section_id,
			$this->section_title,
			'__return_false',
			$this->slug
		);

		add_settings_field(
			$this->slug .'-'. $this->field_id,
			$this->field_title,
			$this->callback(),
			$this->slug,
			$this->slug .'-'. $this->section_id
		);
	}

	/**
	 * Main configuration function
	 * @param string $option 	Option stored in wasp_options in the data base option table. Optional
	 * @param bool $lang 		Filter by current language if WPML is active
	 *
	 * @since WASP 1.0.0
	 */
	function get_option( $option, $lang = false )
	{
		if ( ! $option )
			return;

		$value = get_option( $this->option_name );

		if ( $option ) :
			if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) && $lang ) :
				$current_lang = apply_filters( 'wpml_current_language', NULL );
				$value = $value[$option .'_'. $current_lang];
			else :
				$value = $value[$option];
			endif;
		endif;

		return $value;
	}

	/**
	 * Sanitize Callback
	 *
	 * @since WASP 1.0.0
	 */
	function sanitize_options()
	{
		/**
		 * Filters the Options Input
		 * @param array 	Array of key => value
		 *
		 * @since WASP 1.0.0
		 */
		return apply_filters( 'wasp_options_input', array() );
	}

	/**
	 * Get the callable that will the content of the section.
	 * @return callable
	 *
	 * @since WASP 1.0.0
	 */
	function callback()
	{
		return array( $this, 'render' );
	}

	/**
	 * Render the content of the section
	 *
	 * @since WASP 1.0.0
	 */
	function render()
	{
		$html = new WASP_Html;
		$fields = $this->fields();

		foreach ( $fields as $key => $data ) :
			$value = $this->get_option( $data['meta'] );
			$html->field( $data, $value );
		endforeach;
	}

	/**
	 * Add languages field if WPML is installed and activated
	 *
	 * @since WASP 1.0.0
	 */
	function wpml_field( $fields ){
		if ( ! is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) )
			return $fields;

		$languages = apply_filters( 'wpml_active_languages', NULL );
		foreach ( $languages as $lang ) :
			foreach ( $fields as $key => $value ) :
				if ( isset( $fields[$key]['lang'] ) ) :
					$option = array(
								$lang['code'] => array(
									'label'		=> $lang['translated_name'],
									'option' 	=> $value['option'] .'_'. $lang['code'],
								),
							);
					$fields[$key]['lang'] = ( is_array( $fields[$key]['lang'] ) ) ? array_merge_recursive( $fields[$key]['lang'], array_slice( $option, -1 ) ) : null;
				endif;
			endforeach;
		endforeach;

		return $fields;
	}

	/**
	 * Process validation fields
	 *
	 * @since WASP 1.0.0
	 */
	function validate( $input )
	{
		$fields = $this->fields();

		foreach ( $fields as $key => $data ) :
			if ( ! empty( $data['lang'] ) ) :
				foreach ( $data['lang'] as $code => $data ) :
					if ( isset( $_POST[$data['meta']] ) )
						$input[$data['meta']] = stripslashes( trim( $_POST[$data['meta']] ) );
				endforeach;
			else :
				if ( isset( $_POST[$data['meta']] ) ) :
					if ( is_array( $_POST[$data['meta']] ) ) :
						$input[$data['meta']] = array_map( 'trim', $_POST[$data['meta']] );
					else :
						$input[$data['meta']] = stripslashes( trim( $_POST[$data['meta']] ) );
					endif;
				endif;
			endif;
		endforeach;

		add_settings_error( 'wasp-update', 'wasp', __( 'Setting Updated', 'wasp' ), 'success' );

		return $input;
	}

	/**
	 * Array of fields to render
	 * This method must return an associative array like the example
	 * 		$fields = array(
	 * 			'field_key_name' => array(
	 * 				'label'		=> 'Field Name',
	 * 				'option'	=> 'field_option_name',
	 * 			),
	 * 			...
	 * 		);
	 * @return array 		Array of fields
	 *
	 * @since WASP 1.0.0
	 */
	abstract public function fields();
}
