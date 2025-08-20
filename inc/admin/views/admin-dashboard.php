<!-- 
    portfoliocraft Theme Dashboard Main Content
    
    This is the main dashboard interface for the portfoliocraft WordPress theme admin panel.
    Provides a comprehensive overview and quick access to essential theme features
    including premium features, activation status, and system information.
    
    Layout: 3-column responsive grid with featured content boxes
    Components: Navigation tabs, feature highlights, activation panel, system status
-->

<main>
    <!-- 
        Dashboard Container
        
        Main wrapper for the entire dashboard interface
        Provides consistent spacing and layout structure
    -->
    <div class="pxl-dashboard-wrap">

        <?php 
        /**
         * Include Admin Navigation Tabs
         * 
         * Loads the horizontal tab navigation for switching between
         * different admin sections (Dashboard, Plugins, Import Demo, Theme Options, etc.)
         * Provides consistent navigation across all admin pages
         */
        get_template_part('inc/admin/views/admin-tabs'); 
        ?>
     
        <!-- 
            Dashboard Content Grid
            
            Three-column responsive layout for organizing dashboard widgets
            Each column contains a specific functional area of the theme admin
        -->
        <div class="pxl-row">
            
            <!-- 
                Premium Features Column
                
                First column highlighting premium theme features and upgrades
                Showcases advanced functionality available in pro version
            -->
            <div class="pxl-col pxl-col-4">
                <div class="pxl-dsb-box-wrap pxl-dsb-box featured-box">
                    <!-- Section heading for premium features -->
                    <h4 class="pxl-dsb-title-heading">
                        <?php esc_html_e('Unlock Premium Features', 'portfoliocraft'); ?>
                    </h4>
                    <?php 
                    /**
                     * Include Featured Content Section
                     * 
                     * Displays premium features, upgrade options, and special offers
                     * May include feature comparisons, pricing, and upgrade buttons
                     */
                    get_template_part('inc/admin/views/admin-featured'); 
                    ?>
                </div>
            </div>    
         
            <!-- 
                Theme Activation Column
                
                Second column for theme license activation and registration
                Handles theme validation, updates, and support access
            -->
            <div class="pxl-col pxl-col-4">
                <div class="pxl-dsb-box-wrap pxl-dsb-box activation-box">
                    <!-- Section heading for activation panel -->
                    <h4 class="pxl-dsb-title-heading">
                        <?php esc_html_e('Theme Activation', 'portfoliocraft'); ?>
                    </h4>
                    <?php 
                    /**
                     * Include Registration/Activation Panel
                     * 
                     * Provides interface for:
                     * - Theme license key entry
                     * - Activation status display
                     * - Registration form
                     * - Update notifications
                     */
                    get_template_part('inc/admin/views/admin-registration'); 
                    ?>
                </div>
            </div>    
            
            <!-- 
                System Information Column
                
                Third column displaying technical system status and requirements
                Helps diagnose compatibility issues and server configuration
            -->
            <div class="pxl-col pxl-col-4">
                <div class="pxl-dsb-box-wrap pxl-dsb-box system-info-box">
                    <!-- Section heading for system status -->
                    <h4 class="pxl-dsb-title-heading">
                        <?php esc_html_e('System status', 'portfoliocraft'); ?>
                    </h4>
                    <?php 
                    /**
                     * Include System Information Panel
                     * 
                     * Displays:
                     * - WordPress version and requirements
                     * - PHP version and memory limits
                     * - Server configuration details
                     * - Plugin compatibility status
                     * - Performance recommendations
                     */
                    get_template_part('inc/admin/views/admin-system-info'); 
                    ?>
                </div>
            </div> 
             
        </div> 
 
    </div> 

</main>
