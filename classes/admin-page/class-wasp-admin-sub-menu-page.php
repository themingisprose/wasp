<?php
namespace WASP\Admin;

/**
 * Admin Sub Page
 *
 * @since 1.0.0
 */
abstract class Admin_Sub_Menu_Page
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
	public $dashboard_title;

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
			array( $this, 'setting_options' ),
			$this->position
		);
	}

	/**
	 * Setting Options
	 *
	 * @since 1.0.0
	 */
	function setting_options()
	{
		?>
			<div class="wrap">
				<h2><?php echo $this->dashboard_title ?></h2>
				<?php settings_errors( 'wasp-update' ) ?>
				<form id="<?php echo $this->menu_slug ?>" method="post" action="options.php">
					<?php
						settings_fields( $this->option_group );
						do_settings_sections( $this->menu_slug );
						submit_button();
					?>
				</form>
			</div>
		<?php
	}
}
