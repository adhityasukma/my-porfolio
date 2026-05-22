<?php
/**
 * The template for displaying the Portfolio archive.
 *
 * @package WazTheme
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main id="primary" class="section">
	<div class="container">

		<header class="section-header" data-aos="fade-up">
			<span class="section-badge"><?php esc_html_e( 'Portfolio', 'my-portfolio-html' ); ?></span>
			<h1 class="gradient-text"><?php esc_html_e( 'Explore My work', 'my-portfolio-html' ); ?></h1>
		</header>

		<?php
		$portfolio_terms = get_terms(
			array(
				'taxonomy'   => 'project_category',
				'hide_empty' => true,
			)
		);
		?>

		<?php if ( have_posts() ) : ?>

			<div class="portfolio-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'portfolio' );
				endwhile;
				?>
			</div>

			<div class="mt-5 text-center">
				<?php the_posts_pagination( array(
					'mid_size'  => 2,
					'prev_text' => __( 'Prev', 'my-portfolio-html' ),
					'next_text' => __( 'Next', 'my-portfolio-html' ),
				) ); ?>
			</div>

		<?php else : ?>
			<div class="col-12 text-center text-white">
				<p><?php esc_html_e('No projects found.', 'my-portfolio-html'); ?></p>
			</div>
		<?php endif; ?>

	</div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();
