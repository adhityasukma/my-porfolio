<?php
/**
 * The template for displaying all single portfolio posts
 *
 * @package My_Portfolio_HTML
 */

get_header();
?>

<div class="container py-5 mt-5 single-portfolio">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <?php
            while (have_posts()) :
                the_post();

                // Get Meta Data
                $image_ids = get_post_meta(get_the_ID(), '_portfolio_gallery', true);
                $project_link = get_post_meta(get_the_ID(), '_portfolio_project_link', true);
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>
                    <header class="entry-header mb-4">
                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                        <div class="project-categories mb-2">
                            <?php
                            $terms = get_the_terms(get_the_ID(), 'project_category');
                            if ($terms && !is_wp_error($terms)) {
                                $links = array();
                                foreach ($terms as $term) {
                                    $links[] = '<a href="' . esc_url(get_term_link($term)) . '" class="text-decoration-none text-reset">' . esc_html($term->name) . '</a>';
                                }
                                echo implode(', ', $links);
                            }
                            ?>
                        </div>
                        <?php if (has_post_thumbnail()) : ?>
                        <div class="project-featured-image mt-4 mb-4 rounded overflow-hidden shadow-sm" style="background: var(--bg-secondary); text-align: center; padding: 20px;">
                            <?php the_post_thumbnail('full', array('class' => 'img-fluid', 'style' => 'max-height: 500px; object-fit: contain; box-shadow: none;')); ?>
                        </div>
                        <?php endif; ?>
                    </header>

                    <div class="entry-content">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'my-portfolio-html'),
                            'after'  => '</div>',
                        ));
                        ?>

                        <!-- Project Link -->
                        <?php if ($project_link) : ?>
                        <div class="project-external-link mt-4">
                            <a href="<?php echo esc_url($project_link); ?>" target="_blank" class="btn btn-primary">
                                <?php esc_html_e('Visit Project', 'my-portfolio-html'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-up-right ms-2" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                                    <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                                </svg>
                            </a>
                        </div>
                        <?php endif; ?>

                        <!-- Tech Stack -->
                        <?php
                        $tech_stack = get_post_meta(get_the_ID(), '_portfolio_tech_stack', true);
                        if ($tech_stack) :
                            $techs = array_map('trim', explode(',', $tech_stack));
                            ?>
                            <div class="project-tech mt-5">
                                <strong style="color: var(--text-muted); font-size: 1rem; margin-right: 10px;"><?php _e('Tech Stack:', 'my-portfolio-html'); ?></strong>
                                <?php foreach ($techs as $tech) : ?>
                                    <span class="tech-badge me-2 mb-2" style="display:inline-block;"><?php echo esc_html($tech); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Gallery -->
                        <?php
                        if ($image_ids) :
                            $ids = explode(',', $image_ids);
                            ?>
                            <div class="project-gallery mt-5">
                                <strong class="d-block mb-3" style="color: var(--text-muted); font-size: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;"><?php _e('Gallery', 'my-portfolio-html'); ?></strong>
                                <div class="row g-3">
                                    <?php foreach ($ids as $id) :
                                        $thumb = wp_get_attachment_image_url($id, 'medium_large');
                                        $full = wp_get_attachment_image_url($id, 'full');
                                        if ($thumb) :
                                    ?>
                                    <div class="col-md-4 col-sm-6">
                                        <a href="<?php echo esc_url($full); ?>" class="gallery-item glightbox d-block rounded overflow-hidden shadow-sm" data-gallery="portfolio-gallery" style="background: var(--bg-secondary); height: 220px; display: flex; align-items: center; justify-content: center;">
                                            <img src="<?php echo esc_url($thumb); ?>" alt="Gallery Image" class="img-fluid" style="object-fit: contain; max-height: 100%; width: auto; max-width: 100%;">
                                        </a>
                                    </div>
                                    <?php endif; endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </article>
                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>
        </div>
    </div>
</div>

<?php
get_footer();
