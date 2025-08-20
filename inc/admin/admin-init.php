<?php
/**
* The portfoliocraft_Admin initiate the theme admin
*/

if( !defined( 'ABSPATH' ) )
	exit; 
require_once get_template_directory() . '/inc/classes/class-base.php';
require_once get_template_directory() . '/inc/admin/libs/tgmpa/class-tgm-plugin-activation.php' ; 
require_once get_template_directory() . '/inc/admin/admin-require-plugins.php'; 

class portfoliocraft_Admin extends portfoliocraft_Base{

	public function __construct() {

		$this->add_action( 'init', 'init', 7 ); 
		$this->add_action( 'admin_enqueue_scripts', 'enqueue', 99 );
		$this->add_action( 'admin_init', 'save_plugins' );
		$this->add_action( 'admin_menu', 'fix_parent_menu', 999 ); 
		$this->add_action( 'admin_notices', 'add_admin_tabs_to_templates_page' );
		$this->add_action( 'admin_notices', 'add_admin_tabs_to_theme_options_page' );
		$this->add_action( 'admin_notices', 'add_admin_tabs_to_system_status_page' );
		$this->add_action( 'admin_notices', 'add_admin_tabs_to_ocdi_page' );
	}

	public function init() {
		 
		require_once get_template_directory() . '/inc/admin/libs/merlin/class-merlin.php';
		require_once get_template_directory() . '/inc/admin/libs/merlin/merlin-config.php';

		require_once get_template_directory() . '/inc/admin/updater/register-admin.php';
		require_once get_template_directory() . '/inc/admin/admin-page.php';
		require_once get_template_directory() . '/inc/admin/admin-dashboard.php';
		require_once get_template_directory() . '/inc/admin/admin-plugins.php' ;
		if( class_exists('pxltheme_Core'))
			require_once get_template_directory() . '/inc/admin/admin-templates.php' ;
	}
 	
 	public function enqueue() {
		$pxl_server_info = apply_filters( 'pxl_server_info', ['api_url' => ''] ) ;
		wp_enqueue_style( 'pxlart-dashboard', get_template_directory_uri() . '/assets/css/dashboard.css' );

		if ( ! did_action( 'wp_enqueue_media' ) ) {
	        wp_enqueue_media();
	    }
		wp_enqueue_script( 'pxlart-admin', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery'), false, true );
		wp_localize_script( 'pxlart-admin', 'pxlart_admin', array(
			'ajaxurl'        => admin_url( 'admin-ajax.php' ),
			'wpnonce'        => wp_create_nonce( 'merlin_nonce' ),
			'api_url' 		 => $pxl_server_info['api_url'],
			'theme_slug'     => portfoliocraft()->get_slug()
		));
	}

	  
	public function save_plugins() {

        if ( !current_user_can( 'edit_theme_options' ) ) {
            return;
        }

		// Deactivate Plugin
        if ( isset( $_GET['pxl-deactivate'] ) && 'deactivate-plugin' == sanitize_text_field($_GET['pxl-deactivate']) ) {

			check_admin_referer( 'pxl-deactivate', 'pxl-deactivate-nonce' );

			$plugins = TGM_Plugin_Activation::$instance->plugins;

			foreach( $plugins as $plugin ) {
				if ( $plugin['slug'] == sanitize_text_field($_GET['plugin']) ) {

					deactivate_plugins( $plugin['file_path'] );

                    wp_redirect( admin_url( 'admin.php?page=' . sanitize_text_field($_GET['page']) ) );
					exit;
				}
			}
		}

		// Activate plugin
		if ( isset( $_GET['pxl-activate'] ) && 'activate-plugin' == sanitize_text_field($_GET['pxl-activate']) ) {

			check_admin_referer( 'pxl-activate', 'pxl-activate-nonce' );

			$plugins = TGM_Plugin_Activation::$instance->plugins;

			foreach( $plugins as $plugin ) {
				if ( $plugin['slug'] == sanitize_text_field($_GET['plugin']) ) {

					activate_plugin( $plugin['file_path'] );

					wp_redirect( admin_url( 'admin.php?page=' . sanitize_text_field($_GET['page']) ) );
					exit;
				}
			}
		}
    }

    public function fix_parent_menu() {

        if ( !current_user_can( 'edit_theme_options' ) ) {
            return;
        }
		 
		global $submenu;
 
		$submenu['pxlart'][0][0] = portfoliocraft()->get_name().' '.esc_html__( 'Dashboard', 'portfoliocraft' );

		//remove_submenu_page( 'themes.php', 'tgmpa-install-plugins' );
		remove_submenu_page( 'tools.php', 'redux-about' );
	}

	/**
	 * Add Admin Navigation Tabs to Templates Page
	 * 
	 * Displays the admin navigation tabs on the pxl-template post type admin page
	 * for consistent navigation across all theme admin pages
	 */
	public function add_admin_tabs_to_templates_page() {
		global $pagenow, $post_type;
		
		// Only add tabs on the pxl-template post type page
		if ($pagenow === 'edit.php' && $post_type === 'pxl-template') {
			echo '<div class="pxl-dashboard-wrap">';
			get_template_part('inc/admin/views/admin-tabs');
			echo '</div>';
		}
	}

	/**
	 * Add Admin Navigation Tabs to Theme Options Page
	 * 
	 * Displays the admin navigation tabs on the Redux theme options page
	 * for consistent navigation across all theme admin pages
	 */
	public function add_admin_tabs_to_theme_options_page() {
		global $pagenow;
		
		// Only add tabs on the theme options page
		if ($pagenow === 'admin.php' && isset($_GET['page']) && 'pxlart-theme-options' === sanitize_text_field($_GET['page'])) {
			// Output the navigation tabs at the top of the page
			echo '<div class="pxl-dashboard-wrap" style="margin-bottom: 20px;">';
			get_template_part('inc/admin/views/admin-tabs');
			echo '</div>';
		}
	}

	/**
	 * Add Admin Navigation Tabs to System Status Page
	 * 
	 * Displays the admin navigation tabs on the system status page
	 * for consistent navigation across all theme admin pages
	 */
	public function add_admin_tabs_to_system_status_page() {
		global $pagenow;
		
		// Only add tabs on the system status page
		if ($pagenow === 'admin.php' && isset($_GET['page']) && 'Rakmyat-system-status' === sanitize_text_field($_GET['page'])) {
			// Output the navigation tabs at the top of the page
			echo '<div class="pxl-dashboard-wrap" style="margin-bottom: 20px;">';
			get_template_part('inc/admin/views/admin-tabs');
			echo '</div>';
		}
	}

	/**
	 * Add Admin Navigation Tabs to OCDI (One Click Demo Import) Page
	 * 
	 * Displays the admin navigation tabs on the OCDI demo import page
	 * for consistent navigation across all theme admin pages
	 */
	public function add_admin_tabs_to_ocdi_page() {
		global $pagenow;
		
		// Only add tabs on the OCDI page
		if ($pagenow === 'themes.php' && isset($_GET['page']) && 'one-click-demo-import' === sanitize_text_field($_GET['page'])) {
			// Output the navigation tabs at the top of the page
			echo '<div class="pxl-dashboard-wrap" style="margin-bottom: 20px;">';
			get_template_part('inc/admin/views/admin-tabs');
			echo '</div>';
		}
	}
}
 
new portfoliocraft_Admin;

