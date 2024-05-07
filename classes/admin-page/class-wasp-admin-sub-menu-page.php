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
	 * @access protected
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	protected $parent_slug;

	/**
	 * Page Title
	 * @access protected
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	protected $page_title;

	/**
	 * Menu Title
	 * @access protected
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	protected $menu_title;

	/**
	 * Capability
	 * @access protected
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	protected $capability;

	/**
	 * Manu slug
	 * @access protected
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	protected $menu_slug;

	/**
	 * Option Name
	 * @access protected
	 * @var int|float Optional
	 *
	 * @since 1.0.0
	 */
	protected $position = null;

	/**
	 * Dashboard Title
	 * @access protected
	 * @var string 	Optional
	 *
	 * @since 1.0.0
	 */
	protected $page_heading;

	/**
	 * Option Group
	 * @access protected
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	protected $option_group;


	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	protected function __construct()
	{
		add_action( 'admin_menu', array( $this, 'admin_sub_menu' ) );
	}

	/**
	 * Admin Sub Menu
	 *
	 * @since 1.0.0
	 */
	public function admin_sub_menu()
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
