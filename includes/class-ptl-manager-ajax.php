<?php
/**
 * Manage ajax requests.
 *
 * @package WordPress
 * @subpackage Post Type Looper
 * @since 0.1.0
 */

/**
 * Manage ajax requests.
 *
 * @package WordPress
 * @subpackage Post Type Looper
 * @since 0.1.0
 */
class Ptl_Manager_Ajax {

	/**
	 * Get current request data.
	 *
	 * Helper function to get request data.
	 *
	 * @param string $name key to be checked and retrieved.
	 * @return mixed
	 **/
	public function get_requests( $name ) {

		$data = null;

		if ( isset( $_REQUEST['data'][ $name ] ) ) { //phpcs:ignore WordPress.Security.NonceVerification

			$data = sanitize_text_field( wp_unslash( $_REQUEST['data'][ $name ] ) ); //phpcs:ignore WordPress.Security.NonceVerification
		}

		return $data;
	}

	/**
	 * Respond to post type selected.
	 *
	 * Return informations for selected post type.
	 *
	 * @return void
	 **/
	public function ptl_action_post_type_selected() {

		$selected_post_type = $this->get_requests( 'selected_post_type' );

		$response = array(
			'info' => apply_filters( 'ptl_response_info_post_type_selected', array(), $selected_post_type ),
		);

		wp_send_json( $response );
	}

	/**
	 * Respond to post type selected.
	 *
	 * Return informations for selected post type.
	 *
	 * @return void
	 **/
	public function ptl_action_post_type_process() {

		$selected_post_type = $this->get_requests( 'selected_post_type' );

		$default_args     = array( 'page' => $this->get_requests( 'page' ) );
		$default_response = array();

		$response = apply_filters( 'ptl_process_' . $selected_post_type, $default_response, $default_args );

		wp_send_json( $response );
	}
}
