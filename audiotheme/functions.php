<?php
/**
 * AudioTheme Compatibility File
 * See: http://audiotheme.com/
 *
 * @package MMXIV
 */

/**
 * Register support for AudioTheme features.
 *
 * @since 1.0.0
 */
function mmxiv_framework_setup() {
	// Add support for AudioTheme widgets.
	add_theme_support( 'audiotheme-widgets', array(
		'record',
		'track',
		'upcoming-gigs',
		'video',
	) );
}
add_action( 'after_setup_theme', 'mmxiv_framework_setup' );

/**
 * HTML to display before main AudioTheme content.
 *
 * @since 1.0.0
 */
function mmxiv_before_main_content() {
	echo '<div id="main-content" class="main-content">';
	echo '<div id="primary" class="content-area">';
	echo '<div id="content" class="site-content" role="main">';
}
add_action( 'audiotheme_before_main_content', 'mmxiv_before_main_content' );

/**
 * HTML to display after main AudioTheme content.
 *
 * @since 1.0.0
 */
function mmxiv_after_main_content() {
	echo '</div><!-- #content -->';
	echo '</div><!-- #primary -->';
	echo '</div><!-- #main-content -->';
	get_sidebar( 'content' );
	get_sidebar();
}
add_action( 'audiotheme_after_main_content', 'mmxiv_after_main_content' );

/**
 * Adjust AudioTheme widget image sizes.
 *
 * @since 1.0.0
 *
 * @param array $size Image size (width and height).
 * @return array
 */
function mmxiv_widget_image_size( $size ) {
	return array( 612, 612 ); // sidebar width x 2
}
add_filter( 'audiotheme_widget_record_image_size', 'mmxiv_widget_image_size' );
add_filter( 'audiotheme_widget_track_image_size', 'mmxiv_widget_image_size' );
add_filter( 'audiotheme_widget_video_image_size', 'mmxiv_widget_image_size' );

/**
 * Activate default archive setting fields.
 *
 * @since 1.0.0
 *
 * @param array $fields List of default fields to activate.
 * @param string $post_type Post type archive.
 * @return array
 */
function mmxiv_archive_settings_fields( $fields, $post_type ) {
	if ( ! in_array( $post_type, array( 'audiotheme_record', 'audiotheme_video' ) ) ) {
		return $fields;
	}

	$fields['columns'] = array(
		'choices' => range( 1, 2 ),
		'default' => 2,
	);

	$fields['posts_per_archive_page'] = true;

	return $fields;
}
add_filter( 'audiotheme_archive_settings_fields', 'mmxiv_archive_settings_fields', 10, 2 );
