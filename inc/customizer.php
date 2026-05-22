<?php
/**
 * Theme Customizer Settings
 *
 * @package My_Portfolio_HTML
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add theme customizer settings
 */
function my_portfolio_customize_register($wp_customize) {
    
    // ===========================================
    // PANEL: Theme Settings
    // ===========================================
    $wp_customize->add_panel('theme_settings', array(
        'title'       => __('Theme Settings', 'my-portfolio-html'),
        'description' => __('Customize your portfolio theme', 'my-portfolio-html'),
        'priority'    => 30,
    ));

    // ===========================================
    // SECTION: Maintenance Mode
    // ===========================================
    $wp_customize->add_section('maintenance_section', array(
        'title'    => __('Maintenance Mode', 'my-portfolio-html'),
        'panel'    => 'theme_settings',
        'priority' => 5,
    ));

    // Enable Maintenance Mode
    $wp_customize->add_setting('maintenance_mode_enable', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('maintenance_mode_enable', array(
        'label'       => __('Enable Under Construction', 'my-portfolio-html'),
        'description' => __('If enabled, visitors will see the under construction page. Administrators can still view the site.', 'my-portfolio-html'),
        'section'     => 'maintenance_section',
        'type'        => 'checkbox',
    ));

    // Maintenance Heading
    $wp_customize->add_setting('maintenance_heading', array(
        'default'           => 'Under Construction',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('maintenance_heading', array(
        'label'   => __('Heading', 'my-portfolio-html'),
        'section' => 'maintenance_section',
        'type'    => 'text',
    ));

    // Maintenance Message
    $wp_customize->add_setting('maintenance_message', array(
        'default'           => 'We are currently working on our website. Please check back soon.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('maintenance_message', array(
        'label'   => __('Message', 'my-portfolio-html'),
        'section' => 'maintenance_section',
        'type'    => 'textarea',
    ));


    // ===========================================
    // SECTION: 404 Page
    // ===========================================
    $wp_customize->add_section('error_404_section', array(
        'title'    => __('404 Page', 'my-portfolio-html'),
        'panel'    => 'theme_settings',
        'priority' => 6,
    ));

    // 404 Heading
    $wp_customize->add_setting('error_404_heading', array(
        'default'           => '404 Not Found',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('error_404_heading', array(
        'label'   => __('Heading', 'my-portfolio-html'),
        'section' => 'error_404_section',
        'type'    => 'text',
    ));

    // 404 Message
    $wp_customize->add_setting('error_404_message', array(
        'default'           => 'Oops! The page you are looking for does not exist. It might have been moved or deleted.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('error_404_message', array(
        'label'   => __('Message', 'my-portfolio-html'),
        'section' => 'error_404_section',
        'type'    => 'textarea',
    ));

    // 404 Button Text
    $wp_customize->add_setting('error_404_btn_text', array(
        'default'           => 'Back to Homepage',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('error_404_btn_text', array(
        'label'   => __('Button Text', 'my-portfolio-html'),
        'section' => 'error_404_section',
        'type'    => 'text',
    ));

    // ===========================================
    // SECTION: Hero Section
    // ===========================================
    $wp_customize->add_section('hero_section', array(
        'title'    => __('Hero Section', 'my-portfolio-html'),
        'panel'    => 'theme_settings',
        'priority' => 10,
    ));

    // Hero Name
    $wp_customize->add_setting('hero_name', array(
        'default'           => 'Adhitya Sukma',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('hero_name', array(
        'label'   => __('Your Name', 'my-portfolio-html'),
        'section' => 'hero_section',
        'type'    => 'text',
    ));

    // Hero Subtitle
    $wp_customize->add_setting('hero_subtitle', array(
        'default'           => 'PHP Web Developer (Laravel and Wordpress)',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('hero_subtitle', array(
        'label'   => __('Professional Title', 'my-portfolio-html'),
        'section' => 'hero_section',
        'type'    => 'text',
    ));

    // Hero Description
    $wp_customize->add_setting('hero_description', array(
        'default'           => 'A passionate Web Developer with expertise in building modern, scalable web applications using Laravel, Vue.js, and WordPress.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('hero_description', array(
        'label'   => __('Description', 'my-portfolio-html'),
        'section' => 'hero_section',
        'type'    => 'textarea',
    ));

    // Hero Image
    $wp_customize->add_setting('hero_image', array(
        'default'           => get_template_directory_uri() . '/public/img/foto_saya.jpg',
        'sanitize_callback' => 'absint', // Returns attachment ID
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'hero_image', array(
        'label'         => __('Profile Photo', 'my-portfolio-html'),
        'description'   => __('Upload and crop your profile photo (500x500)', 'my-portfolio-html'),
        'section'       => 'hero_section',
        'width'         => 500,
        'height'        => 500,
        'flex_width'    => false,
        'flex_height'   => false,
    )));

    // Hero Button Work Text
    $wp_customize->add_setting('hero_btn_work_text', array(
        'default'           => 'View My Work',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('hero_btn_work_text', array(
        'label'   => __('Work Button Text', 'my-portfolio-html'),
        'section' => 'hero_section',
        'type'    => 'text',
    ));

    // Hero Button Contact Text
    $wp_customize->add_setting('hero_btn_contact_text', array(
        'default'           => 'Contact Me',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('hero_btn_contact_text', array(
        'label'   => __('Contact Button Text', 'my-portfolio-html'),
        'section' => 'hero_section',
        'type'    => 'text',
    ));

    // ===========================================
    // SECTION: Social Links
    // ===========================================
    $wp_customize->add_section('social_section', array(
        'title'    => __('Social Links', 'my-portfolio-html'),
        'panel'    => 'theme_settings',
        'priority' => 15,
    ));

    // GitHub
    $wp_customize->add_setting('social_github', array(
        'default'           => 'https://github.com/adhityasukma',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('social_github', array(
        'label'   => __('GitHub URL', 'my-portfolio-html'),
        'section' => 'social_section',
        'type'    => 'url',
    ));

    // LinkedIn
    $wp_customize->add_setting('social_linkedin', array(
        'default'           => 'https://www.linkedin.com/in/adhitya-sukma-4499a2197/',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('social_linkedin', array(
        'label'   => __('LinkedIn URL', 'my-portfolio-html'),
        'section' => 'social_section',
        'type'    => 'url',
    ));

    // Telegram
    $wp_customize->add_setting('social_telegram', array(
        'default'           => 'https://t.me/adhitya_s',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('social_telegram', array(
        'label'   => __('Telegram URL', 'my-portfolio-html'),
        'section' => 'social_section',
        'type'    => 'url',
    ));

    // ===========================================
    // SECTION: Skills Section
    // ===========================================
    $wp_customize->add_section('skills_section', array(
        'title'    => __('Skills Section', 'my-portfolio-html'),
        'panel'    => 'theme_settings',
        'priority' => 20,
    ));

    // Skills Badge
    $wp_customize->add_setting('skills_badge', array(
        'default'           => 'What I Do',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('skills_badge', array(
        'label'   => __('Section Badge Text', 'my-portfolio-html'),
        'section' => 'skills_section',
        'type'    => 'text',
    ));

    // Skills Title
    $wp_customize->add_setting('skills_title', array(
        'default'           => 'Skills & Expertise',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('skills_title', array(
        'label'   => __('Section Title', 'my-portfolio-html'),
        'section' => 'skills_section',
        'type'    => 'text',
    ));

    // Skills Subtitle
    $wp_customize->add_setting('skills_subtitle', array(
        'default'           => 'Technologies and tools I use to bring ideas to life',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('skills_subtitle', array(
        'label'   => __('Section Subtitle', 'my-portfolio-html'),
        'section' => 'skills_section',
        'type'    => 'text',
    ));

    // Frontend Skills Enable
    $wp_customize->add_setting('enable_skills_frontend', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('enable_skills_frontend', array(
        'label'   => __('Enable Frontend Card', 'my-portfolio-html'),
        'section' => 'skills_section',
        'type'    => 'checkbox',
    ));

    // Frontend Skills
    $wp_customize->add_setting('skills_frontend', array(
        'default'           => 'HTML5, CSS3, JavaScript, Vue.js, Bootstrap, Tailwind CSS, REST API',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('skills_frontend', array(
        'label'       => __('Frontend Skills', 'my-portfolio-html'),
        'description' => __('Comma-separated list', 'my-portfolio-html'),
        'section'     => 'skills_section',
        'type'        => 'textarea',
    ));

    // Backend Skills Enable
    $wp_customize->add_setting('enable_skills_backend', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('enable_skills_backend', array(
        'label'   => __('Enable Backend Card', 'my-portfolio-html'),
        'section' => 'skills_section',
        'type'    => 'checkbox',
    ));

    // Backend Skills
    $wp_customize->add_setting('skills_backend', array(
        'default'           => 'PHP, Laravel, CodeIgniter, WordPress, MySQL, SQL Server, Redis, JWT Auth, OAuth2',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('skills_backend', array(
        'label'       => __('Backend Skills', 'my-portfolio-html'),
        'description' => __('Comma-separated list', 'my-portfolio-html'),
        'section'     => 'skills_section',
        'type'        => 'textarea',
    ));

    // DevOps Skills Enable
    $wp_customize->add_setting('enable_skills_devops', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('enable_skills_devops', array(
        'label'   => __('Enable DevOps Card', 'my-portfolio-html'),
        'section' => 'skills_section',
        'type'    => 'checkbox',
    ));

    // DevOps Skills
    $wp_customize->add_setting('skills_devops', array(
        'default'           => 'Git, Docker, Linux, NPM, API Development',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('skills_devops', array(
        'label'       => __('DevOps & Tools Skills', 'my-portfolio-html'),
        'description' => __('Comma-separated list', 'my-portfolio-html'),
        'section'     => 'skills_section',
        'type'        => 'textarea',
    ));

    // Integration Skills Enable
    $wp_customize->add_setting('enable_skills_integration', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('enable_skills_integration', array(
        'label'   => __('Enable Integration Card', 'my-portfolio-html'),
        'section' => 'skills_section',
        'type'    => 'checkbox',
    ));

    // Integration Skills
    $wp_customize->add_setting('skills_integration', array(
        'default'           => 'REST API, GraphQL, Webhooks, OAuth, Stripe',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('skills_integration', array(
        'label'       => __('Integration & Middleware', 'my-portfolio-html'),
        'description' => __('Comma-separated list', 'my-portfolio-html'),
        'section'     => 'skills_section',
        'type'        => 'textarea',
    ));

    // Database Skills Enable
    $wp_customize->add_setting('enable_skills_database', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('enable_skills_database', array(
        'label'   => __('Enable Database Card', 'my-portfolio-html'),
        'section' => 'skills_section',
        'type'    => 'checkbox',
    ));

    // Database Skills
    $wp_customize->add_setting('skills_database', array(
        'default'           => 'MySQL, PostgreSQL, MongoDB, Redis',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('skills_database', array(
        'label'       => __('Database', 'my-portfolio-html'),
        'description' => __('Comma-separated list', 'my-portfolio-html'),
        'section'     => 'skills_section',
        'type'        => 'textarea',
    ));

    // ===========================================
    // SECTION: Portfolio Section
    // ===========================================
    $wp_customize->add_section('portfolio_section', array(
        'title'    => __('Portfolio Section', 'my-portfolio-html'),
        'panel'    => 'theme_settings',
        'priority' => 25,
    ));

    // Portfolio Badge
    $wp_customize->add_setting('portfolio_badge', array(
        'default'           => 'My Work',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('portfolio_badge', array(
        'label'   => __('Section Badge Text', 'my-portfolio-html'),
        'section' => 'portfolio_section',
        'type'    => 'text',
    ));

    // Portfolio Title
    $wp_customize->add_setting('portfolio_title', array(
        'default'           => 'Featured Projects',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('portfolio_title', array(
        'label'   => __('Section Title', 'my-portfolio-html'),
        'section' => 'portfolio_section',
        'type'    => 'text',
    ));

    // Portfolio Subtitle
    $wp_customize->add_setting('portfolio_subtitle', array(
        'default'           => "A collection of projects I've worked on",
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('portfolio_subtitle', array(
        'label'   => __('Section Subtitle', 'my-portfolio-html'),
        'section' => 'portfolio_section',
        'type'    => 'text',
    ));

    // Portfolio Button Label
    $wp_customize->add_setting('portfolio_btn_label', array(
        'default'           => 'View All',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('portfolio_btn_label', array(
        'label'   => __('Button Label', 'my-portfolio-html'),
        'section' => 'portfolio_section',
        'type'    => 'text',
    ));

    // Portfolio Button Link
    $wp_customize->add_setting('portfolio_btn_link', array(
        'default'           => home_url('/portfolio'),
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('portfolio_btn_link', array(
        'label'   => __('Button Link', 'my-portfolio-html'),
        'section' => 'portfolio_section',
        'type'    => 'url',
    ));

    // ===========================================
    // SECTION: Video Section
    // ===========================================
    $wp_customize->add_section('video_section', array(
        'title'    => __('Video Section', 'my-portfolio-html'),
        'panel'    => 'theme_settings',
        'priority' => 30,
    ));

    // Video Enable
    $wp_customize->add_setting('video_enable', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('video_enable', array(
        'label'   => __('Enable Video Section', 'my-portfolio-html'),
        'section' => 'video_section',
        'type'    => 'checkbox',
    ));

    // Video Badge
    $wp_customize->add_setting('video_badge', array(
        'default'           => 'Watch',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('video_badge', array(
        'label'   => __('Section Badge Text', 'my-portfolio-html'),
        'section' => 'video_section',
        'type'    => 'text',
    ));

    // Video Title
    $wp_customize->add_setting('video_title', array(
        'default'           => 'My Project Collection',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('video_title', array(
        'label'   => __('Section Title', 'my-portfolio-html'),
        'section' => 'video_section',
        'type'    => 'text',
    ));

    // Video Subtitle
    $wp_customize->add_setting('video_subtitle', array(
        'default'           => 'A video showcase of my projects and work',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('video_subtitle', array(
        'label'   => __('Section Subtitle', 'my-portfolio-html'),
        'section' => 'video_section',
        'type'    => 'text',
    ));

    // Video YouTube URL
    $wp_customize->add_setting('video_youtube_url', array(
        'default'           => 'https://www.youtube.com/embed/V5NtVJmufnQ?si=FrGdgfSynvObEKB0',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('video_youtube_url', array(
        'label'       => __('YouTube Embed URL', 'my-portfolio-html'),
        'description' => __('Use embed URL format (e.g., https://www.youtube.com/embed/VIDEO_ID)', 'my-portfolio-html'),
        'section'     => 'video_section',
        'type'        => 'url',
    ));

    // ===========================================
    // SECTION: Contact Section
    // ===========================================
    $wp_customize->add_section('contact_section', array(
        'title'    => __('Contact Section', 'my-portfolio-html'),
        'panel'    => 'theme_settings',
        'priority' => 35,
    ));

    // Contact Badge
    $wp_customize->add_setting('contact_badge', array(
        'default'           => 'Get In Touch',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('contact_badge', array(
        'label'   => __('Section Badge Text', 'my-portfolio-html'),
        'section' => 'contact_section',
        'type'    => 'text',
    ));

    // Contact Title
    $wp_customize->add_setting('contact_title', array(
        'default'           => 'Contact Me',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('contact_title', array(
        'label'   => __('Section Title', 'my-portfolio-html'),
        'section' => 'contact_section',
        'type'    => 'text',
    ));

    // Contact Subtitle
    $wp_customize->add_setting('contact_subtitle', array(
        'default'           => "Have a project in mind? Let's discuss it together!",
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('contact_subtitle', array(
        'label'   => __('Section Subtitle', 'my-portfolio-html'),
        'section' => 'contact_section',
        'type'    => 'text',
    ));

    // Contact Email
    $wp_customize->add_setting('contact_email', array(
        'default'           => 'adhityamedia@gmail.com',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('contact_email', array(
        'label'   => __('Email Address', 'my-portfolio-html'),
        'section' => 'contact_section',
        'type'    => 'email',
    ));

    // Contact WhatsApp
    $wp_customize->add_setting('contact_whatsapp', array(
        'default'           => '6285217864254',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('contact_whatsapp', array(
        'label'       => __('WhatsApp Number', 'my-portfolio-html'),
        'description' => __('Include country code without + (e.g., 6281234567890)', 'my-portfolio-html'),
        'section'     => 'contact_section',
        'type'        => 'text',
    ));

    // Contact Telegram
    $wp_customize->add_setting('contact_telegram', array(
        'default'           => 'adhitya_s',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('contact_telegram', array(
        'label'       => __('Telegram Username', 'my-portfolio-html'),
        'description' => __('Username without @', 'my-portfolio-html'),
        'section'     => 'contact_section',
        'type'        => 'text',
    ));

    // ===========================================
    // SECTION: Typography
    // ===========================================
    $wp_customize->add_section('typography_section', array(
        'title'    => __('Typography', 'my-portfolio-html'),
        'panel'    => 'theme_settings',
        'priority' => 40,
    ));

    // Font Family
    $wp_customize->add_setting('font_family', array(
        'default'           => 'Inter',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('font_family', array(
        'label'   => __('Font Family', 'my-portfolio-html'),
        'section' => 'typography_section',
        'type'    => 'select',
        'choices' => array(
            'Inter'      => 'Inter',
            'Poppins'    => 'Poppins',
            'Roboto'     => 'Roboto',
            'Open Sans'  => 'Open Sans',
            'Montserrat' => 'Montserrat',
            'Nunito'     => 'Nunito',
            'Lato'       => 'Lato',
        ),
    ));

    // ===========================================
    // SECTION: Colors
    // ===========================================
    $wp_customize->add_section('colors_section', array(
        'title'    => __('Colors', 'my-portfolio-html'),
        'panel'    => 'theme_settings',
        'priority' => 45,
    ));

    // Background Primary
    $wp_customize->add_setting('color_bg_primary', array(
        'default'           => '#0a0a0f',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_bg_primary', array(
        'label'   => __('Background Primary', 'my-portfolio-html'),
        'section' => 'colors_section',
    )));

    // Background Secondary
    $wp_customize->add_setting('color_bg_secondary', array(
        'default'           => '#12121a',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_bg_secondary', array(
        'label'   => __('Background Secondary', 'my-portfolio-html'),
        'section' => 'colors_section',
    )));

    // Accent Primary (Purple)
    $wp_customize->add_setting('color_accent_primary', array(
        'default'           => '#7c3aed',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_accent_primary', array(
        'label'   => __('Accent Primary (Purple)', 'my-portfolio-html'),
        'section' => 'colors_section',
    )));

    // Accent Secondary (Cyan)
    $wp_customize->add_setting('color_accent_secondary', array(
        'default'           => '#06b6d4',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_accent_secondary', array(
        'label'   => __('Accent Secondary (Cyan)', 'my-portfolio-html'),
        'section' => 'colors_section',
    )));

    // Accent Tertiary (Pink)
    $wp_customize->add_setting('color_accent_tertiary', array(
        'default'           => '#ec4899',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_accent_tertiary', array(
        'label'   => __('Accent Tertiary (Pink)', 'my-portfolio-html'),
        'section' => 'colors_section',
    )));

    // Text Primary
    $wp_customize->add_setting('color_text_primary', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_text_primary', array(
        'label'   => __('Text Primary', 'my-portfolio-html'),
        'section' => 'colors_section',
    )));

    // Text Secondary
    $wp_customize->add_setting('color_text_secondary', array(
        'default'           => '#94a3b8',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_text_secondary', array(
        'label'   => __('Text Secondary', 'my-portfolio-html'),
        'section' => 'colors_section',
    )));

    // Text Muted
    $wp_customize->add_setting('color_text_muted', array(
        'default'           => '#64748b',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_text_muted', array(
        'label'   => __('Text Muted', 'my-portfolio-html'),
        'section' => 'colors_section',
    )));

    // ===========================================
    // SECTION: Footer
    // ===========================================
    $wp_customize->add_section('footer_section', array(
        'title'    => __('Footer', 'my-portfolio-html'),
        'panel'    => 'theme_settings',
        'priority' => 50,
    ));

    // Footer Author
    $wp_customize->add_setting('footer_author', array(
        'default'           => 'Adhitya Sukma',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('footer_author', array(
        'label'   => __('Author Name', 'my-portfolio-html'),
        'section' => 'footer_section',
        'type'    => 'text',
    ));

    // Footer Author Link
    $wp_customize->add_setting('footer_author_link', array(
        'default'           => 'https://t.me/adhitya_s',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('footer_author_link', array(
        'label'   => __('Author Link', 'my-portfolio-html'),
        'section' => 'footer_section',
        'type'    => 'url',
    ));

    // Google Analytics ID
    $wp_customize->add_setting('google_analytics_id', array(
        'default'           => 'G-YB6KQ5EYTJ',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('google_analytics_id', array(
        'label'       => __('Google Analytics ID', 'my-portfolio-html'),
        'description' => __('e.g., G-XXXXXXXXXX or UA-XXXXXXXXX-X', 'my-portfolio-html'),
        'section'     => 'footer_section',
        'type'        => 'text',
    ));
}
add_action('customize_register', 'my_portfolio_customize_register');

/**
 * Output custom CSS variables based on customizer settings
 */
function my_portfolio_customizer_css() {
    $font_family        = get_theme_mod('font_family', 'Inter');
    $bg_primary         = get_theme_mod('color_bg_primary', '#0a0a0f');
    $bg_secondary       = get_theme_mod('color_bg_secondary', '#12121a');
    $accent_primary     = get_theme_mod('color_accent_primary', '#7c3aed');
    $accent_secondary   = get_theme_mod('color_accent_secondary', '#06b6d4');
    $accent_tertiary    = get_theme_mod('color_accent_tertiary', '#ec4899');
    $text_primary       = get_theme_mod('color_text_primary', '#ffffff');
    $text_secondary     = get_theme_mod('color_text_secondary', '#94a3b8');
    $text_muted         = get_theme_mod('color_text_muted', '#64748b');

    // Convert hex to RGB for rgba() usage
    $accent_primary_rgb = my_portfolio_hex_to_rgb($accent_primary);
    $accent_secondary_rgb = my_portfolio_hex_to_rgb($accent_secondary);
    $text_primary_rgb = my_portfolio_hex_to_rgb($text_primary);
    ?>
    <style type="text/css">
        :root {
            /* Colors */
            --bg-primary: <?php echo esc_attr($bg_primary); ?>;
            --bg-secondary: <?php echo esc_attr($bg_secondary); ?>;
            --bg-card: rgba(255, 255, 255, 0.03);
            --bg-glass: rgba(255, 255, 255, 0.05);
            
            --accent-primary: <?php echo esc_attr($accent_primary); ?>;
            --accent-secondary: <?php echo esc_attr($accent_secondary); ?>;
            --accent-tertiary: <?php echo esc_attr($accent_tertiary); ?>;
            
            --text-primary: <?php echo esc_attr($text_primary); ?>;
            --text-secondary: <?php echo esc_attr($text_secondary); ?>;
            --text-muted: <?php echo esc_attr($text_muted); ?>;
            
            --gradient-primary: linear-gradient(135deg, <?php echo esc_attr($accent_primary); ?> 0%, <?php echo esc_attr($accent_secondary); ?> 100%);
            --gradient-hero: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f1a 100%);
            --gradient-card: linear-gradient(145deg, rgba(<?php echo $accent_primary_rgb; ?>, 0.1) 0%, rgba(<?php echo $accent_secondary_rgb; ?>, 0.1) 100%);
            
            /* Shadows */
            --shadow-glow: 0 0 40px rgba(<?php echo $accent_primary_rgb; ?>, 0.3);
            --shadow-card: 0 8px 32px rgba(0, 0, 0, 0.3);
            --shadow-button: 0 4px 15px rgba(<?php echo $accent_primary_rgb; ?>, 0.4);
            
            /* Borders */
            --border-glass: 1px solid rgba(<?php echo $text_primary_rgb; ?>, 0.1);
            --border-accent: 1px solid rgba(<?php echo $accent_primary_rgb; ?>, 0.3);
            
            /* Spacing */
            --section-padding: 100px 0;
            --container-padding: 0 20px;
            
            /* Transitions */
            --transition-fast: 0.2s ease;
            --transition-normal: 0.3s ease;
            --transition-slow: 0.5s ease;
            
            /* Border Radius */
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --radius-xl: 30px;
        }

        body {
            font-family: '<?php echo esc_attr($font_family); ?>', -apple-system, BlinkMacSystemFont, sans-serif;
        }
    </style>
    <?php
}
add_action('wp_head', 'my_portfolio_customizer_css');

/**
 * Convert hex color to RGB
 */
function my_portfolio_hex_to_rgb($hex) {
    $hex = str_replace('#', '', $hex);
    
    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    
    return "$r, $g, $b";
}
