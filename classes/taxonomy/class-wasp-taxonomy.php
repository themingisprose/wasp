<?php
namespace WASP\Taxonomy;

/**
 * Custom Taxonomy
 *
 * @since 1.0.0
 */
abstract class Taxonomy
{

	/**
	 * Taxonomy slug
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $taxonomy;

	/**
	 * Post Type slug
	 * @access public
	 * @var array|string Required
	 *
	 * @since 1.0.0
	 */
	public $object_type;

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
 	public function __construct()
 	{
 		add_action( 'init', array( $this, 'register_taxonomy' ) );
 	}

	/**
	 * Register Taxonomy
	 *
	 * @since 1.0.0
	 */
	public function register_taxonomy()
	{
 		$labels = array( 'labels' => $this->labels );
 		$args = array_merge( $labels, $this->args );
 		register_taxonomy( $this->taxonomy, $this->object_type, $args );
	}
}
