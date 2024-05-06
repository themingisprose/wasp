<?php
namespace WASP\Helpers;

use WASP\Helpers\Enqueue;

/**
 * Helper. HTML Form Field
 *
 * @since 1.0.0
 */
class HTML
{

	/**
	 * Form fields to render
	 * @param array $args {
	 *		Array of arguments, supports the following keys:
	 * 		@type string $label 	Field label.
	 * 		@type string $meta 		Field meta. 'key' stored in the database
	 * 		@type string $type 		Field type.
	 * 								Default 'text'.
	 * 								Supported values: 'button', 'checkbox', 'color', 'content',
	 * 								'date', 'datetime-local', 'email', 'file', 'hidden', 'media',
	 * 								'month', 'nonce' 'number', 'password', 'radio', 'range', 'select',
	 * 								'tel', 'text', 'textarea', 'time', 'url', 'week'.
	 * 		@type array $multiple	Array used to define the values of field type 'radio' or 'select'.
	 * 								$multiple = array(
	 * 									'option_1' => 'Label 1',
	 * 									'option_2' => 'Label 2',
	 * 									'option_3' => 'Label 3',
	 * 									...
	 * 								)
	 * 		@type array $attr 		Array of HTML attributes
	 * 								$attr = array(
	 * 									'min' 		=> '1',
	 * 									'max' 		=> '999',
	 * 									'step' 		=> '3.14',
	 * 									...
	 * 								)
	 * 		@type mixed $default 	Default value before to save the data in the database.
	 * }
	 * @param string $value 		Value retrieved from database
	 *
	 * @since 1.0.0
	 * @since 1.0.1 				Added $args['default']
	 * @since 1.0.1 				Added $args['attr']
	 */
	public static function field( $args, $value )
	{
		$defaults = array(
			'type'		=> 'text',
			'label'		=> null,
			'meta'		=> null,
			'default'	=> null,
			'attr'		=> null
		);
		$args = wp_parse_args( $args, $defaults );

		echo '<div class="wasp-field field-'. $args['type'] .'" style="margin-bottom: .5rem">';
			self::title( $args );
			self::paragraph( $args );
			self::default( $args, $value );
			self::content( $args, $value );
			self::textarea( $args, $value );
			self::checkbox( $args, $value );
			self::radio( $args, $value );
			self::select( $args, $value );
			self::media( $args, $value );
			self::file( $args, $value );
			self::nonce( $args );
		echo '</div>';
	}

	/**
	 * Title
	 * @param array $args
	 *
	 * @since 1.0.0
	 */
	public static function title( $args )
	{
		if ( 'title' != $args['type'] )
			return;
		?>
			<h3><?php echo $args['label'] ?></h3>
		<?php
	}

	/**
	 * Paragraph
	 * @param array $args
	 *
	 * @since 1.0.1
	 */
	public static function paragraph( $args )
	{
		if ( 'paragraph' != $args['type'] )
			return;
		?>
			<p><?php echo $args['label'] ?></p>
		<?php
	}

	/**
	 * Defaults
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since 1.0.0
	 */
	public static function default( $args, $value )
	{
		// Supported input types
		$types = array(
			'button',
			'color',
			'date',
			'datetime-local',
			'email',
			'hidden',
			'month',
			'number',
			'password',
			'range',
			'tel',
			'text',
			'time',
			'url',
			'week'
		);
		if ( ! in_array( $args['type'], $types ) )
			return;

		$default	= $value ?? ( ( isset( $args['default'] ) ) ? $args['default'] : null );
		$value 		= ( 'button' != $args['type'] ) ? $default : $args['label'];
		$class 		= ( 'button' != $args['type'] ) ? 'regular-text' : 'button';

		$no_label	= array(
			'button',
			'hidden'
		);
	?>

	<?php if ( ! in_array( $args['type'], $no_label )  ) : ?>
		<p><label for="<?php echo $args['meta'] ?>" class="description"><?php echo $args['label'] ?></label></p>
	<?php endif ?>
		<input
			id="<?php echo $args['meta'] ?>"
			class="<?php echo $class ?>"
			type="<?php echo $args['type'] ?>"
			name="<?php echo $args['meta'] ?>"
			value="<?php echo $value ?>"
			<?php self::attr( $args['attr'] ) ?>
		>
	<?php
	}

