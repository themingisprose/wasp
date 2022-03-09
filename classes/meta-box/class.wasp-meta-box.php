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
			$this->html( $value['meta'], $meta_value, $value['label'], $value['type'] ?? null, $value['select'] ?? null );
		endforeach;
	}

	/**
	 * Form fields to render
	 * @param string $meta
	 * @param string $value
	 * @param string $label
	 * @param string $type
	 * @param array $select 	If $type = 'select', $select must be define as array
	 * 							$select = array(
	 * 								'value_01'	=> 'Label 01',
	 * 								'value_02'	=> 'Label 02',
	 * 								...
	 * 							)
	 *
	 * @since WASP 1.0.0
	 */
	function html( $meta, $value, $label, $type = null, $select = null )
	{
		$type = ( isset( $type ) ) ? $type : 'text';
	?>
		<div class="mb-2">
			<?php if ( 'checkbox' != $type && 'title' != $type ) : ?>
			<p><label for="<?php echo $meta ?>" class="description d-block mb-2"><?php echo $label ?></label></p>
			<?php endif ?>
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
				wp_editor( $value, $meta, $settings );
			elseif ( 'textarea' == $type ) :
		?>
			<textarea id="<?php echo $meta ?>" class="regular-text mb-3" name="<?php echo $meta ?>" cols="30" rows="5"><?php echo $value ?></textarea>
		<?php
			elseif ( 'media' == $type ) :
				$enqueue = new WASP_Enqueue();
				$enqueue->media_upload();
				$thumbnails = ( ! empty( $value ) ) ? array_unique( explode( ',', $value ) ) : null;
		?>
			<div id="media-uploader-<?php echo $meta ?>" class="media-uploader">
				<div id="insert-media-wrapper-<?php echo $meta ?>" class="insert-media-wrapper" style="display: flex; justify-content: flex-start;">
				<?php
				if ( $thumbnails ) :
					foreach ( $thumbnails as $id ) :
				?>
					<div id="thumbnail-<?php echo $id ?>" class="img-wrapper" style="display: flex; flex-direction: column; margin: .5rem">
						<img src="<?php echo wp_get_attachment_image_url( $id ) ?>">
						<small class="img-remover" style="color:#a00; cursor: pointer;" data-remove="<?php echo $id ?>"><?php _e( 'Remove', 'wasp' ) ?></small>
					</div>
				<?php
					endforeach;
				endif;
				?>
				</div>
				<input id="insert-media-input-<?php echo $meta ?>" class="insert-media-input regular-text mb-3" type="hidden" name="<?php echo $meta ?>" value="<?php echo $value ?>">
				<button class="button insert-media-button" type="button" data-input="insert-media-input-<?php echo $meta ?>" data-wrapper="insert-media-wrapper-<?php echo $meta ?>">
					<?php _e( 'Upload images', 'wasp' ) ?>
				</button>
			</div>
		<?php
			elseif ( 'checkbox' == $type ) :
		?>
			<label>
				<input type="checkbox" name="<?php echo $meta ?>" value="1" <?php checked( $value, 1 ) ?>>
				<?php echo $label ?>
			</label>
		<?php
			elseif ( 'select' == $type ) :
				$option = ( ! is_array( $select ) ) ? __( 'No data available', 'wasp' ) : __( 'Select an option', 'wasp' );
		?>
			<select id="<?php echo $meta ?>" name="<?php echo $meta ?>">
				<option value=""><?php echo $option ?></option>
				<?php
				if ( is_array( $select ) ) :
					foreach ( $select as $k => $v ) :
				?>
				<option value="<?php echo $k ?>" <?php selected( $k, $value ) ?>><?php echo $v ?></option>
				<?php
					endforeach;
				endif;
				?>
			</select>
		<?php
			elseif ( 'title' == $type ) :
		?>
			<h3 class="field-title"><?php echo $label ?></h3>
		<?php
			else :
		?>
			<input id="<?php echo $meta ?>" class="regular-text mb-3" type="<?php echo $type ?>" name="<?php echo $meta ?>" value='<?php echo $value ?>'>
		<?php
			endif;
		?>
		</div>
	<?php

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
