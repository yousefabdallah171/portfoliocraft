<?php
/**
 * Breadcrumb Class for portfoliocraft Theme
 * 
 * This class generates hierarchical breadcrumb navigation for different page types
 * Supports custom post types, taxonomies, archives, and various WordPress page types
 * Provides extensive customization options and proper escaping for security
 * 
 * @package portfoliocraft-Themes
 * @version 1.0.0
 */

// Security: Prevent direct access
if (!defined('ABSPATH')) {
    die();
}

/**
 * Breadcrumb Navigation Class
 * 
 * Generates breadcrumb trails for WordPress pages with support for:
 * - Hierarchical pages and categories
 * - Custom post types and taxonomies
 * - Archive pages and search results
 * - Author pages and date archives
 * - Pagination support
 * - Customizable labels and length limits
 */
class CASE_Breadcrumb
{
    /**
     * Array of breadcrumb entries
     * 
     * Each entry contains label and URL for breadcrumb navigation
     * Structure: array('label' => 'Title', 'url' => 'http://example.com')
     *
     * @since 1.0
     * @access protected
     * @var array
     */
    protected $entries = array();

    /**
     * Configuration arguments for breadcrumbs
     * 
     * Contains all settings for breadcrumb generation including
     * labels, length limits, and display options
     *
     * @since 1.0
     * @access protected
     * @var array
     */
    protected $args;

    /**
     * Constructor - Initialize Breadcrumb System
     * 
     * Sets up breadcrumb configuration and generates the breadcrumb trail
     * Handles home page detection and argument validation
     *
     * @param array $args {
     *     Configuration options for breadcrumb generation
     *     
     *     @type string $home_label            Home page label. Set to false to hide. Default: 'Home' or front page title
     *     @type string $404_label             404 error page label. Default: 'Not Found'
     *     @type string $search_results_label  Search results page label. Default: 'Search Results'
     *     @type int    $entry_max_length      Maximum length for entry text. 0 = no limit. Default: 100
     *     @type string $entry_max_length_type Length type: 'words' or 'letters'. Default: 'words'
     *     @type string $more_indicator        Text shown when entry is truncated. Default: '&hellip;'
     * }
     */
    function __construct($args = array())
    {
        // Determine home label based on front page setting
        $page_on_front = get_option('page_on_front');
        if ($page_on_front) {
            $home_label = get_the_title($page_on_front);
        } else {
            $home_label = esc_html__('Home', 'portfoliocraft');
        }
        
        // Set default arguments with proper escaping
        $args = wp_parse_args($args, array(
            'home_label'            => $home_label,
            '404_label'             => esc_html__('Not Found', 'portfoliocraft'),
            'search_results_label'  => esc_html__('Search Results', 'portfoliocraft'),
            'entry_max_length'      => 100,
            'entry_max_length_type' => 'words',
            'more_indicator'        => '&hellip;'
        ));

        // Handle home label disable options
        if ('false' === $args['home_label'] || false === $args['home_label'] || empty($args['home_label'])) {
            $args['home_label'] = false;
        }

        // Validate and sanitize entry length setting
        $args['entry_max_length'] = absint($args['entry_max_length']);
        
        // Validate length type parameter
        if ('words' !== $args['entry_max_length_type'] && 'letters' !== $args['entry_max_length_type']) {
            $args['entry_max_length_type'] = 'words';
        }

        // Sanitize more indicator text
        $args['more_indicator'] = esc_html($args['more_indicator']);

        $this->args = $args;
        
        // Generate the breadcrumb trail
        $this->generate();
    }

    /**
     * Get Breadcrumb Entries
     * 
     * Returns the complete array of breadcrumb entries
     * Each entry contains label and URL information
     *
     * @since 1.0
     * @access public
     * @return array Array of breadcrumb entries
     */
    function get_entries()
    {
        return $this->entries;
    }

