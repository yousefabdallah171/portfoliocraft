<?php

// Force TGMPA to initialize if not already done
if (!did_action('tgmpa_register')) {
	do_action('tgmpa_register');
}

$installed_plugins = get_plugins();
$plugins = array();
$debug_info = '';

// Check if TGM Plugin Activation is available
if (!class_exists('TGM_Plugin_Activation')) {
	$debug_info = 'TGM_Plugin_Activation class not found';
} elseif (!isset(TGM_Plugin_Activation::$instance)) {
	$debug_info = 'TGM_Plugin_Activation instance not initialized';
} else {
	$plugins = TGM_Plugin_Activation::$instance->plugins;
	if (empty($plugins)) {
		$debug_info = 'TGM instance found but plugins array is empty. Check if tgmpa() was called.';
	} else {
		$debug_info = count($plugins) . ' plugins found';
	}
}

$status_ins = false;
$status_act = false;
$btn_text = esc_html__('Install All', 'portfoliocraft');

if (!empty($plugins)) {
	foreach( $plugins as $plugin ){
		$file_path = $plugin['file_path'];
		if( !isset( $installed_plugins[ $file_path ] ) ) {
			$status_ins = true;
			break;
		}
	}
	foreach( $plugins as $plugin ){
		$file_path = $plugin['file_path'];
		if ( is_plugin_inactive( $file_path ) ) {
			$status_act = true;
			break;
		}
	}
}

$merlin_setup = get_option( 'merlin_' . portfoliocraft()->get_slug() . '_completed' );
 
if( $status_ins && $status_act)
	$btn_text = esc_html__('Install & Active All', 'portfoliocraft');
else if($status_ins && !$status_act)
	$btn_text = esc_html__('Install All', 'portfoliocraft');
else if(!$status_ins && $status_act)
	$btn_text = esc_html__('Active All', 'portfoliocraft');
?>
<main>

	<div class="rmt-dashboard-wrap">

		<?php get_template_part( 'inc/admin/views/admin-tabs' ); ?>

		<?php

		$dev_mode = (defined('DEV_MODE') && DEV_MODE);
		$is_registered = ( 'valid' == get_option( portfoliocraft()->get_slug().'_purchase_code_status', false ) );

		if ( !$is_registered && !$dev_mode ) :

			echo '<div class="rmt-notice rmt-notice-warning" style="padding: 15px; margin: 20px 0; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: var(--radius); color: var(--text);">';
			echo '<p style="margin: 0;">' .
					sprintf( wp_kses_post( esc_html__( 'The %s theme needs to be registered for full functionality. %sRegister Now%s', 'portfoliocraft' ) ), portfoliocraft()->get_name(), '<a href="' . admin_url( 'admin.php?page=rmtart') . '" style="color: var(--primary); font-weight: 600;">' , '</a>' ) . '</p>';
			echo '</div>';

		endif;

		// Always show plugins page regardless of registration status
		?>
	
		<header class="rmt-dsb-header admin-plugin">
			<div class="rmt-dsb-header-inner">
				<h4><?php esc_html_e( 'Install Plugins', 'portfoliocraft' ); ?></h4>
				<?php if(!$merlin_setup && ($status_ins || $status_act)): 
					echo '<span class="rmt-install-all-plugin">'.$btn_text.'</span>';
					?>
				<?php endif; ?>
				
			</div> 
			<p><?php esc_html_e( 'Make sure to activate required plugins prior to import a demo.', 'portfoliocraft' ); ?></p> 
		</header>
		  
		<div class="rmt-solid-wrap">
			<div class="rmt-row">
	        <?php

				if (empty($plugins)) {
					echo '<div class="rmt-col rmt-col-12">';
					echo '<div class="rmt-notice rmt-notice-info" style="padding: 20px; margin: 20px 0; background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius); color: var(--text);">';
					echo '<h3 style="color: var(--text-light); margin-bottom: 10px;">' . esc_html__('No Plugins Available', 'portfoliocraft') . '</h3>';
					echo '<p>' . esc_html__('The plugin list is currently empty.', 'portfoliocraft') . '</p>';
					if (defined('WP_DEBUG') && WP_DEBUG && !empty($debug_info)) {
						echo '<p style="margin-top: 10px; padding: 10px; background: rgba(0,0,0,0.2); border-radius: 6px; font-family: monospace; font-size: 12px;">Debug: ' . esc_html($debug_info) . '</p>';
					}
					echo '</div>';
					echo '</div>';
				}

				foreach( $plugins as $plugin ) :
					$class = $status = $display_status = '';
					$file_path = $plugin['file_path'];
	
					// Install
					if( !isset( $installed_plugins[ $file_path ] ) ) {
						$status = 'not-installed';
					}
					// No Active
					elseif ( is_plugin_inactive( $file_path ) ) {
						$status = 'installed';
					}
					// Deactive
					elseif( !is_plugin_inactive( $file_path ) ) {
						$status = 'active';
						$class = ' rmt-dsb-plugin-active';
						$display_status = esc_html__( 'Active:', 'portfoliocraft' );
					}
			?>
				<div class="rmt-col rmt-col-3">
					<div class="rmt-dsb-plugin<?php echo esc_attr( $class ); ?>" data-slug="<?php echo esc_attr($plugin['slug']) ?>">
					<span class="rmt-dsb-plugin-icon">
						<img src="<?php echo esc_url( $plugin['logo'] ); ?>" alt="<?php echo esc_attr( $plugin['name'] ) ?>">
					</span>
					<h3><?php printf( '<span>%s</span>', $display_status ); ?> <?php echo esc_html( $plugin['name'] ) ?></h3>
					<p><?php echo esc_html( $plugin['description'] ) ?></p>
					
					<?php 
					$barplugin = new portfoliocraft_Admin_Plugins;
					$barplugin->tgmpa_plugin_action( $plugin, $status ); 
					?>
				</div> 
				</div> 

			<?php endforeach; ?>

			</div>
		</div>
	</div> 

</main>