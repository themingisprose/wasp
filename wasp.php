<?php
/**
 * Plugin Name: WASP
 * Description: Wow! Another starter plugin
 * Plugin URI: https://github.com/themingisprose/wasp
 * Author: RogerTM
 * Author URI: https://rogertm.dev
 * Version: 1.0.0
 * License: GPL2
 * Text Domain: wasp
 * Domain Path: /languages
 */

/**
 * Copyright (C) 2021  RogerTM  roger213tm@gmail.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) )
    die;

/**
 * Set text domain
 *
 * @since WASP 1.0.0
 */
function wasp_textdomain(){
	load_plugin_textdomain( 'wasp', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'wasp_textdomain' );

/**
 * Include files
 */
require plugin_dir_path( __FILE__ ) .'/classes/index.php';
require plugin_dir_path( __FILE__ ) .'/inc/index.php';
