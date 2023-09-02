<?php
/**
 * Get WASP Option
 * @param string $option 	Required. Options name
 * @return string 			Option value
 *
 * @since WASP 1.0.0
 */
function wasp_get_option( $option ){
    $classes = array();
    foreach ( get_declared_classes() as $class ) :
        if ( is_subclass_of( $class, 'WASP_Setting_Fields' ) )
            $classes[] = $class;
    endforeach;

    $class = new $classes[0];

    $value = get_option( $class->option_name );
    $value = $value[$option];

    return $value;
}

/**
 * Print WASP Option
 * @param string $option 	Required. Options name
 * @return string 			Option value
 *
 * @since WASP 1.0.0
 */
function wasp_option( $option ){
	echo wasp_get_option( $option );
}
