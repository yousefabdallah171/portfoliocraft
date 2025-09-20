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
<header id="rmt-header-default" class="rmt-header">
    <div id="rmt-header-main" class="rmt-header-main">
        <div class="container">
            <div class="rmt-header-inner">
                <div class="rmt-header-logo">
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
                <div class="rmt-sidebar-menu">
                    <div class="rmt-sidebar-box">
                        <div class="rmt-header-logo rmt-hide-xl">
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
                        <nav class="rmt-header-nav">
                            <?php
                                if ( has_nav_menu( 'primary' ) )
                                {
                                    // Ensure theme_location is always set for theme checker compliance
                                    $attr_menu = array(
                                        'theme_location' => 'primary', // Required: theme location must be specified
                                        'container'      => '',
                                        'menu_id'        => '',
                                        'menu_class'     => 'rmt-menu-primary clearfix',
                                        'link_before'    => '<span>',
                                        'link_after'     => '</span>',
                                        'walker'         => class_exists( 'rmt_Mega_Menu_Walker' ) ? new rmt_Mega_Menu_Walker : '',
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
                                        '<ul class="rmt-menu-primary rmt-primary-menu-not-set"><li><a href="%1$s">%2$s</a></li></ul>',
                                        esc_url( admin_url( 'nav-menus.php' ) ),
                                        esc_html__( 'Create New Menu', 'portfoliocraft' )
                                    );
                                }
                            ?>
                        </nav>
                    </div>
                </div>
                <div class="rmt-toggle-menu">
                    <div class="nav-mobile-button rmt-anchor-divider rmt-cursor-cta">
                        <span class="rmt-icon-line rmt-icon-line1"></span>
                        <span class="rmt-icon-line rmt-icon-line2"></span>
                        <span class="rmt-icon-line rmt-icon-line3"></span>
                    </div>
                </div>
                <div class="rmt-header-backdrop"></div>
            </div>
        </div>
    </div>
</header>