    /**
     * Add Entry to Breadcrumb Trail
     * 
     * Adds a new breadcrumb entry with proper text length handling
     * Applies configured length limits and truncation settings
     *
     * @since 1.0
     * @access public
     *
     * @param string $label The breadcrumb entry text/label
     * @param string $url   The breadcrumb entry URL (empty for current page)
     * @return void
     */
    function add_entry($label, $url = '')
    {
        // Initialize entry structure
        $entry = array(
            'label' => $label,
            'url'   => $url
        );

        // Apply length limits if configured
        if ($this->args['entry_max_length'] > 0) {
            switch ($this->args['entry_max_length_type']) {
                case 'letters':
                    // Truncate by character count
                    if (strlen($label) <= $this->args['entry_max_length']) {
                        $entry['label'] = $label;
                    } else {
                        $txt = trim(substr($label, 0, $this->args['entry_max_length'])) . $this->args['more_indicator'];
                        $entry['label'] = $txt;
                    }
                    break;
                
                default:
                    // Truncate by word count (default)
                    $entry['label'] = wp_trim_words($label, $this->args['entry_max_length'], $this->args['more_indicator']);
                    break;
            }
        }

        // Add entry to breadcrumb trail
        $this->entries[] = $entry;
    }

    /**
     * Generate Complete Breadcrumb Trail
     * 
     * Main method that determines page type and generates appropriate breadcrumb trail
     * Handles all WordPress page types and conditions
     * 
     * @since 1.0
     * @access protected
     * @return array Generated breadcrumb entries
     */
    function generate()
    {
        // Define all supported page conditions
        $conditions = array(
            'is_home',
            'is_404',
            'is_attachment',
            'is_single',
            'is_page',
            'is_post_type_archive',
            'is_category',
            'is_tag',
            'is_author',
            'is_date',
            'is_tax',
            'is_search'
        );

        // Skip breadcrumbs on front page
        if (is_front_page()) return array();

        // Add home link if enabled
        if ($this->args['home_label']) {
            $this->add_entry($this->args['home_label'], home_url('/'));
        }

        // Add blog page link for post-related pages
        if (get_option('page_for_posts')) {
            $post_id = get_option('page_for_posts');

            if (is_home() || is_category() || is_tag() || is_singular('post')) {
                $this->add_entry(get_the_title($post_id), get_permalink($post_id));
            }
        }

        // Process each page condition and generate appropriate entries
        foreach ($conditions as $condition) {
            if (call_user_func($condition)) {
                // Call corresponding method (e.g., is_single -> add_single_entry)
                call_user_func(array($this, 'add' . substr($condition, 2) . '_entry'));
                
                // Add pagination if applicable
                if (is_paged()) {
                    $this->add_paged_entry();
                }
                break;
            }
        }

        // Remove URL from last entry (current page should not be linked)
        $entries_count = count($this->entries);
        if ($entries_count >= 1) {
            $this->entries[$entries_count - 1]['url'] = '';
        }
    }

    /**
     * Add Home Page Entry
     * 
     * Placeholder method for home page breadcrumbs
     * Currently does nothing as home is handled in generate()
     * 
     * @since 1.0
     * @access public
     * @return void
     */
    function add_home_entry() {}

    /**
     * Add 404 Error Page Entry
     * 
     * Adds breadcrumb entry for 404 error pages
     * Uses configured 404 label from arguments
     *
     * @since 1.0
     * @access public
     * @return void
     */
    function add_404_entry()
    {
        $this->add_entry($this->args['404_label']);
    }

    /**
     * Add Attachment Page Entry
     * 
     * Generates breadcrumb for attachment pages
     * Includes parent post in breadcrumb trail
     *
     * @since 1.0
     * @access public
     * @return void
     */
    function add_attachment_entry()
    {
        global $post;
        $this->add_single_ancestor_entry($post);
    }

    /**
     * Add Single Post Entry
     * 
     * Generates breadcrumb for single post pages
     * Includes post hierarchy and taxonomy information
     *
     * @since 1.0
     * @access public
     * @return void
     */
    function add_single_entry()
    {
        global $post;
        $this->add_single_ancestor_entry($post);
    }

