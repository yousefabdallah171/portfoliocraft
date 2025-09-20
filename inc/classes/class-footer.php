<?php

if (!class_exists('portfoliocraft_Footer')) {

    class portfoliocraft_Footer
    {
        public function getFooter()
        {
            if(is_singular('elementor_library')) return;
            
            $footer_layout = (int)portfoliocraft()->get_opt('footer_layout');
            
            if ($footer_layout <= 0 || !class_exists('rmttheme_Core') || !is_callable( 'Elementor\Plugin::instance' )) {
                get_template_part( 'template-parts/footer/default');
            } else {
                $args = [
                    'footer_layout' => $footer_layout
                ];
                get_template_part( 'template-parts/footer/elementor','', $args );
            } 

            // Mouse Move Animation
            portfoliocraft_mouse_move_animation();

            // Cookie Policy
            portfoliocraft_cookie_policy();

            // Cart Sidebar
            portfoliocraft_hook_anchor_cart();
            
        }
 
    }
}
 