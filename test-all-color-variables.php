<?php
/**
 * Test File - Complete Color Variables Verification
 * 
 * This file demonstrates what CSS variables will be generated with all the new color controls.
 * 
 * EXPECTED OUTPUT when portfoliocraft_inline_styles() is called:
 * 
 * :root{
 *   --primary-color: var(--subtitle-text-color);
 *   --secondary-color: #010101;
 *   --third-color: #00c5fe;
 *   --body-bg-color: #fff;
 *   --white-text-color: #ffffff;
 *   --light-text-color: #f5f5f5;
 *   --off-white-color: #fafafa;
 *   --light-gray-text-color: #e0e0e0;
 *   --body-text-color: #333333;
 *   --heading-text-color: #1a1a1a;
 *   --post-title-color: #1a1a1a;           // Controls .pxl-title-text
 *   --subtitle-text-color: #666666;
 *   --meta-text-color: #999999;
 *   --comments-title-color: #1a1a1a;       // Controls .title-text & .comment-reply-title
 *   --comment-author-color: #333333;
 *   --comment-text-color: #555555;
 *   --comment-meta-color: #999999;
 *   --link-color: #1F1F1F;
 *   --link-color-hover: #F14F44;
 *   --link-color-active: #F14F44;
 *   --gradient-color-from: #6000ff;
 *   --gradient-color-to: #fe0054;
 *   --primary-font: Kanit;
 *   --secondary-font: Montserrat;
 *   --heading-font: Sora;
 * }
 */

// SOLUTION FOR USER'S SPECIFIC ELEMENTS:

// 1. .title-text (comments section)
// CSS: .title-text { color: var(--comments-title-color, #1a1a1a) !important; }
// Control: WordPress Admin → Theme Options → Global Colors → Comments Section Colors → Comments Title Color

// 2. .comment-reply-title ("Leave a Comment")  
// CSS: .comment-reply-title { color: var(--comments-title-color, #1a1a1a) !important; }
// Control: Same as above - Comments Title Color

// 3. .pxl-title-text (post titles)
// CSS: .pxl-title-text { color: var(--post-title-color, #1a1a1a) !important; }
// Control: WordPress Admin → Theme Options → Global Colors → Main Text Colors → Post Title Color

// HOW TO USE:
// 1. Go to WordPress Admin → Theme Options → Global Colors
// 2. Find "Main Text Colors" section → Change "Post Title Color" for .pxl-title-text
// 3. Find "Comments Section Colors" section → Change "Comments Title Color" for .title-text and .comment-reply-title
// 4. Save changes and view your single post page
// 5. All specified elements should now reflect your chosen colors

// This file is for testing/documentation only - do not include in production
?>

<!-- HTML EXAMPLES OF ELEMENTS THAT WILL BE STYLED: -->

<!-- Post Title (controlled by --post-title-color) -->
<h2 class="pxl-post-title">
    <span class="pxl-title-text">Hello world!</span>
</h2>

<!-- Comments Count (controlled by --comments-title-color) -->
<h3 class="comment-title">
    <span class="title-text">One Comment</span>
</h3>

<!-- Comment Form Title (controlled by --comments-title-color) -->
<h3 id="reply-title" class="comment-reply-title">
    Leave a Comment 
    <small>
        <a rel="nofollow" id="cancel-comment-reply-link" href="#respond" style="display:none;">
            Cancel Reply
        </a>
    </small>
</h3>