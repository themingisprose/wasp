<?php
namespace WASP\Interfaces;

/**
 * Interface Fields
 *
 * @since 1.0.1
 */
interface Fields
{
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
	 * @since 1.0.1
	 */
	public function fields();
}
