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


/**
 * Print a list of all site contributors who published at least one post.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return void
 */
function twentyfourteen_list_authors() {
	$member_ids = array_filter( wp_parse_id_list( get_post_meta( get_the_ID(), 'member_ids', true ) ) );
	$contributor_ids = $member_ids;

	if ( empty( $contributor_ids ) ) {
		$contributor_ids = get_users( array(
			'fields'  => 'ID',
			'orderby' => 'post_count',
			'order'   => 'DESC',
			'who'     => 'authors',
		) );
	}

	foreach ( $contributor_ids as $contributor_id ) :
		$post_count = count_user_posts( $contributor_id );

		// Move on if user has not published a post (yet).
		if ( empty( $member_ids ) && ! $post_count ) {
			continue;
		}
		?>

		<div class="contributor">
			<div class="contributor-info">
				<div class="contributor-avatar"><?php echo get_avatar( $contributor_id, 132 ); ?></div>
				<div class="contributor-summary">
					<h2 class="contributor-name"><?php echo get_the_author_meta( 'display_name', $contributor_id ); ?></h2>
					<p class="contributor-bio">
						<?php echo get_the_author_meta( 'description', $contributor_id ); ?>
					</p>

					<?php if ( $post_count ) : ?>
						<a class="contributor-posts-link" href="<?php echo esc_url( get_author_posts_url( $contributor_id ) ); ?>">
							<?php printf( _n( '%d Article', '%d Articles', $post_count, 'twentyfourteen' ), $post_count ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $twitter_username = get_the_author_meta( 'twitter', $contributor_id ) ) : ?>
						<span class="icon">
							<a class="contributor-twitter-link" href="<?php echo esc_url( 'http://twitter.com/' . $twitter_username ); ?>" target="_blank">
								<?php _e( 'Twitter', 'twentyfourteen' ); ?>
							</a>
						</span>
					<?php endif; ?>

					<?php if ( $facebook_url = get_the_author_meta( 'facebook', $contributor_id ) ) : ?>
						<span class="icon">
							<a class="contributor-facebook-link" href="<?php echo esc_url( $facebook_url ); ?>" target="_blank">
								<?php _e( 'Facebook', 'twentyfourteen' ); ?>
							</a>
						</span>
					<?php endif; ?>

					<?php if ( $website_url = get_the_author_meta( 'user_url', $contributor_id ) ) : ?>
						<span class="icon icon-link">
							<a class="contributor-website-link" href="<?php echo esc_url( $website_url ); ?>" target="_blank">
								<?php _e( 'Website', 'twentyfourteen' ); ?>
							</a>
						</span>
					<?php endif; ?>
				</div><!-- .contributor-summary -->
			</div><!-- .contributor-info -->
		</div><!-- .contributor -->

		<?php
	endforeach;
}
