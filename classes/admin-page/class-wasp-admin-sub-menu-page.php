<?php
namespace WASP\Admin;

use WASP\Admin\Admin_Page_Body as Page_Body;

/**
 * Admin Sub Page
 *
 * @since 1.0.0
 */
abstract class Admin_Sub_Menu_Page extends Page_Body
{

	/**
	 * Parent slug
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $parent_slug;

	/**
	 * Page Title
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $page_title;

	/**
	 * Menu Title
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $menu_title;

	/**
	 * Capability
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $capability;

	/**
	 * Manu slug
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $menu_slug;

	/**
	 * Option Name
	 * @access public
	 * @var int|float Optional
	 *
	 * @since 1.0.0
	 */
	public $position = null;

	/**
	 * Dashboard Title
	 * @access public
	 * @var string 	Optional
	 *
	 * @since 1.0.0
	 */
	public $page_heading;

	/**
	 * Option Group
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $option_group;


	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	function __construct()
	{
		add_action( 'admin_menu', array( $this, 'admin_sub_menu' ) );
	}

	/**
	 * Admin Sub Menu
	 *
	 * @since 1.0.0
	 */
	function admin_sub_menu()
	{
		add_submenu_page(
			$this->parent_slug,
			$this->page_title,
			$this->menu_title,
			$this->capability,
			$this->menu_slug,
			array( $this, 'page_body' ),
			$this->position
		);
	}
}
