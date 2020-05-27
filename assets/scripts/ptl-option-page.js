jQuery(document).ready(function ( $ ) {
	console.log(post_type_looper);

	// Helpers.
	var ptl_ajax = function( action, data, callback, callback2 ) {

		$.ajax({
			type    : 'POST',
			url     : ajaxurl,
			dataType: 'JSON',
			data    : {
				action: action,
				data  : data,
			},
			success : function ( response ) {

				console.log('success');

				if ( 'function' === typeof( callback ) ) {

					callback( response );
				}
			},
			always: function( response ) {

				console.log('always');

				if ( 'function' === typeof( callback2 ) ) {

					callback2( response );
				}
			},
			error: function( response ) {

				console.log('error');

				if ( 'function' === typeof( callback2 ) ) {

					callback2( response );
				}
			},
		});
	};

	var ptl_show_info = function ( response, append ) {

		console.log(response);

		if ( ! response.info ) return;

		var listItem = $( '#ptl-post-type-info #informations .info.template' ).clone(),
			total = Object.keys(response.info).length,
			printIndex = 1;

		if ( ! append ) {
			$( '#ptl-post-type-info #informations' ).empty();
			$( '#ptl-post-type-info #informations' ).prepend( listItem );
		}

		$.each( response.info, function ( key, value ) { 

			var listItem = $( '#ptl-post-type-info #informations .info.template' ).clone();
			$( listItem ).removeClass( 'template' );
			$( listItem ).find( 'b' ).text( key );
			$( listItem ).find( 'span' ).addClass( 'info-' + key );
			$( listItem ).find( 'span' ).text( ( value ? value : '-' ) );

			if ( append ) {

				$('#ptl-post-type-info #informations').append( listItem );
			} else {
				
				$('#ptl-post-type-info #informations').prepend( listItem );
			}

			if ( printIndex === total && append ) {

				$('#ptl-post-type-info #informations').append( '<li class="info new-line"></li>' );
			}

			printIndex++;
		});
	};


	// Requests.
	var ptl_get_selected_post_type_info = function( post_type_slug, callback ) {

		const data = {
			'selected_post_type': post_type_slug,
		};

		ptl_ajax( 'ptl_action_post_type_selected', data, callback );
	};

	var ptl_run_process = function( post_type_slug, callback, callback2, page ) {

		let data = {
			'selected_post_type': post_type_slug,
			'page': ( page ? page : 1 ),
		};

		ptl_ajax( 'ptl_action_post_type_process', data, function( response ) {

			ptl_show_info( response, true );

			console.log( response.page );
			
			if ( response.page ) {
				ptl_run_process( post_type_slug, callback, callback2, response.page );
			} else {
				callback2();
			}
		}, callback2 );
	};


	// Bindings.
	$( '#ptl-selected-post-type' ).on( 'change', function( e ) {

		e.preventDefault();

		const selectedPostType = $( this ).val();

		ptl_get_selected_post_type_info( selectedPostType, function( response ) {

			ptl_show_info( response );
		} );
	} );

	$( '#ptl-post-type-info .ptl-refresh' ).on( 'click', function( e ) {

		e.preventDefault();

		const selectedPostType = $( '#ptl-selected-post-type' ).val();

		ptl_get_selected_post_type_info( selectedPostType, function( response ) {

			ptl_show_info( response );
		} );
	} );

	$( '#ptl-post-type-action #ptl-run' ).on( 'click', function( e ) {

		e.preventDefault();

		const selectedPostType = $( '#ptl-selected-post-type' ).val();
		
		$( this ).attr( 'disabled', 'disabled' );

		ptl_run_process( selectedPostType, function( response ) {

			$( '#ptl-post-type-action #ptl-run' ).removeAttr( 'disabled' );
			
		}, function( response ) {
			
			$( '#ptl-post-type-action #ptl-run' ).removeAttr( 'disabled' );
		} );
	} );
});

