<?php
/**
 * Manage post type info diplay.
 *
 * @package WordPress
 * @subpackage Post Type Looper
 * @since 0.1.0
 */

/**
 * Manage post type info diplay.
 *
 * @package WordPress
 * @subpackage Post Type Looper
 * @since 0.1.0
 */
class Ptl_Manager_Post_Type_Info {

	/**
	 * Register all default info key callback.
	 *
	 * Default informations to be displayed.
	 *
	 * @return void
	 **/
	public function __construct() {

		$default_fields = $this->get_defaults();

		if ( $default_fields ) {

			foreach ( $default_fields as $key ) {

				add_filter( 'ptl_post_type_info_default_field', array( $this, 'get_default_info' ), 10, 3 );
			}
		}
	}

	/**
	 * Get defaults info.
	 *
	 * Default informations to be displayed.
	 *
	 * @param string $val           Post type info.
	 * @param string $key           Post type info.
	 * @param obj    $post_type_obj Post type info.
	 * @return array
	 **/
	public function get_default_info( $val, $key, $post_type_obj ) {

		switch ( $key ) {

			case 'post_total':
				$val = wp_count_posts( $post_type_obj->name )->publish;
				break;
		}

		return $val;
	}

	/**
	 * Get defaults info.
	 *
	 * Default informations to be displayed.
	 *
	 * @return array
	 **/
	public function get_defaults() {

		$defaults = array(
			'post_total',
		);

		return $defaults;
	}

	/**
	 * Get defaults info.
	 *
	 * Default informations to be displayed.
	 *
	 * @param array  $informations   Current post type informations.
	 * @param string $post_type_slug Post type slug.
	 * @return array
	 **/
	public function get_info( $informations, $post_type_slug ) {

		$default_fields = $this->get_defaults();
		$info_fields    = apply_filters( 'ptl_post_type_info', $default_fields, $post_type_slug );
		$post_type_obj  = get_post_type_object( $post_type_slug );

		if ( $post_type_obj && $info_fields ) {

			foreach ( $info_fields as $key ) {

				if ( in_array( $key, $default_fields, true ) ) {

					$informations[ $key ] = apply_filters( 'ptl_post_type_info_default_field', '', $key, $post_type_obj );
				} else {

					$informations[ $key ] = apply_filters( 'ptl_post_type_info_' . $post_type_slug . '_field_' . $key, '', $post_type_obj );
				}
			}
		}

		return $informations;
	}
}
