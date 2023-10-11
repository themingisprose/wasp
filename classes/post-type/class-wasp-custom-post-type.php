<?php
namespace WASP\Posts;

/**
 * Custom Post Type
 *
 * @since 1.0.0
 */
abstract class Custom_Post_Type
{
	/**
	 * Post Type slug
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $post_type;

	/**
	 * Labels
	 * @access public
	 * @var array 	Optional
	 *
	 * @since 1.0.0
	 */
	public $labels = array();

	/**
	 * Arguments
	 * @access public
	 * @var array 	Optional
	 *
	 * @since 1.0.0
	 */
	public $args = array();


 	/**
 	 * Constructor
 	 *
 	 * @since 1.0.0
 	 */
 	function __construct()
 	{
 		add_action( 'init', array( $this, 'register_post_type' ) );
 		register_activation_hook( __FILE__, array( $this, 'flush' ) );
 	}

 	/**
 	 * Register CPT
 	 *
 	 * @since 1.0.0
 	 */
 	function register_post_type()
 	{
 		$labels = array( 'labels' => $this->labels );
 		$args = array_merge( $labels, $this->args );
 		register_post_type( $this->post_type, $args );
 	}

 	/**
 	 * Flush rewrite rules
 	 *
 	 * @since 1.0.0
 	 */
 	function flush()
 	{
 		$this->register_post_type();

 		flush_rewrite_rules();
 	}
}
