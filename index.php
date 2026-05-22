<?php
/**
 * The main template file
 *
 * @package My_Portfolio_HTML
 */

get_header();
?>

<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>
                        <header class="entry-header mb-4">
                            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                        </header>

                        <div class="entry-content">
                            <?php
                            the_content();

                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'my-portfolio-html'),
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>
                    </article>
                    <?php
                endwhile;

                the_posts_navigation();

            else :
                ?>
                <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for.', 'my-portfolio-html'); ?></p>
                <?php
            endif;
            ?>
        </div>
    </div>
</div>

<?php
get_footer();
