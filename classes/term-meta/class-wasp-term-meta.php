<?php
namespace WASP\Taxonomy;

use WASP\Helpers\HTML;

/**
 * Term Meta
 *
 * @since WASP 1.0.0
 */
abstract class Term_Meta
{

	/**
	 * Taxonomy
	 * @access public
	 * @var string 	Required
	 *
	 * @since WASP 1.0.0
	 */
	public $taxonomy;

	/**
	 * Constructor
	 *
	 * @since WASP 1.0.0
	 */
	function __construct()
	{
		add_action( 'admin_init', array( $this, 'init' ) );
	}

	/**
	 * Initializes hooks
	 *
	 * @since WASP 1.0.0
	 */
	function init()
	{
		add_action( $this->taxonomy .'_add_form_fields', array( $this, 'render' ), 10, 2 );
		add_action( $this->taxonomy .'_edit_form_fields', array( $this, 'render' ), 10, 2 );
		add_action( 'create_'. $this->taxonomy, array( $this, 'save_meta' ), 10, 2 );
		add_action( 'edited_'. $this->taxonomy, array( $this, 'save_meta' ), 10, 2 );
	}

	/**
	 * Render the content of the meta box.
	 * @param object $term 	Current term.
	 *
	 * @since WASP 1.0.0
	 */
	function render( $term )
	{
		$fields = $this->fields();

		foreach ( $fields as $key => $data ) :
			$value = ( isset( $term->term_id ) )
							? get_term_meta( $term->term_id, $data['meta'], true )
							: null;

			$this->html( $data, $value );
		endforeach;
	}

	/**
	 * Form fields to render
	 * @param string $args 	This parameter is described in class WASP\Helpers\HTML::field() method
	 * @param string $value	This parameter is described in class WASP\Helpers\HTML::field() method
	 *
	 * @since WASP 1.0.0
	 */
	function html( $args, $value )
	{

		$tr 	= ( $this->taxonomy .'_edit_form_fields' == current_filter() )
					? '<tr class="form-field term-order-wrap">'
					: '<div class="form-field">';
		$tr_end	= ( $this->taxonomy .'_edit_form_fields' == current_filter() )
					? '</tr>'
					: '</div>';

		$th 	= ( $this->taxonomy .'_edit_form_fields' == current_filter() )
					? '<th scope="row">'
					: null;
		$th_end	= ( $this->taxonomy .'_edit_form_fields' == current_filter() )
					? '</th>'
					: null;

		$td 	= ( $this->taxonomy .'_edit_form_fields' == current_filter() )
					? '<td>'
					: null;
		$td_end	= ( $this->taxonomy .'_edit_form_fields' == current_filter() )
					? '</td>'
					: null;

		$label 	= ( $this->taxonomy .'_edit_form_fields' == current_filter() )
					? '<p><label for="'. $args['meta'] .'" class="description">'. $args['label'] .'</label></p>'
					: null;

		echo $tr;
			echo $th;
				echo $label;
			echo $th_end;
			echo $td;

			HTML::field( $args, $value );

			echo $td_end;
		echo $tr_end;
	}

	/**
	 * Save the Term Meta
	 * @param int $term_id 	Current term.
	 *
	 * @since WASP 1.0.0
	 */
	function save_meta( $term_id )
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
	 * 				'label'		=> 'Field Name',
	 * 				'option'	=> 'field_option_name',
	 * 				'type'		=> 'text',
	 * 				'multiple'	=> array()
	 * 			),
	 * 			...
	 * 		);
	 * @see class WASP\Helpers\HTML::field() for full documentation about supported fields.
	 * @return array 	Array of fields
	 *
	 * @since WASP 1.0.0
	 */
	abstract public function fields();
}
