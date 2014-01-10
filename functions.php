<?php
/**
 * Theme functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * For more information on hooks, actions, and filters,
 * see http://codex.wordpress.org/Plugin_API
 *
 * @package twentyfourteen-audiotheme
 * @since 1.0.0
 */

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
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since 1.0.0
 */
function audiotheme_fourteen_setup() {
	// Add support for translating strings in this theme.
	// @link http://codex.wordpress.org/Function_Reference/load_theme_textdomain
	load_child_theme_textdomain( 'audiotheme-fourteen', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'audiotheme_fourteen_setup' );

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