	/**
	 * Content
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since 1.0.0
	 */
	public static function content( $args, $value )
	{
		if ( 'content' != $args['type'] )
			return;
	?>
		<p><label for="<?php echo $args['meta'] ?>" class="description"><?php echo $args['label'] ?></label></p>
	<?php
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
		wp_editor(
			$value ?? ( ( isset( $args['default'] ) ) ? $args['default'] : null ) ?? '',
			$args['meta'],
			$settings
		);
	}

	/**
	 * Textarea
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since 1.0.0
	 */
	public static function textarea( $args, $value )
	{
		if ( 'textarea' != $args['type'] )
			return;
	?>
		<p><label for="<?php echo $args['meta'] ?>" class="description"><?php echo $args['label'] ?></label></p>
		<textarea
			id="<?php echo $args['meta'] ?>"
			class="regular-text mb-3"
			name="<?php echo $args['meta'] ?>"
			cols="30"
			rows="5"
			<?php static::attr( $args['attr'] ) ?>
		><?php echo $value ?? ( ( isset( $args['default'] ) ) ? $args['default'] : null ) ?></textarea>
	<?php
	}

	/**
	 * Media
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since 1.0.0
	 */
	public static function media( $args, $value )
	{
		if ( 'media' != $args['type'] )
			return;

		Enqueue::media_upload( true );
		$thumbnails = ( ! empty( $value ) ) ? array_unique( explode( ',', $value ) ) : null;
	?>
		<p><label for="<?php echo $args['meta'] ?>" class="description"><?php echo $args['label'] ?></label></p>
		<div id="media-uploader-<?php echo $args['meta'] ?>" class="wasp-media-uploader" data-btn="insert-media-btn-<?php echo $args['meta'] ?>">
			<div id="insert-media-wrapper-<?php echo $args['meta'] ?>" class="insert-media-wrapper" style="display: flex; justify-content: flex-start;">
			<?php
			if ( $thumbnails ) :
				foreach ( $thumbnails as $id ) :
			?>
				<div id="thumbnail-<?php echo $args['meta'] .'-'. $id ?>" class="img-wrapper" style="display: flex; flex-direction: column; margin: .5rem">
					<img src="<?php echo wp_get_attachment_image_url( $id ) ?>">
					<small class="img-remover" data-remove="thumbnail-<?php echo $args['meta'] .'-'. $id ?>" data-thumbnail-id="<?php echo $id ?>" style="color:#a00; cursor: pointer;"><?php _e( 'Remove', 'wasp' ) ?></small>
				</div>
			<?php
				endforeach;
			endif;
			?>
			</div>
			<input id="insert-media-input-<?php echo $args['meta'] ?>" class="insert-media-input regular-text mb-3" type="hidden" name="<?php echo $args['meta'] ?>" value="<?php echo $value ?>">
			<button id="insert-media-btn-<?php echo $args['meta'] ?>" class="button insert-media-button" type="button" data-input="insert-media-input-<?php echo $args['meta'] ?>" data-wrapper="insert-media-wrapper-<?php echo $args['meta'] ?>">
				<?php _e( 'Upload images', 'wasp' ) ?>
			</button>
		</div>
	<?php
	}

