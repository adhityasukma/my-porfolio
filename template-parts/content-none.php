<?php
/**
 * Template part for displaying a message when no posts are found.
 *
 * @package WazTheme
 */

?>
<section class="waz-section">
	<div class="waz-container">

		<header>
			<h1 class="waz-section__title">
				<?php esc_html_e( 'Nothing Found', 'waztheme' ); ?>
			</h1>
		</header>

		<div class="waz-section__content">
			<?php if ( is_search() ) : ?>

				<p><?php esc_html_e( 'Sorry, no results matched your search. Try different keywords.', 'waztheme' ); ?></p>
				<?php get_search_form(); ?>

			<?php elseif ( current_user_can( 'publish_posts' ) ) : ?>

				<p>
					<?php
					printf(
						/* translators: %1$s: opening anchor tag, %2$s: closing anchor tag */
						wp_kses_post( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'waztheme' ) ),
						esc_url( admin_url( 'post-new.php' ) )
					);
					?>
				</p>

			<?php else : ?>

				<p><?php esc_html_e( 'It seems we can\'t find what you\'re looking for.', 'waztheme' ); ?></p>
				<?php get_search_form(); ?>

			<?php endif; ?>
		</div>

	</div>
</section>
