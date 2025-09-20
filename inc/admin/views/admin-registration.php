<?php
/**
 * portfoliocraft Theme Registration Status Display
 * 
 * This template displays the current theme registration/activation status
 * and provides appropriate interface elements based on activation state.
 * Handles both activated and non-activated states with proper messaging.
 * 
 * Features:
 * - Development mode detection
 * - License validation status checking
 * - Success confirmation for activated themes
 * - Registration form for non-activated themes
 * - Support and documentation links
 */

/**
 * Registration Status Detection
 * 
 * Determines current theme activation status by checking:
 * - Development mode flag
 * - Stored purchase code
 * - Validation status from server
 */

// Check if development mode is enabled
$dev_mode = (defined('DEV_MODE') && DEV_MODE); 
 
// Get stored license/purchase code (trimmed for validation)
$license = trim(get_option(portfoliocraft()->get_slug() . '_purchase_code'));

// Check if theme is properly activated
$active = get_option(portfoliocraft()->get_slug() . '_purchase_code_status', false) === 'valid';

// Override activation status in development mode
if ($dev_mode === true) {
    $active = true;
}

// Initialize registration handler
$register = new portfoliocraft_Register;

/**
 * Server Information Configuration
 * 
 * Defines support and documentation URLs with filter support
 * for customization by child themes or plugins
 */
$rmt_server_info = apply_filters('rmt_server_info', [
    'docs_url' => 'https://doc.portfoliocraft-themes.net/', 
    'support_url' => 'https://portfoliocraft-themes.ticksy.com/'
]);
?>

<?php if ($active): ?>
    <!-- 
        Activated Theme Interface
        
        Displayed when theme is successfully registered and activated
        Shows confirmation message and provides deactivation option
    -->
    <div class="rmt-dsb-box-head"> 
        
        <!-- 
            Success Confirmation Section
            
            Displays positive confirmation message with support link
            Reassures user that activation was successful
        -->
        <div class="rmt-dsb-confirmation success">
            <h6><?php echo esc_html__('Thanks for the verification!', 'portfoliocraft'); ?></h6>
            <p>
                <?php echo esc_html__('You can now enjoy and build great websites. Looking for help? Visit', 'portfoliocraft'); ?> 
                <a href="<?php echo esc_url($rmt_server_info['support_url']); ?>" target="_blank">
                    <?php echo esc_html__('submit a ticket', 'portfoliocraft'); ?>
                </a>.
            </p>
        </div> 

        <!-- 
            Deactivation Section
            
            Provides option to remove purchase code and deactivate theme
            Uses secure form with hidden action field and nonce protection
        -->
        <div class="rmt-dsb-deactive">
            <form method="POST" action="<?php echo admin_url('admin.php?page=rmtart'); ?>">
                <!-- Hidden field to specify the deactivation action -->
                <input type="hidden" name="action" value="removekey"/>
                
                <!-- 
                    Deactivation Button
                    
                    Allows users to remove their purchase code if needed
                    for transferring license or troubleshooting
                -->
                <button class="btn button" type="submit">
                    <?php esc_html_e('Remove Purchase Code', 'portfoliocraft'); ?>
                </button>
            </form>
        </div> 
    </div> 

<?php else: ?>
    <!-- 
        Non-Activated Theme Interface
        
        Displayed when theme is not yet registered or activation failed
        Shows registration form and any relevant error/status messages
    -->
    <?php 
    /**
     * Display Registration Messages
     * 
     * Shows any error messages, validation feedback, or instructions
     * from the registration handler (success, error, validation issues)
     */
    $register->messages(); 
    ?>
      
<?php endif; ?>
