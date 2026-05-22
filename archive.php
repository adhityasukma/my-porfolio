<?php
/**
 * The template for displaying archive pages.
 *
 * @package WazTheme
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main id="primary" class="waz-archive">
	<div class="waz-container">

		<?php waztheme_breadcrumbs(); ?>

		<header class="waz-archive__header waz-animate">
			<h1 class="waz-section__title"><?php the_archive_title(); ?></h1>
			<?php
			$waz_archive_description = get_the_archive_description();
			if ( $waz_archive_description ) :
				?>
				<div class="waz-section__subtitle"><?php echo wp_kses_post( $waz_archive_description ); ?></div>
			<?php endif; ?>
		</header>

		<?php if ( have_posts() ) : ?>

			<div class="waz-posts-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content' );
				endwhile;
				?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'prev_text' => '&laquo;',
					'next_text' => '&raquo;',
					'mid_size'  => 2,
				)
			);
			?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

	</div><!-- .waz-container -->
</main><!-- #primary -->

<?php
get_sidebar();
get_footer();
