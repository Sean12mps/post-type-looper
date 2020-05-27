<?php
/**
 * Manage admin hooks.
 *
 * @package WordPress
 * @subpackage Post Type Looper
 * @since 0.1.0
 */

/**
 * Manage admin hooks.
 *
 * @package WordPress
 * @subpackage Post Type Looper
 * @since 0.1.0
 */
class Ptl_Admin {

	/**
	 * Add plugin option page.
	 *
	 * Add Post Type Looper option page.
	 *
	 * @return void
	 **/
	public function add_options_page() {

		// Add option page in admin menu.
		add_management_page(
			__( 'Post Type Looper', 'post-type-looper' ),
			__( 'Post Type Looper', 'post-type-looper' ),
			'manage_options',
			'post-type-looper',
			array( $this, 'print_option_page' )
		);
	}

	/**
	 * Add plugin option page.
	 *
	 * Add Post Type Looper option page.
	 *
	 * @return void
	 **/
	public function print_option_page() {

		require_once PTL_DIR . '/templates/option-page.php';
	}

	/**
	 * Register plugin scripts.
	 *
	 * Register javascript and css files through WordPress script hook.
	 *
	 * @return void
	 **/
	public function register_scripts() {

		$css_url = PTL_URL . 'assets/styles/dist/';
		$js_url  = PTL_URL . 'assets/scripts/dist/';

		wp_register_style( 'ptl-option-page', $css_url . 'ptl-option-page.min.css', array(), PTL_VERSION, 'all' );
		wp_register_script( 'ptl-option-page', $js_url . 'ptl-option-page.min.js', array( 'jquery' ), PTL_VERSION, true );
	}

	/**
	 * Localize scripts.
	 *
	 * Print javascript variables.
	 *
	 * @return void
	 **/
	public function localize_scripts() {

		$localized_scripts = array();

		wp_localize_script( 'ptl-option-page', 'post_type_looper', $localized_scripts );
	}

	/**
	 * Print plugin scripts.
	 *
	 * Print plugin scripts as registered.
	 *
	 * @return void
	 **/
	public function enqueue_scripts() {

		wp_enqueue_style( 'ptl-option-page' );
		wp_enqueue_script( 'ptl-option-page' );
	}
}
