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
	 * @var string
	 *
	 * @since WASP 1.0.0
	 */
	public $taxonomy;

	/**
	 * Post Type slug
	 * @access public
	 * @var array|string
	 *
	 * @since WASP 1.0.0
	 */
	public $object_type;

	/**
	 * Labels
	 * @access public
	 * @var array
	 *
	 * @since WASP 1.0.0
	 */
	public $labels = array();

	/**
	 * Arguments
	 * @access public
	 * @var array
	 *
	 * @since WASP 1.0.0
	 */
	public $args = array();

	/**
	 * Constructor
 	 *
 	 * @since WASP 1.0.0
	 */
 	function __construct()
 	{
 		add_action( 'init', array( $this, 'register_taxonomy' ) );
 	}

	/**
	 * Register Taxonomy
	 *
	 * @since WASP 1.0.0
	 */
	function register_taxonomy()
	{
 		$labels = array( 'labels' => $this->labels );
 		$args = array_merge( $labels, $this->args );
 		register_taxonomy( $this->taxonomy, $this->object_type, $args );
	}
}
