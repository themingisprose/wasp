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
	 * Manu slug
	 * @access public
	 *
	 * @since WASP 1.0.0
	 */
	public $menu_slug;

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
				<h2><?php echo $this->page_title ?></h2>
				<table class="form-table" role="presentation">
				<?php $this->render() ?>
				</table>
			</div>
		<?php
	}

	/**
	 * Render the content of the section
	 *
	 * @since WASP 1.0.0
	 */
	public function render()
	{
		$fields = $this->fields();

		foreach ( $fields as $key => $value ) :
		?>
			<tr>
				<th scope="row"><?php echo $value['label'] ?></th>
				<td>
					<p><?php echo $value['description'] ?></p>
					<form method="post">
						<?php
						wp_nonce_field( $value['nonce_attr'], $value['nonce_field'] );
						submit_button( $value['label'] );
						?>
					</form>
				</td>
			</tr>
		<?php
		endforeach;
	}

	/**
	 * Array of fields to render
	 * This method must return an associative array like the example
	 * 		$fields = array(
	 * 			array(
	 * 				'label'			=> 'Field Name',
	 * 				'description'	=> 'Description',
	 * 				'nonce_attr'	=> 'Security nonce attribute',
	 * 				'nonce_field'	=> 'Security nonce field',
	 * 			),
	 * 			...
	 * 		);
	 * @return array 		Array of fields
	 *
	 * @since WASP 1.0.0
	 */
	abstract public function fields();
}
