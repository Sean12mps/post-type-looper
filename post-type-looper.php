<?php
/**
 * Plugin Name: Post Type Looper
 * Plugin URI: https://github.com/Sean12mps
 * Description: Tools to help when looping through posts.
 * Version: 0.1.0
 * Author: Sean12mps
 * Author URI: https://github.com/Sean12mps
 * License: GPLv2 or later
 * Text Domain: post-type-looper
 *
 * @package Post_Type_Looper
 */

/**
 * Plugin main load.
 *
 * Loads when plugins are loaded.
 *
 * @return void
 **/
function ptl_load() {

	ptl_define_constants();
	ptl_require_dependencies();
	ptl_init_hooks();
}
add_action( 'plugins_loaded', 'ptl_load' );


/**
 * Register constants.
 *
 * Registers all needed constants.
 *
 * @return void
 **/
function ptl_define_constants() {

	define( 'PTL_VERSION', '0.1.0' );
	define( 'PTL_DIR', plugin_dir_path( __FILE__ ) );
	define( 'PTL_URL', plugin_dir_url( __FILE__ ) );
}


/**
 * Require all plugin dependencies.
 *
 * Loads all files needed for the plugin to run.
 *
 * @return void
 **/
function ptl_require_dependencies() {

	$includes_dir = PTL_DIR . 'includes/';

	require_once $includes_dir . 'class-ptl-admin.php';
	require_once $includes_dir . 'class-ptl-manager-post-type-info.php';
	require_once $includes_dir . 'class-ptl-manager-ajax.php';

	require_once $includes_dir . 'sruti.php';
}



/**
 * Initialize all hooks.
 *
 * Loads classes and initialize hooks.
 *
 * @return void
 **/
function ptl_init_hooks() {

	$admin    = new Ptl_Admin();
	$ptl_info = new Ptl_Manager_Post_Type_Info();
	$ptl_ajax = new Ptl_Manager_Ajax();

	add_action( 'admin_menu', array( $admin, 'add_options_page' ) );
	add_action( 'admin_enqueue_scripts', array( $admin, 'register_scripts' ) );
	add_action( 'admin_enqueue_scripts', array( $admin, 'localize_scripts' ) );
	add_action( 'admin_enqueue_scripts', array( $admin, 'enqueue_scripts' ) );

	add_filter( 'ptl_post_type_info', array( $ptl_info, 'get_defaults' ), 10, 1 );

	add_action( 'wp_ajax_ptl_action_post_type_selected', array( $ptl_ajax, 'ptl_action_post_type_selected' ) );
	add_action( 'wp_ajax_ptl_action_post_type_process', array( $ptl_ajax, 'ptl_action_post_type_process' ) );

	add_filter( 'ptl_response_info_post_type_selected', array( $ptl_info, 'get_info' ), 10, 2 );
}
