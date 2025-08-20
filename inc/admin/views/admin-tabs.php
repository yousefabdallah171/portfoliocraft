<?php 
/**
 * portfoliocraft Admin Navigation Tabs
 * 
 * This template creates the main navigation interface for the portfoliocraft theme
 * admin dashboard. Provides tab-based navigation between different admin sections
 * and external resource links for support, documentation, and demos.
 * 
 * Features:
 * - Dynamic active state detection
 * - Theme logo and version display
 * - Internal navigation (Dashboard, Plugins, Import Demo, Theme Options, System Status, Templates)
 * - External resource links (Support, Documentation, Demos)
 * - Proper URL sanitization and escaping
 */

/**
 * Check if portfoliocraft Core plugin is active
 * Theme Options, System Status, and Templates pages come from the core plugin
 */
$portfoliocraft_core_active = class_exists('Pxltheme_Core') || function_exists('pixelart');

/**
 * Generate Admin Page URLs
 * 
 * Creates URLs for different admin pages with proper WordPress admin URL structure
 * Handles current page detection for active state styling
 */
$dashboard_page_url = admin_url('admin.php?page=pxlart');

// Remove URL for current page to prevent self-linking
if (isset($_GET['page']) && 'pxlart' === sanitize_text_field($_GET['page'])) {
    $dashboard_page_url = '';
}

$plugin_page_url = admin_url('admin.php?page=pxlart-plugins');
$ocdi_page_url = admin_url('themes.php?page=one-click-demo-import');
$theme_options_page_url = admin_url('admin.php?page=pxlart-theme-options');
$system_status_page_url = admin_url('admin.php?page=Rakmyat-system-status');
$templates_page_url = admin_url('edit.php?post_type=pxl-template');

/**
 * Server Information Configuration
 * 
 * Defines external resource URLs with filter support for customization
 * Allows child themes or plugins to modify these URLs as needed
 */
$pxl_server_info = apply_filters('pxl_server_info', [
    'video_url' => 'https://doc.portfoliocraft-themes.net/video-guide/',
    'demo_url' => 'https://demo.portfoliocraft-themes.net/',
    'docs_url' => 'https://doc.portfoliocraft-themes.net/', 
    'support_url' => 'https://portfoliocraft-themes.ticksy.com/'
]); 
?>

<!-- 
    Admin Dashboard Navigation Bar
    
    Main navigation interface containing theme branding, internal navigation,
    and external resource links organized in a horizontal menu bar
