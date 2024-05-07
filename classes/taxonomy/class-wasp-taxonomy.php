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
	 * @access protected
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	protected $taxonomy;

	/**
	 * Post Type slug
	 * @access protected
	 * @var array|string Required
	 *
	 * @since 1.0.0
	 */
	protected $object_type;

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
