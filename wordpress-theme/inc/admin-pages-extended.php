<?php
/**
 * EIF Backend - Extended Admin Pages
 * Complete content management for all pages including partners
 *
 * @package EIFBackend
 * @version 4.0.0
 * 
 * NOTE: Menu registration has been moved to admin-pages-v5.php for unified management.
 * This file now only contains the page rendering functions (Partners, Services, etc.)
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register admin menus - DISABLED
 * Menu registration is now handled by admin-pages-v5.php to avoid duplicate menus.
 * The functions below (eatisfamily_partners_page, etc.) are still used by v5.
 */
// function eatisfamily_register_admin_menus() { ... }
// add_action('admin_menu', 'eatisfamily_register_admin_menus', 5); // DISABLED

/**
 * ============================================
 * PARTNERS MANAGEMENT PAGE
 * ============================================
 */
function eatisfamily_partners_page() {
    // Handle form submission
    if (isset($_POST['eatisfamily_partners_nonce']) && 
        wp_verify_nonce($_POST['eatisfamily_partners_nonce'], 'save_partners')) {
        
        $partners_data = array(
            'title' => wp_kses_post($_POST['partners_title'] ?? ''),
            'partners' => array()
        );
        
        // Process partners
        if (isset($_POST['partner_logo']) && is_array($_POST['partner_logo'])) {
            foreach ($_POST['partner_logo'] as $index => $logo) {
                if (!empty($logo)) {
                    $partners_data['partners'][] = array(
                        'logo' => esc_url_raw($logo),
                        'alt' => sanitize_text_field($_POST['partner_alt'][$index] ?? ''),
                        'name' => sanitize_text_field($_POST['partner_name'][$index] ?? ''),
                        'url' => esc_url_raw($_POST['partner_url'][$index] ?? '')
                    );
                }
            }
        }
        
        update_option('eatisfamily_partners', $partners_data);
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Partners saved successfully!', 'eatisfamily') . '</p></div>';
    }
    
    // Get current values
    $partners_data = get_option('eatisfamily_partners', array());
    $partners = $partners_data['partners'] ?? array();
    $partners_title = $partners_data['title'] ?? '';
    
    ?>
    <div class="wrap">
        <h1><?php _e('Partners Management', 'eatisfamily'); ?></h1>
        <p class="description"><?php _e('Manage the partners/clients logos displayed on the homepage.', 'eatisfamily'); ?></p>
        
        <form method="post" action="">
            <?php wp_nonce_field('save_partners', 'eatisfamily_partners_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="partners_title"><?php _e('Section Title', 'eatisfamily'); ?></label></th>
                    <td>
                        <textarea name="partners_title" id="partners_title" class="large-text" rows="3"><?php echo esc_textarea($partners_title); ?></textarea>
                        <p class="description"><?php _e('The title displayed above the partners carousel. Use \n for line breaks.', 'eatisfamily'); ?></p>
                    </td>
                </tr>
            </table>
            
            <h2><?php _e('Partners List', 'eatisfamily'); ?></h2>
            <p class="description"><?php _e('Add, edit or remove partners. Drag to reorder.', 'eatisfamily'); ?></p>
            
            <div id="partners-list" class="eatisfamily-repeater">
                <?php 
                if (!empty($partners)) {
                    foreach ($partners as $index => $partner) {
                        eatisfamily_render_partner_row($index, $partner);
                    }
                } else {
                    // Show one empty row
                    eatisfamily_render_partner_row(0, array());
                }
                ?>
            </div>
            
            <p>
                <button type="button" class="button" id="add-partner"><?php _e('+ Add Partner', 'eatisfamily'); ?></button>
            </p>
            
            <?php submit_button(__('Save Partners', 'eatisfamily')); ?>
        </form>
    </div>
    
    <script type="text/html" id="partner-row-template">
        <?php eatisfamily_render_partner_row('{{INDEX}}', array()); ?>
    </script>
    
    <script>
    jQuery(document).ready(function($) {
        var partnerIndex = <?php echo count($partners); ?>;
        
        $('#add-partner').on('click', function() {
            var template = $('#partner-row-template').html();
            template = template.replace(/\{\{INDEX\}\}/g, partnerIndex);
            $('#partners-list').append(template);
            partnerIndex++;
        });
        
        $(document).on('click', '.remove-partner', function() {
            $(this).closest('.partner-row').remove();
        });
        
        // Media uploader
        $(document).on('click', '.eatisfamily-upload-media', function(e) {
            e.preventDefault();
            var button = $(this);
            var targetField = button.data('target');
            
            var mediaUploader = wp.media({
                title: '<?php _e('Select Image', 'eatisfamily'); ?>',
                button: { text: '<?php _e('Use this image', 'eatisfamily'); ?>' },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#' + targetField).val(attachment.url);
                button.siblings('.preview-image').remove();
                button.after('<img src="' + attachment.url + '" class="preview-image" style="max-width:150px;display:block;margin-top:10px;">');
            });
            
            mediaUploader.open();
        });
    });
    </script>
    
    <style>
    .partner-row {
        background: #fff;
        border: 1px solid #ccd0d4;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 4px;
    }
    .partner-row .form-field {
        margin-bottom: 10px;
    }
    .partner-row label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .partner-row input[type="text"] {
        width: 100%;
    }
    .remove-partner {
        color: #a00;
        cursor: pointer;
    }
    .remove-partner:hover {
        color: #dc3232;
    }
    .preview-image {
        max-width: 150px;
        margin-top: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    </style>
    <?php
}

function eatisfamily_render_partner_row($index, $partner) {
    $logo = $partner['logo'] ?? '';
    $alt = $partner['alt'] ?? '';
    $name = $partner['name'] ?? '';
    $url = $partner['url'] ?? '';
    ?>
    <div class="partner-row">
        <div class="form-field">
            <label><?php _e('Partner Name', 'eatisfamily'); ?></label>
            <input type="text" name="partner_name[<?php echo $index; ?>]" value="<?php echo esc_attr($name); ?>" placeholder="<?php _e('e.g. Adidas Arena', 'eatisfamily'); ?>">
        </div>
        <div class="form-field">
            <label><?php _e('Logo URL', 'eatisfamily'); ?></label>
            <input type="text" name="partner_logo[<?php echo $index; ?>]" id="partner_logo_<?php echo $index; ?>" value="<?php echo esc_attr($logo); ?>">
            <button type="button" class="button eatisfamily-upload-media" data-target="partner_logo_<?php echo $index; ?>"><?php _e('Select Image', 'eatisfamily'); ?></button>
            <?php if ($logo): ?>
                <img src="<?php echo esc_url($logo); ?>" class="preview-image">
            <?php endif; ?>
        </div>
        <div class="form-field">
            <label><?php _e('Alt Text', 'eatisfamily'); ?></label>
            <input type="text" name="partner_alt[<?php echo $index; ?>]" value="<?php echo esc_attr($alt); ?>" placeholder="<?php _e('e.g. Adidas Arena Logo', 'eatisfamily'); ?>">
        </div>
        <div class="form-field">
            <label><?php _e('Website URL (optional)', 'eatisfamily'); ?></label>
            <input type="text" name="partner_url[<?php echo $index; ?>]" value="<?php echo esc_attr($url); ?>" placeholder="https://">
        </div>
        <p><span class="remove-partner"><?php _e('Remove Partner', 'eatisfamily'); ?></span></p>
    </div>
    <?php
}

/**
 * ============================================
 * SERVICES MANAGEMENT PAGE
 * ============================================
 */
function eatisfamily_services_page() {
    // Handle form submission
    if (isset($_POST['eatisfamily_services_nonce']) && 
        wp_verify_nonce($_POST['eatisfamily_services_nonce'], 'save_services')) {
        
        $services_data = array(
            'tag' => sanitize_text_field($_POST['services_tag'] ?? ''),
            'title' => array(
                'line_1' => sanitize_text_field($_POST['services_title_line1'] ?? ''),
                'highlight' => sanitize_text_field($_POST['services_title_highlight'] ?? ''),
                'line_2' => sanitize_text_field($_POST['services_title_line2'] ?? '')
            ),
            'services' => array()
        );
        
        // Process services
        if (isset($_POST['service_title']) && is_array($_POST['service_title'])) {
            foreach ($_POST['service_title'] as $index => $title) {
                if (!empty($title)) {
                    $services_data['services'][] = array(
                        'title' => wp_kses_post($_POST['service_title'][$index] ?? ''),
                        'description' => wp_kses_post($_POST['service_description'][$index] ?? ''),
                        'thumbnail' => esc_url_raw($_POST['service_thumbnail'][$index] ?? ''),
                        'btnImage' => esc_url_raw($_POST['service_btn_image'][$index] ?? ''),
                        'linkTo' => sanitize_text_field($_POST['service_link'][$index] ?? '/')
                    );
                }
            }
        }
        
        update_option('eatisfamily_services', $services_data);
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Services saved successfully!', 'eatisfamily') . '</p></div>';
    }
    
    // Get current values
    $services_data = get_option('eatisfamily_services', array());
    $services = $services_data['services'] ?? array();
    
    ?>
    <div class="wrap">
        <h1><?php _e('Services Management', 'eatisfamily'); ?></h1>
        <p class="description"><?php _e('Manage the services section on the homepage.', 'eatisfamily'); ?></p>
        
        <form method="post" action="">
            <?php wp_nonce_field('save_services', 'eatisfamily_services_nonce'); ?>
            
            <h2><?php _e('Section Header', 'eatisfamily'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="services_tag"><?php _e('Tag', 'eatisfamily'); ?></label></th>
                    <td>
                        <input type="text" name="services_tag" id="services_tag" value="<?php echo esc_attr($services_data['tag'] ?? 'OUR SERVICES'); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="services_title_line1"><?php _e('Title Line 1', 'eatisfamily'); ?></label></th>
                    <td>
                        <input type="text" name="services_title_line1" id="services_title_line1" value="<?php echo esc_attr($services_data['title']['line_1'] ?? ''); ?>" class="large-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="services_title_highlight"><?php _e('Title Highlight', 'eatisfamily'); ?></label></th>
                    <td>
                        <input type="text" name="services_title_highlight" id="services_title_highlight" value="<?php echo esc_attr($services_data['title']['highlight'] ?? ''); ?>" class="regular-text">
                        <p class="description"><?php _e('This word will be highlighted with underline.', 'eatisfamily'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="services_title_line2"><?php _e('Title Line 2', 'eatisfamily'); ?></label></th>
                    <td>
                        <input type="text" name="services_title_line2" id="services_title_line2" value="<?php echo esc_attr($services_data['title']['line_2'] ?? ''); ?>" class="large-text">
                    </td>
                </tr>
            </table>
            
            <h2><?php _e('Services List', 'eatisfamily'); ?></h2>
            <div id="services-list" class="eatisfamily-repeater">
                <?php 
                if (!empty($services)) {
                    foreach ($services as $index => $service) {
                        eatisfamily_render_service_row($index, $service);
                    }
                } else {
                    eatisfamily_render_service_row(0, array());
                }
                ?>
            </div>
            
            <p>
                <button type="button" class="button" id="add-service"><?php _e('+ Add Service', 'eatisfamily'); ?></button>
            </p>
            
            <?php submit_button(__('Save Services', 'eatisfamily')); ?>
        </form>
    </div>
    
    <script type="text/html" id="service-row-template">
        <?php eatisfamily_render_service_row('{{INDEX}}', array()); ?>
    </script>
    
    <script>
    jQuery(document).ready(function($) {
        var serviceIndex = <?php echo count($services); ?>;
        
        $('#add-service').on('click', function() {
            var template = $('#service-row-template').html();
            template = template.replace(/\{\{INDEX\}\}/g, serviceIndex);
            $('#services-list').append(template);
            serviceIndex++;
        });
        
        $(document).on('click', '.remove-service', function() {
            $(this).closest('.service-row').remove();
        });
    });
    </script>
    <?php
}

function eatisfamily_render_service_row($index, $service) {
    ?>
    <div class="service-row partner-row">
        <div class="form-field">
            <label><?php _e('Service Title', 'eatisfamily'); ?></label>
            <textarea name="service_title[<?php echo $index; ?>]" rows="2" style="width:100%"><?php echo esc_textarea($service['title'] ?? ''); ?></textarea>
            <p class="description"><?php _e('Use \n for line breaks', 'eatisfamily'); ?></p>
        </div>
        <div class="form-field">
            <label><?php _e('Description', 'eatisfamily'); ?></label>
            <textarea name="service_description[<?php echo $index; ?>]" rows="4" style="width:100%"><?php echo esc_textarea($service['description'] ?? ''); ?></textarea>
        </div>
        <div class="form-field">
            <label><?php _e('Thumbnail Image', 'eatisfamily'); ?></label>
            <input type="text" name="service_thumbnail[<?php echo $index; ?>]" id="service_thumbnail_<?php echo $index; ?>" value="<?php echo esc_attr($service['thumbnail'] ?? ''); ?>">
            <button type="button" class="button eatisfamily-upload-media" data-target="service_thumbnail_<?php echo $index; ?>"><?php _e('Select Image', 'eatisfamily'); ?></button>
        </div>
        <div class="form-field">
            <label><?php _e('Button Image', 'eatisfamily'); ?></label>
            <input type="text" name="service_btn_image[<?php echo $index; ?>]" id="service_btn_image_<?php echo $index; ?>" value="<?php echo esc_attr($service['btnImage'] ?? ''); ?>">
            <button type="button" class="button eatisfamily-upload-media" data-target="service_btn_image_<?php echo $index; ?>"><?php _e('Select Image', 'eatisfamily'); ?></button>
        </div>
        <div class="form-field">
            <label><?php _e('Link To', 'eatisfamily'); ?></label>
            <input type="text" name="service_link[<?php echo $index; ?>]" value="<?php echo esc_attr($service['linkTo'] ?? '/'); ?>">
        </div>
        <p><span class="remove-service remove-partner"><?php _e('Remove Service', 'eatisfamily'); ?></span></p>
    </div>
    <?php
}

/**
 * ============================================
 * SUSTAINABILITY MANAGEMENT PAGE
 * ============================================
 */
function eatisfamily_sustainability_page() {
    // Handle form submission
    if (isset($_POST['eatisfamily_sustainability_nonce']) && 
        wp_verify_nonce($_POST['eatisfamily_sustainability_nonce'], 'save_sustainability')) {
        
        $sustainability_data = array(
            'title' => wp_kses_post($_POST['sustainability_title'] ?? ''),
            'items' => array()
        );
        
        // Process items
        if (isset($_POST['sustainability_item_title']) && is_array($_POST['sustainability_item_title'])) {
            foreach ($_POST['sustainability_item_title'] as $index => $title) {
                if (!empty($title)) {
                    $sustainability_data['items'][] = array(
                        'title' => wp_kses_post($title),
                        'description' => wp_kses_post($_POST['sustainability_item_description'][$index] ?? ''),
                        'icone' => esc_url_raw($_POST['sustainability_item_icon'][$index] ?? ''),
                        'background' => sanitize_text_field($_POST['sustainability_item_bg'][$index] ?? '')
                    );
                }
            }
        }
        
        update_option('eatisfamily_sustainability', $sustainability_data);
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Sustainability content saved successfully!', 'eatisfamily') . '</p></div>';
    }
    
    // Get current values
    $sustainability_data = get_option('eatisfamily_sustainability', array());
    $items = $sustainability_data['items'] ?? array();
    
    ?>
    <div class="wrap">
        <h1><?php _e('Sustainability Section', 'eatisfamily'); ?></h1>
        <p class="description"><?php _e('Manage the "Our Commitment To Sustainable Service" section.', 'eatisfamily'); ?></p>
        
        <form method="post" action="">
            <?php wp_nonce_field('save_sustainability', 'eatisfamily_sustainability_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="sustainability_title"><?php _e('Section Title', 'eatisfamily'); ?></label></th>
                    <td>
                        <textarea name="sustainability_title" id="sustainability_title" class="large-text" rows="2"><?php echo esc_textarea($sustainability_data['title'] ?? ''); ?></textarea>
                        <p class="description"><?php _e('Use \n for line breaks', 'eatisfamily'); ?></p>
                    </td>
                </tr>
            </table>
            
            <h2><?php _e('Sustainability Items', 'eatisfamily'); ?></h2>
            <div id="sustainability-list" class="eatisfamily-repeater">
                <?php 
                if (!empty($items)) {
                    foreach ($items as $index => $item) {
                        eatisfamily_render_sustainability_row($index, $item);
                    }
                } else {
                    eatisfamily_render_sustainability_row(0, array());
                }
                ?>
            </div>
            
            <p>
                <button type="button" class="button" id="add-sustainability"><?php _e('+ Add Item', 'eatisfamily'); ?></button>
            </p>
            
            <?php submit_button(__('Save Sustainability', 'eatisfamily')); ?>
        </form>
    </div>
    
    <script type="text/html" id="sustainability-row-template">
        <?php eatisfamily_render_sustainability_row('{{INDEX}}', array()); ?>
    </script>
    
    <script>
    jQuery(document).ready(function($) {
        var sustainabilityIndex = <?php echo count($items); ?>;
        
        $('#add-sustainability').on('click', function() {
            var template = $('#sustainability-row-template').html();
            template = template.replace(/\{\{INDEX\}\}/g, sustainabilityIndex);
            $('#sustainability-list').append(template);
            sustainabilityIndex++;
        });
        
        $(document).on('click', '.remove-sustainability', function() {
            $(this).closest('.sustainability-row').remove();
        });
    });
    </script>
    <?php
}

function eatisfamily_render_sustainability_row($index, $item) {
    ?>
    <div class="sustainability-row partner-row">
        <div class="form-field">
            <label><?php _e('Title', 'eatisfamily'); ?></label>
            <textarea name="sustainability_item_title[<?php echo $index; ?>]" rows="2" style="width:100%"><?php echo esc_textarea($item['title'] ?? ''); ?></textarea>
        </div>
        <div class="form-field">
            <label><?php _e('Description', 'eatisfamily'); ?></label>
            <textarea name="sustainability_item_description[<?php echo $index; ?>]" rows="3" style="width:100%"><?php echo esc_textarea($item['description'] ?? ''); ?></textarea>
        </div>
        <div class="form-field">
            <label><?php _e('Icon Image', 'eatisfamily'); ?></label>
            <input type="text" name="sustainability_item_icon[<?php echo $index; ?>]" id="sustainability_item_icon_<?php echo $index; ?>" value="<?php echo esc_attr($item['icone'] ?? ''); ?>">
            <button type="button" class="button eatisfamily-upload-media" data-target="sustainability_item_icon_<?php echo $index; ?>"><?php _e('Select Image', 'eatisfamily'); ?></button>
        </div>
        <div class="form-field">
            <label><?php _e('Background SVG', 'eatisfamily'); ?></label>
            <input type="text" name="sustainability_item_bg[<?php echo $index; ?>]" value="<?php echo esc_attr($item['background'] ?? ''); ?>">
        </div>
        <p><span class="remove-sustainability remove-partner"><?php _e('Remove Item', 'eatisfamily'); ?></span></p>
    </div>
    <?php
}

/**
 * ============================================
 * GALLERY MANAGEMENT PAGE
 * ============================================
 */
function eatisfamily_gallery_page() {
    // Handle form submission
    if (isset($_POST['eatisfamily_gallery_nonce']) && 
        wp_verify_nonce($_POST['eatisfamily_gallery_nonce'], 'save_gallery')) {
        
        $gallery_data = array(
            'images' => array()
        );
        
        // Process images
        if (isset($_POST['gallery_image_src']) && is_array($_POST['gallery_image_src'])) {
            foreach ($_POST['gallery_image_src'] as $index => $src) {
                if (!empty($src)) {
                    $gallery_data['images'][] = array(
                        'src' => esc_url_raw($src),
                        'alt' => sanitize_text_field($_POST['gallery_image_alt'][$index] ?? '')
                    );
                }
            }
        }
        
        update_option('eatisfamily_gallery', $gallery_data);
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Gallery saved successfully!', 'eatisfamily') . '</p></div>';
    }
    
    // Get current values
    $gallery_data = get_option('eatisfamily_gallery', array());
    $images = $gallery_data['images'] ?? array();
    
    ?>
    <div class="wrap">
        <h1><?php _e('Gallery Management', 'eatisfamily'); ?></h1>
        <p class="description"><?php _e('Manage the gallery images on the homepage.', 'eatisfamily'); ?></p>
        
        <form method="post" action="">
            <?php wp_nonce_field('save_gallery', 'eatisfamily_gallery_nonce'); ?>
            
            <h2><?php _e('Gallery Images', 'eatisfamily'); ?></h2>
            <p class="description"><?php _e('First image will be displayed larger on the left, others on the right.', 'eatisfamily'); ?></p>
            
            <div id="gallery-list" class="eatisfamily-repeater" style="display:flex;flex-wrap:wrap;gap:15px;">
                <?php 
                if (!empty($images)) {
                    foreach ($images as $index => $image) {
                        eatisfamily_render_gallery_row($index, $image);
                    }
                } else {
                    eatisfamily_render_gallery_row(0, array());
                }
                ?>
            </div>
            
            <p style="clear:both;padding-top:20px;">
                <button type="button" class="button" id="add-gallery-image"><?php _e('+ Add Image', 'eatisfamily'); ?></button>
            </p>
            
            <?php submit_button(__('Save Gallery', 'eatisfamily')); ?>
        </form>
    </div>
    
    <script type="text/html" id="gallery-row-template">
        <?php eatisfamily_render_gallery_row('{{INDEX}}', array()); ?>
    </script>
    
    <script>
    jQuery(document).ready(function($) {
        var galleryIndex = <?php echo count($images); ?>;
        
        $('#add-gallery-image').on('click', function() {
            var template = $('#gallery-row-template').html();
            template = template.replace(/\{\{INDEX\}\}/g, galleryIndex);
            $('#gallery-list').append(template);
            galleryIndex++;
        });
        
        $(document).on('click', '.remove-gallery', function() {
            $(this).closest('.gallery-row').remove();
        });
    });
    </script>
    <?php
}

function eatisfamily_render_gallery_row($index, $image) {
    $src = $image['src'] ?? '';
    ?>
    <div class="gallery-row" style="background:#fff;border:1px solid #ccd0d4;padding:15px;border-radius:4px;width:200px;">
        <div class="form-field">
            <input type="hidden" name="gallery_image_src[<?php echo $index; ?>]" id="gallery_image_src_<?php echo $index; ?>" value="<?php echo esc_attr($src); ?>">
            <?php if ($src): ?>
                <img src="<?php echo esc_url($src); ?>" style="max-width:100%;height:auto;display:block;margin-bottom:10px;">
            <?php endif; ?>
            <button type="button" class="button eatisfamily-upload-media" data-target="gallery_image_src_<?php echo $index; ?>" style="width:100%"><?php _e('Select Image', 'eatisfamily'); ?></button>
        </div>
        <div class="form-field" style="margin-top:10px;">
            <input type="text" name="gallery_image_alt[<?php echo $index; ?>]" value="<?php echo esc_attr($image['alt'] ?? ''); ?>" placeholder="<?php _e('Alt text', 'eatisfamily'); ?>" style="width:100%">
        </div>
        <p style="text-align:center;margin:10px 0 0;"><span class="remove-gallery remove-partner"><?php _e('Remove', 'eatisfamily'); ?></span></p>
    </div>
    <?php
}

/**
 * ============================================
 * EXTENDED PAGES CONTENT PAGE
 * ============================================
 */
function eatisfamily_pages_content_page_extended() {
    // Handle form submission
    if (isset($_POST['eatisfamily_pages_content_nonce']) && 
        wp_verify_nonce($_POST['eatisfamily_pages_content_nonce'], 'save_pages_content')) {
        
        $pages_content = array(
            'homepage' => array(
                'hero_section' => array(
                    'bg' => esc_url_raw($_POST['homepage_hero_bg'] ?? ''),
                    'title' => array(
                        'line_1' => wp_kses_post($_POST['homepage_hero_title_line1'] ?? ''),
                        'line_2' => wp_kses_post($_POST['homepage_hero_title_line2'] ?? ''),
                        'line_3' => wp_kses_post($_POST['homepage_hero_title_line3'] ?? '')
                    )
                ),
                'intro_section' => array(
                    'texte' => wp_kses_post($_POST['homepage_intro_text'] ?? '')
                ),
                'cta_section' => array(
                    'title' => sanitize_text_field($_POST['homepage_cta_title'] ?? ''),
                    'description' => wp_kses_post($_POST['homepage_cta_description'] ?? '')
                ),
                'beautiful' => array(
                    'title' => wp_kses_post($_POST['homepage_beautiful_title'] ?? ''),
                    'text' => wp_kses_post($_POST['homepage_beautiful_text'] ?? ''),
                    'image' => esc_url_raw($_POST['homepage_beautiful_image'] ?? '')
                ),
                'examples' => array(),
                'homepageCTA' => array(
                    'title' => sanitize_text_field($_POST['homepage_final_cta_title'] ?? ''),
                    'description' => wp_kses_post($_POST['homepage_final_cta_description'] ?? ''),
                    'image' => esc_url_raw($_POST['homepage_final_cta_image'] ?? ''),
                    'link' => sanitize_text_field($_POST['homepage_final_cta_link'] ?? ''),
                    'button' => esc_url_raw($_POST['homepage_final_cta_button'] ?? ''),
                    'additionalText' => wp_kses_post($_POST['homepage_final_cta_additional'] ?? ''),
                    'button2' => esc_url_raw($_POST['homepage_final_cta_button2'] ?? ''),
                    'link2' => sanitize_text_field($_POST['homepage_final_cta_link2'] ?? '')
                )
            ),
            'about' => array(
                'hero' => array(
                    'title' => sanitize_text_field($_POST['about_hero_title'] ?? ''),
                    'subtitle' => sanitize_text_field($_POST['about_hero_subtitle'] ?? ''),
                    'description' => wp_kses_post($_POST['about_hero_description'] ?? ''),
                    'background_image' => esc_url_raw($_POST['about_hero_bg'] ?? '')
                ),
                'intro_section' => array(
                    'title' => sanitize_text_field($_POST['about_intro_title'] ?? ''),
                    'content' => wp_kses_post($_POST['about_intro_content'] ?? '')
                ),
                'timeline_title' => sanitize_text_field($_POST['about_timeline_title'] ?? 'Our History')
            ),
            'contact' => array(
                'hero_section' => array(
                    'title' => array(
                        'line_1' => sanitize_text_field($_POST['contact_hero_line1'] ?? ''),
                        'line_1_highlight' => sanitize_text_field($_POST['contact_hero_line1_highlight'] ?? ''),
                        'line_2' => sanitize_text_field($_POST['contact_hero_line2'] ?? ''),
                        'line_3' => sanitize_text_field($_POST['contact_hero_line3'] ?? ''),
                        'line_3_highlight' => sanitize_text_field($_POST['contact_hero_line3_highlight'] ?? '')
                    ),
                    'description' => wp_kses_post($_POST['contact_hero_description'] ?? '')
                ),
                'form' => array(
                    'name_placeholder' => sanitize_text_field($_POST['contact_form_name'] ?? ''),
                    'email_placeholder' => sanitize_text_field($_POST['contact_form_email'] ?? ''),
                    'event_type_placeholder' => sanitize_text_field($_POST['contact_form_event'] ?? ''),
                    'location_placeholder' => sanitize_text_field($_POST['contact_form_location'] ?? ''),
                    'date_placeholder' => sanitize_text_field($_POST['contact_form_date'] ?? ''),
                    'guests_placeholder' => sanitize_text_field($_POST['contact_form_guests'] ?? ''),
                    'message_placeholder' => sanitize_text_field($_POST['contact_form_message'] ?? ''),
                    'submit_button' => sanitize_text_field($_POST['contact_form_submit'] ?? '')
                )
            ),
            'careers' => array(
                'hero_default' => array(
                    'title_line_1' => sanitize_text_field($_POST['careers_hero_line1'] ?? ''),
                    'title_line_2' => sanitize_text_field($_POST['careers_hero_line2'] ?? ''),
                    'image' => esc_url_raw($_POST['careers_hero_image'] ?? ''),
                    'background_image' => esc_url_raw($_POST['careers_hero_bg'] ?? '')
                ),
                'join_box' => array(
                    'title' => sanitize_text_field($_POST['careers_join_title'] ?? ''),
                    'description' => wp_kses_post($_POST['careers_join_description'] ?? '')
                ),
                'search_section' => array(
                    'search_placeholder' => sanitize_text_field($_POST['careers_search_placeholder'] ?? ''),
                    'all_sites_label' => sanitize_text_field($_POST['careers_all_sites'] ?? ''),
                    'job_types' => array_filter(array_map('sanitize_text_field', explode("\n", $_POST['careers_job_types'] ?? '')))
                ),
                'cta_section' => array(
                    'title' => sanitize_text_field($_POST['careers_cta_title'] ?? ''),
                    'description' => wp_kses_post($_POST['careers_cta_description'] ?? '')
                )
            ),
            'events' => array(
                'hero_section' => array(
                    'title' => sanitize_text_field($_POST['events_hero_title'] ?? ''),
                    'subtitle' => sanitize_text_field($_POST['events_hero_subtitle'] ?? '')
                )
            )
        );
        
        // Process examples
        if (isset($_POST['example_title']) && is_array($_POST['example_title'])) {
            foreach ($_POST['example_title'] as $index => $title) {
                if (!empty($title)) {
                    $pages_content['homepage']['examples'][] = array(
                        'title' => wp_kses_post($title),
                        'texte' => wp_kses_post($_POST['example_text'][$index] ?? ''),
                        'btn' => esc_url_raw($_POST['example_btn'][$index] ?? ''),
                        'link' => sanitize_text_field($_POST['example_link'][$index] ?? '')
                    );
                }
            }
        }
        
        update_option('eatisfamily_pages_content', $pages_content);
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Pages content saved successfully!', 'eatisfamily') . '</p></div>';
    }
    
    // Get current values
    $pages_content = get_option('eatisfamily_pages_content', array());
    $homepage = $pages_content['homepage'] ?? array();
    $about = $pages_content['about'] ?? array();
    $contact = $pages_content['contact'] ?? array();
    $careers = $pages_content['careers'] ?? array();
    $events = $pages_content['events'] ?? array();
    
    ?>
    <div class="wrap">
        <h1><?php _e('Pages Content Settings', 'eatisfamily'); ?></h1>
        <p class="description"><?php _e('Edit content for each page of your website.', 'eatisfamily'); ?></p>
        
        <form method="post" action="">
            <?php wp_nonce_field('save_pages_content', 'eatisfamily_pages_content_nonce'); ?>
            
            <h2 class="nav-tab-wrapper">
                <a href="#homepage" class="nav-tab nav-tab-active"><?php _e('Homepage', 'eatisfamily'); ?></a>
                <a href="#about" class="nav-tab"><?php _e('About', 'eatisfamily'); ?></a>
                <a href="#contact" class="nav-tab"><?php _e('Contact', 'eatisfamily'); ?></a>
                <a href="#careers" class="nav-tab"><?php _e('Careers', 'eatisfamily'); ?></a>
                <a href="#events" class="nav-tab"><?php _e('Events', 'eatisfamily'); ?></a>
            </h2>
            
            <!-- Homepage Tab -->
            <div id="homepage" class="tab-content" style="display: block;">
                <h3><?php _e('Hero Section', 'eatisfamily'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="homepage_hero_bg"><?php _e('Background Image', 'eatisfamily'); ?></label></th>
                        <td>
                            <input type="text" name="homepage_hero_bg" id="homepage_hero_bg" value="<?php echo esc_attr($homepage['hero_section']['bg'] ?? ''); ?>" class="regular-text">
                            <button type="button" class="button eatisfamily-upload-media" data-target="homepage_hero_bg"><?php _e('Select', 'eatisfamily'); ?></button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="homepage_hero_title_line1"><?php _e('Title Line 1', 'eatisfamily'); ?></label></th>
                        <td>
                            <textarea name="homepage_hero_title_line1" id="homepage_hero_title_line1" class="large-text" rows="2"><?php echo esc_textarea($homepage['hero_section']['title']['line_1'] ?? ''); ?></textarea>
                            <p class="description"><?php _e('Use \n for line breaks', 'eatisfamily'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="homepage_hero_title_line2"><?php _e('Title Line 2', 'eatisfamily'); ?></label></th>
                        <td>
                            <textarea name="homepage_hero_title_line2" id="homepage_hero_title_line2" class="large-text" rows="2"><?php echo esc_textarea($homepage['hero_section']['title']['line_2'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="homepage_hero_title_line3"><?php _e('Subtitle', 'eatisfamily'); ?></label></th>
                        <td>
                            <textarea name="homepage_hero_title_line3" id="homepage_hero_title_line3" class="large-text" rows="3"><?php echo esc_textarea($homepage['hero_section']['title']['line_3'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                </table>
                
                <h3><?php _e('Intro Section', 'eatisfamily'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="homepage_intro_text"><?php _e('Intro Text', 'eatisfamily'); ?></label></th>
                        <td>
                            <?php 
                            wp_editor($homepage['intro_section']['texte'] ?? '', 'homepage_intro_text', array(
                                'textarea_name' => 'homepage_intro_text',
                                'textarea_rows' => 5,
                                'media_buttons' => false,
                            ));
                            ?>
                        </td>
                    </tr>
                </table>
                
                <h3><?php _e('CTA Section', 'eatisfamily'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="homepage_cta_title"><?php _e('Title', 'eatisfamily'); ?></label></th>
                        <td>
                            <input type="text" name="homepage_cta_title" id="homepage_cta_title" value="<?php echo esc_attr($homepage['cta_section']['title'] ?? ''); ?>" class="large-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="homepage_cta_description"><?php _e('Description', 'eatisfamily'); ?></label></th>
                        <td>
                            <?php 
                            wp_editor($homepage['cta_section']['description'] ?? '', 'homepage_cta_description', array(
                                'textarea_name' => 'homepage_cta_description',
                                'textarea_rows' => 4,
                                'media_buttons' => false,
                            ));
                            ?>
                        </td>
                    </tr>
                </table>
                
                <h3><?php _e('Beautiful Moments Section', 'eatisfamily'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="homepage_beautiful_title"><?php _e('Title', 'eatisfamily'); ?></label></th>
                        <td>
                            <textarea name="homepage_beautiful_title" id="homepage_beautiful_title" class="large-text" rows="2"><?php echo esc_textarea($homepage['beautiful']['title'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="homepage_beautiful_text"><?php _e('Text', 'eatisfamily'); ?></label></th>
                        <td>
                            <textarea name="homepage_beautiful_text" id="homepage_beautiful_text" class="large-text" rows="4"><?php echo esc_textarea($homepage['beautiful']['text'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="homepage_beautiful_image"><?php _e('Image', 'eatisfamily'); ?></label></th>
                        <td>
                            <input type="text" name="homepage_beautiful_image" id="homepage_beautiful_image" value="<?php echo esc_attr($homepage['beautiful']['image'] ?? ''); ?>" class="regular-text">
                            <button type="button" class="button eatisfamily-upload-media" data-target="homepage_beautiful_image"><?php _e('Select', 'eatisfamily'); ?></button>
                        </td>
                    </tr>
                </table>
                
                <h3><?php _e('Examples (Concession & Events)', 'eatisfamily'); ?></h3>
                <div id="examples-list" class="eatisfamily-repeater">
                    <?php 
                    $examples = $homepage['examples'] ?? array();
                    if (!empty($examples)) {
                        foreach ($examples as $index => $example) {
                            eatisfamily_render_example_row($index, $example);
                        }
                    } else {
                        eatisfamily_render_example_row(0, array());
                    }
                    ?>
                </div>
                <p>
                    <button type="button" class="button" id="add-example"><?php _e('+ Add Example', 'eatisfamily'); ?></button>
                </p>
                
                <h3><?php _e('Final CTA Section', 'eatisfamily'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="homepage_final_cta_title"><?php _e('Title', 'eatisfamily'); ?></label></th>
                        <td>
                            <input type="text" name="homepage_final_cta_title" id="homepage_final_cta_title" value="<?php echo esc_attr($homepage['homepageCTA']['title'] ?? ''); ?>" class="large-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="homepage_final_cta_image"><?php _e('Image', 'eatisfamily'); ?></label></th>
                        <td>
                            <input type="text" name="homepage_final_cta_image" id="homepage_final_cta_image" value="<?php echo esc_attr($homepage['homepageCTA']['image'] ?? ''); ?>" class="regular-text">
                            <button type="button" class="button eatisfamily-upload-media" data-target="homepage_final_cta_image"><?php _e('Select', 'eatisfamily'); ?></button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="homepage_final_cta_description"><?php _e('Description', 'eatisfamily'); ?></label></th>
                        <td>
                            <textarea name="homepage_final_cta_description" id="homepage_final_cta_description" class="large-text" rows="3"><?php echo esc_textarea($homepage['homepageCTA']['description'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="homepage_final_cta_link"><?php _e('Button 1 Link', 'eatisfamily'); ?></label></th>
                        <td>
                            <input type="text" name="homepage_final_cta_link" id="homepage_final_cta_link" value="<?php echo esc_attr($homepage['homepageCTA']['link'] ?? ''); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="homepage_final_cta_button"><?php _e('Button 1 Image', 'eatisfamily'); ?></label></th>
                        <td>
                            <input type="text" name="homepage_final_cta_button" id="homepage_final_cta_button" value="<?php echo esc_attr($homepage['homepageCTA']['button'] ?? ''); ?>" class="regular-text">
                            <button type="button" class="button eatisfamily-upload-media" data-target="homepage_final_cta_button"><?php _e('Select', 'eatisfamily'); ?></button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="homepage_final_cta_additional"><?php _e('Additional Text', 'eatisfamily'); ?></label></th>
                        <td>
                            <textarea name="homepage_final_cta_additional" id="homepage_final_cta_additional" class="large-text" rows="4"><?php echo esc_textarea($homepage['homepageCTA']['additionalText'] ?? ''); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="homepage_final_cta_link2"><?php _e('Button 2 Link', 'eatisfamily'); ?></label></th>
                        <td>
                            <input type="text" name="homepage_final_cta_link2" id="homepage_final_cta_link2" value="<?php echo esc_attr($homepage['homepageCTA']['link2'] ?? ''); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="homepage_final_cta_button2"><?php _e('Button 2 Image', 'eatisfamily'); ?></label></th>
                        <td>
                            <input type="text" name="homepage_final_cta_button2" id="homepage_final_cta_button2" value="<?php echo esc_attr($homepage['homepageCTA']['button2'] ?? ''); ?>" class="regular-text">
                            <button type="button" class="button eatisfamily-upload-media" data-target="homepage_final_cta_button2"><?php _e('Select', 'eatisfamily'); ?></button>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- About Tab -->
            <div id="about" class="tab-content" style="display: none;">
                <h3><?php _e('Hero Section', 'eatisfamily'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="about_hero_title"><?php _e('Title', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="about_hero_title" id="about_hero_title" value="<?php echo esc_attr($about['hero']['title'] ?? ''); ?>" class="large-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="about_hero_subtitle"><?php _e('Subtitle', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="about_hero_subtitle" id="about_hero_subtitle" value="<?php echo esc_attr($about['hero']['subtitle'] ?? ''); ?>" class="large-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="about_hero_description"><?php _e('Description', 'eatisfamily'); ?></label></th>
                        <td><textarea name="about_hero_description" id="about_hero_description" class="large-text" rows="3"><?php echo esc_textarea($about['hero']['description'] ?? ''); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="about_hero_bg"><?php _e('Background Image', 'eatisfamily'); ?></label></th>
                        <td>
                            <input type="text" name="about_hero_bg" id="about_hero_bg" value="<?php echo esc_attr($about['hero']['background_image'] ?? ''); ?>" class="regular-text">
                            <button type="button" class="button eatisfamily-upload-media" data-target="about_hero_bg"><?php _e('Select', 'eatisfamily'); ?></button>
                        </td>
                    </tr>
                </table>
                
                <h3><?php _e('Intro Section', 'eatisfamily'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="about_intro_title"><?php _e('Title', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="about_intro_title" id="about_intro_title" value="<?php echo esc_attr($about['intro_section']['title'] ?? ''); ?>" class="large-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="about_intro_content"><?php _e('Content', 'eatisfamily'); ?></label></th>
                        <td>
                            <?php 
                            wp_editor($about['intro_section']['content'] ?? '', 'about_intro_content', array(
                                'textarea_name' => 'about_intro_content',
                                'textarea_rows' => 8,
                                'media_buttons' => true,
                            ));
                            ?>
                        </td>
                    </tr>
                </table>
                
                <h3><?php _e('Timeline', 'eatisfamily'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="about_timeline_title"><?php _e('Timeline Title', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="about_timeline_title" id="about_timeline_title" value="<?php echo esc_attr($about['timeline_title'] ?? 'Our History'); ?>" class="large-text"></td>
                    </tr>
                </table>
            </div>
            
            <!-- Contact Tab -->
            <div id="contact" class="tab-content" style="display: none;">
                <h3><?php _e('Hero Section', 'eatisfamily'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="contact_hero_line1"><?php _e('Line 1', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="contact_hero_line1" id="contact_hero_line1" value="<?php echo esc_attr($contact['hero_section']['title']['line_1'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="contact_hero_line1_highlight"><?php _e('Line 1 Highlight', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="contact_hero_line1_highlight" id="contact_hero_line1_highlight" value="<?php echo esc_attr($contact['hero_section']['title']['line_1_highlight'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="contact_hero_line2"><?php _e('Line 2', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="contact_hero_line2" id="contact_hero_line2" value="<?php echo esc_attr($contact['hero_section']['title']['line_2'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="contact_hero_line3"><?php _e('Line 3', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="contact_hero_line3" id="contact_hero_line3" value="<?php echo esc_attr($contact['hero_section']['title']['line_3'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="contact_hero_line3_highlight"><?php _e('Line 3 Highlight', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="contact_hero_line3_highlight" id="contact_hero_line3_highlight" value="<?php echo esc_attr($contact['hero_section']['title']['line_3_highlight'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="contact_hero_description"><?php _e('Description', 'eatisfamily'); ?></label></th>
                        <td><textarea name="contact_hero_description" id="contact_hero_description" class="large-text" rows="3"><?php echo esc_textarea($contact['hero_section']['description'] ?? ''); ?></textarea></td>
                    </tr>
                </table>
                
                <h3><?php _e('Form Placeholders', 'eatisfamily'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="contact_form_name"><?php _e('Name', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="contact_form_name" id="contact_form_name" value="<?php echo esc_attr($contact['form']['name_placeholder'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="contact_form_email"><?php _e('Email', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="contact_form_email" id="contact_form_email" value="<?php echo esc_attr($contact['form']['email_placeholder'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="contact_form_event"><?php _e('Event Type', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="contact_form_event" id="contact_form_event" value="<?php echo esc_attr($contact['form']['event_type_placeholder'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="contact_form_location"><?php _e('Location', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="contact_form_location" id="contact_form_location" value="<?php echo esc_attr($contact['form']['location_placeholder'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="contact_form_date"><?php _e('Date', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="contact_form_date" id="contact_form_date" value="<?php echo esc_attr($contact['form']['date_placeholder'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="contact_form_guests"><?php _e('Guests', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="contact_form_guests" id="contact_form_guests" value="<?php echo esc_attr($contact['form']['guests_placeholder'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="contact_form_message"><?php _e('Message', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="contact_form_message" id="contact_form_message" value="<?php echo esc_attr($contact['form']['message_placeholder'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="contact_form_submit"><?php _e('Submit Button', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="contact_form_submit" id="contact_form_submit" value="<?php echo esc_attr($contact['form']['submit_button'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                </table>
            </div>
            
            <!-- Careers Tab -->
            <div id="careers" class="tab-content" style="display: none;">
                <h3><?php _e('Hero Section (Default)', 'eatisfamily'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="careers_hero_line1"><?php _e('Title Line 1', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="careers_hero_line1" id="careers_hero_line1" value="<?php echo esc_attr($careers['hero_default']['title_line_1'] ?? ''); ?>" class="large-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="careers_hero_line2"><?php _e('Title Line 2', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="careers_hero_line2" id="careers_hero_line2" value="<?php echo esc_attr($careers['hero_default']['title_line_2'] ?? ''); ?>" class="large-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="careers_hero_image"><?php _e('Hero Image', 'eatisfamily'); ?></label></th>
                        <td>
                            <input type="text" name="careers_hero_image" id="careers_hero_image" value="<?php echo esc_attr($careers['hero_default']['image'] ?? ''); ?>" class="regular-text">
                            <button type="button" class="button eatisfamily-upload-media" data-target="careers_hero_image"><?php _e('Select', 'eatisfamily'); ?></button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="careers_hero_bg"><?php _e('Background Image', 'eatisfamily'); ?></label></th>
                        <td>
                            <input type="text" name="careers_hero_bg" id="careers_hero_bg" value="<?php echo esc_attr($careers['hero_default']['background_image'] ?? ''); ?>" class="regular-text">
                            <button type="button" class="button eatisfamily-upload-media" data-target="careers_hero_bg"><?php _e('Select', 'eatisfamily'); ?></button>
                        </td>
                    </tr>
                </table>
                
                <h3><?php _e('Join Box', 'eatisfamily'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="careers_join_title"><?php _e('Title', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="careers_join_title" id="careers_join_title" value="<?php echo esc_attr($careers['join_box']['title'] ?? ''); ?>" class="large-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="careers_join_description"><?php _e('Description', 'eatisfamily'); ?></label></th>
                        <td><textarea name="careers_join_description" id="careers_join_description" class="large-text" rows="3"><?php echo esc_textarea($careers['join_box']['description'] ?? ''); ?></textarea></td>
                    </tr>
                </table>
                
                <h3><?php _e('Search Section', 'eatisfamily'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="careers_search_placeholder"><?php _e('Search Placeholder', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="careers_search_placeholder" id="careers_search_placeholder" value="<?php echo esc_attr($careers['search_section']['search_placeholder'] ?? ''); ?>" class="large-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="careers_all_sites"><?php _e('All Sites Label', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="careers_all_sites" id="careers_all_sites" value="<?php echo esc_attr($careers['search_section']['all_sites_label'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="careers_job_types"><?php _e('Job Types', 'eatisfamily'); ?></label></th>
                        <td>
                            <textarea name="careers_job_types" id="careers_job_types" class="large-text" rows="5"><?php echo esc_textarea(implode("\n", $careers['search_section']['job_types'] ?? array())); ?></textarea>
                            <p class="description"><?php _e('One job type per line', 'eatisfamily'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <h3><?php _e('CTA Section', 'eatisfamily'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="careers_cta_title"><?php _e('Title', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="careers_cta_title" id="careers_cta_title" value="<?php echo esc_attr($careers['cta_section']['title'] ?? ''); ?>" class="large-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="careers_cta_description"><?php _e('Description', 'eatisfamily'); ?></label></th>
                        <td><textarea name="careers_cta_description" id="careers_cta_description" class="large-text" rows="3"><?php echo esc_textarea($careers['cta_section']['description'] ?? ''); ?></textarea></td>
                    </tr>
                </table>
            </div>
            
            <!-- Events Tab -->
            <div id="events" class="tab-content" style="display: none;">
                <h3><?php _e('Hero Section', 'eatisfamily'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="events_hero_title"><?php _e('Title', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="events_hero_title" id="events_hero_title" value="<?php echo esc_attr($events['hero_section']['title'] ?? ''); ?>" class="large-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="events_hero_subtitle"><?php _e('Subtitle', 'eatisfamily'); ?></label></th>
                        <td><input type="text" name="events_hero_subtitle" id="events_hero_subtitle" value="<?php echo esc_attr($events['hero_section']['subtitle'] ?? ''); ?>" class="large-text"></td>
                    </tr>
                </table>
            </div>
            
            <?php submit_button(__('Save All Pages Content', 'eatisfamily')); ?>
        </form>
    </div>
    
    <script type="text/html" id="example-row-template">
        <?php eatisfamily_render_example_row('{{INDEX}}', array()); ?>
    </script>
    
    <script>
    jQuery(document).ready(function($) {
        // Tab navigation
        $('.nav-tab').on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            $('.tab-content').hide();
            $(target).show();
        });
        
        // Examples repeater
        var exampleIndex = <?php echo count($homepage['examples'] ?? array()); ?>;
        
        $('#add-example').on('click', function() {
            var template = $('#example-row-template').html();
            template = template.replace(/\{\{INDEX\}\}/g, exampleIndex);
            $('#examples-list').append(template);
            exampleIndex++;
        });
        
        $(document).on('click', '.remove-example', function() {
            $(this).closest('.example-row').remove();
        });
        
        // Media uploader
        $(document).on('click', '.eatisfamily-upload-media', function(e) {
            e.preventDefault();
            var button = $(this);
            var targetField = button.data('target');
            
            var mediaUploader = wp.media({
                title: '<?php _e('Select Image', 'eatisfamily'); ?>',
                button: { text: '<?php _e('Use this image', 'eatisfamily'); ?>' },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#' + targetField).val(attachment.url);
            });
            
            mediaUploader.open();
        });
    });
    </script>
    <?php
}

function eatisfamily_render_example_row($index, $example) {
    ?>
    <div class="example-row partner-row">
        <div class="form-field">
            <label><?php _e('Title', 'eatisfamily'); ?></label>
            <textarea name="example_title[<?php echo $index; ?>]" rows="2" style="width:100%"><?php echo esc_textarea($example['title'] ?? ''); ?></textarea>
        </div>
        <div class="form-field">
            <label><?php _e('Text', 'eatisfamily'); ?></label>
            <textarea name="example_text[<?php echo $index; ?>]" rows="4" style="width:100%"><?php echo esc_textarea($example['texte'] ?? ''); ?></textarea>
        </div>
        <div class="form-field">
            <label><?php _e('Button Image', 'eatisfamily'); ?></label>
            <input type="text" name="example_btn[<?php echo $index; ?>]" id="example_btn_<?php echo $index; ?>" value="<?php echo esc_attr($example['btn'] ?? ''); ?>">
            <button type="button" class="button eatisfamily-upload-media" data-target="example_btn_<?php echo $index; ?>"><?php _e('Select', 'eatisfamily'); ?></button>
        </div>
        <div class="form-field">
            <label><?php _e('Link', 'eatisfamily'); ?></label>
            <input type="text" name="example_link[<?php echo $index; ?>]" value="<?php echo esc_attr($example['link'] ?? ''); ?>">
        </div>
        <p><span class="remove-example remove-partner"><?php _e('Remove', 'eatisfamily'); ?></span></p>
    </div>
    <?php
}

/**
 * Enqueue admin scripts for media uploader
 */
function eatisfamily_admin_scripts_extended($hook) {
    if (strpos($hook, 'eatisfamily') !== false) {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'eatisfamily_admin_scripts_extended');