-->
<nav class="pxl-dsb-menubar">
    
    <?php 
    /**
     * Theme Logo Configuration
     * 
     * Retrieves custom favicon/logo from theme options
     * Falls back to default theme logo if no custom logo is set
     */
    $favicon = portfoliocraft()->get_theme_opt('favicon');
    $logo_url = !empty($favicon['url']) ? $favicon['url'] : get_template_directory_uri() . '/assets/img/logo.png'; 
    ?>
    
    <!-- 
        Theme Branding Section
        
        Displays theme logo, welcome message, and version information
        Provides visual identity and context for the admin interface
    -->
    <div class="pxl-dsb-logo">
        
        <!-- 
            Logo Image Container
            
            Displays theme logo with proper alt text for accessibility
        -->
        <div class="pxl-dsb-logo-inner">
            <img src="<?php echo esc_url($logo_url); ?>" 
                 alt="<?php echo esc_attr(portfoliocraft()->get_name()); ?>">
        </div>
        
        <!-- 
            Theme Title and Version
            
            Shows welcome message with theme name and current version
            Helps users identify which theme they're configuring
        -->
        <div class="pxl-dsb-logo-title">
            <h2>
                <?php esc_html_e('Welcome to', 'portfoliocraft'); ?> 
                <?php echo esc_attr(portfoliocraft()->get_name()) . '!'; ?>
            </h2>
            <span class="pxl-v">
                <?php esc_html_e('Version', 'portfoliocraft'); ?> 
                <?php echo esc_html(portfoliocraft()->get_version()); ?>
            </span>
        </div>
    </div>

    <!-- Dark/Light Mode Toggle Button -->
    <button id="pxl-mode-toggle" class="pxl-btn" type="button" aria-pressed="false" style="margin-left:auto;">
      Switch to Light Mode
    </button>

    <!-- 
        Navigation Menu Container
        
        Contains both internal navigation links and external resource links
        Split into left (internal) and right (external) sections
    -->
    <div class="pxl-dsb-menu">
        
        <!-- 
            Internal Navigation Links
            
            Left-side navigation for theme admin pages
            Includes active state detection for current page highlighting
        -->
        <ul class="pxl-dsb-menu-left">
            
            <!-- 
                Dashboard Tab
                
                Main dashboard page with dynamic active state detection
                Uses empty href when on current page to prevent self-linking
            -->
            <li class="<?php echo (isset($_GET['page']) && 'pxlart' === sanitize_text_field($_GET['page'])) ? 'is-active' : ''; ?>">
                <a href="<?php echo esc_attr($dashboard_page_url); ?>">
                    <span>
                        <?php echo sprintf(esc_html__('%s Dashboard', 'portfoliocraft'), portfoliocraft()->get_name()); ?>
                    </span>
                </a>
            </li>
            
            <!-- 
                Plugin Installation Tab
                
                Links to plugin management page with active state detection
            -->
            <li class="<?php echo (isset($_GET['page']) && 'pxlart-plugins' === sanitize_text_field($_GET['page'])) ? 'is-active' : ''; ?>">
                <a href="<?php echo esc_url($plugin_page_url); ?>">
                    <span><?php esc_html_e('Install Plugins', 'portfoliocraft'); ?></span>
                </a>
            </li>
            
            <!-- 
                Demo Import Tab
                
                Links to OCDI demo import page with active state detection
            -->
            <li class="<?php echo (isset($_GET['page']) && 'one-click-demo-import' === sanitize_text_field($_GET['page'])) ? 'is-active' : ''; ?>">
                <a href="<?php echo esc_url($ocdi_page_url); ?>">
                    <span><?php esc_html_e('Import Demo', 'portfoliocraft'); ?></span>
                </a>
            </li>
            
            <?php if ($portfoliocraft_core_active): ?>
            <!-- 
                Theme Options Tab
                
                Links to theme options page with active state detection
                Only shown when portfoliocraft Core plugin is active
            -->
            <li class="<?php echo (isset($_GET['page']) && 'pxlart-theme-options' === sanitize_text_field($_GET['page'])) ? 'is-active' : ''; ?>">
                <a href="<?php echo esc_url($theme_options_page_url); ?>">
                    <span><?php esc_html_e('Theme Options', 'portfoliocraft'); ?></span>
                </a>
            </li>
            
            <!-- 
                System Status Tab
                
                Links to system status page with active state detection
                Only shown when portfoliocraft Core plugin is active
            -->
            <li class="<?php echo (isset($_GET['page']) && 'Rakmyat-system-status' === sanitize_text_field($_GET['page'])) ? 'is-active' : ''; ?>">
                <a href="<?php echo esc_url($system_status_page_url); ?>">
                    <span><?php esc_html_e('System Status', 'portfoliocraft'); ?></span>
                </a>
            </li>
            
            <!-- 
                Templates Tab
                
                Links to templates management page with active state detection
                Only shown when portfoliocraft Core plugin is active
            -->
            <li class="<?php echo (isset($_GET['post_type']) && 'pxl-template' === sanitize_text_field($_GET['post_type'])) ? 'is-active' : ''; ?>">
                <a href="<?php echo esc_url($templates_page_url); ?>">
                    <span><?php esc_html_e('Templates', 'portfoliocraft'); ?></span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
        
        <!-- 
            External Resource Links
            
            Right-side navigation for external resources and support
            All links open in new tabs for better user experience
        -->
        <ul class="pxl-dsb-menu-right">
            
            <!-- 
                Video Tutorials Link
                
                Links to video guide documentation for visual learning
            -->
            <li>
                <a href="<?php echo esc_url($pxl_server_info['video_url']); ?>" target="_blank">
                    <span><?php esc_html_e('Videos tutorial', 'portfoliocraft'); ?></span>
                </a>
            </li>
            
            <!-- 
                Support System Link
                
                Links to ticket-based support system for user assistance
            -->
            <li>
                <a href="<?php echo esc_url($pxl_server_info['support_url']); ?>" target="_blank">
                    <span><?php esc_html_e('Support system', 'portfoliocraft'); ?></span>
                </a>
            </li>
            
            <!-- 
                Live Demo Link
                
                Links to live demonstration of theme features and layouts
            -->
            <li>
                <a href="<?php echo esc_url($pxl_server_info['demo_url']); ?>" target="_blank">
                    <span><?php esc_html_e('Live Demo', 'portfoliocraft'); ?></span>
                </a>
            </li>
             
            <!-- 
                Documentation Link
                
                Links to comprehensive theme documentation with help icon
                Primary resource for theme setup and customization guidance
            -->
            <li>
                <a href="<?php echo esc_url($pxl_server_info['docs_url']); ?>" target="_blank">
                    <i class="pxl-icn-ess icon-md-help-circle"></i>
                    <span><?php esc_html_e('Documentations', 'portfoliocraft'); ?></span>
                </a>
            </li>
        </ul>
    </div>
</nav>
