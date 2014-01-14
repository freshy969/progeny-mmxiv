<?php
/**
 * A template for displaying featured posts on the front page.
 * Includes customizations to display AudioTheme related entry meta.
 *
 * @package Progeny_MMXIV
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
			<?php
			$size = ( 'grid' == get_theme_mod( 'featured_content_layout' ) ) ? 'post-thumbnail' : 'twentyfourteen-full-width';
			the_post_thumbnail( $size );
			?>
		</a>

	<?php endif; ?>

	<header class="entry-header">
		<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && twentyfourteen_categorized_blog() ) : ?>

			<div class="entry-meta">
				<span class="cat-links"><?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'progeny-mmxiv' ) ); ?></span>
			</div>

		<?php  elseif ( ( 'audiotheme_record' || 'audiotheme_video' ) == get_post_type() ) : ?>

			<div class="entry-meta">
				<span class="cat-links archive-link"><?php echo progeny_archive_link(); ?></span>
			</div>

		<?php endif; ?>

		<?php the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">','</a></h1>' ); ?>
	</header>
</article>
