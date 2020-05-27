<?php
/**
 * Option page template.
 *
 * @package WordPress
 * @subpackage Post Type Looper
 * @since 0.1.0
 */

// Vars.
$post_types = get_post_types( array(), 'objects' );
?>
<div class="wrap ptl-options">

	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<div id="ptl-post-type-selection">

		<h2><?php esc_html_e( 'Selected Post Type', 'post-type-looper' ); ?></h2>
		<p>
			<?php if ( $post_types ) : ?>

				<select name="ptl-selected-post-type" id="ptl-selected-post-type">

					<option value="" disabled selected><?php echo esc_html_e( 'Select a registered post type' ); ?></option>

					<?php ksort( $post_types ); ?>

					<?php foreach ( $post_types as $slug => $post_type_obj ) : ?>

						<option value="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $post_type_obj->label ); ?></option>
					<?php endforeach; ?>
				</select>

			<?php else : ?>

				<i><?php esc_html_e( 'No post type registered', 'post-type-looper' ); ?></i>
			<?php endif; ?>
		</p>
	</div>

	<div id="ptl-post-type-action">
		<h2><?php esc_html_e( 'Actions', 'post-type-looper' ); ?></h2>

		<!-- @TODO: Improve action listing. -->
		<button id="ptl-run" class="btn btn-default"><?php esc_html_e( 'Fix', 'post-type-looper' ); ?></button>
	</div>

	<div id="ptl-post-type-info">
		<h2>
			<?php esc_html_e( 'Informations', 'post-type-looper' ); ?>
			<a href="#" class="ptl-refresh"><?php esc_html_e( 'Refresh', 'post-type-looper' ); ?></a>
		</h2>
		<ul id="informations">
			<li class="info template"><b></b><br><span></span></li>
		</ul>
	</div>
</div>
