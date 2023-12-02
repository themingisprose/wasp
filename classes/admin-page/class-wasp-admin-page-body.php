<?php
namespace WASP\Admin;

/**
 * Admin Page Setting Options
 *
 * @since 1.0.1
 */
abstract class Admin_Page_Body
{

	/**
	 * Display Setting Options body page
	 *
	 * @since 1.0.1
	 */
	public function page_body()
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
