<?php
/**
 * Load helper functions and libraries.
 *
 * @since 1.0.0
 */
require( get_stylesheet_directory() . '/includes/template-tags.php' );
require( get_stylesheet_directory() . '/includes/hooks.php' );

/**
 * Load functions and hooks for supported plugins.
 *
 * @since 1.0.0
 */
require( get_stylesheet_directory() . '/includes/audiotheme.php' );
$twentyfourteen_audiotheme = new Audiotheme_Loader();
$twentyfourteen_audiotheme->load();

/**
 * Register support for AudioTheme features.
 *
 * @since 1.0.0
 */
function twentyfourteen_audiotheme_setup() {
	// Add support for AudioTheme widgets
	add_theme_support( 'audiotheme-widgets', array(
		'record',
		'track',
		'upcoming-gigs',
		'video'
	) );
}
add_action( 'after_setup_theme', 'twentyfourteen_audiotheme_setup' );

/**
 * Allow Tags metabox on AudioTheme Post Types. This allows theme to be included
 * in the featured content section.
 *
 * @since 1.0.0
 */
function twentyfourteen_audiotheme_admin_init() {
	register_taxonomy_for_object_type( 'post_tag', 'audiotheme_record' );
}
add_action( 'admin_init', 'twentyfourteen_audiotheme_admin_init' );