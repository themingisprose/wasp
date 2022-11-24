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
	static function field( $meta, $value, $label, $type = null, $select = null )
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
}
