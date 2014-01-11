<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * @package AudioTheme_Fourteen
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
function audiotheme_fourteen_post_classes( $classes, $class, $post_id ) {
	$post = get_post( $post_id );

	if ( 'audiotheme_track' == $post->post_type && ( has_post_thumbnail( $post_id ) || has_post_thumbnail( $post->post_parent ) ) ) {
		$classes[] = 'has-post-thumbnail';
	}

	if ( get_post_meta( get_the_ID(), 'member_ids', true ) ) {
		$classes[] = 'has-members';
	}

	return array_unique( $classes );
}
add_filter( 'post_class', 'audiotheme_fourteen_post_classes', 10, 3 );

/**
 * Add AudioTheme Post Types to featured posts query.
 *
 * @since 1.0.0
 *
 * @param array $posts List of featured post IDs.
 * @return array
 */
function audiotheme_fourteen_get_featured_posts( $posts ) {
	$options = get_option( 'featured-content' );

	$term = get_term_by( 'name', $options['tag-name'], 'post_tag' );

	// Return early if there are no terms with the set tag name.
	if ( ! $term ) {
		return $posts;
	}

	// Query for featured posts.
	$featured = get_posts( array(
		'post_type' => array( 'audiotheme_record', 'audiotheme_video' ),
		'tax_query' => array(
			array(
				'field'    => 'term_id',
				'taxonomy' => 'post_tag',
				'terms'    => $term->term_id,
			),
		),
	) );

	// Ensure correct format before save/return.
	$featured_ids = wp_list_pluck( (array) $featured, 'ID' );
	$featured_ids = array_map( 'absint', $featured_ids );

	foreach( $featured_ids as $id ) {
		$posts[] = $id;
	}

	return $posts;
}
add_filter( 'twentyfourteen_get_featured_posts', 'audiotheme_fourteen_get_featured_posts', 20 );
