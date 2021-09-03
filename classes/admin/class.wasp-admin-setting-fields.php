<?php
/**
 * Setting Fields
 *
 * @since WASP 1.0.0
 */
abstract class WASP_Setting_Fields
{

	/**
	 * Section ID
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $section_id;

	/**
	 * Section Title
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $section_title;

	/**
	 * Field ID
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
	 * WPML Filter
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $wpml_field;



	/**
	 * Construct
	 * @param string $section_id 	HTML section id
	 * @param string $section_title Section title
	 * @param string $field_id 		HTML field id
	 * @param string $field_title 	Field title
	 * @param string $wpml_field 	Name of the filter returned by method fields()
	 *
	 * @since WASP 1.0.0
	 */
	public function __construct( $section_id = '', $section_title = '', $field_id = '', $field_title = '', $wpml_field = null )
	{
		$admin = new WASP_Admin_Page;
		$this->slug 			= $admin->slug;
		$this->option_group 	= $admin->option_group;
		$this->option_name 		= $admin->option_name;

		$this->section_id 		= $section_id;
		$this->section_title 	= $section_title;
		$this->field_id 		= $field_id;
		$this->field_title 		= $field_title;
		$this->wpml_field 		= $wpml_field;
	}

	/**
	 * Init
	 *
	 * @since WASP 1.0.0
	 */
	public function init()
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
	public function register_setting()
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
	public function get_option( $option, $lang = false )
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
	public function sanitize_options()
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
	public function callback()
	{
		return array( $this, 'render' );
	}

	/**
	 * Render the content of the section
	 *
	 * @since WASP 1.0.0
	 */
	public function render()
	{
		$fields = $this->fields();

		foreach ( $fields as $key => $value ) :
			$type = ( isset( $value['type'] ) ) ? $value['type'] : 'text';
			if ( ! empty( $value['lang'] ) ) :
		?>
				<h4 class="mt-0"><?php echo $value['label'] ?></h4>
		<?php
				foreach ( $value['lang'] as $code => $value ) :
					$this->html( $value['option'], $value['label'], $type );
				endforeach;
			else :
				$this->html( $value['option'], $value['label'], $type );
			endif;
		endforeach;

	}

	/**
	 * Form fields to render
	 * @param string $option
	 * @param string $label
	 * @param string $type
	 *
	 * @since WASP 1.0.0
	 */
	public function html( $option, $label, $type = 'text' )
	{
	?>
		<div class="mb-2">
			<label for="<?php echo $option ?>" class="description d-block mb-2"><?php echo $label ?></label>
		<?php
			if ( 'content' == $type ) :
				$settings = array(
					'media_buttons'	=> false,
					'textarea_rows'	=> 7,
					'teeny'			=> true,
					'quicktags'		=> false,
					'tinymce'		=> array(
						'resize'				=> false,
						'wordpress_adv_hidden'	=> false,
						'add_unload_trigger'	=> false,
						'statusbar'				=> false,
						'wp_autoresize_on'		=> false,
						'toolbar1'				=> 'bold,italic,underline,|,bullist,numlist,|,alignleft,aligncenter,alignright,|,link,unlink,|,undo,redo',
					),
				);
				wp_editor( $this->get_option( $option ), $option, $settings );
			elseif ( 'textarea' == $type ) :
		?>
			<textarea id="<?php echo $option ?>" class="regular-text mb-3" name="<?php echo $option ?>" cols="30" rows="5"><?php echo $this->get_option( $option ) ?></textarea>
		<?php
			else :
		?>
			<input id="<?php echo $option ?>" class="regular-text mb-3" type="<?php echo $type ?>" name="<?php echo $option ?>" value="<?php echo $this->get_option( $option ) ?>">
		<?php
			endif;
		?>
		</div>
	<?php
	}

	/**
	 * Add languages field if WPML is installed and activated
	 *
	 * @since WASP 1.0.0
	 */
	public function wpml_field( $fields ){
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
	public function validate( $input )
	{
		$fields = $this->fields();

		foreach ( $fields as $key => $value ) :
			if ( ! empty( $value['lang'] ) ) :
				foreach ( $value['lang'] as $code => $value ) :
					if ( isset( $_POST[$value['option']] ) )
						$input[$value['option']] = stripslashes( trim( $_POST[$value['option']] ) );
				endforeach;
			else :
				if ( isset( $_POST[$value['option']] ) ) :
					if ( is_array( $_POST[$value['option']] ) ) :
						$input[$value['option']] = array_map( 'trim', $_POST[$value['option']] );
					else :
						$input[$value['option']] = stripslashes( trim( $_POST[$value['option']] ) );
					endif;
				endif;
			endif;
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
	 * 			),
	 * 			...
	 * 		);
	 * @return array 		Array of fields
	 *
	 * @since WASP 1.0.0
	 */
	abstract public function fields();
}
