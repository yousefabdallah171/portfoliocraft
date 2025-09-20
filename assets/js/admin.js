(function($) {
    "use strict";
    var rmt_ajax_url = '', api_url = '', theme_slug = '';
    $(document).ready(function () {
        if( typeof merlin_params !== 'undefined'){
            rmt_ajax_url = merlin_params.ajaxurl;
            api_url = merlin_params.api_url;
            theme_slug = merlin_params.theme_slug;
        }
        if(  typeof rmtart_admin !== 'undefined'){
            rmt_ajax_url = rmtart_admin.ajaxurl;
            api_url = rmtart_admin.api_url;
            theme_slug = rmtart_admin.theme_slug;
        }
        initTabs();
        // Demo import functionality removed - using OCDI system instead
        initPlugin();
        initUserProfile();
    });

    function initTabs(){
        $(document).on('click','.rmt-tab-nav > ul > li > a',function(){
            var data_filter = $(this).attr('data-filter');
            $(this).closest('ul').find('a').removeClass('active');
            $(this).addClass('active');
            $(this).closest('.rmt-demos').find('.rmt-col:not(.'+data_filter+')').css('display','none');
            $(this).closest('.rmt-demos').find('.rmt-col.'+data_filter).css('display','flex');
        });
    }

 
     
      
    function reload() {
        setTimeout(function(){ location.reload(); }, 5000);
    }

    function rmtPluginManager(){
        var complete;
        var items_completed     = 0;
        var current_item        = "";
        var $current_node;
        var current_item_hash   = "";

        function ajax_callback(response){  
            var currentSpan = $current_node.find("h3>span"); 
            var current_btn = $current_node.find(".rmt-button"); 
            var new_text = current_btn.attr('data-text-active');
            var new_href = current_btn.attr('data-deactive-url');

            if(typeof response === "object" && typeof response.message !== "undefined"){
                currentSpan.html('Active');
                current_btn.find('span').html(new_text);
                $current_node.removeClass( 'installing success error' ).addClass(response.message.toLowerCase());
                current_btn.attr('href',new_href);

                // The plugin is done (installed, updated and activated).
                if(typeof response.done != "undefined" && response.done){ 
                    $current_node.removeClass('current');
                    find_next();
                }else if(typeof response.url != "undefined"){
                    // we have an ajax url action to perform.
                    if(response.hash == current_item_hash){             
                        $current_node.removeClass( 'installing success' ).addClass("error");
                        current_btn.find('span').html('Error');
                        find_next();
                    }else {
                        current_item_hash = response.hash;
                        jQuery.post(response.url, response, ajax_callback).fail(ajax_callback);
                    }
                }else{
                    // error processing this plugin
                    find_next();
                }
            }else{
                // The TGMPA returns a whole page as response, so check, if this plugin is done.
                process_current();
            }
        }

        function process_current(){ 
            if(current_item){
                $current_node.addClass("current");    
                jQuery.post(rmt_ajax_url, {
                    action: "merlin_plugins",
                    wpnonce: rmtart_admin.wpnonce,
                    slug: current_item,
                }, ajax_callback).fail(ajax_callback);
                
            }
        }
 

        function find_next(){  
            if($current_node){ 
                if(!$current_node.hasClass("rmt-dsb-plugin-active")){
                    items_completed++;
                    $current_node.addClass("rmt-dsb-plugin-active");
                }
            }

            var $plus_item = $('.rmt-plugin-inst');
            if( $plus_item.length > 0 ){
                $plus_item.each(function(){
                    var $item = $(this).closest('.rmt-dsb-plugin');

                    if ( $item.hasClass("rmt-dsb-plugin-active") ) {
                        return true;
                    }
                    
                    current_item = $item.data("slug");
                    $current_node = $item;
                    process_current();
                    return false;
                });
            }
            
            if(items_completed >= $plus_item.length){
                // finished all plugins!
                complete();
            }
        }

        return {
            init: function(){
 
                $('.rmt-install-all-plugin').addClass("installing");
                $('.rmt-dsb-plugin:not(.rmt-dsb-plugin-active)').addClass("installing");
                complete = function(){

                    setTimeout(function(){
                        $(".rmt-dashboard-wrap").addClass('js-plugin-finished');
                        $('.rmt-install-all-plugin').removeClass("installing");
                    },1000);
 
                };
                find_next();
            }
        }
    }

    function initPlugin(){
        $(".rmt-install-all-plugin").on( "click", function(e) {
            e.preventDefault();
            var plugins = new rmtPluginManager();
            plugins.init();
        });
    }

    function initUserProfile(){
        $(document).on('click', '.rmt-select-image',  function (e) {
            e.preventDefault();
            var $this = $(this);
            var image = wp.media({
                title: 'Upload image',
                multiple: false
            }).open()
                .on('select', function (e) {
                    // This will return the selected image from the Media Uploader, the result is an object
                    var uploaded_image = image.state().get('selection').first();
                    // We convert uploaded_image to a JSON object to make accessing it easier
                    // Output to the console uploaded_image
                    var image_url = uploaded_image.toJSON().id;
                    // Let's assign the url value to the input field
                    $this.parent().find('.hide-image-url').val(image_url);
                    $this.parent().find('.rmt-show-image').empty();
                    $this.parent().find('.rmt-show-image').append('<img src = "' + uploaded_image.toJSON().url + '">');
                    $this.hide();
                    $this.parent().find('.rmt-remove-image').show();
                    $this.parents('form').find('input[name="savewidget"]').removeAttr('disabled');
                });
        });

        $(document).on('click', '.rmt-remove-image', function (e) {
            e.preventDefault();
            var $this = $(this);
            $this.parent().find('.hide-image-url').val('');
            $this.parent().find('.rmt-show-image').empty();
            $this.hide();
            $this.parent().find('.rmt-select-image').show();
            $this.parents('form').find('input[name="savewidget"]').removeAttr('disabled');
        });
    }

    function rmt_esc_js(str){
        return String(str).replace(/[^\w. ]/gi, function(c){
            return '&#'+c.charCodeAt(0)+';';
        });
    }

    // Dark/Light Mode Toggle for Dashboard
    $(document).ready(function() {
        var btn = document.getElementById('rmt-mode-toggle');
        if (!btn) return;
        var body = document.body;
        // Set initial mode from localStorage
        if(localStorage.getItem('rmt-dashboard-mode') === 'light') {
            body.classList.add('rmt-light-mode');
            btn.textContent = 'Switch to Dark Mode';
            btn.setAttribute('aria-pressed', 'true');
        }
        btn.addEventListener('click', function() {
            body.classList.toggle('rmt-light-mode');
            var isLight = body.classList.contains('rmt-light-mode');
            btn.textContent = isLight ? 'Switch to Dark Mode' : 'Switch to Light Mode';
            btn.setAttribute('aria-pressed', isLight ? 'true' : 'false');
            localStorage.setItem('rmt-dashboard-mode', isLight ? 'light' : 'dark');
        });
    });

    // Template Kit Import Functionality
    $(document).ready(function() {
        initTemplateKitImport();
    });

    function initTemplateKitImport() {
        var $uploadZone = $('#rmt-upload-zone');
        var $fileInput = $('#rmt-kit-file-input');
        var $selectBtn = $('#rmt-select-kit-file');
        var $progress = $('#rmt-kit-progress');
        var $progressFill = $('#rmt-kit-progress-fill');
        var $progressText = $('#rmt-kit-progress-text');
        var $importOptions = $('#rmt-kit-import-options');
        var $importBtn = $('#rmt-start-kit-import');
        var $status = $('#rmt-kit-status');
        var uploadedFilePath = '';

        // File selection
        $selectBtn.on('click', function() {
            $fileInput.click();
        });

        $fileInput.on('change', function() {
            var files = this.files;
            if (files.length > 0) {
                handleKitFile(files[0]);
            }
        });

        // Drag and drop
        $uploadZone.on('dragover dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('rmt-drag-over');
        });

        $uploadZone.on('dragleave dragend', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('rmt-drag-over');
        });

        $uploadZone.on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('rmt-drag-over');
            
            var files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                handleKitFile(files[0]);
            }
        });

        // Start import
        $importBtn.on('click', function() {
            if (!uploadedFilePath) {
                showKitMessage('Please upload a kit file first.', 'error');
                return;
            }
            startKitImport();
        });

        function handleKitFile(file) {
            // Validate file type
            if (!file.name.toLowerCase().endsWith('.zip')) {
                showKitMessage('Please select a valid zip file.', 'error');
                return;
            }

            uploadKitFile(file);
        }

        function uploadKitFile(file) {
            var formData = new FormData();
            formData.append('action', 'rakmyat_upload_kit');
            formData.append('nonce', rmtart_admin.nonce);
            formData.append('kit_file', file);

            $progress.show();
            updateKitProgress(0, 'Uploading...');

            $.ajax({
                url: rmt_ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            var percentComplete = (e.loaded / e.total) * 100;
                            updateKitProgress(percentComplete, 'Uploading...');
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    if (response.success) {
                        updateKitProgress(100, 'Upload complete');
                        uploadedFilePath = response.data.file_path;
                        $importOptions.show();
                        showKitMessage('Kit uploaded successfully. Ready to import.', 'success');
                    } else {
                        showKitMessage(response.data || 'Upload failed', 'error');
                        $progress.hide();
                    }
                },
                error: function() {
                    showKitMessage('Upload failed. Please try again.', 'error');
                    $progress.hide();
                }
            });
        }

        function startKitImport() {
            var importOptions = {
                import_site_settings: $('#rmt-kit-site-settings').is(':checked'),
                import_global_colors: $('#rmt-kit-global-colors').is(':checked'),
                import_global_fonts: $('#rmt-kit-global-fonts').is(':checked'),
                import_templates: $('#rmt-kit-templates').is(':checked')
            };

            $importBtn.prop('disabled', true).find('span').text('Importing...');
            $status.show().html('<div class="rmt-kit-importing">Importing Template Kit...</div>');

            $.ajax({
                url: rmt_ajax_url,
                type: 'POST',
                data: {
                    action: 'rakmyat_import_kit',
                    nonce: rmtart_admin.nonce,
                    kit_path: uploadedFilePath,
                    import_options: importOptions
                },
                success: function(response) {
                    $importBtn.prop('disabled', false).find('span').text('Import Template Kit');
                    
                    if (response.success) {
                        $status.html('<div class="rmt-kit-success"><h6>Import Successful!</h6><p>' + response.data.message + '</p></div>');
                        showKitMessage('Template Kit imported successfully!', 'success');
                    } else {
                        $status.html('<div class="rmt-kit-error"><h6>Import Failed</h6><p>' + response.data.message + '</p></div>');
                        showKitMessage(response.data.message || 'Import failed', 'error');
                    }
                },
                error: function() {
                    $importBtn.prop('disabled', false).find('span').text('Import Template Kit');
                    $status.html('<div class="rmt-kit-error"><h6>Import Failed</h6><p>Network error occurred</p></div>');
                    showKitMessage('Import failed. Please try again.', 'error');
                }
            });
        }

        function updateKitProgress(percent, text) {
            $progressFill.css('width', percent + '%');
            $progressText.text(Math.round(percent) + '% - ' + text);
        }

        function showKitMessage(message, type) {
            var messageClass = 'rmt-kit-message rmt-kit-' + type;
            var messageHtml = '<div class="' + messageClass + '">' + message + '</div>';
            
            // Remove existing messages
            $('.rmt-kit-message').remove();
            
            // Add new message
            $status.prepend(messageHtml);
            
            // Auto-hide success messages
            if (type === 'success') {
                setTimeout(function() {
                    $('.rmt-kit-message.rmt-kit-success').fadeOut();
                }, 5000);
            }
        }
    }

})(jQuery);
 

