<?php 
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
$header_mobile_layout = portfoliocraft()->get_opt('header_mobile_layout');
$header_mobile_layout_count = (int)portfoliocraft()->get_opt('header_mobile_layout');
$post_header_mobile = get_post($header_mobile_layout);

$has_header_sticky = isset($args['header_layout_sticky']) && $args['header_layout_sticky'] > 0 || false;
?>
<header id="pxl-header-elementor" class="pxl-header">
    <div id="pxl-header-desktop" class="<?php echo esc_attr($header_type); ?>">
        <?php if(isset($args['header_layout']) && $args['header_layout'] > 0) : ?>
            <div class="pxl-header-main">
                <div class="pxl-header-inner">
                    <?php echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $args['header_layout']); ?>
                </div>
            </div>
        <?php endif; ?>
    </div> 
    <?php if($has_header_sticky) : ?>
        <div class="pxl-header-sticky pxl-onepage-sticky <?php echo esc_attr($sticky_scroll); ?>">
            <div class="pxl-header-inner">
                <?php echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $args['header_layout_sticky']); ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if($mobile_display == 'show') : ?>
        <div id="pxl-header-mobile" class="pxl-header-mobile">
            <div class="pxl-header-main">
                <div class="pxl-header-inner">
                    <?php if ($header_mobile_layout_count <= 0 || !class_exists('pxltheme_Core') || !is_callable( 'Elementor\Plugin::instance' )) : ?>
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
                        <div class="pxl-toggle-menu">
                            <div class="nav-mobile-button pxl-anchor-divider pxl-cursor-cta">
                                <span class="pxl-icon-line pxl-icon-line1"></span>
                                <span class="pxl-icon-line pxl-icon-line2"></span>
                                <span class="pxl-icon-line pxl-icon-line3"></span>
                            </div>
                        </div>
                    <?php else : ?>
                            <?php echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $header_mobile_layout ); ?>
                    <?php endif; ?>
                    <div class="pxl-sidebar-menu">
                        <div class="pxl-sidebar-box">
                            <div class="pxl-header-logo pxl-hide-xl">
                                <?php
                                    if ($logo_m['url']) {
                                        printf(
                                            '<a class="pxl-logo-dark" href="%1$s" title="%2$s" rel="home"><img src="%3$s" alt="%2$s"/></a>',
                                            esc_url( home_url( '/' ) ),
                                            esc_attr( get_bloginfo( 'name' ) ),
                                            esc_url( $logo_m['url'] )
                                        );
                                    }
                                ?>
                            </div>
                            <?php portfoliocraft_header_mobile_search_form(); ?>
                            <nav class="pxl-header-nav">
                                <?php 
                                    if ( has_nav_menu( 'primary-mobile' ) )
                                    {
                                        // Ensure theme_location is always set for theme checker compliance
                                        $attr_menu = array(
                                            'theme_location' => 'primary-mobile', // Required: theme location must be specified
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
    
                                    } else { ?>
                                        <ul class="pxl-menu-primary">
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
                            <div class="pxl-close-menu pxl-close-button"></div>
                        </div>
                    </div>
                    <div class="pxl-header-backdrop"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</header>