<?php
/**
 * The template for displaying all pages
 *
 * @package My_Portfolio_HTML
 */

get_header();
?>

<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <?php
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>
                    <header class="entry-header mb-4">
                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                        

                        <?php
                        $tech_stack = get_post_meta(get_the_ID(), '_portfolio_tech_stack', true);
                        if ($tech_stack) :
                            $techs = array_map('trim', explode(',', $tech_stack));
                            ?>
                        
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
                        
                        <?php
                        $tech_stack = get_post_meta(get_the_ID(), '_portfolio_tech_stack', true);
                        if ($tech_stack) :
                            $techs = array_map('trim', explode(',', $tech_stack));
                            ?>
                            <div class="project-tech mt-4">
                                <strong style="color: var(--text-muted); font-size: 0.9rem; margin-right: 10px;"><?php _e('Tech Stack:', 'my-portfolio-html'); ?></strong>
                                <?php foreach ($techs as $tech) : ?>
                                    <span class="tech-badge"><?php echo esc_html($tech); ?></span>
                                <?php endforeach; ?>
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
