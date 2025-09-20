<?php
/**
 * Default Footer Template
 * 
 * @package PortfolioCraft
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<footer id="rmt-footer-default">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php echo wp_kses_post(''.esc_attr(date("Y")).' &copy; All rights reserved by <a target="_blank" rel="nofollow" href="https://themeforest.net/user/rakmyat-themes/portfolio">portfoliocraft-Themes</a>'); ?>
            </div>
        </div>
    </div>
</footer>