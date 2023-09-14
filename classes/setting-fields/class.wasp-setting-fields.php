<?php
/**
 * Setting Fields
 *
 * @since WASP 1.0.0
 */
abstract class WASP_Setting_Fields
{

	/**
	 * Option group
	 * @access public
	 * @var string 	Required
	 *
	 * @since WASP 1.0.0
	 */
	public $option_group;

	/**
	 * Option name
	 * @access public
	 * @var string 	Required
	 *
	 * @since WASP 1.0.0
	 */
	public $option_name;

	/**
	 * HTML section id
	 * @access public
	 * @var string 	Required
	 *
	 * @since WASP 1.0.0
	 */
	public $section_id;

	/**
	 * Section title
	 * @access public
	 * @var string 	Required
	 *
	 * @since WASP 1.0.0
	 */
	public $section_title;

	/**
	 * Page slug
	 * @access public
	 * @var string 	Required
	 *
	 * @since WASP 1.0.0
	 */
	public $slug;

	/**
	 * HTML field id
	 * @access public
	 * @var string 	Required
	 *
	 * @since WASP 1.0.0
	 */
	public $field_id;

	/**
	 * Field Title
	 * @access public
	 * @var string 	Required
	 *
	 * @since WASP 1.0.0
	 */
	public $field_title;


	/**
	 * Construct
	 *
	 * @since WASP 1.0.0
	 */
	function __construct()
	{
		add_action( 'admin_menu', array( $this, 'register_setting' ) );
		add_filter( 'wasp_options_input', array( $this, 'validate' ) );
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
			array( 'type' => 'array', 'sanitize_callback' => array( $this, 'sanitize_options' ) )
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
	 * @param string $meta 	Meta key stored in wasp_options in the data base option table.
	 *
	 * @since WASP 1.0.0
	 */
	function get_option( $meta )
	{
		$option = get_option( $this->option_name );
		return $option[$meta];
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
		$fields = $this->fields();

		foreach ( $fields as $key => $data ) :
			$value = $this->get_option( $data['meta'] );
			WASP_Html::field( $data, $value );
		endforeach;
	}

	/**
	 * Process validation fields
	 *
	 * @since WASP 1.0.0
	 */
	function validate( $input )
	{
		$fields = $this->fields();

		foreach ( $fields as $key => $value ) :
			$input[$value['meta']] = isset( $_POST[$value['meta']] )
										? stripslashes( trim( $_POST[$value['meta']] ) )
										: null;
		endforeach;

		// TODO: This generate an unexpected output.
		// add_settings_error( 'wasp-update', 'wasp', __( 'Setting Updated', 'wasp' ), 'success' );

		return array_merge( $input, $input );
	}

	/**
	 * Array of fields to render
	 * This method must return an associative array like the example
	 * 		$fields = array(
	 * 			'field_key_name' => array(
	 * 				'label'		=> 'Field Name',
	 * 				'option'	=> 'field_option_name',
	 * 				'type'		=> 'text',
	 * 				'multiple'	=> array()
	 * 			),
	 * 			...
	 * 		);
	 * @see class WASP_Html::field() for full documentation of fields supported.
	 * @return array 		Array of fields
	 *
	 * @since WASP 1.0.0
	 */
	abstract public function fields();
}
