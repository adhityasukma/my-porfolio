<?php
/**
 * The front page template file
 *
 * @package My_Portfolio_HTML
 */

get_header();

// Get theme settings
$hero_name = get_theme_mod('hero_name', 'Adhitya Sukma');
$hero_subtitle = get_theme_mod('hero_subtitle', 'PHP Web Developer (Laravel and Wordpress)');
$hero_description = get_theme_mod('hero_description', 'A passionate Web Developer with expertise in building modern, scalable web applications using Laravel, Vue.js, and WordPress.');
$hero_image_id = get_theme_mod('hero_image', get_template_directory_uri() . '/public/img/foto_saya.jpg');
// Handle if it's an ID (from cropped control) or URL (default/old)
if (is_numeric($hero_image_id)) {
    $hero_image = wp_get_attachment_image_url($hero_image_id, 'full');
} else {
    $hero_image = $hero_image_id;
}
$hero_btn_work = get_theme_mod('hero_btn_work_text', 'View My Work');
$hero_btn_contact = get_theme_mod('hero_btn_contact_text', 'Contact Me');

// Social Links
$social_github = get_theme_mod('social_github', 'https://github.com/adhityasukma');
$social_linkedin = get_theme_mod('social_linkedin', 'https://www.linkedin.com/in/adhitya-sukma-4499a2197/');
$social_telegram = get_theme_mod('social_telegram', 'https://t.me/adhitya_s');

// Skills
$skills_badge = get_theme_mod('skills_badge', 'What I Do');
$skills_title = get_theme_mod('skills_title', 'Skills & Expertise');
$skills_subtitle = get_theme_mod('skills_subtitle', 'Technologies and tools I use to bring ideas to life');
$skills_frontend = my_portfolio_get_skills('frontend');
$skills_backend = my_portfolio_get_skills('backend');
$skills_devops = my_portfolio_get_skills('devops');
$skills_integration = my_portfolio_get_skills('integration');
$skills_database = my_portfolio_get_skills('database');

// Portfolio
$portfolio_badge = get_theme_mod('portfolio_badge', 'My Work');
$portfolio_title = get_theme_mod('portfolio_title', 'Featured Projects');
$portfolio_subtitle = get_theme_mod('portfolio_subtitle', "A collection of projects I've worked on");

// Video
$video_enable = get_theme_mod('video_enable', true);
$video_badge = get_theme_mod('video_badge', 'Watch');
$video_title = get_theme_mod('video_title', 'My Project Collection');
$video_subtitle = get_theme_mod('video_subtitle', 'A video showcase of my projects and work');
$video_youtube_url = get_theme_mod('video_youtube_url', 'https://www.youtube.com/embed/V5NtVJmufnQ?si=FrGdgfSynvObEKB0');

