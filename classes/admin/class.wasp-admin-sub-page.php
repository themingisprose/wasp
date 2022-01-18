<?php
/**
 * Admin Sub Page
 *
 * @since WASP 1.0.0
 */
abstract class WASP_Admin_Sub_Page
{
	/**
	 * Parent slug
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $parent_slug;

	/**
	 * Page Title
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $page_title;

	/**
	 * Manu Title
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $menu_title;

	/**
	 * Dashboard Title
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $dashboard_title;

	/**
	 * Manu slug
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $menu_slug;

	/**
	 * Option Group
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $option_group;

	/**
	 * Option Name
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $option_name;

	/**
	 * Init
	 *
	 * @since WASP 1.0.0
	 */
	public function init()
	{
		add_action( 'admin_menu', array( $this, 'admin_sub_menu' ) );
	}

	/**
	 * Admin Sub Menu
	 *
	 * @since WASP 1.0.0
	 */
	public function admin_sub_menu()
	{
		add_submenu_page(
			$this->parent_slug,
			$this->page_title,
			$this->menu_title,
			'manage_options',
			$this->menu_slug,
			array( $this, 'setting_options' )
		);
	}

	/**
	 * Setting Options
	 *
	 * @since WASP 1.0.0
	 */
	public function setting_options()
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
