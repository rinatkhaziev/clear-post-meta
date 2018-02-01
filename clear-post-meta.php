<?php
/**
 * Plugin Name: Clear Post Meta
 * Plugin URI:
 * Description: Clear Post Meta in one click
 * Author: Rinat Khaziev
 * Version: 0.1
 */

define( 'CPM_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'CPM_AJAX_ACTION', 'cpm_clear_meta' );

/**
 * Add Metabox
 */
add_action( 'add_meta_boxes', function() {
	add_meta_box( 'clear-post-meta', 'Clear Post Meta', function( $post ) {
	?>
		<button class="button button-primary clear-post-meta-target" id="clear-post-meta-btn">Clear Post Meta</button>
	<?php
	}, get_post_type(), 'side', 'low' );
} );

/**
 * Add Admin Bar node on post edit screen
 */
add_action( 'admin_bar_menu', function( $wp_admin_bar ) {
	if ( cpm_should_display() )
		$wp_admin_bar->add_node( [
			'id'    => 'clear-post-meta-ab',
			'title' => 'Clear Post Meta',
			'meta'  => [ 'class' => 'clear-post-meta-target' ],
		] );
}, 999 );

/**
 * Add the js
 */
add_action( 'admin_enqueue_scripts', function() {
	if ( ! cpm_should_display() )
		return;

	wp_enqueue_script( 'clear-post-meta', CPM_DIR_URL . '/js/clear-post-meta.js', [], false, true );
	wp_localize_script( 'clear-post-meta', 'ClearPostMeta', [
		'ajaxurl' => add_query_arg( [
			'action'  => CPM_AJAX_ACTION,
			'nonce'   => wp_create_nonce( CPM_AJAX_ACTION ),
			'post_id' => (int) $_GET['post'],
		], admin_url( 'admin-ajax.php' ) )
	] );
});

/**
 * Helper to decide whether AB node should be added
 *
 * @return void
 */
function cpm_should_display() {
	return current_user_can( apply_filters( 'cpm_clear_cap', 'edit_others_posts' ) ) && is_admin() && get_current_screen()->id === 'post';
}

/**
 * WP AJAX callback that actually deletes meta
 */
add_action( 'wp_ajax_' . CPM_AJAX_ACTION, function() {
	if ( ! wp_verify_nonce( $_GET['nonce'], CPM_AJAX_ACTION ) || ! ( $post_id = absint( $_GET['post_id'] ?? 0 ) ) )
		wp_send_json_error( [ 'message' => "Couldn't proccess the request" ] );

	foreach( (array) get_post_custom_keys( $post_id ) as $key )
		delete_post_meta( $post_id, $key );

	wp_send_json_success( [ 'message' => 'All meta was deleted, please refresh the page.' ] );
} );
