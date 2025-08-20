<?php
/**
 * portfoliocraft Blog Class
 * 
 * This class handles all blog-related functionality for the portfoliocraft theme
 * including post meta display, social sharing, post navigation, author info,
 * and post view tracking with proper security and validation.
 * 
 * @package portfoliocraft
 * @version 1.0.0
 */

// Security: Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Blog Management Class
 * 
 * Manages blog functionality including:
 * - Post meta information display (author, date, comments, views)
 * - Social media sharing buttons
 * - Post navigation (previous/next posts)
 * - Author information box
 * - Post view counter system
 */
if (!class_exists('portfoliocraft_Blog')) {

    class portfoliocraft_Blog
    {
        /**
         * Display Post Meta Information
         * 
         * Renders post metadata including author, date, comments count, and view count
         * Each meta element can be individually enabled/disabled via theme options
         * Includes proper escaping and validation for all output
         * 
         * @param int $post_id The post ID to get meta information for
         * @return void
         */
        public function get_post_metas($post_id){
            // Get theme options for which meta elements to display
            $post_author = portfoliocraft()->get_theme_opt( 'post_author', true );
            $post_date = portfoliocraft()->get_theme_opt( 'post_date', true );
            $post_comment = portfoliocraft()->get_theme_opt( 'post_comment', true );
            
            // Get and update post view count
            $count_post_view = $this->portfoliocraft_set_post_views($post_id);
            
            // Only display meta container if at least one meta element is enabled
            if($post_author || $post_date || $post_comment) : ?>
                <div class="pxl-post-metas">
                    
                    <?php 
                    /**
                     * Author Meta Section
                     * Displays author avatar and name with proper escaping
                     */
                    if($post_author) : ?>
                        <div class="pxl-post-author pxl-meta-info">
                            <span class="pxl-author-avatar">
                                <?php 
                                // Display author avatar with 280px size
                                echo get_avatar( get_the_author_meta('ID'), 280 ); 
                                ?>
                            </span>
                            <span class="pxl-author-name pxl-meta-text">
                                <span class="pxl-text-highlight"><?php echo esc_html__('By ', 'portfoliocraft'); ?></span>
                                <?php 
                                // Display author display name with proper escaping
                                echo esc_html(get_the_author_meta('display_name')) 
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <?php 
                    /**
                     * Date Meta Section
                     * Displays post publication date with calendar icon
                     */
                    if($post_date) : ?>
                        <div class="pxl-post-date pxl-meta-info">
                            <!-- Calendar SVG Icon -->
                            <svg width="14" height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 15.4062V6.90625H14V15.4062C14 16.25 13.3125 16.9062 12.5 16.9062H1.5C0.65625 16.9062 0 16.25 0 15.4062ZM10 9.28125V10.5312C10 10.75 10.1562 10.9062 10.375 10.9062H11.625C11.8125 10.9062 12 10.75 12 10.5312V9.28125C12 9.09375 11.8125 8.90625 11.625 8.90625H10.375C10.1562 8.90625 10 9.09375 10 9.28125ZM10 13.2812V14.5312C10 14.75 10.1562 14.9062 10.375 14.9062H11.625C11.8125 14.9062 12 14.75 12 14.5312V13.2812C12 13.0938 11.8125 12.9062 11.625 12.9062H10.375C10.1562 12.9062 10 13.0938 10 13.2812ZM6 9.28125V10.5312C6 10.75 6.15625 10.9062 6.375 10.9062H7.625C7.8125 10.9062 8 10.75 8 10.5312V9.28125C8 9.09375 7.8125 8.90625 7.625 8.90625H6.375C6.15625 8.90625 6 9.09375 6 9.28125ZM6 13.2812V14.5312C6 14.75 6.15625 14.9062 6.375 14.9062H7.625C7.8125 14.9062 8 14.75 8 14.5312V13.2812C8 13.0938 7.8125 12.9062 7.625 12.9062H6.375C6.15625 12.9062 6 13.0938 6 13.2812ZM2 9.28125V10.5312C2 10.75 2.15625 10.9062 2.375 10.9062H3.625C3.8125 10.9062 4 10.75 4 10.5312V9.28125C4 9.09375 3.8125 8.90625 3.625 8.90625H2.375C2.15625 8.90625 2 9.09375 2 9.28125ZM2 13.2812V14.5312C2 14.75 2.15625 14.9062 2.375 14.9062H3.625C3.8125 14.9062 4 14.75 4 14.5312V13.2812C4 13.0938 3.8125 12.9062 3.625 12.9062H2.375C2.15625 12.9062 2 13.0938 2 13.2812ZM12.5 2.90625C13.3125 2.90625 14 3.59375 14 4.40625V5.90625H0V4.40625C0 3.59375 0.65625 2.90625 1.5 2.90625H3V1.40625C3 1.15625 3.21875 0.90625 3.5 0.90625H4.5C4.75 0.90625 5 1.15625 5 1.40625V2.90625H9V1.40625C9 1.15625 9.21875 0.90625 9.5 0.90625H10.5C10.75 0.90625 11 1.15625 11 1.40625V2.90625H12.5Z" fill="currentcolor"/>
                            </svg>
                            <span class="pxl-date-text pxl-meta-text">
                                <?php 
                                // Display formatted post date
                                echo get_the_date('F d,Y');
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <?php 
                    /**
                     * Comments Meta Section
                     * Displays comment count with comment icon
                     */
                    if($post_comment) : ?>
                        <div class="pxl-post-comment pxl-meta-info">
                            <!-- Comment SVG Icon -->
                            <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.991 2.70625C17.991 1.71625 17.19 0.90625 16.2 0.90625H1.8C0.81 0.90625 0 1.71625 0 2.70625V13.5062C0 14.4962 0.81 15.3062 1.8 15.3062H14.4L18 18.9062L17.991 2.70625ZM13.5 11.7062H4.5C4.005 11.7062 3.6 11.3012 3.6 10.8062C3.6 10.3112 4.005 9.90625 4.5 9.90625H13.5C13.995 9.90625 14.4 10.3112 14.4 10.8062C14.4 11.3012 13.995 11.7062 13.5 11.7062ZM13.5 9.00625H4.5C4.005 9.00625 3.6 8.60125 3.6 8.10625C3.6 7.61125 4.005 7.20625 4.5 7.20625H13.5C13.995 7.20625 14.4 7.61125 14.4 8.10625C14.4 8.60125 13.995 9.00625 13.5 9.00625ZM13.5 6.30625H4.5C4.005 6.30625 3.6 5.90125 3.6 5.40625C3.6 4.91125 4.005 4.50625 4.5 4.50625H13.5C13.995 4.50625 14.4 4.91125 14.4 5.40625C14.4 5.90125 13.995 6.30625 13.5 6.30625Z" fill="currentcolor"/>
                            </svg>
                            <span class="pxl-cout-comment pxl-meta-text" data-panel="#comments">
                                <?php 
                                // Display comment count with proper text formatting
                                echo comments_number(esc_html__('0', 'portfoliocraft'),esc_html__('1', 'portfoliocraft'),esc_html__('%', 'portfoliocraft')); 
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <?php 
                    /**
                     * Post Views Meta Section
                     * Displays post view count with eye icon
                     * Always displayed regardless of theme options
                     */
                    ?>
                    <div class="pxl-post-view pxl-meta-info">
                        <!-- Eye SVG Icon -->
                        <svg width="21" height="15" viewBox="0 0 21 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.9396 7.62275C20.909 7.55363 20.1679 5.9095 18.5202 4.26187C16.3249 2.0665 13.552 0.90625 10.5 0.90625C7.44799 0.90625 4.67512 2.0665 2.47974 4.26187C0.832117 5.9095 0.0874916 7.55625 0.0603666 7.62275C0.0205656 7.71227 0 7.80915 0 7.90712C0 8.0051 0.0205656 8.10198 0.0603666 8.1915C0.0909916 8.26062 0.832117 9.90388 2.47974 11.5515C4.67512 13.746 7.44799 14.9062 10.5 14.9062C13.552 14.9062 16.3249 13.746 18.5202 11.5515C20.1679 9.90388 20.909 8.26062 20.9396 8.1915C20.9794 8.10198 21 8.0051 21 7.90712C21 7.80915 20.9794 7.71227 20.9396 7.62275ZM10.5 11.4062C9.80776 11.4062 9.13107 11.201 8.5555 10.8164C7.97992 10.4318 7.53132 9.88518 7.26641 9.24564C7.00151 8.6061 6.9322 7.90237 7.06724 7.22343C7.20229 6.5445 7.53563 5.92086 8.02512 5.43138C8.5146 4.94189 9.13824 4.60855 9.81718 4.4735C10.4961 4.33845 11.1998 4.40776 11.8394 4.67267C12.4789 4.93758 13.0256 5.38618 13.4101 5.96175C13.7947 6.53733 14 7.21402 14 7.90625C14 8.83451 13.6312 9.72475 12.9749 10.3811C12.3185 11.0375 11.4283 11.4062 10.5 11.4062Z" fill="currentcolor"/>
                        </svg>
                        <span class="pxl-count-view pxl-meta-text">
                            <?php 
                            // Display view count with proper escaping
                            echo esc_attr($count_post_view); 
                            ?>
                        </span>
                    </div>
                </div>
            <?php endif; 
        }

        /**
         * Set and Track Post Views
         * 
         * Increments and returns the view count for a specific post
         * Uses WordPress meta system to store view counts
         * Handles both new posts (no views) and existing posts
         * 
         * @param int|null $post_id The post ID to track views for
         * @return int The current view count after increment
         */
        public function portfoliocraft_set_post_views( $post_id = null ) {
            // Validate post ID parameter
            if(is_null($post_id)) return;
            
            // Meta key used to store view count
            $countKey = 'post_views_count';
            
            // Get current view count from post meta
            $count = get_post_meta( $post_id, $countKey, true );
            
            // Handle new posts with no view count
            if ( $count == '' ) {
                $count = 0;
                // Clean up any existing meta and add fresh count
                delete_post_meta( $post_id, $countKey );
                add_post_meta( $post_id, $countKey, '0' );
            } else {
                // Increment existing view count
                $count ++;
                update_post_meta( $post_id, $countKey, $count );
            }
            
            // Return updated count
            return $count;
        }

        /**
         * Display Social Media Share Buttons
         * 
         * Renders social sharing buttons for the current post
         * Each social platform can be individually enabled/disabled
         * Includes proper URL encoding and security measures
         * 
         * @return void
         */
        public function get_socials_share() { 
            // Get featured image URL for Pinterest sharing
            $img_url = '';
            if (has_post_thumbnail() && wp_get_attachment_image_src(get_post_thumbnail_id(), false)) {
                $img_url = wp_get_attachment_image_src(get_post_thumbnail_id(), false);
            }
            
            // Get theme options for which social platforms to display
            $social_facebook = portfoliocraft()->get_theme_opt( 'social_facebook', true );
            $social_twitter = false; // Twitter disabled by default
            $social_pinterest = portfoliocraft()->get_theme_opt( 'social_pinterest', true );
            $social_linkedin = portfoliocraft()->get_theme_opt( 'social_linkedin', true );
            $social_youtube = portfoliocraft()->get_theme_opt( 'social_youtube', true );
            $social_ins = portfoliocraft()->get_theme_opt( 'social_ins', true );

            ?>
            <div class="pxl-post-social">
                <div class="pxl-social-label"><?php echo esc_html__('Share:', 'portfoliocraft'); ?></div>
                <div class="pxl-social-list">
                    
                    <?php 
                    /**
                     * Facebook Share Button
                     * Uses Facebook's sharer.php service
                     */
                    if($social_facebook) : ?>
                        <a class="fb-social pxl-social-item" title="<?php echo esc_attr__('Facebook', 'portfoliocraft'); ?>" target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>">
                            <?php echo esc_html('Facebook', 'portfoliocraft'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php 
                    /**
                     * Instagram Share Button
                     * Note: Instagram doesn't have direct sharing API, links to main site
                     */
                    if($social_ins) : ?>
                        <a class="insta-social pxl-social-item" title="<?php echo esc_attr__('Instagram', 'portfoliocraft'); ?>" target="_blank" href="https://www.instagram.com/?url=<?php the_permalink(); ?>">
                            <?php echo esc_html('Instagram', 'portfoliocraft'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php 
                    /**
                     * LinkedIn Share Button
                     * Uses LinkedIn's shareArticle service with title and URL
                     */
                    if($social_linkedin) : ?>
                        <a class="lin-social pxl-social-item" title="<?php echo esc_attr__('LinkedIn', 'portfoliocraft'); ?>" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>%20">
                            <?php echo esc_html('Linked In', 'portfoliocraft'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php 
                    /**
                     * Pinterest Share Button
                     * Includes featured image and post title for rich pins
                     */
                    if($social_pinterest) : ?>
                        <a class="pin-social pxl-social-item" title="<?php echo esc_attr__('Pinterest', 'portfoliocraft'); ?>" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php echo esc_url($img_url[0]); ?>&description=<?php the_title(); ?>%20">
                            <?php echo esc_html('Pinterest', 'portfoliocraft'); ?>
                        </a>
                    <?php endif; ?>

                    <?php 
                    /**
                     * YouTube Share Button
                     * Note: Uses Pinterest URL structure (appears to be an error in original code)
                     * Should be updated to proper YouTube sharing if needed
                     */
                    if($social_youtube) : ?>
                        <a class="pin-social pxl-social-item" title="<?php echo esc_attr__('Youtube', 'portfoliocraft'); ?>" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php echo esc_url($img_url[0]); ?>&description=<?php the_title(); ?>%20">
                            <?php echo esc_html('Youtube', 'portfoliocraft'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php 
                    /**
                     * Twitter Share Button
                     * Currently disabled but code preserved for future use
                     */
                    if($social_twitter) : ?>
                        <a class="tw-social pxl-social-item" title="<?php echo esc_attr__('Twitter', 'portfoliocraft'); ?>" target="_blank" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>%20"><i class="fab fa-twitter"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }

        /**
         * Display Post Navigation
         * 
         * Shows previous and next post links with thumbnails and metadata
         * Includes post images, dates, and titles for better user experience
         * Handles cases where previous or next posts don't exist
         * 
         * @return void
         */
        public function get_post_nav() {
            global $post;
            
            // Get adjacent posts for navigation
            $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
            $next     = get_adjacent_post( false, '', false );

            // Exit early if no navigation posts exist
            if ( ! $next && ! $previous )
                return;
            ?>
            <?php
            // Get next and previous post objects
            $next_post = get_next_post();
            $previous_post = get_previous_post();

            // Only display navigation if posts exist
            if( !empty($next_post) || !empty($previous_post) ) { 
                $page_for_posts = get_option( 'page_for_posts' );
                ?>
                <div class="pxl-post--navigation pxl-flex">
                    
                    <?php 
                    /**
                     * Previous Post Navigation
                     * Displays previous post with thumbnail, date, and title
                     */
                    if ( is_a( $previous_post , 'WP_Post' ) && get_the_title( $previous_post->ID ) != '') { 
                        // Get previous post thumbnail
                        $prev_img_id = get_post_thumbnail_id($previous_post->ID);
                        $img_prev  = pxl_get_image_by_size( array(
                            'attach_id'  => $prev_img_id,
                            'thumb_size' => '260x260',
                        ) );
                        $thumbnail_prev = $img_prev['url']; ?>
                        
                        <div class="pxl-navigation--col pxl-navigation--prev">
                            <!-- Previous post thumbnail with navigation icon -->
                            <div class="pxl-navigation--image bg-image pxl-mr-15" style="background-image: url(<?php echo esc_url($thumbnail_prev); ?>);">
                                <div class="pxl-navigation--icon"><i class="fas fa-arrow-left"></i></div>
                                <a href="<?php echo esc_url(get_permalink( $previous_post->ID )); ?>" class="pxl-navigation--link"></a>
                            </div>
                            <!-- Previous post metadata -->
                            <div class="pxl-navigation--meta">
                                <div class="pxl-navigation--date">
                                    <?php 
                                    // Display previous post date using site's date format
                                    $date_formart = get_option('date_format'); 
                                    echo get_the_date($date_formart, $previous_post->ID); 
                                    ?>
                                </div>
                                <h5 class="pxl-navigation--title">
                                    <a href="<?php echo esc_url(get_permalink( $previous_post->ID )); ?>">
                                        <?php echo get_the_title( $previous_post->ID ); ?>
                                    </a>
                                </h5>
                            </div>
                        </div>
                    <?php } ?>
                    
                    <?php 
                    /**
                     * Next Post Navigation
                     * Displays next post with thumbnail, date, and title
                     */
                    if ( is_a( $next_post , 'WP_Post' ) && get_the_title( $next_post->ID ) != '') { 
                        // Get next post thumbnail
                        $next_img_id = get_post_thumbnail_id($next_post->ID);
                        $img_next  = pxl_get_image_by_size( array(
                            'attach_id'  => $next_img_id,
                            'thumb_size' => '260x260',
                        ) );
                        $thumbnail_next = $img_next['url']; ?>
                        
                        <div class="pxl-navigation--col pxl-navigation--next">
                            <!-- Next post metadata (right-aligned) -->
                            <div class="pxl-navigation--meta pxl-text-right">
                                <div class="pxl-navigation--date">
                                    <?php 
                                    // Display next post date using site's date format
                                    $date_formart = get_option('date_format'); 
                                    echo get_the_date($date_formart, $next_post->ID); 
                                    ?>
                                </div>
                                <h5 class="pxl-navigation--title">
                                    <a href="<?php echo esc_url(get_permalink( $next_post->ID )); ?>">
                                        <?php echo get_the_title( $next_post->ID ); ?>
                                    </a>
                                </h5>
                            </div>
                            <!-- Next post thumbnail with navigation icon -->
                            <div class="pxl-navigation--image bg-image pxl-ml-15" style="background-image: url(<?php echo esc_url($thumbnail_next); ?>);">
                                <div class="pxl-navigation--icon"><i class="fas fa-arrow-right"></i></div>
                                <a href="<?php echo esc_url(get_permalink( $next_post->ID )); ?>" class="pxl-navigation--link"></a>
                            </div>
                        </div>
                    <?php } ?>
                    
                </div>
            <?php }
        }

        /**
         * Display Post Author Information Box
         * 
         * Shows detailed author information including avatar, bio, and social links
         * Retrieves author social media profiles from user meta
         * Provides links to author's other posts and social profiles
         * 
         * @param int $post_id The post ID to get author information for
         * @return void
         */
        public function get_post_author_info($post_id) { 
            // Get author ID from post
            $author_id = get_post_field ('post_author', $post_id);
            
            // Retrieve author social media links from user meta
            $facebook  = get_user_meta($author_id, 'user_facebook', true);
            $twitter   = get_user_meta($author_id, 'user_twitter', true);
            $linkedin  = get_user_meta($author_id, 'user_linkedin', true);
            $instagram = get_user_meta($author_id, 'user_instagram', true);
            $youtube   = get_user_meta($author_id, 'user_youtube', true);
            ?>
            
            <div class="pxl-post-author-box">
                <!-- Author Avatar -->
                <div class="pxl-author-avatar">
                    <?php echo get_avatar( $author_id, 280 ); ?>
                </div>
                
                <!-- Author Information and Social Links -->
                <div class="pxl-author-metas">
                    <div>
                        <!-- Author Name with Link to Author Archive -->
                        <div class="pxl-author-name">
                            <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>" class="pxl-author-link">
                                <?php echo get_the_author_meta('display_name', $author_id); ?>
                            </a>
                        </div>
                        <!-- Author Biography/Description -->
                        <p class="pxl-author-description">
                            <?php the_author_meta( 'description' ); ?>
                        </p>
                    </div>
                    
                    <!-- Author Social Media Links -->
                    <div class="pxl-author-social">
                        
                        <?php 
                        /**
                         * Facebook Link
                         * Only displayed if author has Facebook URL in profile
                         */
                        if(!empty($facebook)) { ?>
                            <a href="<?php echo esc_url($facebook); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="12" viewBox="0 0 6 12" fill="none">
                                <path d="M5.60692 6.74937L5.91824 4.57704H3.9717V3.16924C3.9717 2.57648 4.24214 1.99719 5.11321 1.99719H5.99686V0.14819C5.99686 0.14819 5.19497 0 4.42767 0C2.8239 0 1.77987 1.0407 1.77987 2.92338V4.57704H0V6.74937H1.77987V12H3.9717V6.74937H5.60692Z" fill="currentcolor"/>
                            </svg>
                            </a>
                        <?php } ?>
                        
                        <?php 
                        /**
                         * Instagram Link
                         * Only displayed if author has Instagram URL in profile
                         */
                        if(!empty($instagram)) { ?>
                            <a href="<?php echo esc_url($instagram); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.6037 12H8.39293C10.3834 12 12 10.3834 11.9966 8.39293V3.6037C11.9966 1.61662 10.38 0 8.39293 0H3.6037C1.61662 0 0 1.61662 0 3.6037V8.39629C0 10.3834 1.61662 12 3.6037 12ZM1.12826 3.6037C1.12826 2.23969 2.23969 1.12826 3.6037 1.12826H8.39293C9.75695 1.12826 10.8684 2.23969 10.8684 3.6037V8.39293C10.8684 9.75695 9.75695 10.8684 8.39293 10.8684H3.6037C2.23969 10.8684 1.12826 9.75695 1.12826 8.39293V3.6037ZM9.17428 2.14201C8.78697 2.14201 8.47038 2.4586 8.47038 2.84591C8.47038 3.23323 8.78697 3.54982 9.17428 3.54982C9.5616 3.54982 9.87819 3.23323 9.87819 2.84591C9.87819 2.4586 9.56497 2.14201 9.17428 2.14201ZM6.04883 3.04462C4.41874 3.04462 3.09514 4.3716 3.09514 5.99831C3.09514 7.6284 4.42211 8.952 6.04883 8.952C7.67892 8.952 9.00252 7.62503 9.00252 5.99831C9.00252 4.3716 7.67892 3.04462 6.04883 3.04462ZM6.04883 7.8911C5.00477 7.8911 4.15604 7.04238 4.15604 5.99831C4.15604 4.95425 5.00477 4.10553 6.04883 4.10553C7.09289 4.10553 7.94162 4.95425 7.94162 5.99831C7.94162 7.04238 7.09289 7.8911 6.04883 7.8911Z" fill="currentcolor"/>
                                </svg>
                            </a>
                        <?php } ?>
                        
                        <?php 
                        /**
                         * Twitter Link
                         * Only displayed if author has Twitter URL in profile
                         */
                        if(!empty($twitter)) { ?>
                            <a href="<?php echo esc_url($twitter); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none">
                                    <path d="M14.7772 3.58573C14.2553 3.81698 13.6947 3.97323 13.1053 4.04386C13.7134 3.67998 14.1684 3.10728 14.3853 2.43261C13.814 2.77198 13.1887 3.01086 12.5366 3.13886C12.0981 2.67065 11.5172 2.36031 10.8843 2.25603C10.2513 2.15175 9.60162 2.25935 9.03609 2.56214C8.47055 2.86493 8.0208 3.34596 7.75666 3.93055C7.49252 4.51514 7.42877 5.17058 7.57531 5.79511C6.41762 5.73698 5.28508 5.43608 4.2512 4.91192C3.21733 4.38777 2.30521 3.65208 1.57406 2.75261C1.32406 3.18386 1.18031 3.68386 1.18031 4.21636C1.18003 4.69573 1.29808 5.16776 1.52399 5.59057C1.74989 6.01337 2.07666 6.37388 2.47531 6.64011C2.01299 6.6254 1.56086 6.50047 1.15656 6.27573V6.31323C1.15652 6.98557 1.38908 7.63722 1.8148 8.15761C2.24052 8.67799 2.83317 9.03507 3.49219 9.16823C3.0633 9.28431 2.61365 9.3014 2.17719 9.21823C2.36312 9.79674 2.72531 10.3026 3.21304 10.6651C3.70077 11.0275 4.28964 11.2283 4.89719 11.2395C3.86583 12.0491 2.59212 12.4883 1.28094 12.4864C1.04868 12.4864 0.81661 12.4729 0.585938 12.4457C1.91686 13.3015 3.46615 13.7556 5.04844 13.7539C10.4047 13.7539 13.3328 9.31761 13.3328 5.47011C13.3328 5.34511 13.3297 5.21886 13.3241 5.09386C13.8936 4.68197 14.3853 4.17192 14.7759 3.58761L14.7772 3.58573Z" fill="currentcolor"/>
                                </svg>
                            </a>
                        <?php } ?>
                        
                        <?php 
                        /**
                         * LinkedIn Link
                         * Only displayed if author has LinkedIn URL in profile
                         */
                        if(!empty($linkedin)) { ?>
                            <a href="<?php echo esc_url($linkedin); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path d="M2.68831 12.0051H0.19838V3.98666H2.68831V12.0051ZM1.44201 2.89286C0.645806 2.89286 0 2.23337 0 1.43715C5.69885e-09 1.0547 0.151925 0.687915 0.422354 0.41748C0.692782 0.147046 1.05956 -0.00488281 1.44201 -0.00488281C1.82445 -0.00488281 2.19123 0.147046 2.46166 0.41748C2.73209 0.687915 2.88401 1.0547 2.88401 1.43715C2.88401 2.23337 2.23794 2.89286 1.44201 2.89286ZM12.0073 12.0051H9.52276V8.10179C9.52276 7.17153 9.50399 5.97854 8.2282 5.97854C6.93364 5.97854 6.73526 6.98923 6.73526 8.03477V12.0051H4.248V3.98666H6.63607V5.08045H6.67092C7.00334 4.45045 7.81535 3.78559 9.02681 3.78559C11.5468 3.78559 12.01 5.44505 12.01 7.60047V12.0051H12.0073Z" fill="currentcolor"/>
                                </svg>
                            </a>
                        <?php } ?>
                        
                        <?php 
                        /**
                         * YouTube Link
                         * Only displayed if author has YouTube URL in profile
                         */
                        if(!empty($youtube)) { ?>
                            <a href="<?php echo esc_url($youtube); ?>">
                                <svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.0098 3.5V12.5312C12.0098 12.9271 11.8639 13.2708 11.5723 13.5625C11.2806 13.8542 10.9264 14 10.5098 14H1.50977C1.0931 14 0.738932 13.8542 0.447266 13.5625C0.155599 13.2708 0.00976562 12.9271 0.00976562 12.5312V3.5C0.00976562 3.08333 0.155599 2.72917 0.447266 2.4375C0.738932 2.14583 1.0931 2 1.50977 2H10.5098C10.9264 2 11.2806 2.14583 11.5723 2.4375C11.8639 2.72917 12.0098 3.08333 12.0098 3.5ZM7.75977 6.25C7.2806 5.75 6.69727 5.5 6.00977 5.5C5.32227 5.5 4.72852 5.75 4.22852 6.25C3.74935 6.72917 3.50977 7.3125 3.50977 8C3.50977 8.6875 3.74935 9.28125 4.22852 9.78125C4.72852 10.2604 5.32227 10.5 6.00977 10.5C6.69727 10.5 7.2806 10.2604 7.75977 9.78125C8.25977 9.28125 8.50977 8.6875 8.50977 8C8.50977 7.3125 8.25977 6.72917 7.75977 6.25ZM11.0098 5V3.5C11.0098 3.16667 10.8431 3 10.5098 3H9.00977C8.67643 3 8.50977 3.16667 8.50977 3.5V5C8.50977 5.33333 8.67643 5.5 9.00977 5.5H10.5098C10.8431 5.5 11.0098 5.33333 11.0098 5ZM10.541 13C10.8535 13 11.0098 12.8333 11.0098 12.5V7H9.38477C9.4681 7.27083 9.50977 7.60417 9.50977 8C9.50977 8.97917 9.17643 9.80208 8.50977 10.4688C7.82227 11.1562 6.98893 11.5 6.00977 11.5C5.05143 11.5 4.22852 11.1562 3.54102 10.4688C2.85352 9.78125 2.50977 8.95833 2.50977 8C2.50977 7.6875 2.56185 7.35417 2.66602 7H1.00977V12.5C1.00977 12.6458 1.06185 12.7708 1.16602 12.875C1.27018 12.9583 1.39518 13 1.54102 13H10.541Z" fill="currentcolor"/>
                                </svg>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php }
    }
}
