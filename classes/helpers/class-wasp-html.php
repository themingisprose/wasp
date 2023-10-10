<?php
/**
 * Helper. HTML Form Field
 *
 * @since WASP 1.0.0
 */
class WASP_Html
{

	/**
	 * Form fields to render
	 * @param array $args 	Array of arguments to generate the form field
	 * 						Accepted arguments:
	 * 							$args['label']: 'Field label'
	 * 							$args['meta']: 'field_meta'
	 * 							$args['type']: Input type: title|button|color|date|datetime-local|
	 * 							email|hidden|month|number|password|range|tel|text|time|url|week|
	 * 							textarea|checkbox|radio|select|media|file.
	 * 							If $args['type'] is equal to 'select' or 'input'
	 * 							Default 'text'
	 * 							$args['multiple']: Array used to define the values of field type
	 * 							'radio', 'select' or 'range'.
	 * 							For field type 'range' $args['multiple'] should be like
	 * 							array( 'min' => '0', 'max' => '100', 'step' => '0' )
	 *
	 * @param string $value Default value
	 *
	 * @since WASP 1.0.0
	 */
	public static function field( $args, $value )
	{
		$defaults = array(
			'type'	=> 'text'
		);
		$args = wp_parse_args( $args, $defaults );

		echo '<div class="wasp-field field-'. $args['type'] .'">';
			static::title( $args );
			static::default( $args, $value );
			static::content( $args, $value );
			static::textarea( $args, $value );
			static::checkbox( $args, $value );
			static::radio( $args, $value );
			static::select( $args, $value );
			static::media( $args, $value );
			static::file( $args, $value );
		echo '</div>';
	}

	/**
	 * Title
	 * @param array $args
	 *
	 * @since WASP 1.0.0
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
	 * Defaults
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since WASP 1.0.0
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

		$min	= ( 'range' == $args['type'] && isset( $args['multiple']['min'] ) ) ? 'min="'. $args['multiple']['min'] .'"' : null;
		$max	= ( 'range' == $args['type'] && isset( $args['multiple']['max'] ) ) ? 'max="'. $args['multiple']['max'] .'"' : null;
		$step	= ( 'range' == $args['type'] && isset( $args['multiple']['step'] ) ) ? 'step="'. $args['multiple']['step'] .'"' : null;
		$value 	= ( 'button' != $args['type'] ) ? $value : $args['label'];
		$class 	= ( 'button' != $args['type'] ) ? 'regular-text' : 'button';
	?>
		<p><label for="<?php echo $args['meta'] ?>" class="description"><?php echo $args['label'] ?></label></p>
		<input
			id="<?php echo $args['meta'] ?>"
			class="<?php echo $class ?>"
			type="<?php echo $args['type'] ?>"
			name="<?php echo $args['meta'] ?>"
			value="<?php echo $value ?>"
			<?php echo $min ?>
			<?php echo $max ?>
			<?php echo $step ?>
		>
	<?php
	}

	/**
	 * Content
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since WASP 1.0.0
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
		wp_editor( $value, $args['meta'], $settings );
	}

	/**
	 * Textarea
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since WASP 1.0.0
	 */
	public static function textarea( $args, $value )
	{
		if ( 'textarea' != $args['type'] )
			return;
	?>
		<p><label for="<?php echo $args['meta'] ?>" class="description"><?php echo $args['label'] ?></label></p>
		<textarea id="<?php echo $args['meta'] ?>" class="regular-text mb-3" name="<?php echo $args['meta'] ?>" cols="30" rows="5"><?php echo $value ?></textarea>
	<?php
	}

	/**
	 * Media
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since WASP 1.0.0
	 */
	public static function media( $args, $value )
	{
		if ( 'media' != $args['type'] )
			return;

		WASP_Enqueue::media_upload( true );
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
	 * @since WASP 1.0.0
	 */
	public static function file( $args, $value )
	{
		if ( 'file' != $args['type'] )
			return;

		WASP_Enqueue::file_upload( true );
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
	 * @since WASP 1.0.0
	 */
	public static function checkbox( $args, $value )
	{
		if ( 'checkbox' != $args['type'] )
			return;
		?>
			<label>
				<input type="checkbox" name="<?php echo $args['meta'] ?>" value="1" <?php checked( $value, 1 ) ?>>
				<?php echo $args['label'] ?>
			</label>
		<?php
	}

	/**
	 * Checkbox
	 * @param array $args 	Array of arguments
	 * @param string $value Default value
	 *
	 * @since WASP 1.0.0
	 */
	public static function radio( $args, $value )
	{
		if ( 'radio' != $args['type'] )
			return;

		if ( is_array( $args['multiple'] ) ) :
			foreach ( $args['multiple'] as $k => $v ) :
		?>
		<p><label>
			<input type="radio" name="<?php echo $args['meta'] ?>" value="<?php echo $k ?>" <?php checked( $k, $value ) ?>>
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
	 * @since WASP 1.0.0
	 */
	public static function select( $args, $value )
	{
		if ( 'select' != $args['type'] )
			return;

		$option = ( ! is_array( $args['multiple'] ) ) ? __( 'No data available', 'wasp' ) : __( 'Select an option', 'wasp' );
	?>
		<p><label for="<?php echo $args['meta'] ?>" class="description"><?php echo $args['label'] ?></label></p>
		<select id="<?php echo $args['meta'] ?>" name="<?php echo $args['meta'] ?>">
			<option value=""><?php echo $option ?></option>
			<?php
			if ( is_array( $args['multiple'] ) ) :
				foreach ( $args['multiple'] as $k => $v ) :
			?>
			<option value="<?php echo $k ?>" <?php selected( $k, $value ) ?>><?php echo $v ?></option>
			<?php
				endforeach;
			endif;
			?>
		</select>
	<?php
	}
}
