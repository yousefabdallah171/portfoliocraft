<?php
if (!defined("ABSPATH")) {
    exit;
}
/**
 * Template part for displaying default header layout
 */

$logo_m = portfoliocraft()->get_opt( 'logo_m', ['url' => get_template_directory_uri().'/assets/img/logo.png'] );
$p_menu = portfoliocraft()->get_page_opt('p_menu');
?>
<header id="pxl-header-default" class="pxl-header">
    <div id="pxl-header-main" class="pxl-header-main">
        <div class="container">
            <div class="pxl-header-inner">
                <div class="pxl-header-logo">
                    <?php
                        if ($logo_m['url']) {
                            printf(
                                '<a href="%1$s" title="%2$s" rel="home"><img src="%3$s" alt="%2$s"/></a>',
                                esc_url( home_url( '/' ) ),
                                esc_attr( get_bloginfo( 'name' ) ),
                                esc_url( $logo_m['url'] )
                            );
                        }
                    ?>
                </div>
                <div class="pxl-sidebar-menu">
                    <div class="pxl-sidebar-box">
                        <div class="pxl-header-logo pxl-hide-xl">
                            <?php
                                if ($logo_m['url']) {
                                    printf(
                                        '<a href="%1$s" title="%2$s" rel="home"><img src="%3$s" alt="%2$s"/></a>',
                                        esc_url( home_url( '/' ) ),
                                        esc_attr( get_bloginfo( 'name' ) ),
                                        esc_url( $logo_m['url'] )
                                    );
                                }
                            ?>
                        </div>
                        <nav class="pxl-header-nav">
                            <?php
                                if ( has_nav_menu( 'primary' ) )
                                {
                                    // Ensure theme_location is always set for theme checker compliance
                                    $attr_menu = array(
                                        'theme_location' => 'primary', // Required: theme location must be specified
                                        'container'      => '',
                                        'menu_id'        => '',
                                        'menu_class'     => 'pxl-menu-primary clearfix',
                                        'link_before'    => '<span>',
                                        'link_after'     => '</span>',
                                        'walker'         => class_exists( 'PXL_Mega_Menu_Walker' ) ? new PXL_Mega_Menu_Walker : '',
                                    );
                                    
                                    // Override with specific menu if set in page options
                                    if(isset($p_menu) && !empty($p_menu)) {
                                        $attr_menu['menu'] = $p_menu;
                                        // Keep theme_location even when using specific menu
                                        $attr_menu['theme_location'] = 'primary';
                                    }
                                    
                                    // Ensure theme_location is always present for theme checker
                                    if (!isset($attr_menu['theme_location']) || empty($attr_menu['theme_location'])) {
                                        $attr_menu['theme_location'] = 'primary';
                                    }
                                    
                                    // Call wp_nav_menu with theme_location guaranteed to be set
                                    wp_nav_menu( $attr_menu );
                                } else { 
                                    printf(
                                        '<ul class="pxl-menu-primary pxl-primary-menu-not-set"><li><a href="%1$s">%2$s</a></li></ul>',
                                        esc_url( admin_url( 'nav-menus.php' ) ),
                                        esc_html__( 'Create New Menu', 'portfoliocraft' )
                                    );
                                }
                            ?>
                        </nav>
                    </div>
                </div>
                <div class="pxl-toggle-menu">
                    <div class="nav-mobile-button pxl-anchor-divider pxl-cursor-cta">
                        <span class="pxl-icon-line pxl-icon-line1"></span>
                        <span class="pxl-icon-line pxl-icon-line2"></span>
                        <span class="pxl-icon-line pxl-icon-line3"></span>
                    </div>
                </div>
                <div class="pxl-header-backdrop"></div>
            </div>
        </div>
    </div>
</header>
