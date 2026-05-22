<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package My_Portfolio_HTML
 */

$heading = get_theme_mod('error_404_heading', '404 Not Found');
$message = get_theme_mod('error_404_message', 'Oops! The page you are looking for does not exist. It might have been moved or deleted.');
$btn_text = get_theme_mod('error_404_btn_text', 'Back to Homepage');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo esc_html($heading); ?> - <?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>
    <style>
        body {
            background-color: var(--bg-primary, #0a0a0f);
            color: var(--text-primary, #ffffff);
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin: 0;
            padding: 20px;
        }
        .maintenance-container {
            max-width: 600px;
            padding: 40px;
            background: var(--bg-secondary, #12121a);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--accent-primary, #7c3aed) 0%, var(--accent-secondary, #06b6d4) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: var(--text-secondary, #94a3b8);
        }
        .icon {
            font-size: 4rem;
            margin-bottom: 20px;
            display: inline-block;
            animation: float 3s ease-in-out infinite;
        }
        .btn-home {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 30px;
            background: linear-gradient(135deg, var(--accent-primary, #7c3aed) 0%, var(--accent-secondary, #06b6d4) 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(124, 58, 237, 0.3);
            color: white;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="icon">🔍</div>
        <h1><?php echo esc_html($heading); ?></h1>
        <p><?php echo nl2br(esc_html($message)); ?></p>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-home"><?php echo esc_html($btn_text); ?></a>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
