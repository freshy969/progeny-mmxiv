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
 * @package Progeny_MMXIV
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
$progeny = new Audiotheme_Loader();
$progeny->load();

/**
 * Set up theme defaults and register support for various WordPress features.
 *
 * @since 1.0.0
 */
function progeny_setup() {
	// Add support for translating strings in this theme.
	// @link http://codex.wordpress.org/Function_Reference/load_theme_textdomain
	load_child_theme_textdomain( 'progeny-mmxiv', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'progeny_setup' );

/**
 * Add Tags metabox on AudioTheme post types. This allows theme to be included
 * in the featured content section.
 *
 * @since 1.0.0
 */
function progeny_admin_init() {
	register_taxonomy_for_object_type( 'post_tag', 'audiotheme_record' );
}
add_action( 'admin_init', 'progeny_admin_init' );