	/**
	 * File
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since 1.0.0
	 */
	public static function file( $args, $value )
	{
		if ( 'file' != $args['type'] )
			return;

		Enqueue::file_upload( true );
		$attach_url = wp_get_attachment_url( $value );
	?>
		<p><label for="insert-file-url-<?php echo $args['meta'] ?>" class="description"><?php echo $args['label'] ?></label></p>
		<div id="file-uploader-<?php echo $args['meta'] ?>" class="wasp-file-uploader">
			<input id="insert-file-input-<?php echo $args['meta'] ?>" class="insert-file-input mb-3" type="hidden" name="<?php echo $args['meta'] ?>" value="<?php echo $value ?>">
			<input id="insert-file-url-<?php echo $args['meta'] ?>" class="insert-file-url mb-3" type="url" value="<?php echo $attach_url ?>">
			<button class="button insert-file-button" type="button" data-input="insert-file-input-<?php echo $args['meta'] ?>" data-url="insert-file-url-<?php echo $args['meta'] ?>">
				<?php _e( 'Upload file', 'wasp' ) ?>
			</button>
			<button class="button clear-file-button" type="button" data-input="insert-file-input-<?php echo $args['meta'] ?>" data-url="insert-file-url-<?php echo $args['meta'] ?>">
				<?php _e( 'Clear', 'wasp' ) ?>
			</button>
		</div>
	<?php
	}

	/**
	 * Checkbox
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since 1.0.0
	 */
	public static function checkbox( $args, $value )
	{
		if ( 'checkbox' != $args['type'] )
			return;

		$value = $value ?? ( ( 'checked' == $args['default'] ) ? 1 : null );
		?>
			<label>
				<input
					type="checkbox"
					name="<?php echo $args['meta'] ?>"
					value="1"
					<?php checked( $value, 1 ) ?>
					<?php static::attr( $args['attr'] ) ?>
				>
				<?php echo $args['label'] ?>
			</label>
		<?php
	}

	/**
	 * Checkbox
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since 1.0.0
	 */
	public static function radio( $args, $value )
	{
		if ( 'radio' != $args['type'] )
			return;

		if ( is_array( $args['multiple'] ) ) :
			foreach ( $args['multiple'] as $k => $v ) :
		?>
		<p><label>
			<input
				type="radio"
				name="<?php echo $args['meta'] ?>"
				value="<?php echo $k ?>"
				<?php checked( $k, $value ?? ( ( isset( $args['default'] ) ) ? $args['default'] : null ) ) ?>
				<?php static::attr( $args['attr'] ) ?>
			>
			<?php echo $v ?>
		</label></p>
		<?php
			endforeach;
		endif;
	}

	/**
	 * Select
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since 1.0.0
	 */
	public static function select( $args, $value )
	{
		if ( 'select' != $args['type'] )
			return;

		$option = ( ! is_array( $args['multiple'] ) )
					? __( 'No data available', 'wasp' )
					: __( '&mdash; Select an option &mdash;', 'wasp' );
	?>
		<p><label for="<?php echo $args['meta'] ?>" class="description"><?php echo $args['label'] ?></label></p>
		<select
			id="<?php echo $args['meta'] ?>"
			name="<?php echo $args['meta'] ?>"
			<?php static::attr( $args['attr'] ) ?>
		>
			<option value=""><?php echo $option ?></option>
			<?php
			if ( is_array( $args['multiple'] ) ) :
				foreach ( $args['multiple'] as $k => $v ) :
			?>
			<option value="<?php echo $k ?>" <?php selected( $k, $value ?? ( ( isset( $args['default'] ) ) ? $args['default'] : null ) ) ?>><?php echo $v ?></option>
			<?php
				endforeach;
			endif;
			?>
		</select>
	<?php
	}

	/**
	 * Nonce
	 * @param array $args 	Array of arguments
	 *
	 * @since 1.0.1
	 */
	public static function nonce( $args )
	{
		if ( 'nonce' != $args['type'] )
			return;

		$value = ( isset( $args['default'] ) ) ? $args['default'] : -1;
	?>
		<input
			id="<?php echo $args['meta'] ?>"
			type="hidden"
			name="<?php echo $args['meta'] ?>"
			value="<?php echo wp_create_nonce( $args['default'] ) ?>"
		>
	<?php
	}

	/**
	 * Process the $args['attr'] var
	 * @param array $attr
	 * @return string
	 *
	 * @since 1.0.1
	 */
	private static function attr( $attr )
	{
		if ( ! is_array( $attr ) )
			return;

		foreach ( $attr as $k => $v ) :
			echo $k .'="'. $v .'" ';
		endforeach;
	}
}
