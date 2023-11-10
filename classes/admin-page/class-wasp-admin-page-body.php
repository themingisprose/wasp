<?php
namespace WASP\Admin;

/**
 * Admin Page Body
 *
 * @since 1.0.1
 */
class Admin_Page_Body
{

	/**
	 * Display the page body
	 * @param array $args {
	 * 		Array of arguments, supports the following keys:
	 * 		@type string $page_heading	Page heading
	 * 		@type string $menu_slug		Menu slug
	 * 		@type string $option_group	Option group
	 * }
	 * @see WASP\Admin\Admin_Page::setting_options()
	 * @see WASP\Admin\Admin_Sub_Menu_Page::setting_options()
	 *
	 * @since 1.0.1
	 */
	public static function body( $args )
	{
		$defaults = array(
			'page_heading'	=> '',
			'menu_slug'		=> '',
			'option_group'	=> ''
		);
		$args = wp_parse_args( $args, $defaults );
		?>
			<div class="wrap">
				<h2><?php echo $args['page_heading'] ?></h2>
				<?php settings_errors( 'wasp-update' ) ?>
				<form id="<?php echo $args['menu_slug'] ?>" method="post" action="options.php">
					<?php
						settings_fields( $args['option_group'] );
						do_settings_sections( $args['menu_slug'] );
						submit_button();
					?>
				</form>
			</div>
		<?php
	}
}
