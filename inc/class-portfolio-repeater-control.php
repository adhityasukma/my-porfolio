<?php
/**
 * Custom Repeater Control for Portfolio Projects
 *
 * @package My_Portfolio_HTML
 */

if (!defined('ABSPATH')) {
    exit;
}

if (class_exists('WP_Customize_Control')) {
    
    class My_Portfolio_Repeater_Control extends WP_Customize_Control {
        
        public $type = 'portfolio_repeater';
        public $fields = array();
        public $items_per_page = 9;
        
        public function __construct($manager, $id, $args = array()) {
            parent::__construct($manager, $id, $args);
            
            if (isset($args['fields'])) {
                $this->fields = $args['fields'];
            }
            
            if (isset($args['items_per_page'])) {
                $this->items_per_page = $args['items_per_page'];
            }
        }
        
        public function enqueue() {
            wp_enqueue_style(
                'portfolio-repeater-control',
                get_template_directory_uri() . '/assets/css/customizer-repeater.css',
                array(),
                '1.0.0'
            );
            
            wp_enqueue_script(
                'portfolio-repeater-control',
                get_template_directory_uri() . '/assets/js/customizer-repeater.js',
                array('jquery', 'customize-controls'),
                '1.0.0',
                true
            );
            
            wp_localize_script('portfolio-repeater-control', 'portfolioRepeater', array(
                'addText' => __('Add Project', 'my-portfolio-html'),
                'deleteText' => __('Delete', 'my-portfolio-html'),
                'confirmDelete' => __('Are you sure you want to delete this project?', 'my-portfolio-html'),
                'itemsPerPage' => $this->items_per_page,
                'prevText' => __('Previous', 'my-portfolio-html'),
                'nextText' => __('Next', 'my-portfolio-html'),
                'pageText' => __('Page', 'my-portfolio-html'),
                'ofText' => __('of', 'my-portfolio-html'),
            ));
        }
        
        public function render_content() {
            $value = $this->value();
            $projects = json_decode($value, true);
            
            if (!is_array($projects)) {
                $projects = array();
            }
            ?>
            <div class="portfolio-repeater-control">
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                
                <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php endif; ?>
                
                <input type="hidden" 
                       <?php $this->link(); ?> 
                       class="portfolio-repeater-value"
                       value="<?php echo esc_attr($value); ?>">
                
                <div class="portfolio-repeater-container">
                    <div class="portfolio-repeater-items" data-items-per-page="<?php echo esc_attr($this->items_per_page); ?>">
                        <?php 
                        if (!empty($projects)) {
                            foreach ($projects as $index => $project) {
                                $this->render_project_item($index, $project);
                            }
                        }
                        ?>
                    </div>
                    
                    <div class="portfolio-repeater-pagination">
                        <button type="button" class="button pagination-prev" disabled>
                            <span class="dashicons dashicons-arrow-left-alt2"></span>
                            <?php esc_html_e('Previous', 'my-portfolio-html'); ?>
                        </button>
                        <span class="pagination-info">
                            <?php esc_html_e('Page', 'my-portfolio-html'); ?> 
                            <span class="current-page">1</span> 
                            <?php esc_html_e('of', 'my-portfolio-html'); ?> 
                            <span class="total-pages">1</span>
                        </span>
                        <button type="button" class="button pagination-next" disabled>
                            <?php esc_html_e('Next', 'my-portfolio-html'); ?>
                            <span class="dashicons dashicons-arrow-right-alt2"></span>
                        </button>
                    </div>
                    
                    <button type="button" class="button button-primary portfolio-repeater-add">
                        <span class="dashicons dashicons-plus-alt2"></span>
                        <?php esc_html_e('Add Project', 'my-portfolio-html'); ?>
                    </button>
                </div>
                
                <!-- Template for new items -->
                <script type="text/html" id="tmpl-portfolio-repeater-item">
                    <?php $this->render_project_item('{{index}}', array()); ?>
                </script>
            </div>
            <?php
        }
        
        private function render_project_item($index, $project) {
            $defaults = array(
                'title' => '',
                'category' => '',
                'description' => '',
                'tech' => '',
                'link' => '',
                'image' => '',
            );
            $project = wp_parse_args($project, $defaults);
            ?>
            <div class="portfolio-repeater-item" data-index="<?php echo esc_attr($index); ?>">
                <div class="repeater-item-header">
                    <span class="repeater-item-title">
                        <span class="dashicons dashicons-portfolio"></span>
                        <?php 
                        $title = !empty($project['title']) ? $project['title'] : sprintf(__('Project %s', 'my-portfolio-html'), is_numeric($index) ? $index + 1 : '{{num}}');
                        ?>
                        <span class="title-text"><?php echo esc_html($title); ?></span>
                    </span>
                    <div class="repeater-item-actions">
                        <button type="button" class="repeater-toggle" title="<?php esc_attr_e('Toggle', 'my-portfolio-html'); ?>">
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </button>
                        <button type="button" class="repeater-delete" title="<?php esc_attr_e('Delete', 'my-portfolio-html'); ?>">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </div>
                </div>
                
                <div class="repeater-item-content" style="display: none;">
                    <div class="repeater-field">
                        <label><?php esc_html_e('Project Title', 'my-portfolio-html'); ?></label>
                        <input type="text" 
                               class="repeater-input" 
                               data-field="title" 
                               value="<?php echo esc_attr($project['title']); ?>"
                               placeholder="<?php esc_attr_e('Enter project title', 'my-portfolio-html'); ?>">
                    </div>
                    
                    <div class="repeater-field">
                        <label><?php esc_html_e('Category', 'my-portfolio-html'); ?></label>
                        <input type="text" 
                               class="repeater-input" 
                               data-field="category" 
                               value="<?php echo esc_attr($project['category']); ?>"
                               placeholder="<?php esc_attr_e('e.g., Laravel, WordPress', 'my-portfolio-html'); ?>">
                    </div>
                    
                    <div class="repeater-field">
                        <label><?php esc_html_e('Description', 'my-portfolio-html'); ?></label>
                        <textarea class="repeater-input" 
                                  data-field="description" 
                                  rows="3"
                                  placeholder="<?php esc_attr_e('Project description', 'my-portfolio-html'); ?>"><?php echo esc_textarea($project['description']); ?></textarea>
                    </div>
                    
                    <div class="repeater-field">
                        <label><?php esc_html_e('Technologies', 'my-portfolio-html'); ?></label>
                        <input type="text" 
                               class="repeater-input" 
                               data-field="tech" 
                               value="<?php echo esc_attr($project['tech']); ?>"
                               placeholder="<?php esc_attr_e('PHP, Laravel, MySQL (comma-separated)', 'my-portfolio-html'); ?>">
                    </div>
                    
                    <div class="repeater-field">
                        <label><?php esc_html_e('Project URL', 'my-portfolio-html'); ?></label>
                        <input type="url" 
                               class="repeater-input" 
                               data-field="link" 
                               value="<?php echo esc_attr($project['link']); ?>"
                               placeholder="<?php esc_attr_e('https://github.com/...', 'my-portfolio-html'); ?>">
                    </div>
                    
                    <div class="repeater-field">
                        <label><?php esc_html_e('Image URL', 'my-portfolio-html'); ?></label>
                        <div class="repeater-image-field">
                            <input type="text" 
                                   class="repeater-input repeater-image-url" 
                                   data-field="image" 
                                   value="<?php echo esc_attr($project['image']); ?>"
                                   placeholder="<?php esc_attr_e('Image URL or select from media', 'my-portfolio-html'); ?>">
                            <button type="button" class="button repeater-upload-image">
                                <span class="dashicons dashicons-upload"></span>
                            </button>
                        </div>
                        <?php if (!empty($project['image'])) : ?>
                        <div class="repeater-image-preview">
                            <img src="<?php echo esc_url($project['image']); ?>" alt="">
                        </div>
                        <?php else : ?>
                        <div class="repeater-image-preview" style="display: none;">
                            <img src="" alt="">
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
