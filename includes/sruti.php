<?php 

function sruti_add_post_info( $infos, $post_type_slug ) {

	if ( ! in_array( $post_type_slug, array( 'event_post', 'event_songs' ) ) )
		return $infos;

	$infos[] = 'post_name';
	$infos[] = 'post_label';

	return $infos;
}
add_filter( 'ptl_post_type_info', 'sruti_add_post_info', 15, 2 );

function sruti_add_post_name_info( $info, $post_type_obj ) {

	$info = $post_type_obj->name;

	return $info;
}
add_filter( 'ptl_post_type_info_event_post_field_post_name', 'sruti_add_post_name_info', 10, 2 );
add_filter( 'ptl_post_type_info_event_songs_field_post_name', 'sruti_add_post_name_info', 10, 2 );

function sruti_add_post_label_info( $info, $post_type_obj ) {

	$info = $post_type_obj->label;

	return $info;
}
add_filter( 'ptl_post_type_info_event_post_field_post_label', 'sruti_add_post_label_info', 10, 2 );
add_filter( 'ptl_post_type_info_event_songs_field_post_label', 'sruti_add_post_label_info', 10, 2 );


function sruti_process_event_post( $response, $args ) {

	$page           = ( isset( $args['page'] ) ? $args['page'] : 1 );
	$posts_per_page = 50;

	$args = array(
		'post_type'      => 'event_post',
		'post_status'    => 'any',
		'posts_per_page' => $posts_per_page,
		'paged'          => $page,
	);

	$response['query']['args'] = $args;

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {

		$current_found_posts = 0;

		$post_ids = array();

		while ( $query->have_posts() ) {

			$query->the_post();

			$post_ids[] = get_the_ID();

			$event_songs = get_post_meta( get_the_ID(), 'event_songs', true );

			$processed = update_field( 'event_songs', $event_songs, get_the_ID() );

			// Got the function from sruti theme.
			sruti_update_date_to_event_songs( get_the_ID() );

			if ( $event_songs && $processed ) {

				$current_found_posts++;
			}
		}

		$response['page'] = intval( $page ) + 1;

		$response['query']['post_ids'] = $post_ids;

		$response['info']['processed'] = wp_sprintf( 'Page %s: Processed %s posts from %s.', $page, $current_found_posts, $query->post_count );
	} else {

		unset( $response['page'] );

		$response = array_values( $response );
	}

	wp_reset_postdata();

	return $response;
}
add_filter( 'ptl_process_event_post', 'sruti_process_event_post', 10, 2 );


function sruti_process_event_songs( $response, $args ) {

	$page           = ( isset( $args['page'] ) ? $args['page'] : 1 );
	$posts_per_page = 50;

	$args = array(
		'post_type'      => 'event_songs',
		'posts_per_page' => $posts_per_page,
		'paged'          => $page,
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {

		$current_found_posts = 0;

		$post_ids = array();

		while ( $query->have_posts() ) {

			$query->the_post();

			$event_id = get_post_meta( get_the_ID(), 'event', true );

			if ( ! $event_id ) {

				$legacy_event_id = get_post_meta( get_the_ID(), 'event_identifier', true );

				$event_post = get_event_by_legacy_id( $legacy_event_id );

				if ( $event_post ) {

					update_field( 'event', $event_post->ID, get_the_ID() );

					sruti_update_date_to_event_songs( $event_post->ID );
				}

				$post_ids[] = get_the_ID();
			}
		}

		$response['page'] = intval( $page ) + 1;

		$response['info']['processed'] = wp_sprintf( 'Page %s: Found %s posts from %s.', $page, count( $post_ids ), $query->post_count );

		$response['info']['post_ids'] = implode( ', ', $post_ids );
	} else {

		unset( $response['page'] );

		$response = array_values( $response );
	}

	return $response;
}
add_filter( 'ptl_process_event_songs', 'sruti_process_event_songs', 10, 2 );


function get_event_by_legacy_id( $legacy_event_id ) {

	$args = array(
		'post_type'      => 'event_post',
		'meta_key'       => 'event_identifier',
		'meta_value'     => $legacy_event_id,
		'posts_per_page' => 1,
	);

	$legacy_events_found = get_posts( $args );

	if ( $legacy_events_found ) {

		$legacy_events_found = $legacy_events_found[0];
	}

	return $legacy_events_found;
}
