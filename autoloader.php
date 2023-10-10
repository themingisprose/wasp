<?php
/**
 * Class Autoloader 101
 *
 * @since WASP 1.0.0
 */
spl_autoload_register( function( $class ){
	$directories = glob( plugin_dir_path( __FILE__ ) .'classes/*', GLOB_ONLYDIR );

	$parts = explode( '\\', $class );
	$class_name = end( $parts );

	foreach ( $directories as $dir ) :
		$file = $dir .'/class-wasp-'. str_replace( '_', '-', strtolower( $class_name ) ) .'.php';

		if ( file_exists( $file ) ) :
			require_once $file;
			break;
		endif;
	endforeach;
} );
