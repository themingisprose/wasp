<?php
namespace WASP\Meta_Box;

use WASP\Helpers\HTML;

/**
 * Meta Boxes
 *
 * @since 1.0.0
 */
abstract class Meta_Box
{

	/**
	 * Meta box ID
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $id;

	/**
	 * Title of the meta box
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $title;

	/**
	 * The screens or screens on which to show the box
	 * @access public
	 * @var string|array Optional
	 *
	 * @since 1.0.0
	 */
	public $screen = null;

	/**
	 * The context within the screens where the box should display
	 * @access public
	 * @var string Optional
	 *
	 * @since 1.0.0
	 */
	public $context = 'advanced';

	/**
	 * The priority within the context where the box should show
	 * @access public
	 * @var string Optional
	 *
	 * @since 1.0.0
	 */
	public $priority = 'default';

	/**
	 * Data that should be set as the $args property of the box array
	 * @access public
	 * @var array Optional
	 *
	 * @since 1.0.0
	 */
	public $callback_args = null;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	function __construct()
	{
		add_action( 'add_meta_boxes', array( $this, 'meta_box' ), 10, 6 );
		add_action( 'save_post', array( $this, 'save_meta' ) );
	}

	/**
	 * Adds a meta box
	 *
	 * @since 1.0.0
	 */
	function meta_box()
	{
		add_meta_box(
			$this->id,
			$this->title,
			$this->callback(),
			$this->screen,
			$this->context,
			$this->priority,
			$this->callback_args
		);
	}

	/**
	 * Get the callable that will the content of the meta box.
	 * @return callable
	 *
	 * @since 1.0.0
	 */
	function callback()
	{
		return array( $this, 'render' );
	}

	/**
	 * Render the content of the meta box.
	 * @param WP_Post $post 		Required. Post object.
	 *
	 * @since 1.0.0
	 */
	function render( \WP_Post $post )
	{
		wp_nonce_field( $this->id .'_attr', $this->id .'_field' );
		$fields = $this->fields();

		foreach ( $fields as $key => $data ) :
			$value = get_post_meta( $post->ID, $data['meta'], true );
			HTML::field( $data, $value );
		endforeach;
	}

	/**
	 * Save the data
	 * @param int $post_id 	Required. Current post ID
	 *
	 * @since 1.0.0
	 */
	function save_meta( $post_id )
	{
		// Check if the current user is authorized to do this action.
		if ( ! current_user_can( 'edit_posts' ) )
			return;

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		// Check if the user intended to change this value.
		if ( ! isset( $_POST[$this->id .'_field'] )
			|| ! wp_verify_nonce( $_POST[$this->id .'_field'], $this->id .'_attr' ) )
			return;

		$fields = $this->fields();

		foreach ( $fields as $key => $value ) :
			if ( isset( $_POST[$value['meta']] ) && '' !== $_POST[$value['meta']] ) :
				update_post_meta( $post_id, $value['meta'], sanitize_text_field( $_POST[$value['meta']] ) );
			else :
				delete_post_meta( $post_id, $value['meta'] );
			endif;
		endforeach;

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
	 * @see class WASP\Helpers\HTML::field() for full documentation about supported fields.
	 *
	 * @since 1.0.0
	 */
	abstract public function fields();

}
