<?php
/**
 * Template part for displaying blog post cards on archive/index pages.
 *
 * @package WazTheme
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'waz-blog__card waz-post-card waz-animate' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="waz-blog__image">
			<?php waztheme_post_thumbnail( 'blog-thumb' ); ?>
		</div>
	<?php endif; ?>

	<div class="waz-blog__content">

		<div class="waz-blog__meta">
			<?php
			waztheme_posted_on();
			waztheme_posted_by();
			?>
		</div>

		<h3 class="waz-blog__title">
			<a href="<?php the_permalink(); ?>">
				<?php echo esc_html( get_the_title() ); ?>
			</a>
		</h3>

		<div class="waz-blog__excerpt">
			<?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
		</div>

		<a class="waz-blog__read-more" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Read More', 'waztheme' ); ?>
			<svg class="waz-blog__arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
		</a>

	</div>

</article>
