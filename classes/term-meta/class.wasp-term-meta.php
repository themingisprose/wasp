<?php
/**
 * Term Meta
 *
 * @since WASP 1.0.0
 */
abstract class WASP_Term_Meta
{

	/**
	 * Taxonomy
	 * @access public
	 * @var string
	 *
	 * @since WASP 1.0.0
	 */
	public $taxonomy;

	/**
	 * Constructor
	 * @param string $taxonomy
	 *
	 * @since WASP 1.0.0
	 */
	public function __construct( $taxonomy )
	{
		$this->taxonomy = $taxonomy;
	}

	/**
	 * Hook the forms
	 *
	 * @since WASP 1.0.0
	 */
	public function init()
	{
		add_action( $this->taxonomy .'_add_form_fields', array( $this, 'render' ), 10, 2 );
		add_action( $this->taxonomy .'_edit_form_fields', array( $this, 'render' ), 10, 2 );
		add_action( 'create_'. $this->taxonomy, array( $this, 'save_meta' ), 10, 2 );
		add_action( 'edited_'. $this->taxonomy, array( $this, 'save_meta' ), 10, 2 );
	}

	/**
	 * Render the content of the meta box.
	 * @param WP_Post $post 		Required. Post object.
	 *
	 * @since WASP 1.0.0
	 */
	public function render( $term )
	{
		$fields = $this->fields();

		foreach ( $fields as $key => $value ) :
			$meta_value = ( isset( $term->term_id ) )
							? get_term_meta( $term->term_id, $value['meta'], true )
							: null;

			$this->html( $value['meta'], $meta_value, $value['label'], $value['type'] ?? null, $value['select'] ?? null );
		endforeach;
	}

	/**
	 * Form fields to render
	 * @param string $meta
	 * @param string $value
	 * @param string $label
	 * @param string $type
	 * @param array $select 	If $type = 'select', $select must be define as array
	 * 							$select = array(
	 * 								'value_01'	=> 'Label 01',
	 * 								'value_02'	=> 'Label 02',
	 * 								...
	 * 							)
	 *
	 * @since WASP 1.0.0
	 */
	function html( $meta, $value, $label, $type = null, $select = null )
	{
		$type = ( isset( $type ) ) ? $type : 'text';

		$tr 	= ( $this->taxonomy .'_edit_form_fields' == current_filter() )
					? '<tr class="form-field term-order-wrap">'
					: null;
		$tr_end	= ( $this->taxonomy .'_edit_form_fields' == current_filter() )
					? '</tr>'
					: '<div class="mb-2">';

		$th 	= ( $this->taxonomy .'_edit_form_fields' == current_filter() )
					? '<th scope="row">'
					: '</div>';
		$th_end	= ( $this->taxonomy .'_edit_form_fields' == current_filter() )
					? '</th>'
					: null;

		$td 	= ( $this->taxonomy .'_edit_form_fields' == current_filter() )
					? '<td>'
					: null;
		$td_end	= ( $this->taxonomy .'_edit_form_fields' == current_filter() )
					? '</td>'
					: null;

		echo $tr;
			echo $th;
	?>
			<p><label for="<?php echo $meta ?>" class="description d-block mb-2"><?php echo $label ?></label></p>
		<?php
			echo $th_end;
			echo $td;

			$html = new WASP_Html;
			$html::field( $meta, $value, $label, $type, $select );

			echo $td_end;
		echo $tr_end;
	}

	/**
	 * Save the Term Meta
	 * @param int $term_id 		Current Term
	 *
	 * @since WASP 1.0.0
	 */
	public function save_meta( $term_id )
	{
		$fields = $this->fields();

		foreach ( $fields as $key => $value ) :
			if ( isset( $_POST[$value['meta']] ) && '' != $_POST[$value['meta']] ) :
				update_term_meta( $term_id, $value['meta'], $_POST[$value['meta']] );
			else :
				delete_term_meta( $term_id, $value['meta'] );
			endif;
		endforeach;
	}

	/**
	 * Array of fields to render
	 * This method must return an associative array like the example
	 * 		$fields = array(
	 * 			'field_key_name' => array(
	 * 				'label'	=> 'Field Name',
	 * 				'meta'	=> 'field_option_name',
	 * 			),
	 * 			...
	 * 		);
	 * @return array 		Array of fields
	 *
	 * @since WASP 1.0.0
	 */
	abstract public function fields();
}
