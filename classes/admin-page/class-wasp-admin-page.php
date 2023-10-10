<?php
/**
 * Admin Page
 *
 * @since WASP 1.0.0
 */
abstract class WASP_Admin_Page
{

	/**
	 * Page title
	 * @access public
	 * @var string 	Required
	 *
	 * @since WASP 1.0.0
	 */
	public $page_title;

	/**
	 * Menu title
	 * @access public
	 * @var string 	Required
	 *
	 * @since WASP 1.0.0
	 */
	public $menu_title;

	/**
	 * Capability
	 * @access public
	 * @var string 	Required
	 *
	 * @since WASP 1.0.0
	 */
	public $capability;

	/**
	 * Menu slug
	 * @access public
	 * @var string 	Required
	 *
	 * @since WASP 1.0.0
	 */
	public $menu_slug;

	/**
	 * Icon URL
	 * @access public
	 * @var string 	Optional
	 *
	 * @since WASP 1.0.0
	 */
	public $icon_url = '';

	/**
	 * Position
	 * @access public
	 * @var string 	Optional
	 *
	 * @since WASP 1.0.0
	 */
	public $position = null;

	/**
	 * Page heading
	 * @access public
	 * @var string 	Optional
	 *
	 * @since WASP 1.0.0
	 */
	public $page_heading;

	/**
	 * Option Group
	 * @access public
	 * @var string 	Required
	 *
	 * @since WASP 1.0.0
	 */
	public $option_group;

	/**
	 * Construct
	 *
	 * @since WASP 1.0.0
	 */
	function __construct()
	{
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	/**
	 * Add the admin menu page
	 *
	 * @since WASP 1.0.0
	 */
	function admin_menu()
	{
		add_menu_page(
			$this->page_title,
			$this->menu_title,
			$this->capability,
			$this->menu_slug,
			array( $this, 'setting_options' ),
			$this->icon_url,
			$this->position
		);
	}

	/**
	 * Display the form
	 *
	 * @since WASP 1.0.0
	 */
	function setting_options()
	{
		?>
			<div class="wrap">
				<h2><?php echo $this->page_heading ?></h2>
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
