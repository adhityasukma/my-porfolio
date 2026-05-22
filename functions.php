<?php
/**
 * Theme Functions
 *
 * @package My_Portfolio_HTML
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define theme version
define('MY_PORTFOLIO_VERSION', '1.0.0');

/**
 * Theme Setup
 */
function my_portfolio_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');

    // Custom logo support
    add_theme_support('custom-logo', array(
        'height'      => 180,
        'width'       => 180,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // HTML5 support
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Register navigation menu
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'my-portfolio-html'),
    ));
}
add_action('after_setup_theme', 'my_portfolio_setup');

/**
 * Security: Redirect wp-login.php and wp-admin for non-logged in users
 */
function my_portfolio_security_redirects() {
    // Check if user is logged in
    if (is_user_logged_in()) {
        return;
    }

    // Prevent blocking AJAX requests which might be needed for frontend
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }

    // Optional: Secret key bypass (uncomment if needed)
    // if (isset($_GET['access_key']) && $_GET['access_key'] === 'portfolio_admin') { return; }

    // Redirect to 404 page
    // We use a non-existent URL to force WordPress to load the 404 template
    wp_safe_redirect(home_url('/404-not-found'));
    exit;
}
// add_action('login_init', 'my_portfolio_security_redirects'); // Disabled for WPS Hide Login compatibility
add_action('admin_init', 'my_portfolio_security_redirects');

/**
 * Enqueue scripts and styles
 */
function my_portfolio_scripts() {
    $theme_uri = get_template_directory_uri();
    $theme_version = MY_PORTFOLIO_VERSION;

    // Google Fonts
    $font_family = get_theme_mod('font_family', 'Inter');
    $font_family_url = str_replace(' ', '+', $font_family);
    wp_enqueue_style(
        'google-fonts',
        "https://fonts.googleapis.com/css2?family={$font_family_url}:wght@300;400;500;600;700;800&display=swap",
        array(),
        null
    );

    // Bootstrap CSS (CDN)
    wp_enqueue_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
        array(),
        '5.3.0'
    );

    // AOS CSS (CDN)
    wp_enqueue_style(
        'aos',
        'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css',
        array(),
        '2.3.4'
    );

    // GLightbox CSS (CDN)
    wp_enqueue_style(
        'glightbox',
        'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css',
        array(),
        '3.1.0'
    );

    // Theme Style
    wp_enqueue_style(
        'theme-style',
        $theme_uri . '/assets/css/theme-style.css',
        array('bootstrap', 'aos', 'glightbox'),
        $theme_version
    );

    // Main stylesheet (required by WordPress)
    wp_enqueue_style(
        'my-portfolio-style',
        get_stylesheet_uri(),
        array('theme-style'),
        $theme_version
    );

    // Bootstrap JS (CDN)
    wp_enqueue_script(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
        array(),
        '5.3.0',
        true
    );

    // AOS JS (CDN)
    wp_enqueue_script(
        'aos',
        'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js',
        array(),
        '2.3.4',
        true
    );

    // GLightbox JS
    wp_enqueue_script(
        'glightbox',
        'https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js',
        array(),
        '3.1.0',
        true
    );

    // GSAP for text animation
    wp_enqueue_script(
        'gsap',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/gsap.min.js',
        array(),
        '3.10.4',
        true
    );

    wp_enqueue_script(
        'gsap-text',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/TextPlugin.min.js',
        array('gsap'),
        '3.10.4',
        true
    );

    // Theme Script
    wp_enqueue_script(
        'theme-script',
        $theme_uri . '/assets/js/theme-script.js',
        array('bootstrap', 'aos', 'gsap', 'gsap-text', 'glightbox'),
        $theme_version,
        true
    );

    // Pass variables to JavaScript
    wp_localize_script('theme-script', 'myPortfolioData', array(
        'heroSubtitle' => get_theme_mod('hero_subtitle', 'PHP Web Developer (Laravel and Wordpress)'),
    ));
}
add_action('wp_enqueue_scripts', 'my_portfolio_scripts');

/**
 * Add Google Analytics to head
 */
function my_portfolio_google_analytics() {
    $ga_id = get_theme_mod('google_analytics_id', '');
    if (!empty($ga_id)) {
        ?>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga_id); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo esc_js($ga_id); ?>');
        </script>
        <?php
    }
}
add_action('wp_head', 'my_portfolio_google_analytics', 1);



