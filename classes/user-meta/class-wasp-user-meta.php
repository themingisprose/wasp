<?php
namespace WASP\Users;

use WASP\Helpers\HTML;
use WASP\Interfaces\Fields;

/**
 * User Meta
 *
 * @since 1.0.0
 */
abstract class User_Meta implements Fields
{

	/**
	 * User
	 * @access protected
	 * @var object
	 *
	 * @since 1.0.0
	 */
	protected $user_id;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		if ( ! function_exists( 'wp_get_current_user' ) )
			include_once( ABSPATH .'wp-includes/pluggable.php' );

		$current_user = wp_get_current_user();
		$user_id = $_GET['user_id'] ?? $current_user->ID;
		$this->user_id = $user_id;

		add_action( 'show_user_profile', array( $this, 'render' ) );
		add_action( 'edit_user_profile', array( $this, 'render' ) );
		add_action( 'user_new_form', array( $this, 'render' ) );
		add_action( 'personal_options_update', array( $this, 'save' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save' ) );
		add_action( 'user_register', array( $this, 'save' ) );
	}

	/**
	 * Render the content of the section
	 * @see WASP\Helpers\HTML::field()
	 *
	 * @since 1.0.0
	 */
	public function render()
	{
		$fields = $this->fields();
	?>
		<table class="form-table">
	<?php
		foreach ( $fields as $key => $data ) :
			$value = ( isset( $this->user_id ) )
						? get_user_meta( $this->user_id, $data['meta'], true )
						: null;

			$this->html( $data, $value );
		endforeach;
	?>
		</table>
	<?php
	}

	/**
	 * Form fields to render
	 * @param string $args 	This parameter is described in class WASP\Helpers\HTML::field() method
	 * @param string $value	This parameter is described in class WASP\Helpers\HTML::field() method
	 *
	 * @since 1.0.0
	 */
	private function html( $data, $value )
	{
		$screen = get_current_screen();
		$value 	= ( 'user' != $screen->id ) ? $value : null;
	?>
		<tr>
			<th>
				<label for="<?php echo $data['meta'] ?>"><?php echo $data['label'] ?></label>
			</th>
			<td>
				<?php HTML::field( $data, $value ); ?>
			</td>
		</tr>
	<?php
	}

	/**
	 * Save the user meta
	 * @param object $user_id
	 *
	 * @since 1.0.0
	 */
	public function save( $user_id )
	{
		$fields = $this->fields();

		foreach ( $fields as $key => $value ) :
			if ( isset( $_POST[$value['meta']] ) && '' != $_POST[$value['meta']] ) :
				update_user_meta( $user_id, $value['meta'], $_POST[$value['meta']] );
			else :
				delete_user_meta( $user_id, $value['meta'] );
			endif;
		endforeach;
	}
}
