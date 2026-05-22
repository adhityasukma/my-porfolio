<?php
/**
 * Visitor Tracking Class
 * Tracks unique visitors by IP address per hour
 *
 * @package My_Portfolio_HTML
 */

if (!defined('ABSPATH')) {
    exit;
}

class My_Portfolio_Visitor_Tracker {
    
    private static $instance = null;
    private $table_name;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'portfolio_visitors';
        
        // Create table on theme activation
        add_action('after_switch_theme', array($this, 'create_table'));
        
        // Also create table on init if it doesn't exist (for existing theme)
        add_action('init', array($this, 'maybe_create_table'));
        
        // Track visitor on homepage
        add_action('wp', array($this, 'track_visitor'));
        
        // Add admin dashboard widget
        add_action('wp_dashboard_setup', array($this, 'add_dashboard_widget'));
        
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Handle admin actions early (before headers sent)
        add_action('admin_init', array($this, 'handle_admin_actions'));
    }
    
    public function maybe_create_table() {
        global $wpdb;
        
        // Check if table exists
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") === $this->table_name;
        
        if (!$table_exists) {
            $this->create_table();
        } else {
            // Check if country column exists, if not add it
            $column_exists = $wpdb->get_var("SHOW COLUMNS FROM {$this->table_name} LIKE 'country'");
            if (!$column_exists) {
                $wpdb->query("ALTER TABLE {$this->table_name} ADD COLUMN country varchar(100) DEFAULT '' AFTER user_agent");
            }
            
            // Check if domain column exists, if not add it
            $domain_exists = $wpdb->get_var("SHOW COLUMNS FROM {$this->table_name} LIKE 'domain'");
            if (!$domain_exists) {
                $wpdb->query("ALTER TABLE {$this->table_name} ADD COLUMN domain varchar(255) DEFAULT '' AFTER country");
            }
        }
    }
    
    public function create_table() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            ip_address varchar(45) NOT NULL,
            visit_date date NOT NULL,
            visit_hour tinyint(2) NOT NULL,
            visit_count int(11) NOT NULL DEFAULT 1,
            first_visit datetime NOT NULL,
            last_visit datetime NOT NULL,
            user_agent text,
            country varchar(100) DEFAULT '',
            domain varchar(255) DEFAULT '',
            PRIMARY KEY (id),
            UNIQUE KEY unique_visitor (ip_address, visit_date, visit_hour, domain),
            KEY visit_date (visit_date),
            KEY visit_hour (visit_hour),
            KEY domain (domain)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Get visitor IP address
     */
    private function get_visitor_ip() {
        $ip = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return sanitize_text_field(trim($ip));
    }
    
    /**
     * Get country from IP address using free geolocation API
     */
    private function get_country_from_ip($ip) {
        // Skip for localhost/private IPs
        if ($ip === '127.0.0.1' || $ip === '::1' || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
            return 'Local';
        }
        
        // Use transient cache to avoid too many API calls
        $cache_key = 'visitor_country_' . md5($ip);
        $cached = get_transient($cache_key);
        
        if ($cached !== false) {
            return $cached;
        }
        
        // Use ip-api.com (free, no API key needed, 45 requests/minute limit)
        $response = wp_remote_get("http://ip-api.com/json/{$ip}?fields=status,country", array(
            'timeout' => 5,
            'sslverify' => false,
        ));
        
        if (is_wp_error($response)) {
            return '';
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (!empty($data['status']) && $data['status'] === 'success' && !empty($data['country'])) {
            $country = sanitize_text_field($data['country']);
            // Cache for 24 hours
            set_transient($cache_key, $country, DAY_IN_SECONDS);
            return $country;
        }
        
        return '';
    }
    
    /**
     * Track visitor on homepage access
     */
    public function track_visitor() {
        // Only track on frontend homepage
        if (is_admin() || !is_front_page()) {
            return;
        }
        
        // Don't track bots
        if ($this->is_bot()) {
            return;
        }
        
        global $wpdb;
        
        $ip = $this->get_visitor_ip();
        if (empty($ip)) {
            return;
        }
        
        $now = current_time('mysql');
        $date = current_time('Y-m-d');
        $hour = (int) current_time('G');
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';
        
        // Get current domain
        $domain = $this->get_current_domain();
        
        // Try to update existing record (now includes domain in unique check)
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT id, country FROM {$this->table_name} WHERE ip_address = %s AND visit_date = %s AND visit_hour = %d AND domain = %s",
            $ip, $date, $hour, $domain
        ));
        
        if ($existing) {
            // Update visit count and last visit time
            $update_data = array(
                'visit_count' => $wpdb->get_var($wpdb->prepare(
                    "SELECT visit_count FROM {$this->table_name} WHERE id = %d",
                    $existing->id
                )) + 1,
                'last_visit' => $now,
            );
            $update_format = array('%d', '%s');
            
            // Always try to get country from IP to ensure it's up to date/filled
            $country = $this->get_country_from_ip($ip);
            if (!empty($country)) {
                $update_data['country'] = $country;
                $update_format[] = '%s';
            }
            
            $wpdb->update(
                $this->table_name,
                $update_data,
                array('id' => $existing->id),
                $update_format,
                array('%d')
            );
        } else {
            // Get country for new record
            $country = $this->get_country_from_ip($ip);
            
            // Insert new record
            $wpdb->insert(
                $this->table_name,
                array(
                    'ip_address' => $ip,
                    'visit_date' => $date,
                    'visit_hour' => $hour,
                    'visit_count' => 1,
                    'first_visit' => $now,
                    'last_visit' => $now,
                    'user_agent' => $user_agent,
                    'country' => $country,
                    'domain' => $domain,
                ),
                array('%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%s')
            );
        }
    }
    
    /**
     * Get current domain URL
     */
    private function get_current_domain() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field($_SERVER['HTTP_HOST']) : '';
        return $protocol . $host;
    }
    
    /**
     * Check if visitor is a bot
     */
    private function is_bot() {
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }
        
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $bots = array('bot', 'crawler', 'spider', 'slurp', 'googlebot', 'bingbot', 'yandex', 'baidu');
        
        foreach ($bots as $bot) {
            if (strpos($user_agent, $bot) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get visitor statistics
     */
    public function get_stats($period = 'today') {
        global $wpdb;
        
        switch ($period) {
            case 'today':
                $date_condition = "visit_date = CURDATE()";
                break;
            case 'yesterday':
                $date_condition = "visit_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
                break;
            case 'week':
                $date_condition = "visit_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
                break;
            case 'month':
                $date_condition = "visit_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                break;
            default:
                $date_condition = "visit_date = CURDATE()";
        }
        
        // Unique visitors
        $unique_visitors = $wpdb->get_var(
            "SELECT COUNT(DISTINCT ip_address) FROM {$this->table_name} WHERE $date_condition"
        );
        
        // Total page views
        $total_views = $wpdb->get_var(
            "SELECT SUM(visit_count) FROM {$this->table_name} WHERE $date_condition"
        );
        
        return array(
            'unique_visitors' => (int) $unique_visitors,
            'total_views' => (int) $total_views,
        );
    }
    
    /**
     * Get hourly stats for today
     */
    public function get_hourly_stats() {
        global $wpdb;
        
        $results = $wpdb->get_results(
            "SELECT visit_hour, COUNT(DISTINCT ip_address) as unique_visitors, SUM(visit_count) as total_views
             FROM {$this->table_name}
             WHERE visit_date = CURDATE()
             GROUP BY visit_hour
             ORDER BY visit_hour ASC"
        );
        
        // Fill in missing hours with zeros
        $stats = array();
        for ($i = 0; $i < 24; $i++) {
            $stats[$i] = array('unique_visitors' => 0, 'total_views' => 0);
        }
        
        foreach ($results as $row) {
            $stats[$row->visit_hour] = array(
                'unique_visitors' => (int) $row->unique_visitors,
                'total_views' => (int) $row->total_views,
            );
        }
        
        return $stats;
    }
    
    /**
     * Add dashboard widget
     */
    public function add_dashboard_widget() {
        wp_add_dashboard_widget(
            'portfolio_visitor_stats',
            __('Homepage Visitor Statistics', 'my-portfolio-html'),
            array($this, 'render_dashboard_widget')
        );
    }
    
    /**
     * Render dashboard widget
     */
    public function render_dashboard_widget() {
        $today = $this->get_stats('today');
        $yesterday = $this->get_stats('yesterday');
        $week = $this->get_stats('week');
        $hourly = $this->get_hourly_stats();
        $current_hour = (int) current_time('G');
        
        ?>
        <style>
            .visitor-stats-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
                margin-bottom: 20px;
            }
            .visitor-stat-box {
                background: #f0f0f1;
                padding: 15px;
                border-radius: 8px;
                text-align: center;
            }
            .visitor-stat-box h3 {
                margin: 0 0 5px;
                font-size: 24px;
                color: #1d2327;
            }
            .visitor-stat-box p {
                margin: 0;
                color: #646970;
                font-size: 12px;
            }
            .hourly-chart {
                display: flex;
                align-items: flex-end;
                height: 100px;
                gap: 2px;
                margin-top: 15px;
                padding: 10px 0;
                border-top: 1px solid #ddd;
            }
            .hourly-bar {
                flex: 1;
                background: #7c3aed;
                min-height: 2px;
                border-radius: 2px 2px 0 0;
                position: relative;
            }
            .hourly-bar.current {
                background: #10b981;
            }
            .hourly-bar:hover::after {
                content: attr(data-tooltip);
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                background: #1d2327;
                color: #fff;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 11px;
                white-space: nowrap;
                z-index: 100;
            }
            .chart-labels {
                display: flex;
                justify-content: space-between;
                font-size: 10px;
                color: #646970;
            }
        </style>
        
        <div class="visitor-stats-grid">
            <div class="visitor-stat-box">
                <h3><?php echo number_format($today['unique_visitors']); ?></h3>
                <p><?php _e('Today (Unique)', 'my-portfolio-html'); ?></p>
            </div>
            <div class="visitor-stat-box">
                <h3><?php echo number_format($yesterday['unique_visitors']); ?></h3>
                <p><?php _e('Yesterday', 'my-portfolio-html'); ?></p>
            </div>
            <div class="visitor-stat-box">
                <h3><?php echo number_format($week['unique_visitors']); ?></h3>
                <p><?php _e('Last 7 Days', 'my-portfolio-html'); ?></p>
            </div>
        </div>
        
        <strong><?php _e('Hourly Visitors Today', 'my-portfolio-html'); ?></strong>
        <div class="hourly-chart">
            <?php
            $max = max(array_column($hourly, 'unique_visitors'));
            $max = $max > 0 ? $max : 1;
            
            foreach ($hourly as $hour => $data) {
                $height = ($data['unique_visitors'] / $max) * 100;
                $height = max($height, 2);
                $is_current = ($hour === $current_hour) ? 'current' : '';
                $tooltip = sprintf('%02d:00 - %d visitors', $hour, $data['unique_visitors']);
                ?>
                <div class="hourly-bar <?php echo $is_current; ?>" 
                     style="height: <?php echo $height; ?>%;"
                     data-tooltip="<?php echo esc_attr($tooltip); ?>">
                </div>
                <?php
            }
            ?>
        </div>
        <div class="chart-labels">
            <span>00:00</span>
            <span>06:00</span>
            <span>12:00</span>
            <span>18:00</span>
            <span>23:00</span>
        </div>
        
        <p style="margin-top: 15px; text-align: center;">
            <a href="<?php echo admin_url('admin.php?page=portfolio-visitor-stats'); ?>" class="button">
                <?php _e('View Full Statistics', 'my-portfolio-html'); ?>
            </a>
        </p>
        <?php
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'themes.php',
            __('Visitor Statistics', 'my-portfolio-html'),
            __('Visitor Stats', 'my-portfolio-html'),
            'manage_options',
            'portfolio-visitor-stats',
            array($this, 'render_admin_page')
        );
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        global $wpdb;
        
        $today = $this->get_stats('today');
        $yesterday = $this->get_stats('yesterday');
        $week = $this->get_stats('week');
        $month = $this->get_stats('month');
        $hourly = $this->get_hourly_stats();
        
        // Filter parameters
        $per_page = isset($_GET['show']) ? sanitize_text_field($_GET['show']) : '10';
        $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : '';
        $date_to = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : '';
        
        // Build WHERE clause
        $where = "1=1";
        if (!empty($date_from)) {
            $where .= $wpdb->prepare(" AND visit_date >= %s", $date_from);
        }
        if (!empty($date_to)) {
            $where .= $wpdb->prepare(" AND visit_date <= %s", $date_to);
        }
        
        // Get total count
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE $where");
        
        // Pagination
        if ($per_page === 'all') {
            $limit = '';
            $total_pages = 1;
        } else {
            $per_page_int = intval($per_page);
            $offset = ($paged - 1) * $per_page_int;
            $limit = "LIMIT $per_page_int OFFSET $offset";
            $total_pages = ceil($total_items / $per_page_int);
        }
        
        // Get visitors with filters
        $recent_visitors = $wpdb->get_results(
            "SELECT id, ip_address, visit_date, visit_hour, visit_count, first_visit, last_visit, country, domain
             FROM {$this->table_name}
             WHERE $where
             ORDER BY last_visit DESC
             $limit"
        );
        
        ?>
        <div class="wrap">
            <h1><?php _e('Homepage Visitor Statistics', 'my-portfolio-html'); ?></h1>
            
            <?php if (isset($_GET['deleted'])) : ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php printf(__('%d visitor record(s) deleted.', 'my-portfolio-html'), intval($_GET['deleted'])); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['generated'])) : ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php printf(__('%d missing countries generated.', 'my-portfolio-html'), intval($_GET['generated'])); ?></p>
                </div>
            <?php endif; ?>
            
            <style>
                .stats-cards {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 20px;
                    margin: 20px 0;
                }
                .stat-card {
                    background: #fff;
                    padding: 25px;
                    border-radius: 8px;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                    text-align: center;
                }
                .stat-card h2 {
                    margin: 0;
                    font-size: 36px;
                    color: #7c3aed;
                }
                .stat-card p {
                    margin: 10px 0 0;
                    color: #666;
                }
                .visitors-table {
                    margin-top: 15px;
                }
                .visitors-table th, .visitors-table td {
                    padding: 10px 15px;
                }
                .visitors-table .check-column {
                    width: 35px;
                    padding: 10px 8px;
                    vertical-align: middle;
                    text-align: center;
                }
                .visitors-table .check-column input[type="checkbox"] {
                    margin: 0;
                    vertical-align: middle;
                }
                .delete-btn {
                    color: #b32d2e;
                    cursor: pointer;
                    text-decoration: none;
                }
                .delete-btn:hover {
                    color: #dc3232;
                    text-decoration: underline;
                }
                .bulk-actions {
                    display: flex;
                    gap: 10px;
                    align-items: center;
                    margin: 15px 0;
                }
                .tablenav {
                    margin: 15px 0;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
            </style>
            
            <div class="stats-cards">
                <div class="stat-card">
                    <h2><?php echo number_format($today['unique_visitors']); ?></h2>
                    <p><?php _e('Today (Unique IPs)', 'my-portfolio-html'); ?></p>
                </div>
                <div class="stat-card">
                    <h2><?php echo number_format($yesterday['unique_visitors']); ?></h2>
                    <p><?php _e('Yesterday', 'my-portfolio-html'); ?></p>
                </div>
                <div class="stat-card">
                    <h2><?php echo number_format($week['unique_visitors']); ?></h2>
                    <p><?php _e('Last 7 Days', 'my-portfolio-html'); ?></p>
                </div>
                <div class="stat-card">
                    <h2><?php echo number_format($month['unique_visitors']); ?></h2>
                    <p><?php _e('Last 30 Days', 'my-portfolio-html'); ?></p>
                </div>
            </div>
            
            <h2><?php _e('Recent Visitors', 'my-portfolio-html'); ?></h2>
            
            <!-- Filters -->
            <div class="tablenav top" style="margin-bottom: 10px;">
                <div class="alignleft actions">
                    <label for="show-filter"><?php _e('Show:', 'my-portfolio-html'); ?></label>
                    <select id="show-filter" onchange="applyFilters()">
                        <option value="10" <?php selected($per_page, '10'); ?>>10</option>
                        <option value="100" <?php selected($per_page, '100'); ?>>100</option>
                        <option value="all" <?php selected($per_page, 'all'); ?>><?php _e('All', 'my-portfolio-html'); ?></option>
                    </select>
                    
                    <label for="date-from" style="margin-left: 15px;"><?php _e('From:', 'my-portfolio-html'); ?></label>
                    <input type="date" id="date-from" value="<?php echo esc_attr($date_from); ?>" onchange="applyFilters()">
                    
                    <label for="date-to" style="margin-left: 10px;"><?php _e('To:', 'my-portfolio-html'); ?></label>
                    <input type="date" id="date-to" value="<?php echo esc_attr($date_to); ?>" onchange="applyFilters()">
                    
                    <?php if (!empty($date_from) || !empty($date_to)) : ?>
                        <a href="<?php echo admin_url('themes.php?page=portfolio-visitor-stats&show=' . $per_page); ?>" class="button"><?php _e('Clear Filter', 'my-portfolio-html'); ?></a>
                    <?php endif; ?>
                    
                    <a href="<?php echo wp_nonce_url(admin_url('themes.php?page=portfolio-visitor-stats&action=generate_countries'), 'generate_countries'); ?>" class="button button-secondary" style="margin-left: 10px; border-color: #7c3aed; color: #7c3aed;"><?php _e('Generate Missing Countries', 'my-portfolio-html'); ?></a>
                </div>
                <div class="alignright">
                    <span class="displaying-num">
                        <?php printf(__('%s items', 'my-portfolio-html'), number_format($total_items)); ?>
                    </span>
                </div>
            </div>
            
            <script>
            function applyFilters() {
                var show = document.getElementById('show-filter').value;
                var dateFrom = document.getElementById('date-from').value;
                var dateTo = document.getElementById('date-to').value;
                var url = '<?php echo admin_url('themes.php?page=portfolio-visitor-stats'); ?>';
                url += '&show=' + show;
                if (dateFrom) url += '&date_from=' + dateFrom;
                if (dateTo) url += '&date_to=' + dateTo;
                window.location.href = url;
            }
            </script>
            
            <form method="post" id="visitors-form">
                <?php wp_nonce_field('visitor_bulk_action', 'visitor_nonce'); ?>
                
                <div class="tablenav top">
                    <div class="bulk-actions">
                        <select name="bulk_action" id="bulk-action-selector">
                            <option value=""><?php _e('Bulk Actions', 'my-portfolio-html'); ?></option>
                            <option value="delete"><?php _e('Delete Selected', 'my-portfolio-html'); ?></option>
                            <option value="delete_all"><?php _e('Delete All Records', 'my-portfolio-html'); ?></option>
                        </select>
                        <input type="submit" class="button action" value="<?php esc_attr_e('Apply', 'my-portfolio-html'); ?>">
                    </div>
                    
                    <?php if ($total_pages > 1) : ?>
                    <div class="tablenav-pages">
                        <span class="pagination-links">
                            <?php
                            $base_url = admin_url('themes.php?page=portfolio-visitor-stats&show=' . $per_page);
                            if (!empty($date_from)) $base_url .= '&date_from=' . $date_from;
                            if (!empty($date_to)) $base_url .= '&date_to=' . $date_to;
                            
                            // First page
                            if ($paged > 1) : ?>
                                <a class="first-page button" href="<?php echo esc_url($base_url . '&paged=1'); ?>">«</a>
                                <a class="prev-page button" href="<?php echo esc_url($base_url . '&paged=' . ($paged - 1)); ?>">‹</a>
                            <?php else : ?>
                                <span class="tablenav-pages-navspan button disabled">«</span>
                                <span class="tablenav-pages-navspan button disabled">‹</span>
                            <?php endif; ?>
                            
                            <span class="paging-input">
                                <?php echo $paged; ?> of <span class="total-pages"><?php echo $total_pages; ?></span>
                            </span>
                            
                            <?php if ($paged < $total_pages) : ?>
                                <a class="next-page button" href="<?php echo esc_url($base_url . '&paged=' . ($paged + 1)); ?>">›</a>
                                <a class="last-page button" href="<?php echo esc_url($base_url . '&paged=' . $total_pages); ?>">»</a>
                            <?php else : ?>
                                <span class="tablenav-pages-navspan button disabled">›</span>
                                <span class="tablenav-pages-navspan button disabled">»</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <table class="wp-list-table widefat fixed striped visitors-table">
                    <thead>
                        <tr>
                            <th class="check-column">
                                <input type="checkbox" id="cb-select-all" />
                            </th>
                            <th><?php _e('IP Address', 'my-portfolio-html'); ?></th>
                            <th><?php _e('Country', 'my-portfolio-html'); ?></th>
                            <th><?php _e('Domain', 'my-portfolio-html'); ?></th>
                            <th><?php _e('Date', 'my-portfolio-html'); ?></th>
                            <th><?php _e('Hour', 'my-portfolio-html'); ?></th>
                            <th><?php _e('Page Views', 'my-portfolio-html'); ?></th>
                            <th><?php _e('First Visit', 'my-portfolio-html'); ?></th>
                            <th><?php _e('Last Visit', 'my-portfolio-html'); ?></th>
                            <th><?php _e('Actions', 'my-portfolio-html'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_visitors)) : ?>
                            <tr>
                                <td colspan="10"><?php _e('No visitors recorded yet.', 'my-portfolio-html'); ?></td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($recent_visitors as $visitor) : ?>
                                <tr>
                                    <td class="check-column">
                                        <input type="checkbox" name="visitor_ids[]" value="<?php echo esc_attr($visitor->id); ?>" />
                                    </td>
                                    <td><code><?php echo esc_html($visitor->ip_address); ?></code></td>
                                    <td><?php echo !empty($visitor->country) ? esc_html($visitor->country) : '<em style="color:#999;">-</em>'; ?></td>
                                    <td><?php echo !empty($visitor->domain) ? '<a href="' . esc_url($visitor->domain) . '" target="_blank">' . esc_html(parse_url($visitor->domain, PHP_URL_HOST)) . '</a>' : '<em style="color:#999;">-</em>'; ?></td>
                                    <td><?php echo esc_html($visitor->visit_date); ?></td>
                                    <td><?php echo sprintf('%02d:00', $visitor->visit_hour); ?></td>
                                    <td><?php echo number_format($visitor->visit_count); ?></td>
                                    <td><?php echo esc_html($visitor->first_visit); ?></td>
                                    <td><?php echo esc_html($visitor->last_visit); ?></td>
                                    <td>
                                        <a href="<?php echo wp_nonce_url(admin_url('themes.php?page=portfolio-visitor-stats&action=delete&id=' . $visitor->id), 'delete_visitor_' . $visitor->id); ?>" 
                                           class="delete-btn" 
                                           onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?', 'my-portfolio-html'); ?>');">
                                            <?php _e('Delete', 'my-portfolio-html'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="check-column">
                                <input type="checkbox" id="cb-select-all-2" />
                            </th>
                            <th><?php _e('IP Address', 'my-portfolio-html'); ?></th>
                            <th><?php _e('Country', 'my-portfolio-html'); ?></th>
                            <th><?php _e('Domain', 'my-portfolio-html'); ?></th>
                            <th><?php _e('Date', 'my-portfolio-html'); ?></th>
                            <th><?php _e('Hour', 'my-portfolio-html'); ?></th>
                            <th><?php _e('Page Views', 'my-portfolio-html'); ?></th>
                            <th><?php _e('First Visit', 'my-portfolio-html'); ?></th>
                            <th><?php _e('Last Visit', 'my-portfolio-html'); ?></th>
                            <th><?php _e('Actions', 'my-portfolio-html'); ?></th>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="tablenav bottom">
                    <div class="bulk-actions">
                        <select name="bulk_action2">
                            <option value=""><?php _e('Bulk Actions', 'my-portfolio-html'); ?></option>
                            <option value="delete"><?php _e('Delete Selected', 'my-portfolio-html'); ?></option>
                            <option value="delete_all"><?php _e('Delete All Records', 'my-portfolio-html'); ?></option>
                        </select>
                        <input type="submit" class="button action" value="<?php esc_attr_e('Apply', 'my-portfolio-html'); ?>">
                    </div>
                </div>
            </form>
            
            <script>
            jQuery(document).ready(function($) {
                // Select all checkboxes
                $('#cb-select-all, #cb-select-all-2').on('change', function() {
                    $('input[name="visitor_ids[]"]').prop('checked', $(this).prop('checked'));
                });
                
                // Sync select all checkboxes
                $('input[name="visitor_ids[]"]').on('change', function() {
                    var allChecked = $('input[name="visitor_ids[]"]:checked').length === $('input[name="visitor_ids[]"]').length;
                    $('#cb-select-all, #cb-select-all-2').prop('checked', allChecked);
                });
                
                // Confirm delete all
                $('#visitors-form').on('submit', function() {
                    var action = $('select[name="bulk_action"]').val() || $('select[name="bulk_action2"]').val();
                    
                    if (action === 'delete_all') {
                        return confirm('<?php esc_attr_e('Are you sure you want to delete ALL visitor records? This cannot be undone.', 'my-portfolio-html'); ?>');
                    }
                    
                    if (action === 'delete' && $('input[name="visitor_ids[]"]:checked').length === 0) {
                        alert('<?php esc_attr_e('Please select at least one record to delete.', 'my-portfolio-html'); ?>');
                        return false;
                    }
                    
                    if (action === 'delete') {
                        return confirm('<?php esc_attr_e('Are you sure you want to delete the selected records?', 'my-portfolio-html'); ?>');
                    }
                    
                    return true;
                });
            });
            </script>
        </div>
        <?php
    }
    

    
    /**
     * Handle admin actions (delete, generate countries)
     */
    public function handle_admin_actions() {
        // Only run on our page
        if (!isset($_GET['page']) || $_GET['page'] !== 'portfolio-visitor-stats') {
            return;
        }
        
        global $wpdb;
        
        // Generate Missing Countries
        if (isset($_GET['action']) && $_GET['action'] === 'generate_countries') {
            if (!wp_verify_nonce($_GET['_wpnonce'], 'generate_countries')) {
                wp_die(__('Security check failed.', 'my-portfolio-html'));
            }
            
            // Get count of remaining records
            $remaining = $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE country = '' OR country IS NULL");
            
            // Get records to process (limit 5 to be very safe with rate limits)
            $records = $wpdb->get_results("SELECT id, ip_address FROM {$this->table_name} WHERE country = '' OR country IS NULL LIMIT 5");
            
            $count = 0;
            foreach ($records as $record) {
                $country = $this->get_country_from_ip($record->ip_address);
                
                // If country found, use it. If not found (API error/private IP), mark as 'Unknown' so we don't retry forever
                if (empty($country)) {
                    $country = 'Unknown';
                }
                
                $wpdb->update(
                    $this->table_name,
                    array('country' => $country),
                    array('id' => $record->id),
                    array('%s'),
                    array('%d')
                );
                $count++;
                
                // Delay between requests
                usleep(200000); // 200ms
            }
            
            $total_processed = isset($_GET['processed_count']) ? intval($_GET['processed_count']) + $count : $count;
            $remaining -= $count; // Approximate remaining
            
            if ($remaining > 0) {
                // Determine redirect URL for next batch
                $next_url = admin_url('themes.php?page=portfolio-visitor-stats&action=generate_countries&processed_count=' . $total_processed . '&_wpnonce=' . $_GET['_wpnonce']);
                
                // Show intermediate progress page
                wp_die(
                    sprintf(
                        '<h1>' . __('Processing GeoIP Data...', 'my-portfolio-html') . '</h1>' .
                        '<p>' . __('Processed %d records so far. %d remaining...', 'my-portfolio-html') . '</p>' .
                        '<p>' . __('Please wait, automatically continuing in 2 seconds to respect API rate limits...', 'my-portfolio-html') . '</p>' .
                        '<script>setTimeout(function(){window.location.href="%s";}, 2000);</script>',
                        $total_processed,
                        $remaining,
                        $next_url
                    ),
                    __('Processing...', 'my-portfolio-html'),
                    array('response' => 200)
                );
            } else {
                // Done
                wp_redirect(admin_url('themes.php?page=portfolio-visitor-stats&generated=' . $total_processed));
                exit;
            }
        }
        
        // Single delete
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            
            if (!wp_verify_nonce($_GET['_wpnonce'], 'delete_visitor_' . $id)) {
                wp_die(__('Security check failed.', 'my-portfolio-html'));
            }
            
            $wpdb->delete($this->table_name, array('id' => $id), array('%d'));
            
            wp_redirect(admin_url('themes.php?page=portfolio-visitor-stats&deleted=1'));
            exit;
        }
        
        // Bulk delete
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['visitor_nonce'])) {
            if (!wp_verify_nonce($_POST['visitor_nonce'], 'visitor_bulk_action')) {
                wp_die(__('Security check failed.', 'my-portfolio-html'));
            }
            
            $action = !empty($_POST['bulk_action']) ? $_POST['bulk_action'] : (!empty($_POST['bulk_action2']) ? $_POST['bulk_action2'] : '');
            
            if ($action === 'delete_all') {
                // Delete all records
                $deleted = $wpdb->query("TRUNCATE TABLE {$this->table_name}");
                wp_redirect(admin_url('themes.php?page=portfolio-visitor-stats&deleted=' . $wpdb->rows_affected));
                exit;
            }
            
            if ($action === 'delete' && !empty($_POST['visitor_ids'])) {
                $ids = array_map('intval', $_POST['visitor_ids']);
                $ids_string = implode(',', $ids);
                
                $deleted = $wpdb->query("DELETE FROM {$this->table_name} WHERE id IN ($ids_string)");
                
                wp_redirect(admin_url('themes.php?page=portfolio-visitor-stats&deleted=' . count($ids)));
                exit;
            }
        }
    }
    
    /**
     * Deprecated: Use handle_admin_actions instead
     */
    public function handle_delete_actions() {
        $this->handle_admin_actions();
    }

}

// Initialize tracker
My_Portfolio_Visitor_Tracker::get_instance();
