<?php


/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
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
 * Activate the settings meta box on record and video archives.
 */
add_action( 'add_audiotheme_archive_settings_meta_box_audiotheme_record', '__return_true' );
add_action( 'add_audiotheme_archive_settings_meta_box_audiotheme_video', '__return_true' );


/**
 * Activate default archive setting fields.
 *
 * @param array $fields List of default fields to activate.
 * @param string $post_type Post type archive.
 * @return array
 */
function twentyfourteen_audiotheme_archive_settings_fields( $fields, $post_type ) {
	if ( ! in_array( $post_type, array( 'audiotheme_gig', 'audiotheme_record', 'audiotheme_video' ) ) ) {
		return $fields;
	}

	$fields['columns'] = array(
		'choices' => range( 2, 2 ),
		'default' => 2,
	);

	$fields['posts_per_archive_page'] = true;

	return $fields;
}
add_filter( 'audiotheme_archive_settings_fields', 'twentyfourteen_audiotheme_archive_settings_fields', 10, 2 );


/**
 * Before AudioTheme Main Content
 */
function twentyfourteen_audiotheme_before_main_content() {
	echo '<div id="main-content" class="main-content">';
	echo '<div id="primary" class="content-area">';
	echo '<div id="content" class="site-content" role="main">';
}
add_action( 'audiotheme_before_main_content', 'twentyfourteen_audiotheme_before_main_content' );


/**
 * After AudioTheme Main Content
 */
function twentyfourteen_audiotheme_after_main_content() {
	echo '</div><!-- #content -->';
	echo '</div><!-- #primary -->';
	echo '</div><!-- #main-content -->';
	get_sidebar( 'content' );
	get_sidebar();
}
add_action( 'audiotheme_after_main_content', 'twentyfourteen_audiotheme_after_main_content' );


/**
 * Register support for AudioTheme features.
 */
function nowell_audiotheme_setup() {
	// Add thumbnail support to archive pages
	//add_post_type_support( 'audiotheme_archive', 'thumbnail' );

	// Add support for AudioTheme widgets
	add_theme_support( 'audiotheme-widgets', array(
		'recent-posts',
		'record',
		'track',
		'upcoming-gigs',
		'video'
	) );
}
add_action( 'after_setup_theme', 'nowell_audiotheme_setup' );
