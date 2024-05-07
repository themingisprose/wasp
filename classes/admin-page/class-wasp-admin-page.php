<?php
namespace WASP\Admin;

use WASP\Admin\Admin_Page_Body as Page_Body;

/**
 * Admin Page
 *
 * @since 1.0.0
 */
abstract class Admin_Page extends Page_Body
{

	/**
	 * Page title
	 * @access protected
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	protected $page_title;

	/**
	 * Menu title
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
	 * Menu slug
	 * @access protected
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	protected $menu_slug;

	/**
	 * Icon URL
	 * @access protected
	 * @var string 	Optional
	 *
	 * @since 1.0.0
	 */
	protected $icon_url = '';

	/**
	 * Position
	 * @access protected
	 * @var string 	Optional
	 *
	 * @since 1.0.0
	 */
	protected $position = null;

	/**
	 * Page heading
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
	 * Construct
	 *
	 * @since 1.0.0
	 */
	protected function __construct()
	{
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	/**
	 * Add the admin menu page
	 *
	 * @since 1.0.0
	 */
	public function admin_menu()
	{
		add_menu_page(
			$this->page_title,
			$this->menu_title,
			$this->capability,
			$this->menu_slug,
			array( $this, 'page_body' ),
			$this->icon_url,
			$this->position
		);
	}
}
