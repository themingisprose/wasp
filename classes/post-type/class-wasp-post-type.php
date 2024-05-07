<?php
namespace WASP\Posts;

/**
 * Custom Post Type
 *
 * @since 1.0.0
 */
abstract class Post_Type
{
	/**
	 * Post Type slug
	 * @access protected
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	protected $post_type;

	/**
	 * Labels
	 * @access protected
	 * @var array 	Optional
	 *
	 * @since 1.0.0
	 */
	protected $labels = array();

	/**
	 * Arguments
	 * @access protected
	 * @var array 	Optional
	 *
	 * @since 1.0.0
	 */
	protected $args = array();


 	/**
 	 * Constructor
 	 *
 	 * @since 1.0.0
 	 */
 	protected function __construct()
 	{
 		add_action( 'init', array( $this, 'register_post_type' ) );
 		register_activation_hook( __FILE__, array( $this, 'flush' ) );
 	}

 	/**
 	 * Register CPT
 	 *
 	 * @since 1.0.0
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
 	 * @since 1.0.0
 	 */
 	public function flush()
 	{
 		$this->register_post_type();

 		flush_rewrite_rules();
 	}
}
