<?php
/**
 * Class Autoloader 101
 *
 * @since WASP 1.0.0
 */
spl_autoload_register( function( $class ){
	$directories = glob( plugin_dir_path( __DIR__ ) .'classes/*', GLOB_ONLYDIR );

	foreach ( $directories as $dir ) :
		$file = $dir .'/class-'. str_replace( '_', '-', strtolower( $class ) ) .'.php';

		if ( file_exists( $file ) ) :
			require_once $file;
			break;
		endif;
	endforeach;
} );
