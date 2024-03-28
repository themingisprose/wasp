<?php
/**
 * Class Autoloader 101
 *
 * @since 1.0.0
 */
spl_autoload_register( function( $class ){
	$directories = glob( plugin_dir_path( __FILE__ ) .'classes/*', GLOB_ONLYDIR );

	$parts = explode( '\\', $class );
	$class_name = end( $parts );

	foreach ( $directories as $dir ) :
		$prefixes = array(
			'class',
			'interface'
		);
		foreach ( $prefixes as $prefix ) :
			$file = $dir .'/'. $prefix .'-wasp-'. str_replace( '_', '-', strtolower( $class_name ) ) .'.php';

			if ( file_exists( $file ) ) :
				require_once $file;
				break;
			endif;
		endforeach;
	endforeach;
} );
