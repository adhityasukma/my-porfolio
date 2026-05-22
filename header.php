<?php
/**
 * The header for our theme
 *
 * @package My_Portfolio_HTML
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get theme settings for meta tags (using defaults if not set, though these are usually page specific, 
// but for the original theme they were global. We might want to make them dynamic later, but for now keep as is or use wp_head)
$hero_name = get_theme_mod('hero_name', 'Adhitya Sukma');
$hero_subtitle = get_theme_mod('hero_subtitle', 'PHP Web Developer (Laravel and Wordpress)');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo esc_attr($hero_name); ?> - <?php echo esc_attr($hero_subtitle); ?>">
    <meta name="keywords" content="web developer, laravel, vue.js, php, wordpress, indonesia">
    <meta name="author" content="<?php echo esc_attr($hero_name); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/favicon.ico">
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'navbar-nav ms-auto',
                    'fallback_cb'    => '__return_false',
                    'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth'          => 2,
                    // We will add a walker or filters in functions.php to handle 'nav-item' and 'nav-link' classes
                ));
                ?>
            </div>
        </div>
    </nav>
