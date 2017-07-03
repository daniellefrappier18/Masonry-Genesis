<?php
/*
	Template Name: Posts in Masonry Grid
*/

// Full width content
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
// Enqueue Masonry
wp_enqueue_script( 'masonry' );
// Initialize Masonry
wp_enqueue_script( 'masonry-init', get_bloginfo( 'stylesheet_directory' ) . '/js/masonry-init.js', '', '', true );
// jQuery for placing archive pagination below the Posts list
wp_enqueue_script( 'reposition-pagination', get_bloginfo( 'stylesheet_directory' ) . '/js/reposition-pagination.js', array( 'jquery' ), '1.0.0', true );
// Add custom body class to the head
add_filter( 'body_class', 'new_body_class' );
function new_body_class( $classes ) {
	$classes[] = 'masonry-page';
	return $classes;
}
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'masonry_loop' );
// Outputs a custom loop
function masonry_loop() {
	$include = genesis_get_option( 'blog_cat' );
	$exclude = genesis_get_option( 'blog_cat_exclude' ) ? explode( ',', str_replace( ' ', '', genesis_get_option( 'blog_cat_exclude' ) ) ) : '';
	$paged   = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	//* Easter Egg
	$query_args = wp_parse_args(
		genesis_get_custom_field( 'query_args' ),
		array(
			'cat'              => $include,
			'category__not_in' => $exclude,
			'showposts'        => genesis_get_option( 'blog_cat_num' ),
			'paged'            => $paged,
		)
	);
	genesis_custom_loop( $query_args );
}
// Force Content Limit
add_filter( 'genesis_pre_get_option_content_archive', 'show_full_content' );
add_filter( 'genesis_pre_get_option_content_archive_limit', 'content_limit' );
function show_full_content() {
	return 'full';
}
function content_limit() {
	return '100'; // Limit content to 100 characters
}
// Remove author and comment link in entry header's entry meta
add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter($post_info) {
	$post_info = '[post_date] [post_edit]';
	return $post_info;
}
genesis();