<?php
/**
 * Custom Taxonomy
 *
 * @since WASP 1.0.0
 */
abstract class WASP_Taxonomy
{

	/**
	 * Taxonomy slug
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $taxonomy;

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
 		add_action( 'init', array( $this, 'register_taxonomy' ) );
 	}

	/**
	 * Register Taxonomy
	 *
	 * @since WASP 1.0.0
	 */
	public function register_taxonomy()
	{
 		$labels = array( 'labels' => $this->labels );
 		$args = array_merge( $labels, $this->args );
 		register_taxonomy( $this->taxonomy, $this->post_type, $args );
	}
}
