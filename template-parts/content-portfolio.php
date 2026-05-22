<?php
/**
 * Template part for displaying portfolio cards in grid layouts.
 *
 * @package My_Portfolio_HTML
 */

// Get Data
$image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
$link = get_post_meta(get_the_ID(), '_portfolio_project_link', true);
$tech_stack = get_post_meta(get_the_ID(), '_portfolio_tech_stack', true);

// Tech Badges
$tech_badges = array();
if ($tech_stack) {
    $tech_badges = array_map('trim', explode(',', $tech_stack));
}

// Category (First one)
$categories = get_the_terms(get_the_ID(), 'project_category');
$category_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : '';

// Description
$description = get_the_excerpt();
if (empty($description)) {
    $description = wp_trim_words(get_the_content(), 20);
}

// Year
$year = get_post_meta(get_the_ID(), '_portfolio_project_year', true);
?>
<div <?php post_class('project-card'); ?> data-aos="fade-up">
    <div class="project-image">
        <?php if ($image_url) : ?>
        <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>">
        <?php endif; ?>
        <?php if ($link) : ?>
        <div class="project-overlay">
            <a href="<?php echo esc_url($link); ?>" target="_blank" class="btn-primary-custom"><?php esc_html_e('View Project', 'my-portfolio-html'); ?></a>
        </div>
        <?php endif; ?>
    </div>
    <div class="project-content">
        <?php if ($category_name || $year) : ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <?php if ($category_name) : ?>
            <span class="project-category mb-0"><?php echo esc_html($category_name); ?></span>
            <?php else : ?>
            <span></span>
            <?php endif; ?>
            
            <?php if ($year) : ?>
            <span class="badge bg-secondary text-light px-2 py-1 rounded"><?php echo esc_html($year); ?></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <h3 class="project-title">
            <a href="<?php the_permalink(); ?>" class="text-decoration-none text-reset"><?php the_title(); ?></a>
        </h3>
        
        <p class="project-description">
            <?php echo esc_html($description); ?>
        </p>
        
        <div class="project-footer">
            <?php if (!empty($tech_badges)) : ?>
            <div class="project-tech">
                <?php foreach ($tech_badges as $badge) : ?>
                <span class="tech-badge"><?php echo esc_html($badge); ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <?php if ($link) : ?>
            <a href="<?php echo esc_url($link); ?>" target="_blank" class="project-link">
                <?php esc_html_e('View Project', 'my-portfolio-html'); ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
            </a>
            <?php else : ?>
            <a href="<?php the_permalink(); ?>" class="project-link">
                <?php esc_html_e('View Details', 'my-portfolio-html'); ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
