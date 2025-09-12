/**
 * WooCommerce Custom Placeholders
 * 
 * Handles custom placeholder text for WooCommerce form fields
 * Part of PortfolioCraft theme
 * 
 * @package PortfolioCraft
 */

jQuery(document).ready(function($) {
    // Set custom placeholders for login/register forms
    if ($('#customer_login').length) {
        $('#customer_login #username').attr("placeholder", "Username or email address");
        $('#customer_login #password').attr("placeholder", "Password");
        $('#customer_login #reg_email').attr("placeholder", "Email address");
        $('#customer_login #reg_username').attr("placeholder", "Username");
        $('#customer_login #reg_password').attr("placeholder", "Password");
    }
    
    // Set placeholder for lost password form
    if ($('.lost_reset_password').length) {
        $('.lost_reset_password #user_login').attr("placeholder", "Username or email");
    }
});