<?php
namespace WASP\Helpers;

/**
 * Enqueue scripts and styles
 *
 * @since 1.0.0
 */
class Enqueue
{

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	protected function __construct()
	{
		add_action( 'admin_enqueue_scripts', array( $this, 'media_upload' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'file_upload' ) );
	}

	/**
	 * Enqueue Media Upload
	 *
	 * @since 1.0.0
	 */
	static public function media_upload()
	{
		wp_enqueue_media();
		wp_register_script( 'wasp-media-upload', plugin_dir_url( dirname( __DIR__ ) ) .'assets/js/media-upload.js', array(), null, true );
		$l10n = array(
			'media_frame_windows_title'	=> __( 'Select images', 'wasp' ),
			'media_frame_button_title'	=> __( 'Create gallery', 'wasp' )
		);
		$data = 'media_frame_l10n = '. json_encode( $l10n );
		wp_add_inline_script( 'wasp-media-upload', $data, 'before' );
		wp_enqueue_script( 'wasp-media-upload' );

	}

	/**
	 * Enqueue File Upload
	 *
	 * @since 1.0.0
	 */
	static public function file_upload()
	{
		wp_enqueue_media();
		wp_register_script( 'wasp-file-upload', plugin_dir_url( dirname( __DIR__ ) ) .'assets/js/file-upload.js', array(), null, true );
		$l10n = array(
			'file_frame_windows_title'	=> __( 'Select file', 'wasp' ),
			'file_frame_button_title'	=> __( 'Use this file', 'wasp' )
		);
		$data = 'file_frame_l10n = '. json_encode( $l10n );
		wp_add_inline_script( 'wasp-file-upload', $data, 'before' );
		wp_enqueue_script( 'wasp-file-upload' );
	}
}
