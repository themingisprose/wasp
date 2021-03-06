<?php
/**
 * Custom Post Type
 *
 * @since WASP 1.0.0
 */
abstract class WASP_Custom_Post_Type
{
	/**
	 * Post Type slug
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $post_type;

	/**
	 * Labels
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $labels = array();

	/**
	 * Arguments
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $args = array();

 	/**
 	 * Init
 	 *
 	 * @since WASP 1.0.0
 	 */
 	public function init()
 	{
 		add_action( 'init', array( $this, 'register_post_type' ) );
 		register_activation_hook( __FILE__, array( $this, 'flush' ) );
 		add_action( 'init', array( $this, 'unregister_post_type' ) );
 	}

 	/**
 	 * Register CPT
 	 *
 	 * @since WASP 1.0.0
 	 */
 	public function register_post_type()
 	{
 		$labels = array( 'labels' => $this->labels );
 		$args = array_merge( $labels, $this->args );
 		register_post_type( $this->post_type, $args );
 	}

 	/**
 	 * Flush rewrite rules
 	 *
 	 * @since WASP 1.0.0
 	 */
 	public function flush()
 	{
 		$this->register_post_type();

 		flush_rewrite_rules();
 	}

 	/**
 	 * Unregister CPT
 	 * @param array $post_types 	Array of CPT to unregister
 	 *
 	 * @since WASP 1.0.0
 	 */
 	public function unregister_post_type( $post_types )
 	{
 		/**
 		 * Filter CPT to unregister
 		 * @param array $post_types 	Array of CPT to unregister
 		 *
 		 * @since WASP 1.0.0
 		 */
 		$post_types = apply_filters( 'wasp_unregister_post_types', $post_types );

 		if ( ! is_array( $post_types ) )
 			return;

 		foreach ( $post_types as $cpt )
 			unregister_post_type( $cpt );
 	}
}
