<?php
/**
 * Get WASP Option
 * @param string $option 	Required. Options name
 * @param bool $lang        Set to true to search for the option corresponding to the current language
 * @return string 			Option value
 *
 * @since WASP 1.0.0
 */
function wasp_get_option( $option, $lang = false ){
	if ( ! $option )
		return;

	$value = get_option( 'wasp_options' );

    if ( $option ) :
        include_once( ABSPATH .'wp-admin/includes/plugin.php' );
        if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) && $lang ) :
            $current_lang = apply_filters( 'wpml_current_language', NULL );
            $value = $value[$option .'_'. $current_lang];
        else :
            $value = $value[$option];
        endif;
    endif;

	return $value;
}

/**
 * Print WASP Option
 * @param string $option 	Required. Options name
 * @param bool $lang        Set to true to search for the option corresponding to the current language
 * @return string 			Option value
 *
 * @since WASP 1.0.0
 */
function wasp_option( $option, $lang = false ){
	echo wasp_get_option( $option, $lang );
}
