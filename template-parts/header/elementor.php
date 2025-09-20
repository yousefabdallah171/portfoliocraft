<?php 
if (!defined("ABSPATH")) {
    exit;
}
$logo_m = portfoliocraft()->get_opt( 'logo_m', ['url' => get_template_directory_uri().'/assets/img/logo.png', 'id' => 'null'] );
$logo_light_m = portfoliocraft()->get_opt( 'logo_light_m', ['url' => get_template_directory_uri().'/assets/img/logo.png', 'id' => 'null'] );
$p_menu = portfoliocraft()->get_page_opt('p_menu');
$header_display = portfoliocraft()->get_page_opt('header_display', 'show');

$sticky_scroll = portfoliocraft()->get_opt('sticky_scroll');

$header_layout = portfoliocraft()->get_opt('header_layout');
$post_header = get_post($header_layout);
$header_type = get_post_meta( $post_header->ID, 'header_type', true );
$header_sidebar_style = get_post_meta( $post_header->ID, 'header_sidebar_style', true );
$page_mobile_style = portfoliocraft()->get_page_opt('page_mobile_style');
$mobile_display = portfoliocraft()->get_opt('mobile_display');

$has_header_sticky = isset($args['header_layout_sticky']) && $args['header_layout_sticky'] > 0 || false;
?>
<header id="rmt-header-elementor" class="rmt-header">
    <div id="rmt-header-desktop" class="<?php echo esc_attr($header_type); ?>">
        <?php if(isset($args['header_layout']) && $args['header_layout'] > 0) : ?>
            <div class="rmt-header-main">
                <div class="rmt-header-inner">
                    <?php echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $args['header_layout']); ?>
                </div>
            </div>
        <?php endif; ?>
    </div> 
    <?php if($has_header_sticky) : ?>
        <div class="rmt-header-sticky rmt-onepage-sticky <?php echo esc_attr($sticky_scroll); ?>">
            <div class="rmt-header-inner">
                <?php echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $args['header_layout_sticky']); ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if($mobile_display == 'show') : ?>
        <div id="rmt-header-mobile" class="rmt-header-mobile">
            <div class="rmt-header-main">
                <div class="rmt-header-inner">
                    <?php if(isset($args['header_layout']) && $args['header_layout'] > 0) : ?>
                        <?php echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $args['header_layout'] ); ?>
                    <?php else : ?>
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
                        <div class="rmt-toggle-menu">
                            <div class="nav-mobile-button rmt-anchor-divider rmt-cursor-cta">
                                <span class="rmt-icon-line rmt-icon-line1"></span>
                                <span class="rmt-icon-line rmt-icon-line2"></span>
                                <span class="rmt-icon-line rmt-icon-line3"></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="rmt-sidebar-menu">
                        <div class="rmt-sidebar-box">
                            <div class="rmt-header-logo rmt-hide-xl">
                                <?php
                                    if ($logo_m['url']) {
                                        printf(
                                            '<a class="rmt-logo-dark" href="%1$s" title="%2$s" rel="home"><img src="%3$s" alt="%2$s"/></a>',
                                            esc_url( home_url( '/' ) ),
                                            esc_attr( get_bloginfo( 'name' ) ),
                                            esc_url( $logo_m['url'] )
                                        );
                                    }
                                ?>
                            </div>
                            <?php portfoliocraft_header_mobile_search_form(); ?>
                            <nav class="rmt-header-nav">
                                <?php 
                                    if ( has_nav_menu( 'primary-mobile' ) )
                                    {
                                        // Ensure theme_location is always set for theme checker compliance
                                        $attr_menu = array(
                                            'theme_location' => 'primary-mobile', // Required: theme location must be specified
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
                                            $attr_menu['theme_location'] = 'primary-mobile';
                                        }
                                        
                                        // Ensure theme_location is always present for theme checker
                                        if (!isset($attr_menu['theme_location']) || empty($attr_menu['theme_location'])) {
                                            $attr_menu['theme_location'] = 'primary-mobile';
                                        }
                                        
                                        // Call wp_nav_menu with theme_location guaranteed to be set
                                        wp_nav_menu( $attr_menu );
                                    } elseif ( has_nav_menu( 'primary' ) ) {
    
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
    
                                    } else { ?>
                                        <ul class="rmt-menu-primary">
                                            <?php wp_list_pages( array(
                                                'depth'        => 0,
                                                'show_date'    => '',
                                                'date_format'  => get_option( 'date_format' ),
                                                'child_of'     => 0,
                                                'exclude'      => '',
                                                'title_li'     => '',
                                                'echo'         => 1,
                                                'authors'      => '',
                                                'sort_column'  => 'menu_order, post_title',
                                                'link_before'  => '',
                                                'link_after'   => '',
                                                'item_spacing' => 'preserve',
                                                'walker'       => '',
                                            ) ); ?>
                                        </ul>
                                    <?php }
                                ?>
                            </nav>
                            <div class="rmt-close-menu rmt-close-button"></div>
                        </div>
                    </div>
                    <div class="rmt-header-backdrop"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</header>