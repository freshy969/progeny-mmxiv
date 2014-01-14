<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * @package Progeny_MMXIV
 * @since 1.0.0
 */

/**
 * Extend the default AudioTheme post classes.
 *
 * @since 1.0.0
 *
 * @param array $classes List of HTML class names.
 * @param string $class One or more classes added to the class list.
 * @param int $post_id The post ID.
 * @return array
 */
function progeny_post_classes( $classes, $class, $post_id ) {
	$post = get_post( $post_id );

	if ( 'audiotheme_track' == $post->post_type && ( has_post_thumbnail( $post_id ) || has_post_thumbnail( $post->post_parent ) ) ) {
		$classes[] = 'has-post-thumbnail';
	}

	if ( get_post_meta( get_the_ID(), 'member_ids', true ) ) {
		$classes[] = 'has-members';
	}

	return array_unique( $classes );
}
add_filter( 'post_class', 'progeny_post_classes', 10, 3 );

/**
 * Add AudioTheme Post Types to featured posts query.
 *
 * @since 1.0.0
 *
 * @param array $posts List of featured posts.
 * @return array
 */
function progeny_get_featured_posts( $posts ) {
	$tag_id = Featured_Content::get_setting( 'tag-id' );

	// Return early if a tag id hasn't been set.
	if ( empty( $tag_id ) ) {
		return $posts;
	}

	// Query for featured posts.
	$featured = get_posts( array(
		'post_type' => array( 'audiotheme_record', 'audiotheme_video' ),
		'tax_query' => array(
			array(
				'field'    => 'term_id',
				'taxonomy' => 'post_tag',
				'terms'    => $tag_id,
			),
		),
	) );

	return array_merge( $posts, $featured );
}
add_filter( 'twentyfourteen_get_featured_posts', 'progeny_get_featured_posts', 20 );
