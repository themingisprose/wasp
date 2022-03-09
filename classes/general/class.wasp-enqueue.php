<?php
/**
 * Enqueue scripts and styles
 *
 * @since WASP 1.0.0
 */
class WASP_Enqueue
{

	/**
	 * Enqueue init
	 *
	 * @since WASP 1.0.0
	 */
	public function init()
	{
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
	}

	/**
	 * Enqueue Scripts
	 * @param bool $enqueue 	Enqueue the current script
	 *
	 * @since WASP 1.0.0
	 */
	public function scripts( $enqueue = false )
	{
		if ( ! $enqueue )
			return;

		wp_register_script( 'wasp-scripts', plugin_dir_url( dirname( __DIR__ ) ) .'assets/dist/js/scripts.js', array(), null, true );
		wp_enqueue_script( 'wasp-scripts' );
	}

	/**
	 * Enqueue Media Upload
	 *
	 * @since WASP 1.0.0
	 */
	public function media_upload()
	{
		wp_enqueue_media();
		wp_register_script( 'wasp-media-upload', plugin_dir_url( dirname( __DIR__ ) ) .'assets/dist/js/media-upload.js', array(), null, true );
		$l10n = array(
			'media_frame_windows_title'	=> __( 'Select images', 'wasp' ),
			'media_frame_button_title'	=> __( 'Create gallery', 'wasp' ),
		);
		$data = 'media_frame_l10n = '. json_encode( $l10n );
		wp_add_inline_script( 'wasp-media-upload', $data, 'before' );
		wp_enqueue_script( 'wasp-media-upload' );

	}

}

$enqueue = new WASP_Enqueue();
$enqueue->init();
