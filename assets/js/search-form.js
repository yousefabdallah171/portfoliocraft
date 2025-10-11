/**
 * PortfolioCraft Search Form
 * @package PortfolioCraft
 */

(function($) {
    'use strict';

    // Search form functionality
    $(document).ready(function() {
        // Add focus class on input focus
        $('.portfoliocraft-search-form input[type="search"]').on('focus', function() {
            $(this).closest('.portfoliocraft-search-form').addClass('focused');
        }).on('blur', function() {
            $(this).closest('.portfoliocraft-search-form').removeClass('focused');
        });
    });

})(jQuery);