    /**
     * Add Page Entry with Hierarchy
     * 
     * Generates breadcrumb for pages including parent page hierarchy
     * Handles nested page structures and front page exclusion
     *
     * @since 1.0
     * @access public
     * @param int $page_id Specific page ID, defaults to current page
     * @return void
     */
    function add_page_entry($page_id = 0)
    {
        // Get page object
        if ($page_id) {
            $post = get_post($page_id);
        } else {
            global $post;
        }

        // Build ancestor trail
        $page_ancestors_trail = array();
        $page_parent = $post->post_parent;
        $page_as_front = get_option('page_on_front');

        // Walk up the page hierarchy
        while ($page_parent) {
            // Skip front page in breadcrumb trail
            if ($page_as_front != $page_parent) {
                $page_obj = get_post($page_parent);

                if (!empty($page_obj)) {
                    $page_ancestors_trail[] = array(
                        'label' => get_the_title($page_obj->ID), 
                        'url' => get_permalink($page_obj->ID)
                    );
                }
            }

            $page_parent = empty($page_obj) ? null : $page_obj->post_parent;
        }

        // Add ancestors in correct order (reverse to show hierarchy)
        $page_ancestors_trail = array_reverse($page_ancestors_trail);
        foreach ($page_ancestors_trail as $key => $page_ancestor) {
            $this->add_entry($page_ancestor['label'], $page_ancestor['url']);
        }
        
        // Add current page
        $this->add_entry(get_the_title($page_id), get_permalink($page_id));
    }

    /**
     * Add Post Type Archive Entry
     * 
     * Generates breadcrumb for custom post type archive pages
     * Uses post type labels for breadcrumb text
     *
     * @since 1.0
     * @access public
     * @return void
     */
    function add_post_type_archive_entry()
    {
        $post_type = get_post_type_object(get_post_type());

        if ($post_type) {
            $this->add_entry($post_type->labels->name, get_post_type_archive_link(get_post_type()));
        }
    }

    /**
     * Add Category Archive Entry
     * 
     * Generates breadcrumb for category archive pages
     * Includes category hierarchy
     *
     * @since 1.0
     * @access public
     * @return void
     */
    function add_category_entry()
    {
        $this->add_category_ancestor_entry();
    }

    /**
     * Add Tag Archive Entry
     * 
     * Generates breadcrumb for tag archive pages
     * Includes tag name with "Tag:" prefix
     *
     * @since 1.0
     * @access public
     * @return void
     */
    function add_tag_entry()
    {
        $queried_object = $GLOBALS['wp_query']->get_queried_object();
        $this->add_entry(
            esc_html__('Tag:', 'portfoliocraft') . ' ' . single_tag_title('', false),
            get_tag_link($queried_object->term_id)
        );
    }

    /**
     * Add Author Archive Entry
     * 
     * Generates breadcrumb for author archive pages
     * Includes author display name with "Author:" prefix
     *
     * @since 1.0
     * @access public
     * @return void
     */
    function add_author_entry()
    {
        global $author;
        $userdata = get_userdata($author);
        $this->add_entry(
            esc_html__('Author:', 'portfoliocraft') . ' ' . $userdata->display_name
        );
    }

    /**
     * Add Date Archive Entry
     * 
     * Generates breadcrumb for date-based archive pages
     * Handles year, month, and day archives with proper hierarchy
     *
     * @since 1.0
     * @access public
     * @return void
     */
    function add_date_entry()
    {
        // Add year for all date archives
        if (is_year() || is_month() || is_day()) {
            $this->add_entry(
                get_the_time('Y'),
                get_year_link(get_the_time('Y'))
            );
        }
        
        // Add month for month and day archives
        if (is_month() || is_day()) {
            $this->add_entry(
                get_the_time('F'),
                get_month_link(get_the_time('Y'), get_the_time('m'))
            );
        }
        
        // Add day for day archives
        if (is_day()) {
            $this->add_entry(
                get_the_time('d')
            );
        }
    }

