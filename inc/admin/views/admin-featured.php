<!-- 
    portfoliocraft Theme Featured Benefits Section
    
    This section displays the key benefits and features available to users
    who activate or upgrade their theme. Each benefit is presented in an
    icon box format with visual indicators and descriptive content.
    
    Layout: Vertical stack of feature boxes with icons and descriptions
    Purpose: Highlight premium features and encourage theme activation
-->

<!-- 
    Auto Updates Feature Box
    
    Highlights the automatic update functionality that keeps the theme
    and associated plugins current with the latest versions and security patches
-->
<div class="rmt-iconbox">
    <!-- 
        Icon Container
        
        Visual indicator using a check mark image to represent
        the completed/available status of this feature
    -->
    <span class="rmt-icon-container">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/check.png'); ?>" 
             alt="<?php esc_attr_e('Check', 'portfoliocraft'); ?>">
    </span>
    
    <!-- 
        Feature Content
        
        Contains the feature title and detailed description
        explaining the auto-update benefit and lifetime access
    -->
    <div class="rmt-iconbox-contents">
        <h6><?php esc_html_e('Enable Auto Updates', 'portfoliocraft'); ?></h6>
        <p><?php esc_html_e('Smart Dashboard keeps your site up-to-date. Free for lifetime.', 'portfoliocraft'); ?></p>
    </div>
</div>

<!-- 
    Premium Plugins Feature Box
    
    Showcases access to exclusive and premium plugins that are included
    with theme activation, providing additional value and functionality
-->
<div class="rmt-iconbox">
    <!-- 
        Icon Container
        
        Check mark icon indicating this premium feature is available
        upon theme activation or registration
    -->
    <span class="rmt-icon-container">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/check.png'); ?>" 
             alt="<?php esc_attr_e('Check', 'portfoliocraft'); ?>">
    </span>
    
    <!-- 
        Feature Content
        
        Describes the premium plugin access benefit, emphasizing
        the free access to normally paid plugins and exclusive tools
    -->
    <div class="rmt-iconbox-contents">
        <h6><?php esc_html_e('Exclusive and Premium Plugins', 'portfoliocraft'); ?></h6>
        <p><?php esc_html_e('Get access to premium and exclusive plugins for free.', 'portfoliocraft'); ?></p>
    </div>
</div>

<!-- 
    Premium Support Feature Box
    
    Highlights the enhanced support services available to activated users
    including priority assistance and expert technical help
-->
<div class="rmt-iconbox">
    <!-- 
        Icon Container
        
        Check mark visual confirming the availability of premium support
        services for registered theme users
    -->
    <span class="rmt-icon-container">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/check.png'); ?>" 
             alt="<?php esc_attr_e('Check', 'portfoliocraft'); ?>">
    </span>
    
    <!-- 
        Feature Content
        
        Emphasizes the quality of support available, using "top-notch"
        to convey premium service level and expert assistance
    -->
    <div class="rmt-iconbox-contents">
        <h6><?php esc_html_e('Premium Support', 'portfoliocraft'); ?></h6>
        <p><?php esc_html_e('Get access to top-notch support.', 'portfoliocraft'); ?></p>
    </div>
</div>
