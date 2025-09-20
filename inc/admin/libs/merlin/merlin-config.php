<?php
/**
 * Merlin WP configuration file.
 *
 * @package   Merlin WP
 * @version   @@pkg.version
 * @link      https://merlinwp.com/
 * @author    Rich Tabor, from ThemeBeans.com & the team at ProteusThemes.com
 * @copyright Copyright (c) 2018, Merlin WP of Inventionn LLC
 * @license   Licensed GPLv3 for Open Source Use
 */

if ( ! class_exists( 'Merlin' ) ) {
	return;
}

/**
 * Set directory locations, text strings, and settings.
 */
$rmt_server_info = apply_filters( 'rmt_server_info', ['docs_url' => '', 'email_support' => ''] ) ;
$wizard = new Merlin(

	$config = array(
		'directory'            => 'merlin', // Location / directory where Merlin WP is placed in your theme.
		'merlin_url'           => 'rmtart-setup', // The wp-admin page slug where Merlin WP loads.
		'parent_slug'          => 'rmtart', // The wp-admin parent page slug for the admin menu item.
		'capability'           => 'manage_options', // The capability required for this menu to be displayed to the user.
		'child_action_btn_url' => 'https://codex.wordpress.org/child_themes', // URL for the 'child-action-link'.
		'dev_mode'             => false, // Enable development mode for testing.
		'license_step'         => true, // EDD license activation step.
		'license_required'     => true, // Require the license activation step.
		'license_help_url'     => 'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-', // URL for the 'license-tooltip'.
		'edd_remote_api_url'   => '', // EDD_Theme_Updater_Admin remote_api_url.
		'edd_item_name'        => '', // EDD_Theme_Updater_Admin item_name.
		'edd_theme_slug'       => '', // EDD_Theme_Updater_Admin item_slug.
		'ready_big_button_url' => home_url( '/' ), // Link for the big button on the ready step.
	),
	$strings = array(
		'admin-menu'               => esc_html__( 'Setup Wizard', 'portfoliocraft' ),

		/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
		'title%s%s%s%s'            => esc_html__( '%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'portfoliocraft' ),
		'return-to-dashboard'      => esc_html__( 'Return to the dashboard', 'portfoliocraft' ),
		'ignore'                   => esc_html__( 'Disable this wizard', 'portfoliocraft' ),

		'btn-skip'                 => esc_html__( 'Skip', 'portfoliocraft' ),
		'btn-next'                 => esc_html__( 'Next', 'portfoliocraft' ),
		'btn-start'                => esc_html__( 'Start', 'portfoliocraft' ),
		'btn-no'                   => esc_html__( 'Cancel', 'portfoliocraft' ),
		'btn-plugins-install'      => esc_html__( 'Install', 'portfoliocraft' ),
		'btn-child-install'        => esc_html__( 'Install', 'portfoliocraft' ),
		'btn-content-install'      => esc_html__( 'Install', 'portfoliocraft' ),
		'btn-import'               => esc_html__( 'Import', 'portfoliocraft' ),
		'btn-license-activate'     => esc_html__( 'Activate', 'portfoliocraft' ),
		'btn-license-skip'         => esc_html__( 'Later', 'portfoliocraft' ),

		/* translators: Theme Name */
		'license-header'         => esc_html__( 'Activate Theme', 'portfoliocraft' ),
		'license-header2'         => esc_html__( 'Activate Your Theme', 'portfoliocraft' ),
		/* translators: Theme Name */
		'license-header-success%s' => esc_html__( '%s is Activated', 'portfoliocraft' ),
		/* translators: Theme Name */
		'license%s'                => esc_html__( 'Please add your Envato purchase code to confirm the purchase.', 'portfoliocraft' ),
		'license-label'            => esc_html__( 'License key', 'portfoliocraft' ),
		'license-success%s'        => esc_html__( 'The theme is already registered, so you can go to the next step!', 'portfoliocraft' ),
		'license-json-success%s'   => esc_html__( 'Your theme is activated! Remote updates and theme support are enabled.', 'portfoliocraft' ),
		'license-tooltip'          => esc_html__( 'Need help?', 'portfoliocraft' ),

		/* translators: Theme Name */
		'welcome-header%s'         => esc_html__( 'Let\'s Get You Started', 'portfoliocraft' ),
		'welcome-header-success%s' => esc_html__( 'Hi. Welcome back', 'portfoliocraft' ),
		'welcome%s'                => esc_html__( 'Thanks for purchasing theme! You can now register your product to install plugins, import demos and unlock exlusive features.', 'portfoliocraft' ),
		'welcome-success%s'        => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'portfoliocraft' ),

		'child-header'             => esc_html__( 'Install Child Theme', 'portfoliocraft' ),
		'child-header-success'     => esc_html__( 'You\'re good to go!', 'portfoliocraft' ),
		'child'                    => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.', 'portfoliocraft' ),
		'child-success%s'          => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.', 'portfoliocraft' ),
		'child-action-link'        => esc_html__( 'Learn about child themes', 'portfoliocraft' ),
		'child-json-success%s'     => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.', 'portfoliocraft' ),
		'child-json-already%s'     => esc_html__( 'Awesome. Your child theme has been created and is now activated.', 'portfoliocraft' ),

		'plugins-header'           => esc_html__( 'Install Plugins', 'portfoliocraft' ),
		'plugins-header-success'   => esc_html__( 'Plugins are all installed', 'portfoliocraft' ),
		'plugins'                  => esc_html__( 'Let\'s install some essential WordPress plugins to get you started.', 'portfoliocraft' ),
		'plugins-success%s'        => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'portfoliocraft' ),
		'plugins-action-link'      => esc_html__( 'View Plugins', 'portfoliocraft' ),

		'import-header'            => esc_html__( 'Import a Demo', 'portfoliocraft' ),
		'import'                   => esc_html__( 'Choose a demo for importing to your website', 'portfoliocraft' ),
		'import-action-link'       => esc_html__( 'Advanced', 'portfoliocraft' ),

		'ready-header'             => esc_html__( 'You\'re Ready!', 'portfoliocraft' ),

		/* translators: Theme Author */
		'ready%s'                  => esc_html__( 'Your theme has been all set up. Enjoy your new theme by %s.', 'portfoliocraft' ),
		'ready-action-link'        => esc_html__( 'Extras', 'portfoliocraft' ),
		'ready-big-button'         => esc_html__( 'View your website', 'portfoliocraft' ),
		'ready-link-1'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', $rmt_server_info['docs_url'], esc_html__( 'Help center', 'portfoliocraft' ) ),
		'ready-link-2'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', $rmt_server_info['email_support'], esc_html__( 'Get Theme Support', 'portfoliocraft' ) ),
		'ready-link-3'             => sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=rmtart-theme-options' ), esc_html__( 'Theme Options', 'portfoliocraft' ) ),
	)
);