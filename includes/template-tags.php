<?php
/**
 * Retrieve the title for an archive.
 *
 * @param int|WP_Post $post Optional. Post to get the archive title for. Defaults to the current post.
 * @return string
 *
 * @since 1.0.0
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
 *
 * @since 1.0.0
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
