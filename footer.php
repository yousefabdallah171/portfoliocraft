<?php
/**
 * Footer Template
 *
 * Contains the closing of the #pxl-main div and all content after.
 * This template handles footer display, smooth scrolling, and back to top functionality.
 *
 * @package portfoliocraft-Themes
 * @since 1.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Get smooth scroll option from theme settings
$smooth_scroll = portfoliocraft()->get_opt('smooth_scroll', 'off');  

// Get back to top button option from theme settings
$back_to_top = portfoliocraft()->get_opt('back_to_top', false);
$back_to_top_position = portfoliocraft()->get_opt('back_to_top_position', 'right');
?>

		</div><!-- #pxl-main -->
		
		<!-- Footer section -->
		<?php portfoliocraft()->footer->getFooter(); ?>
		
		<!-- Back to Top Button -->
		<?php if ($back_to_top) : ?>
			<button id="pxl-back-to-top" class="pxl-back-to-top <?php echo esc_attr($back_to_top_position); ?>" aria-label="Back to top">
				<span class="pxl-back-to-top__icon">&uarr;</span>
			</button>
		<?php endif; ?>
		
		<!-- Close smooth scroll wrapper if enabled -->
		<?php if ($smooth_scroll === 'on') : ?>
			</div><!-- #smooth-content -->
			</div><!-- #smooth-wrapper -->
		<?php endif; ?>
		
		<!-- Anchor target hook for custom functionality -->
		<?php do_action('pxl_anchor_target'); ?>
		
		</div><!-- #pxl-wrapper -->
		
	<!-- WordPress footer hook - Essential for plugins -->
	<?php wp_footer(); ?>
	
	</body>
</html>
