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
	 * Enqueue WPML Selector
	 * @param bool $enqueue 	Enqueue the current script
	 *
	 * @since WASP 1.0.0
	 */
	public function scripts( $enqueue = false )
	{
		if ( ! $enqueue )
			return;

		wp_register_script( 'wasp-scripts', plugin_dir_url( dirname( __DIR__ ) ) .'assets/dist/js/scripts.js', array(), '', true );
		wp_enqueue_script( 'wasp-scripts' );
	}
}

$enqueue = new WASP_Enqueue();
$enqueue->init();