/**
 * Include Customizer settings
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Include Visitor Tracker
 */
require get_template_directory() . '/inc/class-visitor-tracker.php';

/**
 * Include Work Experience CPT
 */
require get_template_directory() . '/inc/cpt-work-experience.php';

/**
 * Helper function to get skills as array
 */
function my_portfolio_get_skills($type) {
    $defaults = array(
        'frontend'    => 'HTML5, CSS3, JavaScript, Vue.js, Bootstrap, Tailwind CSS, REST API',
        'backend'     => 'PHP, Laravel, CodeIgniter, WordPress, MySQL, SQL Server, Redis, JWT Auth, OAuth2',
        'devops'      => 'Git, Docker, Linux, NPM, API Development',
        'integration' => 'REST API, GraphQL, Webhooks, OAuth, Stripe',
        'database'    => 'MySQL, PostgreSQL, MongoDB, Redis'
    );
    
    $default_val = isset($defaults[$type]) ? $defaults[$type] : '';
    $skills_string = get_theme_mod("skills_{$type}", $default_val);
    
    if (empty($skills_string)) {
        return array();
    }
    
    $skills = array_map('trim', explode(',', $skills_string));
    return array_filter($skills);
}

/**
 * Add Bootstrap nav-item class to menu items
 */
function my_portfolio_nav_menu_css_class($classes, $item, $args, $depth) {
    if ($args->theme_location === 'primary') {
        $classes[] = 'nav-item';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'my_portfolio_nav_menu_css_class', 10, 4);

/**
 * Add Bootstrap nav-link class to menu links
 */
function my_portfolio_nav_menu_link_attributes($atts, $item, $args, $depth) {
    if ($args->theme_location === 'primary') {
        $atts['class'] = isset($atts['class']) ? $atts['class'] . ' nav-link' : 'nav-link';
        
        // Add active class if current page
        if ($item->current || $item->current_item_ancestor || $item->current_item_parent) {
            $atts['class'] .= ' active';
        }
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'my_portfolio_nav_menu_link_attributes', 10, 4);

add_action('save_post', 'portfolio_save_tech_stack_meta');

/**
 * Register Portfolio Custom Post Type
 */
function portfolio_register_cpt() {
    $labels = array(
        'name'                  => _x('Portfolio', 'Post Type General Name', 'my-portfolio-html'),
        'singular_name'         => _x('Project', 'Post Type Singular Name', 'my-portfolio-html'),
        'menu_name'             => __('Portfolio', 'my-portfolio-html'),
        'name_admin_bar'        => __('Portfolio', 'my-portfolio-html'),
        'archives'              => __('Item Archives', 'my-portfolio-html'),
        'attributes'            => __('Item Attributes', 'my-portfolio-html'),
        'parent_item_colon'     => __('Parent Item:', 'my-portfolio-html'),
        'all_items'             => __('All Projects', 'my-portfolio-html'),
        'add_new_item'          => __('Add New Project', 'my-portfolio-html'),
        'add_new'               => __('Add New', 'my-portfolio-html'),
        'new_item'              => __('New Project', 'my-portfolio-html'),
        'edit_item'             => __('Edit Project', 'my-portfolio-html'),
        'update_item'           => __('Update Project', 'my-portfolio-html'),
        'view_item'             => __('View Project', 'my-portfolio-html'),
        'view_items'            => __('View Projects', 'my-portfolio-html'),
        'search_items'          => __('Search Project', 'my-portfolio-html'),
        'not_found'             => __('Not found', 'my-portfolio-html'),
        'not_found_in_trash'    => __('Not found in Trash', 'my-portfolio-html'),
        'featured_image'        => __('Featured Image', 'my-portfolio-html'),
        'set_featured_image'    => __('Set featured image', 'my-portfolio-html'),
        'remove_featured_image' => __('Remove featured image', 'my-portfolio-html'),
        'use_featured_image'    => __('Use as featured image', 'my-portfolio-html'),
    );
    $args = array(
        'label'                 => __('Project', 'my-portfolio-html'),
        'description'           => __('Portfolio Projects', 'my-portfolio-html'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail'),
        'taxonomies'            => array('project_category'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type('portfolio', $args);
    
    // Register Taxonomy
    $tax_labels = array(
        'name'                       => _x('Project Categories', 'Taxonomy General Name', 'my-portfolio-html'),
        'singular_name'              => _x('Project Category', 'Taxonomy Singular Name', 'my-portfolio-html'),
        'menu_name'                  => __('Categories', 'my-portfolio-html'),
        'all_items'                  => __('All Categories', 'my-portfolio-html'),
        'parent_item'                => __('Parent Category', 'my-portfolio-html'),
        'parent_item_colon'          => __('Parent Category:', 'my-portfolio-html'),
        'new_item_name'              => __('New Category Name', 'my-portfolio-html'),
        'add_new_item'               => __('Add New Category', 'my-portfolio-html'),
        'edit_item'                  => __('Edit Category', 'my-portfolio-html'),
        'update_item'                => __('Update Category', 'my-portfolio-html'),
        'view_item'                  => __('View Category', 'my-portfolio-html'),
        'separate_items_with_commas' => __('Separate categories with commas', 'my-portfolio-html'),
        'add_or_remove_items'        => __('Add or remove categories', 'my-portfolio-html'),
        'choose_from_most_used'      => __('Choose from the most used', 'my-portfolio-html'),
        'popular_items'              => __('Popular Categories', 'my-portfolio-html'),
        'search_items'               => __('Search Categories', 'my-portfolio-html'),
        'not_found'                  => __('Not Found', 'my-portfolio-html'),
    );
    $tax_args = array(
        'labels'                     => $tax_labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy('project_category', array('portfolio'), $tax_args);
}
add_action('init', 'portfolio_register_cpt');

/**
 * Register Meta Box for Tech Stack
 */
function portfolio_add_tech_stack_meta_box() {
    $screens = array('post', 'page', 'portfolio');
    foreach ($screens as $screen) {
        add_meta_box(
            'portfolio_tech_stack',
            __('Tech Stack', 'my-portfolio-html'),
            'portfolio_tech_stack_callback',
            $screen,
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'portfolio_add_tech_stack_meta_box');

/**
 * Meta Box Callback
 */
function portfolio_tech_stack_callback($post) {
    wp_nonce_field('portfolio_save_tech_stack_data', 'portfolio_tech_stack_nonce');
    
    $value = get_post_meta($post->ID, '_portfolio_tech_stack', true);
    
    echo '<p>';
    echo '<label for="portfolio_tech_stack_field">' . __('Enter technologies used (comma separated):', 'my-portfolio-html') . '</label>';
    echo '</p>';
    echo '<input type="text" id="portfolio_tech_stack_field" name="portfolio_tech_stack_field" value="' . esc_attr($value) . '" class="widefat" placeholder="e.g. PHP, Laravel, MySQL" />';
}

/**
 * Save Meta Box Data
 */
function portfolio_save_tech_stack_meta($post_id) {
    if (!isset($_POST['portfolio_tech_stack_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['portfolio_tech_stack_nonce'], 'portfolio_save_tech_stack_data')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['portfolio_tech_stack_field'])) {
        $my_data = sanitize_text_field($_POST['portfolio_tech_stack_field']);
        update_post_meta($post_id, '_portfolio_tech_stack', $my_data);
    }
}
add_action('save_post', 'portfolio_save_tech_stack_meta');

/**
 * Register Meta Boxes for Project Details and Gallery
 */
function portfolio_add_custom_meta_boxes() {
    add_meta_box(
        'portfolio_project_details',
        __('Project Details', 'my-portfolio-html'),
        'portfolio_project_details_callback',
        'portfolio',
        'normal',
        'high'
    );
    
    add_meta_box(
        'portfolio_gallery',
        __('Project Gallery', 'my-portfolio-html'),
        'portfolio_gallery_callback',
        'portfolio',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'portfolio_add_custom_meta_boxes');

/**
 * Project Details Callback (Link)
 */
function portfolio_project_details_callback($post) {
    wp_nonce_field('portfolio_save_details_data', 'portfolio_details_nonce');
    
    $link = get_post_meta($post->ID, '_portfolio_project_link', true);
    $year = get_post_meta($post->ID, '_portfolio_project_year', true);
    
    echo '<p>';
    echo '<label for="portfolio_project_link">' . __('Project Link (URL):', 'my-portfolio-html') . '</label>';
    echo '<input type="url" id="portfolio_project_link" name="portfolio_project_link" value="' . esc_attr($link) . '" class="widefat" placeholder="https://example.com" />';
    echo '</p>';
    
    echo '<p>';
    echo '<label for="portfolio_project_year">' . __('Project Year:', 'my-portfolio-html') . '</label>';
    echo '<input type="text" id="portfolio_project_year" name="portfolio_project_year" value="' . esc_attr($year) . '" class="widefat" placeholder="e.g. 2023" />';
    echo '</p>';
}

/**
 * Gallery Callback
 */
function portfolio_gallery_callback($post) {
    wp_nonce_field('portfolio_save_gallery_data', 'portfolio_gallery_nonce');
    
    $image_ids = get_post_meta($post->ID, '_portfolio_gallery', true);
    
    echo '<div id="portfolio_gallery_images_container" style="margin-bottom: 10px;">';
    
    if (!empty($image_ids)) {
        $ids = explode(',', $image_ids);
        foreach ($ids as $id) {
            $url = wp_get_attachment_thumb_url($id);
            if ($url) {
                echo '<div class="gallery-image-preview" style="display:inline-block; margin:5px; position:relative;">';
                echo '<img src="' . esc_url($url) . '" width="80" height="80" style="border:1px solid #ccc;" />';
                echo '<span class="remove-gallery-image" data-id="' . esc_attr($id) . '" style="position:absolute; top:-5px; right:-5px; background:red; color:white; border-radius:50%; width:18px; height:18px; text-align:center; line-height:16px; cursor:pointer;">&times;</span>';
                echo '</div>';
            }
        }
    }
    
    echo '</div>';
    
    echo '<input type="hidden" id="portfolio_gallery_image_ids" name="portfolio_gallery_image_ids" value="' . esc_attr($image_ids) . '" />';
    echo '<button type="button" class="button button-secondary" id="portfolio_gallery_add_image">' . __('Add Images', 'my-portfolio-html') . '</button>';
    echo '<p class="description">' . __('Select images for the project gallery (lightbox support included).', 'my-portfolio-html') . '</p>';
}

/**
 * Save Project Meta Data
 */
function portfolio_save_custom_meta_boxes($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    // Save Link and Year
    if (isset($_POST['portfolio_details_nonce']) && wp_verify_nonce($_POST['portfolio_details_nonce'], 'portfolio_save_details_data')) {
        if (isset($_POST['portfolio_project_link'])) {
            update_post_meta($post_id, '_portfolio_project_link', esc_url_raw($_POST['portfolio_project_link']));
        }
        if (isset($_POST['portfolio_project_year'])) {
            update_post_meta($post_id, '_portfolio_project_year', sanitize_text_field($_POST['portfolio_project_year']));
        }
    }
    
    // Save Gallery
    if (isset($_POST['portfolio_gallery_nonce']) && wp_verify_nonce($_POST['portfolio_gallery_nonce'], 'portfolio_save_gallery_data')) {
        if (isset($_POST['portfolio_gallery_image_ids'])) {
            update_post_meta($post_id, '_portfolio_gallery', sanitize_text_field($_POST['portfolio_gallery_image_ids']));
        }
    }
}
add_action('save_post', 'portfolio_save_custom_meta_boxes');

/**
 * Enqueue Admin Scripts for Gallery
 */
function portfolio_admin_scripts($hook) {
    global $post;
    
    if ($hook == 'post-new.php' || $hook == 'post.php') {
        if ('portfolio' === $post->post_type) {
            wp_enqueue_media();
            wp_enqueue_script('portfolio-admin-js', get_template_directory_uri() . '/assets/js/admin-portfolio.js', array('jquery'), MY_PORTFOLIO_VERSION, true);
        }
    }
}
add_action('admin_enqueue_scripts', 'portfolio_admin_scripts');

/**
 * Maintenance Mode Redirect
 */
function my_portfolio_maintenance_mode() {
    if (get_theme_mod('maintenance_mode_enable', false) && !current_user_can('administrator') && !is_user_logged_in()) {
        include(get_template_directory() . '/under-construction.php');
        exit;
    }
}
add_action('template_redirect', 'my_portfolio_maintenance_mode');

/**
 * Set posts per page for portfolio archive
 */
function my_portfolio_archive_posts_per_page( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'portfolio' ) ) {
        $query->set( 'posts_per_page', 6 );
    }
}
add_action( 'pre_get_posts', 'my_portfolio_archive_posts_per_page' );
