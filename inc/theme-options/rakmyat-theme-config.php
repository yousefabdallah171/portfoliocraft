<?php
/**
 * Theme Configuration and Styling System
 * 
 * This file handles the theme's color scheme, typography, and CSS variable generation.
 * It provides a centralized way to manage theme styling through WordPress customizer options.
 *
 * @package portfoliocraft
 * @since 1.0.0
 */


if (!function_exists('portfoliocraft_configs')) {
    /**
     * Get theme configuration values
     * 
     * Retrieves theme configuration settings including colors, typography, and other styling options.
     * These values are used to generate CSS variables and maintain consistent styling throughout the theme.
     * 
     * The configuration includes:
     * - Theme colors (primary, secondary, third, body background)
     * - Link colors (regular, hover, active states)
     * - Gradient colors (from, to)
     * - Typography settings (primary, secondary, heading fonts)
     * 
     * @param string $value The configuration key to retrieve
     * @return array|string The requested configuration value
     */
    function portfoliocraft_configs($value) {
        $configs = [
            // Theme color scheme configuration
            'theme_colors' => [
                'primary' => [
                    'title' => esc_html__('Primary', 'portfoliocraft'), 
                    'value' => portfoliocraft()->get_opt('primary_color', '#FF3D00')
                ],
                'secondary' => [
                    'title' => esc_html__('Secondary', 'portfoliocraft'), 
                    'value' => portfoliocraft()->get_opt('secondary_color', '#010101')
                ],
                'third' => [
                    'title' => esc_html__('Third', 'portfoliocraft'), 
                    'value' => portfoliocraft()->get_opt('third_color', '#00c5fe')
                ],
                'body-bg' => [
                    'title' => esc_html__('Body Background Color', 'portfoliocraft'), 
                    'value' => portfoliocraft()->get_page_opt('body_bg_color', '#fff')
                ],
                // White and light text colors
                'white-text' => [
                    'title' => esc_html__('White Text', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('white_text_color', '#ffffff')
                ],
                'light-text' => [
                    'title' => esc_html__('Light Text', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('light_text_color', '#f5f5f5')
                ],
                'off-white' => [
                    'title' => esc_html__('Off White', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('off_white_color', '#fafafa')
                ],
                'light-gray-text' => [
                    'title' => esc_html__('Light Gray Text', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('light_gray_text_color', '#e0e0e0')
                ],
                // Main text colors
                'body-text' => [
                    'title' => esc_html__('Body Text', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('body_text_color', '#333333')
                ],
                'heading-text' => [
                    'title' => esc_html__('Heading Text', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('heading_text_color', '#1a1a1a')
                ],
                'post-title' => [
                    'title' => esc_html__('Post Title', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('post_title_color', '#1a1a1a')
                ],
                'subtitle-text' => [
                    'title' => esc_html__('Subtitle Text', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('subtitle_text_color', '#666666')
                ],
                'meta-text' => [
                    'title' => esc_html__('Meta Text', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('meta_text_color', '#999999')
                ],
                // Comments section colors
                'comments-title' => [
                    'title' => esc_html__('Comments Title', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('comments_title_color', '#1a1a1a')
                ],
                'comment-author' => [
                    'title' => esc_html__('Comment Author', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('comment_author_color', '#333333')
                ],
                'comment-text' => [
                    'title' => esc_html__('Comment Text', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('comment_text_color', '#555555')
                ],
                'comment-meta' => [
                    'title' => esc_html__('Comment Meta', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('comment_meta_color', '#999999')
                ],
            ],
            // Link color states configuration
            'link' => [
                'color' => portfoliocraft()->get_opt('link_color', ['regular' => '#1F1F1F'])['regular'],
                'color-hover' => portfoliocraft()->get_opt('link_color', ['hover' => '#F14F44'])['hover'],
                'color-active' => portfoliocraft()->get_opt('link_color', ['active' => '#F14F44'])['active'],
            ],
            // Gradient color configuration
            'gradient' => [
                'color-from' => portfoliocraft()->get_opt('gradient_color', ['from' => '#6000ff'])['from'],
                'color-to' => portfoliocraft()->get_opt('gradient_color', ['to' => '#fe0054'])['to'],
            ],
            // Typography configuration
            'theme_typography' => [
                'primary' => [
                    'title' => esc_html__('Primary', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('primary_font', 'Kanit')
                ],
                'secondary' => [
                    'title' => esc_html__('Secondary', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('secondary_font', 'Montserrat')
                ],
                'heading' => [
                    'title' => esc_html__('Heading', 'portfoliocraft'),
                    'value' => portfoliocraft()->get_opt('heading_font', 'Sora')
                ],
            ]
        ];
        return $configs[$value];
    }
}

if (!function_exists('portfoliocraft_inline_styles')) {
    /**
     * Generate CSS variables for theme styling
     * 
     * Creates CSS custom properties (variables) for colors, typography, and other theme settings.
     * These variables are used throughout the theme's stylesheets to maintain consistent styling
     * and enable easy customization through the WordPress customizer.
     * 
     * The generated CSS variables include:
     * - Theme colors (primary, secondary, third, body background)
     * - Link colors (regular, hover, active states)
     * - Gradient colors (from, to)
     * - Typography settings (primary, secondary, heading fonts)
     * 
     * The variables are generated in the :root selector to ensure global availability
     * and are used throughout the theme's stylesheets for consistent styling.
     * 
     * @return string Generated CSS variables
     */
    function portfoliocraft_inline_styles() {  
        // Get theme configuration values
        $theme_colors = portfoliocraft_configs('theme_colors');
        $link_color = portfoliocraft_configs('link');
        $gradient_color = portfoliocraft_configs('gradient');
        $theme_typography = portfoliocraft_configs('theme_typography');
        
        ob_start();
        echo ':root{';
            
            // Generate color variables
            foreach ($theme_colors as $color => $value) {
                printf('--%1$s-color: %2$s;', str_replace('#', '', $color), $value['value']);
            }
            
            // Generate link color variables
            foreach ($link_color as $color => $value) {
                printf('--link-%1$s: %2$s;', $color, $value);
            }
            
            // Generate gradient color variables
            foreach ($gradient_color as $color => $value) {
                printf('--gradient-%1$s: %2$s;', $color, $value);
            }
            
            // Generate typography variables
            foreach ($theme_typography as $font => $value) {
                $font_family = is_array($value['value']) ? $value['value']['font-family'] : $value['value'];
                printf('--%1$s-font: %2$s;', str_replace('#', '', $font), $font_family);
            }
        echo '}';

        return ob_get_clean();
    }
}
 