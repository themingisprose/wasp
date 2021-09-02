<?php
/**
 * Admin Page
 *
 * @since WASP 1.0.0
 */
class WASP_Admin_Page
{

	/**
	 * Page slug
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $slug;

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
	 * Construct
	 *
	 * @since WASP 1.0.0
	 */
	public function __construct()
	{
		$this->slug 			= 'wasp-setting';
		$this->option_group 	= 'wasp_setting';
		$this->option_name 		= 'wasp_options';
	}

	/**
	 * Init
	 *
	 * @since WASP 1.0.0
	 */
	public function init()
	{
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	/**
	 * Add the admin menu page
	 *
	 * @since WASP 1.0.0
	 */
	public function admin_menu()
	{
		add_menu_page(
			__( 'WASP Admin Page', 'wasp' ),
			__( 'WASP', 'wasp' ),
			'manage_options',
			$this->slug,
			array( $this, 'setting_options' ),
			'',
			2
		);
	}

	/**
	 * Display the form
	 *
	 * @since WASP 1.0.0
	 */
	public function setting_options()
	{
		?>
			<div class="wrap">
				<h2><?php _e( 'WASP Dashboard', 'wasp' ) ?></h2>
				<?php settings_errors( 'wasp-update' ) ?>
				<form id="<?php echo $this->slug ?>" method="post" action="options.php">
					<?php
						settings_fields( $this->option_group );
						do_settings_sections( $this->slug );
						submit_button();
					?>
				</form>
			</div>
		<?php
	}
}

$init = new WASP_Admin_Page;
$init->init();
