<?php
/**
 * Register support for AudioTheme features.
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
 */
function twentyfourteen_audiotheme_admin_init() {
	register_taxonomy( 'post_tag', array( 'audiotheme_record' ) );
}
add_action( 'admin_init', 'twentyfourteen_audiotheme_admin_init' );

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
 * Adjust AudioTheme widget image sizes
 */
function twentyfourteen_audiotheme_widget_image_size( $size, $instance ) {
	return array( 612, 612 ); // Sidebar width x 2
}
add_filter( 'audiotheme_widget_record_image_size', 'twentyfourteen_audiotheme_widget_image_size', 20, 2 );
add_filter( 'audiotheme_widget_track_image_size', 'twentyfourteen_audiotheme_widget_image_size', 20, 2 );
add_filter( 'audiotheme_widget_video_image_size', 'twentyfourteen_audiotheme_widget_image_size', 20, 2 );

/**
 * Extend the default AudioTheme post classes.
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
 * Add AudioTheme Post Types to featured posts query
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

/**
 * Retrieve the title for an archive.
 *
 * @param int|WP_Post $post Optional. Post to get the archive title for. Defaults to the current post.
 * @return string
 */
function twentyfourteen_audiotheme_get_archive_title( $post = null, $singular = false ) {
	$post = get_post( $post );

	if ( $singular ) {
		$title = get_post_type_object( $post->post_type )->labels->singular_name;
	} else {
		$title = get_post_type_object( $post->post_type )->label;
	}

	return $title;
}

/**
 * Print archive link
 */
function twentyfourteen_audiotheme_archive_link() {
	global $post;

	$post_type = get_post_type();
	$link      = get_post_type_archive_link( $post_type );

	if ( 'audiotheme_track' == $post_type ) {
		$link = get_permalink( $post->post_parent );
	}
	?>
	<a href="<?php echo esc_url( $link ); ?>">
		<?php echo esc_html( twentyfourteen_audiotheme_get_archive_title() ); ?>
	</a>
	<?php
}