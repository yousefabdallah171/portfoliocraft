<?php 
/**
 * portfoliocraft System Information Display
 * 
 * This file provides system status checking and display functionality
 * for the portfoliocraft theme admin dashboard. It validates server configuration
 * against theme requirements and displays visual status indicators.
 * 
 * Features:
 * - PHP configuration validation
 * - Memory and file size limit checking
 * - Execution time and input variable limits
 * - Visual status indicators with icons
 */

/**
 * Convert Size String to Bytes
 * 
 * Converts PHP ini size values (like "64M", "2G") to actual byte values
 * for accurate comparison against minimum requirements
 * 
 * @param string $size Size string from PHP ini settings (e.g., "64M", "2G")
 * @return int Size converted to bytes
 */
function pxl_convert_to_byte($size) {
    // Extract the numeric value and unit label
    $label = substr($size, -1);
    $num = substr($size, 0, -1);
    
    // Convert based on unit type (case-insensitive)
    switch (strtoupper($label)) {
        case 'P': // Petabytes
            $num *= 1024;
        case 'T': // Terabytes
            $num *= 1024;
        case 'G': // Gigabytes
            $num *= 1024;
        case 'M': // Megabytes
            $num *= 1024;
        case 'K': // Kilobytes
            $num *= 1024;
    }
    
    return $num;
}

/**
 * Get System Information Array
 * 
 * Collects and validates various PHP configuration settings
 * against theme requirements. Returns array of status items
 * with titles and pass/fail status for each requirement.
 * 
 * @return array Array of system status items with title and status
 */
function pxl_get_system_info() {
    $system_info = array();

    /**
     * Upload Max File Size Check
     * 
     * Validates that the server allows file uploads of at least 64MB
     * Important for media uploads and demo content import
     */
    $upload_max_size = ini_get('upload_max_filesize');
    $upload_max_size_to_byte = pxl_convert_to_byte($upload_max_size);
    
    array_push(
        $system_info,
        [
            'title' => esc_attr__('Upload max file size (64MB)', 'portfoliocraft'),
            'status' => $upload_max_size_to_byte > 67108864 // 64MB in bytes
        ]
    );

    /**
     * Memory Limit Check
     * 
     * Ensures PHP has sufficient memory allocation (minimum 256MB)
     * Critical for theme functionality and plugin compatibility
     */
    $memory_limit = ini_get('memory_limit');
    $memory_limit_to_byte = pxl_convert_to_byte($memory_limit);

    array_push(
        $system_info,
        [
            'title' => esc_attr__('Memory limit (256MB)', 'portfoliocraft'),
            'status' => $memory_limit_to_byte >= 268435456, // 256MB in bytes
        ]
    );

    /**
     * Post Max Size Check
     * 
     * Validates maximum POST data size (minimum 64MB)
     * Essential for form submissions and admin operations
     */
    $post_maxsite = ini_get('post_max_size');
    $post_maxsite_to_byte = pxl_convert_to_byte($post_maxsite);

    array_push(
        $system_info,
        [
            'title' => esc_attr__('Post max size (64MB)', 'portfoliocraft'),
            'status' => $post_maxsite_to_byte >= 67108864, // 64MB in bytes
        ]
    );

    /**
     * Max Execution Time Check
     * 
     * Ensures scripts can run for sufficient time (minimum 360 seconds)
     * Important for demo imports and complex operations
     */
    array_push(
        $system_info,
        [
            'title' => esc_attr__('Max Execution Time (360s)', 'portfoliocraft'),
            'status' => ini_get('max_execution_time') >= 360,
        ]
    );

    /**
     * Max Input Variables Check
     * 
     * Validates that PHP can handle sufficient input variables (minimum 3000)
     * Required for complex forms and theme options
     */
    array_push(
        $system_info,
        [
            'title' => esc_attr__('Max input vars (3000)', 'portfoliocraft'),
            'status' => ini_get('max_input_vars') >= 3000,
        ]
    );
  
    return $system_info;
}

/**
 * Display System Status Items
 * 
 * Generates the visual display of system status checks
 * with appropriate icons and styling based on pass/fail status
 */
$system_status = pxl_get_system_info();

foreach ($system_status as $item) :
?>
    <!-- 
        System Status Item
        
        Individual status check display with icon and description
        Uses different icons based on whether requirement is met
    -->
    <div class="pxl-iconbox">
        
        <!-- 
            Status Icon Container
            
            Displays check mark for passed requirements
            or crossed icon for failed requirements
        -->
        <div class="pxl-icon-container">
            <?php if ($item['status']) : ?>
                <!-- Success icon for met requirements -->
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/check.png'); ?>" 
                     alt="<?php esc_attr_e('Check', 'portfoliocraft'); ?>">
            <?php else : ?>
                <!-- Failure icon for unmet requirements -->
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/crossed.png'); ?>" 
                     alt="<?php esc_attr_e('Un Check', 'portfoliocraft'); ?>">
            <?php endif; ?>
        </div>
        
        <!-- 
            Status Item Content
            
            Contains the requirement description with minimum values
            Helps users understand what needs to be configured
        -->
        <div class="pxl-iconbox-contents">
            <span class="status-item-title">
                <?php echo esc_html($item['title']); ?>
            </span>
        </div>
    </div>
<?php endforeach; ?>
