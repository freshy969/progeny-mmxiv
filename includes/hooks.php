<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * @package Progeny_MMXIV
 * @since 1.0.0
 */

/**
 * Add additional HTML classes to posts.
 *
 * @since 1.1.0
 *
 * @param array $classes List of HTML classes.
 * @return array
 */
function progeny_post_class( $classes ) {
	if ( get_post_meta( get_the_ID(), 'member_ids', true ) ) {
		$classes[] = 'has-members';
	}

	return array_unique( $classes );
}
add_filter( 'post_class', 'progeny_post_class' );
