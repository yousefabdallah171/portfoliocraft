<?php
/**
 * portfoliocraft Page Class
 * 
 * This class handles all page-related functionality including site loader,
 * page titles, post titles, breadcrumbs, and pagination for the portfoliocraft theme.
 * All methods are properly secured and follow WordPress coding standards.
 * 
 * @package portfoliocraft
 * @version 1.0.0
 */

// Security: Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Page Handler Class
 * 
 * Handles various page elements and functionality including:
 * - Site preloader/loader
 * - Page and post titles
 * - Breadcrumb navigation
 * - Pagination
 * - Link pages for multi-page posts
 */
if (!class_exists('portfoliocraft_Page')) {

    class portfoliocraft_Page
    {
        /**
         * Display Site Loader/Preloader
         * 
         * Renders the site loading animation based on theme options
         * Supports multiple loader styles with proper escaping
         * 
         * @return void
         */
        public function get_site_loader() {
            // Get loader settings from theme options
            $site_loader = portfoliocraft()->get_theme_opt('site_loader', 'off');
            
            if ($site_loader == 'on') : 
                $site_loader_style = portfoliocraft()->get_theme_opt('site_loader_style', 'loader-default');
                // Sanitize loader style for security
                $site_loader_style = sanitize_html_class($site_loader_style);
            ?>
                <div id="preloader" class="preloader <?php echo esc_attr($site_loader_style); ?>">
                    <?php if ($site_loader_style === 'loader-style1') : ?>
                        <!-- Custom loader style with animated lines -->
                        <div class="line"></div>
                        <div class="split top"></div>
                        <div class="split bottom"></div>
                    <?php else: ?>
                        <!-- Default bouncing loader -->
                        <div class="pxl-loader-spinner">
                            <div class="pxl-loader-bounce1"></div>
                            <div class="pxl-loader-bounce2"></div>
                            <div class="pxl-loader-bounce3"></div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif;
        }

        /**
         * Display Link Pages Navigation
         * 
         * Renders pagination for multi-page posts using wp_link_pages()
         * with proper styling and security
         * 
         * @return void
         */
        public function get_link_pages() {
            wp_link_pages(array(
                'before'      => '<div class="page-links">',
                'after'       => '</div>',
                'link_before' => '<span>',
                'link_after'  => '</span>',
            )); 
        }

        /**
         * Display Post Title Section
         * 
         * Renders post title based on post type and configuration
         * Supports both default and Elementor builder modes
         * Handles different post types: portfolio, service, team, product, search
         * 
         * @return void
         */
        public function get_post_title() {
            // Default post title settings
            $post_title_mode = portfoliocraft()->get_theme_opt('post_title_mode', '');
            $post_title_layout = (int)portfoliocraft()->get_theme_opt('post_title_layout', 0);
            
            // Override settings based on post type
            if (is_singular('portfolio')) {
                $post_title_mode = portfoliocraft()->get_theme_opt('portfolio_title_mode', '');
                $post_title_layout = (int)portfoliocraft()->get_theme_opt('portfolio_title_layout', 0);
            } elseif (is_singular('service')) {
                $post_title_mode = portfoliocraft()->get_theme_opt('service_title_mode', '');
                $post_title_layout = (int)portfoliocraft()->get_theme_opt('service_title_layout', 0);
            } elseif (is_singular('team')) {
                $post_title_mode = portfoliocraft()->get_theme_opt('team_title_mode', '');
                $post_title_layout = (int)portfoliocraft()->get_theme_opt('team_title_layout', 0);
            } elseif (is_singular('product')) {
                $post_title_mode = portfoliocraft()->get_theme_opt('product_title_mode', '');
                $post_title_layout = (int)portfoliocraft()->get_theme_opt('product_title_layout', 0);
            } elseif (is_search()) {
                $post_title_mode = portfoliocraft()->get_theme_opt('search_title_mode', '');
                $post_title_layout = (int)portfoliocraft()->get_theme_opt('search_title_layout', 0);
            }
            
            // Exit early if title is disabled
            if ($post_title_mode === 'disable') return;

            // Determine if using builder mode
            $is_builder = false;
            $id = 'pxl-post-title-default';

            if ($post_title_mode === 'builder' && $post_title_layout > 0 && 
                class_exists('pxltheme_Core') && is_callable('Elementor\Plugin::instance')) {
                $is_builder = true;
                $id = 'pxl-post-title-builder';
            }
            ?>
            <section id="<?php echo esc_attr($id); ?>" class="pxl-post-title">
                <div class="pxl-post-title-inner">
                    <?php if ($is_builder) : ?>
                        <!-- Render Elementor template -->
                        <?php echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display($post_title_layout); ?>
                    <?php else : ?>
                        <!-- Render default title -->
                        <?php 
                            $title = $this->get_title();
                            $post_title = $title['title'];    
                        ?>
                        <h1 class="pxl-post-title">
                            <span class="pxl-title-text">
                                <?php echo esc_html($post_title); ?>
                            </span>
                        </h1>
                    <?php endif; ?>
                </div>
            </section>
            <?php
        }

        /**
         * Display Page Title Section
         * 
         * Renders page title based on configuration mode (builder/default)
         * Supports different layouts for regular pages and WooCommerce shop
         * 
         * @return void
         */
        public function get_page_title() {
            $titles = $this->get_title();
            $pt_mode = portfoliocraft()->get_opt('pt_mode');
            $pt_mode_product = portfoliocraft()->get_opt('pt_mode_product');
            $ptitle_layout = (int)portfoliocraft()->get_opt('ptitle_layout');
            $ptitle_layout_product = (int)portfoliocraft()->get_opt('ptitle_layout_product');
            
            // Builder mode for regular pages
            if ($pt_mode == 'bd' && $ptitle_layout > 0 && 
                class_exists('pxltheme_Core') && is_callable('Elementor\Plugin::instance')) { ?>
                <div id="pxl-page-title-elementor">
                    <?php echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display($ptitle_layout); ?>
                </div>
                <?php 
            } 
            // Builder mode for WooCommerce products
            elseif ($pt_mode_product == 'bd' && $ptitle_layout_product > 0 && 
                    class_exists('pxltheme_Core') && is_callable('Elementor\Plugin::instance')) { ?>
                <?php if (class_exists('WooCommerce') && (is_shop() || is_singular('product'))) : ?>
                    <div id="pxl-page-title-elementor" class="pxl-page-title-shop">
                        <?php echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display($ptitle_layout_product); ?>
                    </div>
                <?php endif; ?>
            <?php } 
            // Default mode
            elseif ($pt_mode == 'df') {
                $ptitle_breadcrumb_on = portfoliocraft()->get_opt('ptitle_breadcrumb_on', '1'); ?>
                <div id="pxl-page-title-default">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <h1 class="pxl-page-title"><?php echo portfoliocraft_html($titles['title']); ?></h1>
                            </div>
                            <div class="ptitle-col-right col-sm-12 col-md-6 col-lg-6">
                                <?php if ($ptitle_breadcrumb_on == '1') : ?>
                                    <?php $this->get_breadcrumb(); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } 
        }

        /**
         * Get Page/Post Title
         * 
         * Retrieves the appropriate title for different page types
         * Handles custom titles, archive titles, and special cases
         * 
         * @return array Array containing the title
         */
        public function get_title() {
            $title = '';
            
            // Handle non-archive pages
            if (!is_archive()) {
                // Posts page (blog home)
                if (is_home()) {
                    if (!is_front_page() && $page_for_posts = get_option('page_for_posts')) {
                        $title = get_post_meta($page_for_posts, 'custom_title', true);
                        if (empty($title)) {
                            $title = get_the_title($page_for_posts);
                        }
                    }
                    if (is_front_page()) {
                        $title = esc_html__('Blog', 'portfoliocraft');
                    }
                } 
                // Single page
                elseif (is_page()) {
                    $title = get_post_meta(get_the_ID(), 'custom_title', true);
                    if (!$title) {
                        $title = get_the_title();
                    }
                } 
                // 404 error page
                elseif (is_404()) {
                    $title = esc_html__('404 Error', 'portfoliocraft');
                } 
                // Search results page
                elseif (is_search()) {
                    $title = esc_html__('Search results', 'portfoliocraft');
                } 
                // LearnPress course page
                elseif (is_singular('lp_course')) {
                    $title = esc_html__('Course', 'portfoliocraft');
                } 
                // Other single posts
                else {
                    $title = get_post_meta(get_the_ID(), 'custom_title', true);
                    if (!$title) {
                        $title = get_the_title();
                    }
                }
            } 
            // Handle archive pages
            else {
                $title = get_the_archive_title();
                
                // Special handling for WooCommerce shop page
                if (class_exists('WooCommerce') && is_shop()) {
                    $title = get_post_meta(wc_get_page_id('shop'), 'custom_title', true);
                    if (!$title) {
                        $title = get_the_title(get_option('woocommerce_shop_page_id'));
                    }
                }
            }

            return array(
                'title' => $title,
            );
        }

        /**
         * Display Breadcrumb Navigation
         * 
         * Renders breadcrumb navigation using CASE_Breadcrumb class
         * Supports custom breadcrumb titles for different post types
         * Includes proper escaping and validation
         * 
         * @return void
         */
        public function get_breadcrumb() {
            // Check if breadcrumb class exists
            if (!class_exists('CASE_Breadcrumb')) {
                return;
            }

            $breadcrumb = new CASE_Breadcrumb();
            $entries = $breadcrumb->get_entries();

            if (empty($entries)) {
                return;
            }

            ob_start();

            foreach ($entries as $entry) {
                // Parse entry with defaults
                $entry = wp_parse_args($entry, array(
                    'label' => '',
                    'url'   => ''
                ));

                $entry_label = $entry['label'];
                
                // Handle custom blog title from URL parameter
                if (!empty($_GET['blog_title'])) {
                    $blog_title = sanitize_text_field($_GET['blog_title']);
                    $custom_title = explode('_', $blog_title);
                    $arr_str_b = array();
                    foreach ($custom_title as $index => $value) {
                        $arr_str_b[$index] = sanitize_text_field($value);
                    }
                    $str = implode(' ', $arr_str_b);
                    $entry_label = $str;
                }

                if (empty($entry_label)) {
                    continue;
                }

                echo '<li>';
                
                // Render link or current item
                if (!empty($entry['url'])) {
                    printf(
                        '<a class="pxl-breadcrumb-link" href="%1$s">%2$s</a>',
                        esc_url($entry['url']),
                        esc_html($entry_label)
                    );
                } else {
                    // Handle custom breadcrumb titles for different post types
                    if (is_singular('portfolio')) {
                        $single_post_title = portfoliocraft()->get_theme_opt('sg_portfolio_breadcrumb', 'default');
                        $custom_single_post_title = portfoliocraft()->get_theme_opt('custom_sg_portfolio_breadcrumb', 'Portfolio Details');
                    } elseif (is_singular('service')) {
                        $single_post_title = portfoliocraft()->get_theme_opt('sg_service_breadcrumb', 'default');
                        $custom_single_post_title = portfoliocraft()->get_theme_opt('custom_sg_service_breadcrumb', 'Service Details');
                    } elseif (is_singular('product')) {
                        $single_post_title = portfoliocraft()->get_theme_opt('sg_product_breadcrumb', 'default');
                        $custom_single_post_title = portfoliocraft()->get_theme_opt('custom_sg_product_breadcrumb', 'Product Single');
                    } elseif (is_singular('post')) {
                        $single_post_title = portfoliocraft()->get_theme_opt('sg_post_breadcrumb', 'default');
                        $custom_single_post_title = portfoliocraft()->get_theme_opt('custom_sg_post_breadcrumb', 'Blog Details');
                    } elseif (is_singular('team')) {
                        $single_post_title = portfoliocraft()->get_theme_opt('sg_team_breadcrumb', 'default');
                        $custom_single_post_title = portfoliocraft()->get_theme_opt('custom_sg_team_breadcrumb', 'Team Single');
                    } elseif (is_search()) {
                        $single_post_title = portfoliocraft()->get_theme_opt('sg_search_breadcrumb', 'default');
                        $custom_single_post_title = portfoliocraft()->get_theme_opt('custom_sg_search_breadcrumb', 'Search Results');
                    }
                    
                    // Use custom title if configured
                    if (isset($single_post_title) && $single_post_title === 'custom' && 
                        (is_single() || is_search()) && isset($custom_single_post_title)) {
                        $entry_label = $custom_single_post_title;
                    }
                    
                    printf('<span class="pxl-breadcrumb-current">%s</span>', esc_html($entry_label));
                }

                echo '</li>';
                echo '<li class="pxl-breadcrumb-separator">.</li>';
            }

            $output = ob_get_clean();

            if ($output) {
                printf('<ul class="pxl-breadcrumb">%s</ul>', wp_kses_post($output));
            }
        }

        /**
         * Display Pagination
         * 
         * Renders pagination navigation with custom styling
         * Supports both regular and AJAX pagination
         * Includes proper number formatting and navigation arrows
         * 
         * @param WP_Query|null $query The query object to paginate
         * @param bool $ajax Whether to enable AJAX pagination
         * @return void
         */
        public function get_pagination($query = null, $ajax = false) {
            
            // Enable AJAX pagination filter if requested
            if ($ajax) {
                add_filter('paginate_links', 'portfoliocraft_ajax_paginate_links');
            }

            // Use global query if none provided
            if (empty($query)) {
                $query = $GLOBALS['wp_query'];
            }

            // Exit if pagination not needed
            if (empty($query->max_num_pages) || !is_numeric($query->max_num_pages) || $query->max_num_pages < 2) {
                return;
            }

            // Get current page number
            $paged = $query->get('paged', '');

            if (!$paged && is_front_page() && !is_home()) {
                $paged = $query->get('page', '');
            }

            $paged = $paged ? intval($paged) : 1;

            // Build pagination URL structure
            $pagenum_link = html_entity_decode(get_pagenum_link());
            $query_args = array();
            $url_parts = explode('?', $pagenum_link);

            if (isset($url_parts[1])) {
                wp_parse_str($url_parts[1], $query_args);
            }

            $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
            $pagenum_link = trailingslashit($pagenum_link) . '%_%';
            
            // Add leading zero for single digit pages
            $prefix_number = $paged < 10 ? '0' : null;
            
            // Configure pagination arguments
            $paginate_links_args = array(
                'base'     => $pagenum_link,
                'total'    => $query->max_num_pages,
                'current'  => $paged,
                'mid_size' => 1,
                'add_args' => array_map('urlencode', $query_args),
                'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
                                    <path transform="scale(-1,1) translate(-13,0)" d="M4.09569 11.2054C3.9325 11.0488 3.84082 10.8365 3.84082 10.615C3.84082 10.3936 3.9325 10.1812 4.09569 10.0246L8.40461 5.89077L4.09569 1.75696C3.93712 1.59945 3.84938 1.3885 3.85136 1.16954C3.85335 0.950574 3.9449 0.741117 4.10629 0.58628C4.26769 0.431443 4.48602 0.343616 4.71426 0.341713C4.9425 0.33981 5.16238 0.423985 5.32656 0.576108L10.2509 5.30035C10.4141 5.45696 10.5058 5.66933 10.5058 5.89077C10.5058 6.11222 10.4141 6.32459 10.2509 6.4812L5.32656 11.2054C5.16332 11.362 4.94195 11.45 4.71112 11.45C4.4803 11.45 4.25893 11.362 4.09569 11.2054Z" fill="currentcolor"/>
                                </svg>',
                'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13">
                                    <path d="M4.09862 11.2054C3.93543 11.0488 3.84375 10.8365 3.84375 10.615C3.84375 10.3936 3.93543 10.1812 4.09862 10.0246L8.40754 5.89077L4.09862 1.75696C3.94005 1.59945 3.85231 1.3885 3.85429 1.16954C3.85628 0.950574 3.94783 0.741117 4.10922 0.58628C4.27062 0.431443 4.48895 0.343616 4.71719 0.341713C4.94542 0.33981 5.16531 0.423985 5.32949 0.576108L10.2538 5.30035C10.417 5.45696 10.5087 5.66933 10.5087 5.89077C10.5087 6.11222 10.417 6.32459 10.2538 6.4812L5.32949 11.2054C5.16625 11.362 4.94488 11.45 4.71405 11.45C4.48323 11.45 4.26186 11.362 4.09862 11.2054Z" fill="currentcolor"/>
                                </svg>',
                'before_page_number' => '<span>' . $prefix_number,
                'after_page_number' => '</span>',
            );
            
            // Adjust format for AJAX pagination
            if ($ajax) {
                $paginate_links_args['format'] = '?page=%#%';
            }
            
            // Generate pagination links
            $links = paginate_links($paginate_links_args);
            
            if ($links) :
            ?>
            <nav class="pxl-pagination-wrap <?php echo esc_attr($ajax ? 'ajax' : ''); ?>">
                <?php echo wp_kses_post($links); ?>
            </nav>
            <?php
            endif;
        }
    }
}