    /**
     * Add Custom Taxonomy Entry
     * 
     * Generates breadcrumb for custom taxonomy archive pages
     * Includes taxonomy hierarchy and proper term relationships
     *
     * @since 1.0
     * @access public
     * @return void
     */
    function add_tax_entry()
    {
        $this_term = $GLOBALS['wp_query']->get_queried_object();
        $taxonomy = get_taxonomy($this_term->taxonomy);
        
        // Initialize entries with taxonomy name
        $entries = array(
            array(
                'label' => $taxonomy->labels->name
            )
        );

        // Add hierarchical term ancestors if taxonomy is hierarchical
        if (is_taxonomy_hierarchical($this_term->taxonomy)) {
            $term_ancestors = array_reverse(get_ancestors($this_term->term_id, $this_term->taxonomy));

            foreach ($term_ancestors as $key => $term_parent) {
                $term_parent = get_term($term_parent, $this_term->taxonomy);
                $entries[] = array(
                    'label' => $term_parent->name,
                    'url'   => get_term_link($term_parent)
                );
            }
        }

        // Add current term
        $entries[] = array(
            'label' => $this_term->name,
            'url'   => get_term_link($this_term)
        );

        /**
         * Filter taxonomy breadcrumb entries
         * 
         * Allows customization of taxonomy breadcrumb structure
         *
         * @since 1.1
         * @param array  $entries   Each entry needs 'label' and 'url' keys
         * @param object $this_term Current taxonomy term object
         */
        $entries = apply_filters('portfoliocraft_breadcrumb_taxonomy', $entries, $this_term);

        // Process and add all entries
        foreach ($entries as $entry) {
            if (!is_array($entry) || empty($entry['label'])) {
                continue;
            }

            if (!isset($entry['url'])) {
                $entry['url'] = '';
            }

            $this->add_entry($entry['label'], $entry['url']);
        }
    }

    /**
     * Add Pagination Entry
     * 
     * Adds page number information for paginated content
     * Handles both 'paged' and 'page' query variables
     *
     * @since 1.0
     * @access public
     * @return void
     */
    function add_paged_entry()
    {
        // Get current page number
        $page = (int)get_query_var('paged');

        if (!$page) {
            $page = (int)get_query_var('page');
        }

        // Add page indicator if not on first page
        if ($page > 1) {
            $this->add_entry(
                apply_filters('portfoliocraft_breadcrumb_paged', sprintf('Page %s', $page)),
                ''
            );
        }
    }

    /**
     * Add Search Results Entry
     * 
     * Generates breadcrumb for search results pages
     * Uses configured search results label
     *
     * @since 1.0
     * @access public
     * @return void
     */
    function add_search_entry()
    {
        $this->add_entry($this->args['search_results_label']);
    }

    /**
     * Add Category Hierarchy to Breadcrumb
     * 
     * Builds complete category hierarchy including parent categories
     * Handles nested category structures properly
     *
     * @since 1.0
     * @access public
     * @param object|null $cat_obj Category object, null for current category
     * @return void
     */
    function add_category_ancestor_entry($cat_obj = null)
    {
        // Get current category if none specified
        if (is_null($cat_obj)) {
            $cat_obj = get_category($GLOBALS['wp_query']->get_queried_object());
        }
        
        // Get category ancestors in reverse order (parent to child)
        $cat_ancestors = array_reverse(get_ancestors($cat_obj->term_id, 'category'));
        
        // Add each parent category
        foreach ($cat_ancestors as $key => $cat_parent) {
            $this->add_entry(get_cat_name($cat_parent), get_category_link($cat_parent));
        }

        // Add current category
        $this->add_entry(get_cat_name($cat_obj->term_id), get_category_link($cat_obj->term_id));
    }

    /**
     * Add Single Post Ancestor Entries
     * 
     * Generates breadcrumb entries for single posts including
     * post title and any relevant taxonomy or hierarchy information
     *
     * @since 1.0
     * @access public
     * @param WP_Post $post Post object to generate breadcrumb for
     * @return void
     */
    function add_single_ancestor_entry($post)
    {
        // Validate post object
        if (!is_a($post, 'WP_Post')) {
            global $post;
        }

        // Initialize entries with current post
        $entries = array();
        $entries[] = array(
            'label' => get_the_title(),
            'url'   => ''
        );

        /**
         * Filter single post breadcrumb entries
         * 
         * Allows customization of single post breadcrumb structure
         * Useful for adding custom post type hierarchies or taxonomies
         *
         * @since 1.1
         * @param array   $entries Each entry needs 'label' and 'url' keys
         * @param WP_Post $post    Current post object
         */
        $entries = apply_filters('portfoliocraft_breadcrumb_single', $entries, $post);

        // Process and add all entries
        foreach ($entries as $entry) {
            if (!is_array($entry) || empty($entry['label'])) {
                continue;
            }

            if (!isset($entry['url'])) {
                $entry['url'] = '';
            }

            $this->add_entry($entry['label'], $entry['url']);
        }
    }
}