// Contact
$contact_badge = get_theme_mod('contact_badge', 'Get In Touch');
$contact_title = get_theme_mod('contact_title', 'Contact Me');
$contact_subtitle = get_theme_mod('contact_subtitle', "Have a project in mind? Let's discuss it together!");
$contact_email = get_theme_mod('contact_email', 'adhityamedia@gmail.com');
$contact_whatsapp = get_theme_mod('contact_whatsapp', '6285217864254');
$contact_telegram = get_theme_mod('contact_telegram', 'adhitya_s');
?>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <!-- Animated Orbs -->
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
        <div class="hero-orb hero-orb-3"></div>
        
        <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
            <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_name); ?>" class="hero-image">
            <h1 class="hero-title">
                <?php esc_html_e("Hi, I'm", 'my-portfolio-html'); ?> <span class="gradient-text"><?php echo esc_html($hero_name); ?></span>
            </h1>
            <p class="hero-subtitle lead"></p>
            <p class="hero-description">
                <?php echo nl2br(esc_html($hero_description)); ?>
            </p>
            <div class="hero-buttons">
                <a href="#portfolio" class="btn-primary-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                    <?php echo esc_html($hero_btn_work); ?>
                </a>
                <a href="#contact" class="btn-secondary-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <?php echo esc_html($hero_btn_contact); ?>
                </a>
            </div>
            <div class="social-links">
                <?php if (!empty($social_github)) : ?>
                <a href="<?php echo esc_url($social_github); ?>" target="_blank" class="social-link" title="GitHub">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                </a>
                <?php endif; ?>
                <?php if (!empty($social_linkedin)) : ?>
                <a href="<?php echo esc_url($social_linkedin); ?>" target="_blank" class="social-link" title="LinkedIn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                </a>
                <?php endif; ?>
                <?php if (!empty($social_telegram)) : ?>
                <a href="<?php echo esc_url($social_telegram); ?>" target="_blank" class="social-link" title="Telegram">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="section section-dark">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-badge"><?php echo esc_html($skills_badge); ?></span>
                <h2 class="gradient-text"><?php echo esc_html($skills_title); ?></h2>
                <p class="section-subtitle">
                    <?php echo esc_html($skills_subtitle); ?>
                </p>
            </div>
            
            <div class="skills-grid">
                <!-- Frontend -->
                <?php if (get_theme_mod('enable_skills_frontend', true)) : ?>
                <div class="skill-category" data-aos="fade-up" data-aos-delay="100">
                <div class="skill-category-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="16 18 22 12 16 6"></polyline>
                            <polyline points="8 6 2 12 8 18"></polyline>
                        </svg>
                    </div>
                    <h3 class="skill-category-title"><?php esc_html_e('Frontend Development', 'my-portfolio-html'); ?></h3>
                    <div class="skill-tags">
                        <?php foreach ($skills_frontend as $skill) : ?>
                        <span class="skill-tag"><?php echo esc_html($skill); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Backend -->
                <?php if (get_theme_mod('enable_skills_backend', true)) : ?>
                <div class="skill-category" data-aos="fade-up" data-aos-delay="200">
                <div class="skill-category-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                    </div>
                    <h3 class="skill-category-title"><?php esc_html_e('Backend Development', 'my-portfolio-html'); ?></h3>
                    <div class="skill-tags">
                        <?php foreach ($skills_backend as $skill) : ?>
                        <span class="skill-tag"><?php echo esc_html($skill); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- DevOps & Tools -->
                <?php if (get_theme_mod('enable_skills_devops', true)) : ?>
                <div class="skill-category" data-aos="fade-up" data-aos-delay="300">
                <div class="skill-category-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="4 17 10 11 4 5"></polyline>
                            <line x1="12" y1="19" x2="20" y2="19"></line>
                        </svg>
                    </div>
                    <h3 class="skill-category-title"><?php esc_html_e('DevOps & Tools', 'my-portfolio-html'); ?></h3>
                    <div class="skill-tags">
                        <?php foreach ($skills_devops as $skill) : ?>
                        <span class="skill-tag"><?php echo esc_html($skill); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Integration & Middleware -->
                <?php if (get_theme_mod('enable_skills_integration', true)) : ?>
                <div class="skill-category" data-aos="fade-up" data-aos-delay="400">
                    <div class="skill-category-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="18" cy="5" r="3"></circle>
                            <circle cx="6" cy="12" r="3"></circle>
                            <circle cx="18" cy="19" r="3"></circle>
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                        </svg>
                    </div>
                    <h3 class="skill-category-title"><?php esc_html_e('Integration & Middleware', 'my-portfolio-html'); ?></h3>
                    <div class="skill-tags">
                        <?php foreach ($skills_integration as $skill) : ?>
                        <span class="skill-tag"><?php echo esc_html($skill); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Database -->
                <?php if (get_theme_mod('enable_skills_database', true)) : ?>
                <div class="skill-category" data-aos="fade-up" data-aos-delay="500">
                    <div class="skill-category-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                            <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                            <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                        </svg>
                    </div>
                    <h3 class="skill-category-title"><?php esc_html_e('Database', 'my-portfolio-html'); ?></h3>
                    <div class="skill-tags">
                        <?php foreach ($skills_database as $skill) : ?>
                        <span class="skill-tag"><?php echo esc_html($skill); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-badge"><?php echo esc_html($portfolio_badge); ?></span>
                <h2 class="gradient-text"><?php echo esc_html($portfolio_title); ?></h2>
                <p class="section-subtitle">
                    <?php echo esc_html($portfolio_subtitle); ?>
                </p>
            </div>
            
            <div class="portfolio-grid">
                <?php 
                $delay = 100;
                
                // Query Portfolio CPT
                $args = array(
                    'post_type'      => 'portfolio',
                    'posts_per_page' => 6,
                    'orderby'        => 'menu_order date',
                    'order'          => 'DESC'
                );
                $portfolio_query = new WP_Query($args);
                
                if ($portfolio_query->have_posts()) :
                    while ($portfolio_query->have_posts()) :
                        $portfolio_query->the_post();
                        
                        // Get Data
                        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        $link = get_post_meta(get_the_ID(), '_portfolio_project_link', true);
                        $tech_stack = get_post_meta(get_the_ID(), '_portfolio_tech_stack', true);
                        $year = get_post_meta(get_the_ID(), '_portfolio_project_year', true);
                        
                        // Tech Badges
                        $tech_badges = array();
                        if ($tech_stack) {
                            $tech_badges = array_map('trim', explode(',', $tech_stack));
                        }
                        
                        // Category (First one)
                        $categories = get_the_terms(get_the_ID(), 'project_category');
                        $category_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : '';
                        
                        // Description (ID manually controlled or automatic)
                        $description = get_the_excerpt();
                        if (empty($description)) {
                            $description = wp_trim_words(get_the_content(), 20);
                        }
                    ?>
                    <div class="project-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
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
                                <div class="project-tech">
                                    <?php foreach ($tech_badges as $badge) : ?>
                                    <span class="tech-badge"><?php echo esc_html($badge); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                
                                <?php if ($link) : ?>
                                <a href="<?php echo esc_url($link); ?>" target="_blank" class="project-link">
                                    <?php esc_html_e('View Project', 'my-portfolio-html'); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                        $delay += 100;
                    endwhile;
                    wp_reset_postdata();
                else :
                    if (current_user_can('edit_theme_options')) {
                        echo '<div class="col-12 text-center text-white"><p>' . __('No projects found. Please add projects in Dashboard > Portfolio.', 'my-portfolio-html') . '</p></div>';
                    }
                endif; 
                ?>
            </div>
            
            <?php 
            $btn_label = get_theme_mod('portfolio_btn_label', 'View All');
            $btn_link = get_theme_mod('portfolio_btn_link', home_url('/portfolio'));
            
            if (!empty($btn_link) && !empty($btn_label)) : 
            ?>
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="<?php echo esc_url($btn_link); ?>" class="btn-primary-custom">
                    <?php echo esc_html($btn_label); ?>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($video_enable) : ?>
    <!-- Video Showcase Section -->
    <section id="video" class="section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-badge"><?php echo esc_html($video_badge); ?></span>
                <h2 class="gradient-text"><?php echo esc_html($video_title); ?></h2>
                <p class="section-subtitle">
                    <?php echo esc_html($video_subtitle); ?>
                </p>
            </div>
            
            <div class="video-wrapper" data-aos="fade-up" data-aos-delay="100">
                <div class="video-container">
                    <iframe 
                        src="<?php echo esc_url($video_youtube_url); ?>" 
                        title="<?php echo esc_attr($video_title); ?>" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Contact Section -->
    <section id="contact" class="section section-dark">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-badge"><?php echo esc_html($contact_badge); ?></span>
                <h2 class="gradient-text"><?php echo esc_html($contact_title); ?></h2>
                <p class="section-subtitle">
                    <?php echo esc_html($contact_subtitle); ?>
                </p>
            </div>
            
            <!-- Contact Options -->
            <div class="contact-options" data-aos="fade-up" data-aos-delay="100">
                <a href="mailto:<?php echo esc_attr($contact_email); ?>" class="contact-option-card">
                    <div class="contact-option-icon">📧</div>
                    <h3><?php esc_html_e('Email', 'my-portfolio-html'); ?></h3>
                </a>
                <a href="https://wa.me/<?php echo esc_attr($contact_whatsapp); ?>" target="_blank" class="contact-option-card">
                    <div class="contact-option-icon">💬</div>
                    <h3><?php esc_html_e('WhatsApp', 'my-portfolio-html'); ?></h3>
                </a>
                <a href="https://t.me/<?php echo esc_attr($contact_telegram); ?>" target="_blank" class="contact-option-card">
                    <div class="contact-option-icon">✈️</div>
                    <h3><?php esc_html_e('Telegram', 'my-portfolio-html'); ?></h3>
                </a>
            </div>
        </div>
    </section>

<?php get_footer(); ?>
