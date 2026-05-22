<?php
/**
 * Custom Post Type: Work Experience
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Work Experience CPT
 */
function my_portfolio_register_work_experience_cpt() {
    $labels = array(
        'name'                  => _x('Work Experiences', 'Post Type General Name', 'my-portfolio-html'),
        'singular_name'         => _x('Work Experience', 'Post Type Singular Name', 'my-portfolio-html'),
        'menu_name'             => __('Work Experience', 'my-portfolio-html'),
        'name_admin_bar'        => __('Work Experience', 'my-portfolio-html'),
        'add_new_item'          => __('Add New Experience', 'my-portfolio-html'),
        'add_new'               => __('Add New', 'my-portfolio-html'),
        'new_item'              => __('New Experience', 'my-portfolio-html'),
        'edit_item'             => __('Edit Experience', 'my-portfolio-html'),
        'update_item'           => __('Update Experience', 'my-portfolio-html'),
        'view_item'             => __('View Experience', 'my-portfolio-html'),
        'search_items'          => __('Search Experience', 'my-portfolio-html'),
    );
    $args = array(
        'label'                 => __('Work Experience', 'my-portfolio-html'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor'), // title = Role, editor = details
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-businessman',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type('work_experience', $args);
}
add_action('init', 'my_portfolio_register_work_experience_cpt');

/**
 * Add Meta Boxes
 */
function my_portfolio_work_meta_boxes() {
    add_meta_box(
        'work_experience_meta',
        __('Experience Details', 'my-portfolio-html'),
        'my_portfolio_work_meta_callback',
        'work_experience',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'my_portfolio_work_meta_boxes');

function my_portfolio_work_meta_callback($post) {
    wp_nonce_field('work_experience_save_meta', 'work_experience_meta_nonce');
    
    $company = get_post_meta($post->ID, '_work_company', true);
    $start_date = get_post_meta($post->ID, '_work_start_date', true);
    $end_date = get_post_meta($post->ID, '_work_end_date', true);
    $location = get_post_meta($post->ID, '_work_location', true);
    $work_type = get_post_meta($post->ID, '_work_type', true);
    ?>
    <style>
        .work-meta-row { margin-bottom: 15px; }
        .work-meta-row label { display: block; font-weight: bold; margin-bottom: 5px; }
        .work-meta-row input[type="text"] { width: 100%; max-width: 400px; }
    </style>
    <div class="work-meta-row">
        <label for="work_company"><?php _e('Company Name', 'my-portfolio-html'); ?></label>
        <input type="text" id="work_company" name="work_company" value="<?php echo esc_attr($company); ?>">
    </div>
    <div class="work-meta-row">
        <label for="work_start_date"><?php _e('Start Date (e.g., Jan 2020)', 'my-portfolio-html'); ?></label>
        <input type="text" id="work_start_date" name="work_start_date" value="<?php echo esc_attr($start_date); ?>">
    </div>
    <div class="work-meta-row">
        <label for="work_end_date"><?php _e('End Date (e.g., Present or Dec 2022)', 'my-portfolio-html'); ?></label>
        <input type="text" id="work_end_date" name="work_end_date" value="<?php echo esc_attr($end_date); ?>">
    </div>
    <div class="work-meta-row">
        <label for="work_location"><?php _e('Location (e.g., Remote, Jakarta)', 'my-portfolio-html'); ?></label>
        <input type="text" id="work_location" name="work_location" value="<?php echo esc_attr($location); ?>">
    </div>
    <div class="work-meta-row">
        <label for="work_type"><?php _e('Tipe Kerja', 'my-portfolio-html'); ?></label>
        <select id="work_type" name="work_type" style="width: 100%; max-width: 400px;">
            <option value="" <?php selected($work_type, ''); ?>>-- Pilih Tipe Kerja --</option>
            <option value="Remote" <?php selected($work_type, 'Remote'); ?>>Remote (Kerja jarak jauh, dari mana saja)</option>
            <option value="WFH" <?php selected($work_type, 'WFH'); ?>>WFH (Work From Home, kerja dari rumah)</option>
            <option value="WFO" <?php selected($work_type, 'WFO'); ?>>WFO (Work From Office, kerja dari kantor)</option>
            <option value="Hybrid" <?php selected($work_type, 'Hybrid'); ?>>Hybrid (Kombinasi kerja kantor dan remote/WFH)</option>
        </select>
    </div>
    <?php
}

function my_portfolio_work_save_meta($post_id) {
    if (!isset($_POST['work_experience_meta_nonce']) || !wp_verify_nonce($_POST['work_experience_meta_nonce'], 'work_experience_save_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['work_company'])) {
        update_post_meta($post_id, '_work_company', sanitize_text_field($_POST['work_company']));
    }
    if (isset($_POST['work_start_date'])) {
        update_post_meta($post_id, '_work_start_date', sanitize_text_field($_POST['work_start_date']));
    }
    if (isset($_POST['work_end_date'])) {
        update_post_meta($post_id, '_work_end_date', sanitize_text_field($_POST['work_end_date']));
    }
    if (isset($_POST['work_location'])) {
        update_post_meta($post_id, '_work_location', sanitize_text_field($_POST['work_location']));
    }
    if (isset($_POST['work_type'])) {
        update_post_meta($post_id, '_work_type', sanitize_text_field($_POST['work_type']));
    }
}
add_action('save_post', 'my_portfolio_work_save_meta');

/**
 * Add Custom Columns to List Table
 */
function my_portfolio_work_experience_columns($columns) {
    $new_columns = array(
        'cb' => $columns['cb'],
        'title' => $columns['title'],
        'company' => __('Company', 'my-portfolio-html'),
        'duration' => __('Duration', 'my-portfolio-html'),
        'location' => __('Location', 'my-portfolio-html'),
        'work_type' => __('Tipe Kerja', 'my-portfolio-html'),
        'date' => $columns['date']
    );
    return $new_columns;
}
add_filter('manage_work_experience_posts_columns', 'my_portfolio_work_experience_columns');

/**
 * Populate Custom Columns
 */
function my_portfolio_work_experience_custom_column($column, $post_id) {
    switch ($column) {
        case 'company':
            $company = get_post_meta($post_id, '_work_company', true);
            echo $company ? esc_html($company) : '-';
            break;
        case 'duration':
            $start = get_post_meta($post_id, '_work_start_date', true);
            $end = get_post_meta($post_id, '_work_end_date', true);
            if ($start || $end) {
                echo esc_html($start . ' - ' . $end);
            } else {
                echo '-';
            }
            break;
        case 'location':
            $location = get_post_meta($post_id, '_work_location', true);
            echo $location ? esc_html($location) : '-';
            break;
        case 'work_type':
            $work_type = get_post_meta($post_id, '_work_type', true);
            echo $work_type ? esc_html($work_type) : '-';
            break;
    }
}
add_action('manage_work_experience_posts_custom_column', 'my_portfolio_work_experience_custom_column', 10, 2);

/**
 * Add Filter Dropdown
 */
function my_portfolio_work_experience_filter_dropdown() {
    global $typenow;
    if ($typenow === 'work_experience') {
        $selected = isset($_GET['filter_work_type']) ? sanitize_text_field($_GET['filter_work_type']) : '';
        ?>
        <select name="filter_work_type" id="filter_work_type">
            <option value=""><?php _e('Semua Tipe Kerja', 'my-portfolio-html'); ?></option>
            <option value="Remote" <?php selected($selected, 'Remote'); ?>>Remote</option>
            <option value="WFH" <?php selected($selected, 'WFH'); ?>>WFH</option>
            <option value="WFO" <?php selected($selected, 'WFO'); ?>>WFO</option>
            <option value="Hybrid" <?php selected($selected, 'Hybrid'); ?>>Hybrid</option>
        </select>
        <?php
    }
}
add_action('restrict_manage_posts', 'my_portfolio_work_experience_filter_dropdown');

/**
 * Apply Filter to Query
 */
function my_portfolio_work_experience_filter_query($query) {
    global $pagenow, $typenow;
    if ($pagenow === 'edit.php' && $typenow === 'work_experience' && isset($_GET['filter_work_type']) && $_GET['filter_work_type'] !== '') {
        $query->query_vars['meta_key'] = '_work_type';
        $query->query_vars['meta_value'] = sanitize_text_field($_GET['filter_work_type']);
    }
}
add_filter('parse_query', 'my_portfolio_work_experience_filter_query');

/**
 * Make Custom Columns Sortable
 */
function my_portfolio_work_experience_sortable_columns($columns) {
    $columns['company'] = 'company';
    $columns['duration'] = 'duration';
    $columns['location'] = 'location';
    $columns['work_type'] = 'work_type';
    return $columns;
}
add_filter('manage_edit-work_experience_sortable_columns', 'my_portfolio_work_experience_sortable_columns');

/**
 * Handle Sorting Logic for Custom Columns
 */
function my_portfolio_work_experience_orderby($query) {
    if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'work_experience') {
        return;
    }

    $orderby = $query->get('orderby');

    switch ($orderby) {
        case 'company':
            $query->set('meta_key', '_work_company');
            $query->set('orderby', 'meta_value');
            break;
        case 'duration':
            // Sort by start date when duration column is clicked
            $query->set('meta_key', '_work_start_date');
            $query->set('orderby', 'meta_value');
            break;
        case 'location':
            $query->set('meta_key', '_work_location');
            $query->set('orderby', 'meta_value');
            break;
        case 'work_type':
            $query->set('meta_key', '_work_type');
            $query->set('orderby', 'meta_value');
            break;
    }
}
add_action('pre_get_posts', 'my_portfolio_work_experience_orderby');

/**
 * Shortcode for Work Experience
 * Usage: [work_experience] or [work_experience id="123"] or [work_experience id="12,13"]
 */
function my_portfolio_work_experience_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id'    => '',
        'order' => 'DESC',
    ), $atts, 'work_experience');

    $args = array(
        'post_type'      => 'work_experience',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    );

    if (!empty($atts['id'])) {
        // Support comma separated IDs
        $ids = array_map('intval', explode(',', $atts['id']));
        $args['post__in'] = $ids;
    }

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '';
    }
    
    $posts = $query->posts;

    // Helper to parse date string to timestamp for sorting
    $parse_date = function($date_string) {
        if (empty($date_string) || in_array(strtolower(trim($date_string)), array('present', 'sekarang', 'now', 'saat ini'))) {
            return time(); // Treat "Present" as current time
        }
        $date_string = trim($date_string);
        // Handle MM/YYYY, MM-YYYY, or MM YYYY
        if (preg_match('/^(\d{1,2})[\/\-\s]+(\d{4})$/', $date_string, $matches)) {
            return strtotime($matches[2] . '-' . sprintf('%02d', $matches[1]) . '-01'); // YYYY-MM-DD
        }
        // Handle YYYY only
        if (preg_match('/^(\d{4})$/', $date_string, $matches)) {
            return strtotime($matches[1] . '-01-01');
        }
        $time = strtotime($date_string);
        return $time ? $time : 0;
    };

    usort($posts, function($a, $b) use ($parse_date, $atts) {
        $a_start = get_post_meta($a->ID, '_work_start_date', true);
        $a_end = get_post_meta($a->ID, '_work_end_date', true);
        $b_start = get_post_meta($b->ID, '_work_start_date', true);
        $b_end = get_post_meta($b->ID, '_work_end_date', true);

        $a_end_time = $parse_date($a_end ? $a_end : $a_start);
        $b_end_time = $parse_date($b_end ? $b_end : $b_start);

        if ($a_end_time === $b_end_time) {
            $a_start_time = $parse_date($a_start);
            $b_start_time = $parse_date($b_start);
            if ($a_start_time === $b_start_time) {
                return 0;
            }
            if (strtoupper($atts['order']) === 'ASC') {
                return ($a_start_time < $b_start_time) ? -1 : 1;
            } else {
                return ($a_start_time > $b_start_time) ? -1 : 1;
            }
        }

        if (strtoupper($atts['order']) === 'ASC') {
            return ($a_end_time < $b_end_time) ? -1 : 1;
        } else {
            return ($a_end_time > $b_end_time) ? -1 : 1;
        }
    });

    ob_start();
    ?>
    <style>
        .work-timeline {
            position: relative;
            width: 100%;
            padding-left: 2rem;
        }
        .work-timeline::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 5px;
            width: 2px;
            background: var(--border-glass);
        }
        .work-item {
            position: relative;
            margin-bottom: 2.5rem;
            background: var(--bg-card);
            border: var(--border-glass);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            transition: var(--transition-normal);
        }
        .work-item:hover {
            border-color: var(--accent-primary);
            box-shadow: var(--shadow-glow);
            transform: translateY(-3px);
        }
        .work-item::before {
            content: '';
            position: absolute;
            left: -2rem;
            top: 1.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--accent-primary);
            box-shadow: 0 0 10px var(--accent-primary);
        }
        .work-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            margin-bottom: 1rem;
            gap: 1rem;
        }
        .work-title-area {
            flex: 1 1 250px;
        }
        .work-role {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.25rem 0;
        }
        .work-company {
            font-size: 1rem;
            color: var(--accent-secondary);
            font-weight: 600;
            margin: 0;
        }
        .work-meta-area {
            text-align: left;
        }
        @media (min-width: 768px) {
            .work-meta-area {
                text-align: right;
            }
        }
        .work-duration {
            display: inline-block;
            background: rgba(255,255,255,0.05);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.875rem;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }
        .work-location {
            font-size: 0.875rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        @media (min-width: 768px) {
            .work-location {
                justify-content: flex-end;
            }
        }
        .work-content {
            color: var(--text-secondary);
            line-height: 1.6;
        }
        .work-content ul {
            list-style: none;
            padding-left: 0;
            margin-left: 0;
            margin-bottom: 0;
        }
        .work-content ul li {
            position: relative;
            padding-left: 1.2rem;
            margin-bottom: 0.5rem;
        }
        .work-content ul li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: var(--text-secondary);
            font-size: 1.2em;
            line-height: 1.2;
        }
    </style>
    <div class="work-timeline">
        <?php
        global $post;
        foreach ($posts as $post) : 
            setup_postdata($post);
            $company = get_post_meta(get_the_ID(), '_work_company', true);
            $start_date = get_post_meta(get_the_ID(), '_work_start_date', true);
            $end_date = get_post_meta(get_the_ID(), '_work_end_date', true);
            $location = get_post_meta(get_the_ID(), '_work_location', true);
            $work_type = get_post_meta(get_the_ID(), '_work_type', true);
        ?>
        <div class="work-item">
            <div class="work-header">
                <div class="work-title-area">
                    <h3 class="work-role"><?php the_title(); ?></h3>
                    <?php if ($company) : ?>
                    <p class="work-company"><?php echo esc_html($company); ?></p>
                    <?php endif; ?>
                </div>
                <div class="work-meta-area">
                    <?php if ($start_date || $end_date) : ?>
                    <div class="work-duration">
                        <?php echo esc_html($start_date); ?> <?php echo ($start_date && $end_date) ? '-' : ''; ?> <?php echo esc_html($end_date); ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($location || $work_type) : ?>
                    <div class="work-location">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <?php 
                        $loc_parts = array();
                        if ($location) $loc_parts[] = esc_html($location);
                        if ($work_type) $loc_parts[] = esc_html($work_type);
                        echo implode(' &bull; ', $loc_parts);
                        ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="work-content">
                <?php the_content(); ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('work_experience', 'my_portfolio_work_experience_shortcode');

