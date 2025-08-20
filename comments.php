<?php
/**
 * Comments Template
 *
 * Displays comments and comment form for posts and pages.
 * This template handles both existing comments display and new comment submission form.
 *
 * @package portfoliocraft-Themes
 * @since 1.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Exit if post is password protected and password hasn't been entered
if (post_password_required()) {
    return;
}

// Enqueue comments-specific styles
wp_enqueue_style('portfoliocraft-comments', get_template_directory_uri() . '/assets/css/comments.css', array('portfoliocraft-style'), '1.0.0');
?>

<!-- Comments section container -->
<div id="comments" class="comments-area">
    
    <?php if (have_comments()) : ?>
        <!-- Comments wrapper -->
        <div class="comment-wrapper">
            
            <!-- Comments title with count -->
            <h3 class="comment-title">
                <span class="title-text">
                    <?php
                    printf(
                        esc_html(_nx('One Comment', '%1$s Comments', get_comments_number(), 'comments title', 'portfoliocraft')),
                        number_format_i18n(get_comments_number())
                    );
                    ?>
                </span>
            </h3>

            <!-- Comments navigation (top) -->
            <?php the_comments_navigation(); ?>

            <!-- Comments list -->
            <ul class="comment-list">
                <?php
                wp_list_comments(array(
                    'style'      => 'ul',
                    'short_ping' => true,
                    'callback'   => 'portfoliocraft_comment_list',
                    'max_depth'  => 3
                ));
                ?>
            </ul>

            <!-- Comments navigation (bottom) -->
            <?php the_comments_navigation(); ?>
            
        </div>
        
        <!-- Message when comments are closed -->
        <?php if (!comments_open()) : ?>
            <p class="no-comments">
                <?php esc_html_e('Comments are closed.', 'portfoliocraft'); ?>
            </p>
        <?php endif; ?>
        
    <?php endif; ?>

    <?php
    // Get current commenter information
    $commenter = wp_get_current_commenter();
    
    // Comment form arguments
    $comment_form_args = array(
        'id_form'           => 'commentform',
        'id_submit'         => 'submit',
        'class_submit'      => 'btn pxl-btn-split',
        'title_reply'       => esc_html__('Leave a Comment', 'portfoliocraft'),
        'title_reply_to'    => esc_html__('Leave a Reply to %s', 'portfoliocraft'),
        'cancel_reply_link' => esc_html__('Cancel Reply', 'portfoliocraft'),
        'label_submit'      => esc_html__('Post Comment', 'portfoliocraft'),
        
        // Custom submit button with SVG icons
        'submit_button'     => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">
            <span class="pxl-btn-icon icon-duplicated">
                <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 38 38" fill="none">
                    <path d="M26.673 23.8894L26.5877 12.0397C26.5877 11.5282 26.1899 11.1304 25.6784 11.1304L13.8287 11.0451C13.3172 11.0451 12.9194 11.443 12.9194 11.9545C12.9194 12.466 13.3172 12.8638 13.8287 12.8638L23.4619 12.9491L11.3849 25.0261C11.0439 25.3671 11.0439 25.9354 11.3849 26.2764C11.7259 26.6174 12.3226 26.6458 12.6636 26.3048L24.7975 14.171L24.8828 23.9178C24.8828 24.1452 24.9964 24.3725 25.1669 24.543C25.3374 24.7135 25.5647 24.8272 25.8205 24.7988C26.2752 24.7988 26.7014 24.3725 26.673 23.8894Z" fill="currentcolor"/>
                </svg>
            </span>
            <span class="pxl-btn-text">' . esc_html__('Send Message', 'portfoliocraft') . '</span>
            <span class="pxl-btn-icon icon-main">
                <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 38 38" fill="none">
                    <path d="M26.673 23.8894L26.5877 12.0397C26.5877 11.5282 26.1899 11.1304 25.6784 11.1304L13.8287 11.0451C13.3172 11.0451 12.9194 11.443 12.9194 11.9545C12.9194 12.466 13.3172 12.8638 13.8287 12.8638L23.4619 12.9491L11.3849 25.0261C11.0439 25.3671 11.0439 25.9354 11.3849 26.2764C11.7259 26.6174 12.3226 26.6458 12.6636 26.3048L24.7975 14.171L24.8828 23.9178C24.8828 24.1452 24.9964 24.3725 25.1669 24.543C25.3374 24.7135 25.5647 24.8272 25.8205 24.7988C26.2752 24.7988 26.7014 24.3725 26.673 23.8894Z" fill="currentcolor"/>
                </svg>
            </span>
        </button>',
        
        // Comment form notice
        'comment_notes_before' => '<p class="comment-notes">' . 
            esc_html__('Your email address will not be published. Required fields are marked *', 'portfoliocraft') . 
            '</p>',
        
        // Form fields configuration
        'fields' => apply_filters('comment_form_default_fields', array(
            'author' => '<div class="form-control-group">
                <div class="comment-form-author form-control">
                    <input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" 
                           size="30" maxlength="245" required 
                           placeholder="' . esc_attr__('Name *', 'portfoliocraft') . '" />
                </div>',
            
            'email' => '<div class="comment-form-email form-control">
                    <input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" 
                           size="30" maxlength="100" aria-describedby="email-notes" required 
                           placeholder="' . esc_attr__('Email Address *', 'portfoliocraft') . '" />
                </div>
            </div>',
            
            'url' => '<div class="comment-form-url form-control">
                    <input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" 
                           size="30" maxlength="200" 
                           placeholder="' . esc_attr__('Website (Optional)', 'portfoliocraft') . '" />
                </div>'
        )),
        
        // Comment textarea field
        'comment_field' => '<div class="comment-form-comment form-control">
            <textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required 
                      placeholder="' . esc_attr__('Write your comment here...', 'portfoliocraft') . '" 
                      aria-required="true"></textarea>
        </div>',
    );
    
    // Display the comment form
    comment_form($comment_form_args);
    ?>
    
</div><!-- #comments -->
