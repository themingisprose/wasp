<?php
/**
 * Meta Boxes
 *
 * @since WASP 1.0.0
 */
abstract class WASP_Meta_Box
{

	/**
	 * Meta box ID
	 * @access public
	 * @var string
	 *
	 * @since WASP 1.0.0
	 */
	public $id;

	/**
	 * Title of the meta box
	 * @access private
	 * @var string
	 *
	 * @since WASP 1.0.0
	 */
	private $title;

	/**
	 * The screens or screens on which to show the box
	 * @access private
	 * @var string|array
	 *
	 * @since WASP 1.0.0
	 */
	private $screens;

	/**
	 * The context within the screens where the box should display
	 * @access private
	 * @var string
	 *
	 * @since WASP 1.0.0
	 */
	private $context;

	/**
	 * The priority within the context where the box should show
	 * @access private
	 * @var string
	 *
	 * @since WASP 1.0.0
	 */
	private $priority;

	/**
	 * Data that should be set as the $args property of the box array
	 * @access private
	 * @var array
	 *
	 * @since WASP 1.0.0
	 */
	private $callback_args;

	/**
	 * Constructor
	 * @param string 	$id
	 * @param string 	$title
	 * @param string 	$screens
	 * @param string 	$context
	 * @param string 	$priority
	 * @param array 	$callback_args
	 */
	public function __construct( $id, $title, $screens = array(), $context = 'advanced', $priority = 'default', $callback_args = null )
	{
			$this->screens 			= ( is_string( $screens ) ) ? (array) $screens : $screens;
			$this->id				= $id;
			$this->title			= $title;
			$this->context			= $context;
			$this->priority			= $priority;
			$this->callback_args	= $callback_args;
	}

	/**
	 * Hooks some methods
	 *
	 * @since WASP 1.0.0
	 */
	public function init()
	{
		add_action( 'add_meta_boxes', array( $this, 'meta_box' ), 10, 6 );
		add_action( 'save_post', array( $this, 'save_meta' ) );
	}

	/**
	 * Adds a meta box
	 *
	 * @since WASP 1.0.0
	 */
	public function meta_box()
	{
		add_meta_box(
			$this->id,
			$this->title,
			$this->callback(),
			$this->screens,
			$this->context,
			$this->priority,
			$this->callback_args
		);
	}

	/**
	 * Get the callable that will the content of the meta box.
	 * @return callable
	 *
	 * @since WASP 1.0.0
	 */
	public function callback()
	{
		return array( $this, 'render' );
	}

	/**
	 * Render the content of the meta box.
	 * @param WP_Post $post 		Required. Post object.
	 *
	 * @since WASP 1.0.0
	 */
	public function render( WP_Post $post )
	{
		wp_nonce_field( $this->id .'_attr', $this->id .'_field' );
		$fields = $this->fields();

		foreach ( $fields as $key => $value ) :
			$meta_value = get_post_meta( $post->ID, $value['meta'], true );
		?>
			<h4><label for="<?php echo $value['meta'] ?>"><?php echo $value['label'] ?></label></h4>
			<input id="<?php echo $value['meta'] ?>" type="text" min="1" name="<?php echo $value['meta'] ?>" value="<?php echo $meta_value ?>" class="w-100">
		<?php
		endforeach;
	}

	/**
	 * Save the data
	 * @param int $post_id 			Required. Current post ID
	 *
	 * @since WASP 1.0.0
	 */
	public function save_meta( $post_id )
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
	 * 				'label'	=> 'Field Name',
	 * 				'meta'	=> 'field_option_name',
	 * 			),
	 * 			...
	 * 		);
	 * @return array 		Array of fields
	 *
	 * @since WASP 1.0.0
	 */
	abstract public function fields();

}
