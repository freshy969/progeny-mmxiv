<?php
/**
 * Extend the default AudioTheme post classes.
 *
 * @since 1.0.0
 */
function twentyfourteen_audiotheme_post_classes( $classes ) {
	global $post;

	if ( 'audiotheme_track' == $post->post_type && has_post_thumbnail( $post->post_parent ) ) {
		$classes[] = 'has-post-thumbnail';
	}

	return array_unique( $classes );
}
add_filter( 'post_class', 'twentyfourteen_audiotheme_post_classes' );

/**
 * Add AudioTheme Post Types to featured posts query
 *
 * @since 1.0.0
 */
function twentyfourteen_audiotheme_get_featured_posts( $posts ) {
	$options = get_option( 'featured-content' );

	$term = get_term_by( 'name', $options['tag-name'], 'post_tag' );

	// Return early if there are no terms with the set tag name
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
add_filter( 'twentyfourteen_get_featured_posts', 'twentyfourteen_audiotheme_get_featured_posts', 20 );