/**
 * Add Shortcode Help Submenu
 */
function my_portfolio_work_experience_submenu() {
    add_submenu_page(
        'edit.php?post_type=work_experience',
        __('Shortcode Usage', 'my-portfolio-html'),
        __('Shortcode', 'my-portfolio-html'),
        'edit_posts',
        'work-experience-shortcode',
        'my_portfolio_work_experience_shortcode_page'
    );
}
add_action('admin_menu', 'my_portfolio_work_experience_submenu');

function my_portfolio_work_experience_shortcode_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Penggunaan Shortcode Work Experience', 'my-portfolio-html'); ?></h1>
        <div class="notice notice-info" style="padding: 15px; margin-top: 15px;">
            <p style="font-size: 14px; margin-bottom: 10px;"><strong><?php _e('Penggunaan Shortcode:', 'my-portfolio-html'); ?></strong> <?php _e('Anda dapat menampilkannya di halaman atau postingan mana saja dengan memasukkan shortcode berikut ke editor (Gutenberg/Classic):', 'my-portfolio-html'); ?></p>
            
            <ul style="list-style-type: disc; margin-left: 20px; font-size: 14px;">
                <li style="margin-bottom: 10px;">
                    <?php _e('Menampilkan Semua riwayat pekerjaan (berurutan):', 'my-portfolio-html'); ?><br>
                    <div style="display: flex; align-items: center; margin-top: 5px; gap: 10px;">
                        <code id="shortcode-1" style="font-size: 14px; padding: 5px 10px; background: #fff; border: 1px solid #ccc;">[work_experience]</code>
                        <button type="button" class="button copy-btn" data-clipboard-target="#shortcode-1" title="Copy to clipboard">
                            <span class="dashicons dashicons-admin-page"></span> Copy
                        </button>
                    </div>
                </li>
                <li style="margin-bottom: 10px;">
                    <?php _e('Menampilkan riwayat spesifik berdasarkan 1 ID post:', 'my-portfolio-html'); ?><br>
                    <div style="display: flex; align-items: center; margin-top: 5px; gap: 10px;">
                        <code id="shortcode-2" style="font-size: 14px; padding: 5px 10px; background: #fff; border: 1px solid #ccc;">[work_experience id="12"]</code>
                        <button type="button" class="button copy-btn" data-clipboard-target="#shortcode-2" title="Copy to clipboard">
                            <span class="dashicons dashicons-admin-page"></span> Copy
                        </button>
                    </div>
                </li>
                <li style="margin-bottom: 10px;">
                    <?php _e('Menampilkan beberapa ID secara spesifik:', 'my-portfolio-html'); ?><br>
                    <div style="display: flex; align-items: center; margin-top: 5px; gap: 10px;">
                        <code id="shortcode-3" style="font-size: 14px; padding: 5px 10px; background: #fff; border: 1px solid #ccc;">[work_experience id="12,15,20"]</code>
                        <button type="button" class="button copy-btn" data-clipboard-target="#shortcode-3" title="Copy to clipboard">
                            <span class="dashicons dashicons-admin-page"></span> Copy
                        </button>
                    </div>
                </li>
                <li>
                    <?php _e('Mengubah urutan (default: dari terbaru ke terlama / DESC). Gunakan "asc" untuk dari terlama ke terbaru:', 'my-portfolio-html'); ?><br>
                    <div style="display: flex; align-items: center; margin-top: 5px; gap: 10px;">
                        <code id="shortcode-4" style="font-size: 14px; padding: 5px 10px; background: #fff; border: 1px solid #ccc;">[work_experience order="asc"]</code>
                        <button type="button" class="button copy-btn" data-clipboard-target="#shortcode-4" title="Copy to clipboard">
                            <span class="dashicons dashicons-admin-page"></span> Copy
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var copyButtons = document.querySelectorAll('.copy-btn');
            copyButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var targetSelector = this.getAttribute('data-clipboard-target');
                    var targetElement = document.querySelector(targetSelector);
                    if (targetElement) {
                        var textToCopy = targetElement.textContent || targetElement.innerText;
                        navigator.clipboard.writeText(textToCopy).then(function() {
                            var originalHTML = btn.innerHTML;
                            btn.innerHTML = '<span class="dashicons dashicons-saved"></span> Copied!';
                            setTimeout(function() {
                                btn.innerHTML = originalHTML;
                            }, 2000);
                        }).catch(function(err) {
                            console.error('Failed to copy text: ', err);
                        });
                    }
                });
            });
        });
    </script>
    <?php
}
