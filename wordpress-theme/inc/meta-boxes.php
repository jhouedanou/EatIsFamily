<?php
/**
 * EIF Backend - Meta Boxes
 *
 * This file handles all custom meta boxes for the theme.
 * Uses WYSIWYG editors instead of JSON arrays and dynamic dropdowns for relationships.
 *
 * @package EIFBackend
 * @version 4.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * ============================================================================
 * META BOX REGISTRATION
 * ============================================================================
 */

/**
 * Register all meta boxes
 */
function eatisfamily_register_meta_boxes() {
    // Jobs Meta Box
    add_meta_box(
        'eatisfamily_job_details',
        __('Job Details', 'eatisfamily'),
        'eatisfamily_job_meta_box_callback',
        'job',
        'normal',
        'high'
    );
    
    // Venues Meta Box
    add_meta_box(
        'eatisfamily_venue_details',
        __('Venue Details', 'eatisfamily'),
        'eatisfamily_venue_meta_box_callback',
        'venue',
        'normal',
        'high'
    );
    
    // Activities Meta Box
    add_meta_box(
        'eatisfamily_activity_details',
        __('Activity Details', 'eatisfamily'),
        'eatisfamily_activity_meta_box_callback',
        'activity',
        'normal',
        'high'
    );
    
    // Events Meta Box
    add_meta_box(
        'eatisfamily_event_details',
        __('Event Details', 'eatisfamily'),
        'eatisfamily_event_meta_box_callback',
        'event',
        'normal',
        'high'
    );
    
    // Blog Posts Meta Box (for additional fields)
    add_meta_box(
        'eatisfamily_blog_details',
        __('Blog Post Details', 'eatisfamily'),
        'eatisfamily_blog_meta_box_callback',
        'post',
        'normal',
        'high'
    );
    
    // Timeline Events Meta Box
    add_meta_box(
        'eatisfamily_timeline_details',
        __('Timeline Event Details', 'eatisfamily'),
        'eatisfamily_timeline_meta_box_callback',
        'timeline_event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'eatisfamily_register_meta_boxes');

/**
 * ============================================================================
 * HELPER FUNCTIONS
 * ============================================================================
 */

/**
 * Get all venues for dropdown
 */
function eatisfamily_get_venues_dropdown_options() {
    $venues = get_posts(array(
        'post_type' => 'venue',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC',
    ));
    
    $options = array('' => __('-- Select a Venue --', 'eatisfamily'));
    
    foreach ($venues as $venue) {
        $venue_slug = get_post_meta($venue->ID, 'venue_slug', true);
        $city = get_post_meta($venue->ID, 'city', true);
        $label = $venue->post_title;
        if ($city) {
            $label .= ' (' . $city . ')';
        }
        $options[$venue_slug ?: $venue->post_name] = $label;
    }
    
    return $options;
}

/**
 * Get venue types for dropdown
 * Fetches from eatisfamily_venues option (v5) with fallback to legacy eatisfamily_event_types
 */
function eatisfamily_get_event_types_dropdown() {
    // First try to get from unified venues option (v5)
    $venues_data = get_option('eatisfamily_venues', array());

    // Support both venue_types (new) and event_types (legacy) keys
    $venue_types = !empty($venues_data['venue_types'])
        ? $venues_data['venue_types']
        : (!empty($venues_data['event_types']) ? $venues_data['event_types'] : array());

    // Fallback to old separate option if nothing found
    if (empty($venue_types)) {
        $venue_types = get_option('eatisfamily_event_types', array());
    }

    // Default venue types if still empty
    if (empty($venue_types)) {
        $venue_types = array(
            array('id' => 'stadium', 'name' => 'Stadium'),
            array('id' => 'festival', 'name' => 'Festival'),
            array('id' => 'arena', 'name' => 'Arena'),
        );
    }

    $options = array('' => __('-- Select Venue Type --', 'eatisfamily'));
    foreach ($venue_types as $type) {
        $options[$type['id']] = $type['name'];
    }

    return $options;
}

/**
 * Get departments for dropdown
 * Uses dynamic values from admin settings if available
 */
function eatisfamily_get_departments_dropdown() {
    // Try to use dynamic function if available
    if (function_exists('eatisfamily_get_departments_dropdown_dynamic')) {
        return eatisfamily_get_departments_dropdown_dynamic();
    }
    
    // Fallback to static values
    return array(
        '' => __('-- Sélectionner un département --', 'eatisfamily'),
        'culinary' => __('Cuisine', 'eatisfamily'),
        'service' => __('Service', 'eatisfamily'),
        'beverage' => __('Boissons', 'eatisfamily'),
        'operations' => __('Opérations', 'eatisfamily'),
        'quality' => __('Qualité', 'eatisfamily'),
        'management' => __('Direction', 'eatisfamily'),
        'marketing' => __('Marketing', 'eatisfamily'),
        'hr' => __('Ressources Humaines', 'eatisfamily'),
    );
}

/**
 * Get job types for dropdown
 * Uses dynamic values from admin settings if available
 */
function eatisfamily_get_job_types_dropdown() {
    // Try to use dynamic function if available
    if (function_exists('eatisfamily_get_job_types_dropdown_dynamic')) {
        return eatisfamily_get_job_types_dropdown_dynamic();
    }
    
    // Fallback to static values
    return array(
        '' => __('-- Sélectionner un type d\'emploi --', 'eatisfamily'),
        'full-time' => __('Temps plein', 'eatisfamily'),
        'part-time' => __('Temps partiel', 'eatisfamily'),
        'seasonal' => __('Saisonnier', 'eatisfamily'),
        'contract' => __('Contrat', 'eatisfamily'),
        'internship' => __('Stage', 'eatisfamily'),
    );
}

/**
 * Get activity categories for dropdown
 */
function eatisfamily_get_activity_categories_dropdown() {
    return array(
        '' => __('-- Sélectionner une catégorie --', 'eatisfamily'),
        'Cooking' => __('Cuisine', 'eatisfamily'),
        'Baking' => __('Pâtisserie', 'eatisfamily'),
        'Wine & Spirits' => __('Vins & Spiritueux', 'eatisfamily'),
        'Tasting' => __('Dégustation', 'eatisfamily'),
        'Team Building' => __('Team Building', 'eatisfamily'),
        'Workshop' => __('Atelier', 'eatisfamily'),
        'Masterclass' => __('Masterclass', 'eatisfamily'),
    );
}

/**
 * Get activity status for dropdown
 */
function eatisfamily_get_activity_status_dropdown() {
    return array(
        'open' => __('Open for Registration', 'eatisfamily'),
        'closed' => __('Closed', 'eatisfamily'),
        'full' => __('Fully Booked', 'eatisfamily'),
        'cancelled' => __('Cancelled', 'eatisfamily'),
    );
}

/**
 * Render a dropdown field
 */
function eatisfamily_render_dropdown($name, $value, $options, $description = '') {
    echo '<select name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" class="regular-text">';
    foreach ($options as $option_value => $option_label) {
        echo '<option value="' . esc_attr($option_value) . '"' . selected($value, $option_value, false) . '>';
        echo esc_html($option_label);
        echo '</option>';
    }
    echo '</select>';
    if ($description) {
        echo '<p class="description">' . esc_html($description) . '</p>';
    }
}

/**
 * Render a text field
 */
function eatisfamily_render_text_field($name, $value, $placeholder = '', $description = '') {
    echo '<input type="text" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" value="' . esc_attr($value) . '" class="regular-text" placeholder="' . esc_attr($placeholder) . '">';
    if ($description) {
        echo '<p class="description">' . esc_html($description) . '</p>';
    }
}

/**
 * Render a number field
 */
function eatisfamily_render_number_field($name, $value, $min = 0, $max = '', $step = 1, $description = '') {
    $max_attr = $max !== '' ? ' max="' . esc_attr($max) . '"' : '';
    echo '<input type="number" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" value="' . esc_attr($value) . '" class="small-text" min="' . esc_attr($min) . '"' . $max_attr . ' step="' . esc_attr($step) . '">';
    if ($description) {
        echo '<p class="description">' . esc_html($description) . '</p>';
    }
}

/**
 * Render a WYSIWYG editor
 */
function eatisfamily_render_wysiwyg($name, $value, $description = '') {
    $editor_id = str_replace(array('[', ']'), '_', $name);
    
    wp_editor($value, $editor_id, array(
        'textarea_name' => $name,
        'textarea_rows' => 8,
        'media_buttons' => true,
        'teeny' => false,
        'quicktags' => true,
        'tinymce' => array(
            'toolbar1' => 'formatselect,bold,italic,underline,bullist,numlist,blockquote,link,unlink,undo,redo',
            'toolbar2' => '',
        ),
    ));
    
    if ($description) {
        echo '<p class="description">' . esc_html($description) . '</p>';
    }
}

/**
 * Render a repeater field (for lists like requirements, benefits, services)
 */
function eatisfamily_render_repeater_field($name, $values, $placeholder = '', $description = '') {
    $values = is_array($values) ? $values : array();
    if (empty($values)) {
        $values = array('');
    }
    
    echo '<div class="eatisfamily-repeater" data-name="' . esc_attr($name) . '">';
    echo '<div class="repeater-items">';
    
    foreach ($values as $index => $value) {
        echo '<div class="repeater-item">';
        echo '<input type="text" name="' . esc_attr($name) . '[]" value="' . esc_attr($value) . '" class="regular-text" placeholder="' . esc_attr($placeholder) . '">';
        echo '<button type="button" class="button repeater-remove" title="' . esc_attr__('Remove', 'eatisfamily') . '">✕</button>';
        echo '</div>';
    }
    
    echo '</div>';
    echo '<button type="button" class="button repeater-add">+ ' . __('Add Item', 'eatisfamily') . '</button>';
    echo '</div>';
    
    if ($description) {
        echo '<p class="description">' . esc_html($description) . '</p>';
    }
}

/**
 * Render a media upload field
 */
function eatisfamily_render_media_field($name, $value, $description = '') {
    ?>
    <div class="eatisfamily-media-field">
        <input type="text" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" class="regular-text">
        <button type="button" class="button eatisfamily-upload-btn" data-target="<?php echo esc_attr($name); ?>"><?php _e('Select Image', 'eatisfamily'); ?></button>
        <?php if ($value): ?>
            <div class="media-preview" style="margin-top: 10px;">
                <img src="<?php echo esc_url($value); ?>" style="max-width: 150px; height: auto; border: 1px solid #ccc; padding: 2px;">
            </div>
        <?php endif; ?>
    </div>
    <?php
    if ($description) {
        echo '<p class="description">' . esc_html($description) . '</p>';
    }
}

/**
 * Render a gallery field (multiple images)
 */
function eatisfamily_render_gallery_field($name, $values, $description = '') {
    $values = is_array($values) ? $values : array();
    ?>
    <div class="eatisfamily-gallery-field" data-name="<?php echo esc_attr($name); ?>">
        <div class="gallery-items" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 10px;">
            <?php foreach ($values as $index => $url): if (!empty($url)): ?>
                <div class="gallery-item" style="position: relative;">
                    <img src="<?php echo esc_url($url); ?>" style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ccc;">
                    <input type="hidden" name="<?php echo esc_attr($name); ?>[]" value="<?php echo esc_attr($url); ?>">
                    <button type="button" class="gallery-remove" style="position: absolute; top: -5px; right: -5px; background: #d63638; color: #fff; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer; font-size: 12px;">✕</button>
                </div>
            <?php endif; endforeach; ?>
        </div>
        <button type="button" class="button eatisfamily-gallery-add"><?php _e('Add Images', 'eatisfamily'); ?></button>
    </div>
    <?php
    if ($description) {
        echo '<p class="description">' . esc_html($description) . '</p>';
    }
}

/**
 * Render a complex repeater for shops
 */
function eatisfamily_render_shops_repeater($name, $values, $description = '') {
    $values = is_array($values) ? $values : array();
    if (empty($values)) {
        $values = array(array('name' => '', 'image' => ''));
    }
    ?>
    <div class="eatisfamily-complex-repeater" data-name="<?php echo esc_attr($name); ?>">
        <div class="complex-repeater-items">
            <?php foreach ($values as $index => $item): ?>
                <div class="complex-repeater-item" style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px; padding: 10px; background: #fff; border: 1px solid #ddd;">
                    <div style="flex: 1;">
                        <label><?php _e('Name', 'eatisfamily'); ?></label>
                        <input type="text" name="<?php echo esc_attr($name); ?>[<?php echo $index; ?>][name]" value="<?php echo esc_attr($item['name'] ?? ''); ?>" class="regular-text" placeholder="<?php _e('Shop name...', 'eatisfamily'); ?>">
                    </div>
                    <div style="flex: 1;">
                        <label><?php _e('Image', 'eatisfamily'); ?></label>
                        <div class="eatisfamily-media-field">
                            <input type="text" name="<?php echo esc_attr($name); ?>[<?php echo $index; ?>][image]" id="<?php echo esc_attr($name); ?>_<?php echo $index; ?>_image" value="<?php echo esc_attr($item['image'] ?? ''); ?>" class="regular-text" placeholder="<?php _e('Image URL...', 'eatisfamily'); ?>">
                            <button type="button" class="button eatisfamily-upload-btn" data-target="<?php echo esc_attr($name); ?>_<?php echo $index; ?>_image"><?php _e('Select Image', 'eatisfamily'); ?></button>
                        </div>
                    </div>
                    <button type="button" class="button complex-repeater-remove" style="color: #d63638;">✕</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button complex-repeater-add" data-type="shop">+ <?php _e('Add Shop', 'eatisfamily'); ?></button>
    </div>
    <?php
    if ($description) {
        echo '<p class="description">' . esc_html($description) . '</p>';
    }
}

/**
 * Render a complex repeater for menu items
 */
function eatisfamily_render_menu_items_repeater($name, $values, $description = '') {
    $values = is_array($values) ? $values : array();
    if (empty($values)) {
        $values = array(array('name' => '', 'price' => '', 'description' => '', 'thumbnail' => ''));
    }
    ?>
    <div class="eatisfamily-complex-repeater" data-name="<?php echo esc_attr($name); ?>">
        <div class="complex-repeater-items">
            <?php foreach ($values as $index => $item): ?>
                <div class="complex-repeater-item" style="display: grid; grid-template-columns: 1fr 100px 2fr 200px auto auto; gap: 10px; align-items: center; margin-bottom: 10px; padding: 10px; background: #fff; border: 1px solid #ddd;">
                    <div>
                        <input type="text" name="<?php echo esc_attr($name); ?>[<?php echo $index; ?>][name]" value="<?php echo esc_attr($item['name'] ?? ''); ?>" class="regular-text" placeholder="<?php _e('Item name', 'eatisfamily'); ?>">
                    </div>
                    <div>
                        <input type="text" name="<?php echo esc_attr($name); ?>[<?php echo $index; ?>][price]" value="<?php echo esc_attr($item['price'] ?? ''); ?>" class="small-text" placeholder="<?php _e('Price', 'eatisfamily'); ?>">
                    </div>
                    <div>
                        <input type="text" name="<?php echo esc_attr($name); ?>[<?php echo $index; ?>][description]" value="<?php echo esc_attr($item['description'] ?? ''); ?>" class="regular-text" placeholder="<?php _e('Description', 'eatisfamily'); ?>">
                    </div>
                    <div>
                        <input type="text" name="<?php echo esc_attr($name); ?>[<?php echo $index; ?>][thumbnail]" id="<?php echo esc_attr($name); ?>_<?php echo $index; ?>_thumbnail" value="<?php echo esc_attr($item['thumbnail'] ?? ''); ?>" class="regular-text" placeholder="<?php _e('Thumbnail URL', 'eatisfamily'); ?>">
                    </div>
                    <button type="button" class="button eatisfamily-upload-btn" data-target="<?php echo esc_attr($name); ?>_<?php echo $index; ?>_thumbnail"><?php _e('Select', 'eatisfamily'); ?></button>
                    <button type="button" class="button complex-repeater-remove" style="color: #d63638;">✕</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button complex-repeater-add" data-type="menu">+ <?php _e('Add Menu Item', 'eatisfamily'); ?></button>
    </div>
    <?php
    if ($description) {
        echo '<p class="description">' . esc_html($description) . '</p>';
    }
}

/**
 * ============================================================================
 * JOB META BOX
 * ============================================================================
 */

/**
 * Job meta box callback
 */
function eatisfamily_job_meta_box_callback($post) {
    wp_nonce_field('eatisfamily_job_meta_box', 'eatisfamily_job_meta_box_nonce');
    
    // Get current values
    $venue_id = get_post_meta($post->ID, 'venue_id', true);
    $department = get_post_meta($post->ID, 'department', true);
    $job_type = get_post_meta($post->ID, 'job_type', true);
    $salary = get_post_meta($post->ID, 'salary', true);
    $requirements = get_post_meta($post->ID, 'requirements', true);
    $missions = get_post_meta($post->ID, 'missions', true);
    $benefits = get_post_meta($post->ID, 'benefits', true);
    $life_at_venue_images = get_post_meta($post->ID, 'life_at_venue_images', true);

    // Per-job UI text overrides (all optional; fall back to defaults if empty)
    $positions               = get_post_meta($post->ID, 'positions', true);
    $cta_title               = get_post_meta($post->ID, 'cta_title', true);
    $cta_subtitle            = get_post_meta($post->ID, 'cta_subtitle', true);
    $life_section_title      = get_post_meta($post->ID, 'life_section_title', true);
    $description_section_title = get_post_meta($post->ID, 'description_section_title', true);
    $missions_title          = get_post_meta($post->ID, 'missions_title', true);
    $missions_intro          = get_post_meta($post->ID, 'missions_intro', true);
    $requirements_title      = get_post_meta($post->ID, 'requirements_title', true);
    $requirements_intro      = get_post_meta($post->ID, 'requirements_intro', true);
    $benefits_title          = get_post_meta($post->ID, 'benefits_title', true);
    $quick_facts_title       = get_post_meta($post->ID, 'quick_facts_title', true);
    $label_location          = get_post_meta($post->ID, 'label_location', true);
    $label_department        = get_post_meta($post->ID, 'label_department', true);
    $label_job_type          = get_post_meta($post->ID, 'label_job_type', true);
    $label_positions         = get_post_meta($post->ID, 'label_positions', true);
    $share_title             = get_post_meta($post->ID, 'share_title', true);
    $share_subtitle          = get_post_meta($post->ID, 'share_subtitle', true);
    $bottom_cta_title        = get_post_meta($post->ID, 'bottom_cta_title', true);
    $bottom_cta_subtitle     = get_post_meta($post->ID, 'bottom_cta_subtitle', true);
    
    // Decode JSON if needed
    if (is_string($requirements)) {
        $requirements = json_decode($requirements, true);
    }
    if (is_string($missions)) {
        $missions = json_decode($missions, true);
    }
    if (is_string($benefits)) {
        $benefits = json_decode($benefits, true);
    }
    if (is_string($life_at_venue_images)) {
        $life_at_venue_images = json_decode($life_at_venue_images, true);
    }
    
    ?>
    <style>
        .eatisfamily-meta-box { display: grid; gap: 20px; }
        .eatisfamily-meta-box .field-row { display: grid; grid-template-columns: 200px 1fr; gap: 10px; align-items: start; }
        .eatisfamily-meta-box label { font-weight: 600; padding-top: 5px; }
        .eatisfamily-meta-box .field-group { border: 1px solid #ccc; padding: 15px; background: #f9f9f9; margin-top: 10px; }
        .eatisfamily-meta-box .field-group h4 { margin-top: 0; }
        .repeater-item { display: flex; gap: 5px; margin-bottom: 5px; }
        .repeater-item input { flex: 1; }
        .repeater-remove { color: #dc3232 !important; }
        .repeater-add { margin-top: 5px; }
    </style>
    
    <div class="eatisfamily-meta-box">
        <!-- Venue Selection (Dynamic Dropdown) -->
        <div class="field-row">
            <label for="venue_id"><?php _e('Venue', 'eatisfamily'); ?></label>
            <div>
                <?php eatisfamily_render_dropdown('venue_id', $venue_id, eatisfamily_get_venues_dropdown_options(), __('Select the venue where this job is located.', 'eatisfamily')); ?>
            </div>
        </div>
        
        <!-- Department (Dropdown) -->
        <div class="field-row">
            <label for="department"><?php _e('Department', 'eatisfamily'); ?></label>
            <div>
                <?php eatisfamily_render_dropdown('department', $department, eatisfamily_get_departments_dropdown()); ?>
            </div>
        </div>
        
        <!-- Job Type (Dropdown) -->
        <div class="field-row">
            <label for="job_type"><?php _e('Job Type', 'eatisfamily'); ?></label>
            <div>
                <?php eatisfamily_render_dropdown('job_type', $job_type, eatisfamily_get_job_types_dropdown()); ?>
            </div>
        </div>
        
        <!-- Salary -->
        <div class="field-row">
            <label for="salary"><?php _e('Salary', 'eatisfamily'); ?></label>
            <div>
                <?php eatisfamily_render_text_field('salary', $salary, '€45,000 - €60,000', __('Enter salary range or hourly rate', 'eatisfamily')); ?>
            </div>
        </div>
        
        <!-- Requirements (Repeater) -->
        <div class="field-group">
            <h4><?php _e('Requirements', 'eatisfamily'); ?></h4>
            <?php eatisfamily_render_repeater_field('requirements', $requirements, __('Enter a requirement...', 'eatisfamily'), __('List the job requirements. Each item will be displayed as a bullet point.', 'eatisfamily')); ?>
        </div>
        
        <!-- Missions (Repeater) -->
        <div class="field-group">
            <h4><?php _e('Missions (Vos missions)', 'eatisfamily'); ?></h4>
            <?php eatisfamily_render_repeater_field('missions', $missions, __('Enter a mission...', 'eatisfamily'), __('List the job missions / responsibilities. Each item will be displayed as a bullet point in the "Vos missions" section.', 'eatisfamily')); ?>
        </div>
        
        <!-- Benefits (Repeater) -->
        <div class="field-group">
            <h4><?php _e('Benefits', 'eatisfamily'); ?></h4>
            <?php eatisfamily_render_repeater_field('benefits', $benefits, __('Enter a benefit...', 'eatisfamily'), __('List the job benefits. Each item will be displayed as a bullet point.', 'eatisfamily')); ?>
        </div>
        
        <!-- Life at Venue Images (Gallery) -->
        <div class="field-group">
            <h4><?php _e('Life at Venue - Image Gallery', 'eatisfamily'); ?></h4>
            <p class="description"><?php _e('Add images that showcase the work environment and team culture at this venue.', 'eatisfamily'); ?></p>
            <?php eatisfamily_render_gallery_field('life_at_venue_images', $life_at_venue_images, __('These images will appear in the "Life at [Venue]" section on the job detail page.', 'eatisfamily')); ?>
        </div>

        <!-- ============================================================ -->
        <!-- UI TEXT OVERRIDES (per job) - all optional, fall back to defaults -->
        <!-- ============================================================ -->
        <div class="field-group">
            <h4><?php _e('Textes de la page (personnalisables par offre)', 'eatisfamily'); ?></h4>
            <p class="description"><?php _e('Tous les champs ci-dessous sont optionnels. Si laissés vides, les textes par défaut du site seront utilisés. Vous pouvez surcharger ces textes pour cette offre uniquement.', 'eatisfamily'); ?></p>

            <div class="field-row">
                <label for="positions"><?php _e('Postes disponibles (valeur)', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('positions', $positions, '2 postes', __('Ex: "2 postes", "1 poste", "Plusieurs"', 'eatisfamily')); ?>
                </div>
            </div>

            <hr>
            <h4 style="margin-top:15px;"><?php _e('Bannière CTA (haut)', 'eatisfamily'); ?></h4>
            <div class="field-row">
                <label for="cta_title"><?php _e('Titre CTA haut', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('cta_title', $cta_title, 'Prêt à rejoindre notre équipe ?'); ?>
                </div>
            </div>
            <div class="field-row">
                <label for="cta_subtitle"><?php _e('Sous-titre CTA haut', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('cta_subtitle', $cta_subtitle, 'Postulez maintenant et faites partie d\'une aventure exceptionnelle'); ?>
                </div>
            </div>

            <hr>
            <h4 style="margin-top:15px;"><?php _e('Section "La vie à..."', 'eatisfamily'); ?></h4>
            <div class="field-row">
                <label for="life_section_title"><?php _e('Titre section vie au venue', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('life_section_title', $life_section_title, 'La vie à {venue}', __('Utilisez {venue} pour insérer dynamiquement le nom du lieu.', 'eatisfamily')); ?>
                </div>
            </div>

            <hr>
            <h4 style="margin-top:15px;"><?php _e('Section "Description du poste"', 'eatisfamily'); ?></h4>
            <div class="field-row">
                <label for="description_section_title"><?php _e('Titre section description', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('description_section_title', $description_section_title, 'Description du poste et prérequis'); ?>
                </div>
            </div>
            <div class="field-row">
                <label for="missions_title"><?php _e('Titre "Vos missions"', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('missions_title', $missions_title, 'Vos missions'); ?>
                </div>
            </div>
            <div class="field-row">
                <label for="missions_intro"><?php _e('Intro "Vos missions"', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('missions_intro', $missions_intro, 'Vous serez amené(e) à :'); ?>
                </div>
            </div>
            <div class="field-row">
                <label for="requirements_title"><?php _e('Titre "Profil recherché"', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('requirements_title', $requirements_title, 'Profil recherché'); ?>
                </div>
            </div>
            <div class="field-row">
                <label for="requirements_intro"><?php _e('Intro "Profil recherché"', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('requirements_intro', $requirements_intro, 'Voici les compétences et qualités que nous recherchons'); ?>
                </div>
            </div>

            <hr>
            <h4 style="margin-top:15px;"><?php _e('Cartes (Pourquoi nous rejoindre / En bref / Partager)', 'eatisfamily'); ?></h4>
            <div class="field-row">
                <label for="benefits_title"><?php _e('Titre carte "Pourquoi nous rejoindre"', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('benefits_title', $benefits_title, 'Pourquoi nous rejoindre'); ?>
                </div>
            </div>
            <div class="field-row">
                <label for="quick_facts_title"><?php _e('Titre carte "En bref"', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('quick_facts_title', $quick_facts_title, 'En bref'); ?>
                </div>
            </div>
            <div class="field-row">
                <label for="label_location"><?php _e('Label "LIEU"', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('label_location', $label_location, 'LIEU'); ?>
                </div>
            </div>
            <div class="field-row">
                <label for="label_department"><?php _e('Label "DÉPARTEMENT"', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('label_department', $label_department, 'DÉPARTEMENT'); ?>
                </div>
            </div>
            <div class="field-row">
                <label for="label_job_type"><?php _e('Label "TYPE DE CONTRAT"', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('label_job_type', $label_job_type, 'TYPE DE CONTRAT'); ?>
                </div>
            </div>
            <div class="field-row">
                <label for="label_positions"><?php _e('Label "POSTES DISPONIBLES"', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('label_positions', $label_positions, 'POSTES DISPONIBLES'); ?>
                </div>
            </div>
            <div class="field-row">
                <label for="share_title"><?php _e('Titre carte partage', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('share_title', $share_title, 'Vous connaissez quelqu\'un de parfait pour ce poste ?'); ?>
                </div>
            </div>
            <div class="field-row">
                <label for="share_subtitle"><?php _e('Sous-titre carte partage', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('share_subtitle', $share_subtitle, 'Partagez cette offre avec cette personne'); ?>
                </div>
            </div>

            <hr>
            <h4 style="margin-top:15px;"><?php _e('Section CTA (bas de page)', 'eatisfamily'); ?></h4>
            <div class="field-row">
                <label for="bottom_cta_title"><?php _e('Titre CTA bas', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('bottom_cta_title', $bottom_cta_title, 'Prêt à faire la différence ?'); ?>
                </div>
            </div>
            <div class="field-row">
                <label for="bottom_cta_subtitle"><?php _e('Sous-titre CTA bas', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('bottom_cta_subtitle', $bottom_cta_subtitle, 'Rejoignez notre équipe et participez à la création d\'expériences inoubliables dans l\'un des lieux les plus passionnants de France.'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Save job meta box data
 */
function eatisfamily_save_job_meta_box($post_id) {
    // Verify nonce
    if (!isset($_POST['eatisfamily_job_meta_box_nonce']) || !wp_verify_nonce($_POST['eatisfamily_job_meta_box_nonce'], 'eatisfamily_job_meta_box')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save fields
    $fields = array('venue_id', 'department', 'job_type', 'salary');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Save per-job UI text overrides (sanitize_text_field; empty allowed = fall back to default)
    $text_override_fields = array(
        'positions',
        'cta_title',
        'cta_subtitle',
        'life_section_title',
        'description_section_title',
        'missions_title',
        'missions_intro',
        'requirements_title',
        'requirements_intro',
        'benefits_title',
        'quick_facts_title',
        'label_location',
        'label_department',
        'label_job_type',
        'label_positions',
        'share_title',
        'share_subtitle',
        'bottom_cta_title',
        'bottom_cta_subtitle',
    );
    foreach ($text_override_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
        }
    }
    
    // Save arrays
    if (isset($_POST['requirements'])) {
        $requirements = array_filter(array_map('sanitize_text_field', $_POST['requirements']));
        update_post_meta($post_id, 'requirements', $requirements);
    }
    
    if (isset($_POST['missions'])) {
        $missions = array_filter(array_map('sanitize_text_field', $_POST['missions']));
        update_post_meta($post_id, 'missions', $missions);
    }
    
    if (isset($_POST['benefits'])) {
        $benefits = array_filter(array_map('sanitize_text_field', $_POST['benefits']));
        update_post_meta($post_id, 'benefits', $benefits);
    }
    
    // Save gallery images
    if (isset($_POST['life_at_venue_images'])) {
        $images = array_filter(array_map('esc_url_raw', $_POST['life_at_venue_images']));
        update_post_meta($post_id, 'life_at_venue_images', $images);
    } else {
        update_post_meta($post_id, 'life_at_venue_images', array());
    }
}
add_action('save_post_job', 'eatisfamily_save_job_meta_box');

/**
 * Expose missions (and related array fields) in the WordPress REST API.
 * This allows the eatisfamily/v1 custom endpoint and WP native REST API
 * to return the missions field stored as a PHP array in post meta.
 */
function eatisfamily_register_job_array_meta_for_rest() {
    $array_schema = array(
        'type'  => 'array',
        'items' => array('type' => 'string'),
    );
    $array_meta_args = array(
        'object_subtype' => 'job',
        'type'           => 'array',
        'single'         => true,
        'show_in_rest'   => array('schema' => $array_schema),
    );
    register_post_meta('job', 'missions',     $array_meta_args);
    register_post_meta('job', 'requirements', $array_meta_args);
    register_post_meta('job', 'benefits',     $array_meta_args);
    register_post_meta('job', 'life_at_venue_images', $array_meta_args);
}
add_action('init', 'eatisfamily_register_job_array_meta_for_rest');

/**
 * Inject missions, missions_title and missions_intro into the response of the
 * custom eatisfamily/v1/jobs endpoint, which is registered in functions.php
 * and does not natively include these fields.
 * Uses rest_post_dispatch so no changes to functions.php are required.
 */
function eatisfamily_inject_missions_into_jobs_response($result, $server, $request) {
    if (strpos($request->get_route(), '/eatisfamily/v1/jobs') === false) {
        return $result;
    }
    $data = $result->get_data();
    if (!is_array($data)) {
        return $result;
    }

    // Helper: enrich one job array with meta fields if not already present
    $enrich = function(&$job) {
        if (!is_array($job) || empty($job['id'])) return;
        $id = (int) $job['id'];
        if (!array_key_exists('missions', $job)) {
            $val = get_post_meta($id, 'missions', true);
            $job['missions'] = is_array($val) ? array_values($val) : [];
        }
        if (!array_key_exists('missions_title', $job)) {
            $job['missions_title'] = (string) get_post_meta($id, 'missions_title', true);
        }
        if (!array_key_exists('missions_intro', $job)) {
            $job['missions_intro'] = (string) get_post_meta($id, 'missions_intro', true);
        }
        if (!array_key_exists('life_at_venue_images', $job)) {
            $val = get_post_meta($id, 'life_at_venue_images', true);
            // Legacy entries may be stored as a JSON string
            if (is_string($val) && $val !== '') {
                $decoded = json_decode($val, true);
                $val = is_array($decoded) ? $decoded : array($val);
            }
            $job['life_at_venue_images'] = is_array($val) ? array_values(array_filter($val)) : [];
        }
    };

    if (isset($data[0])) {
        // List response
        foreach ($data as &$job) { $enrich($job); }
        unset($job);
    } else {
        // Single job response
        $enrich($data);
    }
    $result->set_data($data);
    return $result;
}
add_filter('rest_post_dispatch', 'eatisfamily_inject_missions_into_jobs_response', 10, 3);


function eatisfamily_venue_meta_box_callback($post) {
    wp_nonce_field('eatisfamily_venue_meta_box', 'eatisfamily_venue_meta_box_nonce');
    
    // Get current values
    $venue_slug = get_post_meta($post->ID, 'venue_slug', true);
    $location = get_post_meta($post->ID, 'location', true);
    $city = get_post_meta($post->ID, 'city', true);
    $country = get_post_meta($post->ID, 'country', true);
    $venue_type = get_post_meta($post->ID, 'venue_type', true);
    $latitude = get_post_meta($post->ID, 'latitude', true);
    $longitude = get_post_meta($post->ID, 'longitude', true);
    $capacity = get_post_meta($post->ID, 'capacity', true);
    $staff_members = get_post_meta($post->ID, 'staff_members', true);
    $recent_event = get_post_meta($post->ID, 'recent_event', true);
    $guests_served = get_post_meta($post->ID, 'guests_served', true);
    $services = get_post_meta($post->ID, 'services', true);
    $logo_url = get_post_meta($post->ID, 'logo_url', true);
    
    // Additional image fields for the grid
    $grid_image_1 = get_post_meta($post->ID, 'grid_image_1', true);
    $grid_image_2 = get_post_meta($post->ID, 'grid_image_2', true);
    $image2 = get_post_meta($post->ID, 'image2', true); // Legacy field
    $shops = get_post_meta($post->ID, 'shops', true);
    $menu_items = get_post_meta($post->ID, 'menu_items', true);
    
    // Decode JSON if needed
    if (is_string($services)) {
        $services = json_decode($services, true);
    }
    if (is_string($shops)) {
        $shops = json_decode($shops, true);
    }
    if (is_string($menu_items)) {
        $menu_items = json_decode($menu_items, true);
    }
    
    ?>
    <div class="eatisfamily-meta-box">
        <!-- Basic Information -->
        <div class="field-group">
            <h4><?php _e('Location Information', 'eatisfamily'); ?></h4>
            
            <div class="field-row">
                <label for="venue_slug"><?php _e('Venue ID/Slug', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('venue_slug', $venue_slug, 'stade-jean-bouin', __('Unique identifier for this venue (used in API)', 'eatisfamily')); ?>
                </div>
            </div>
            
            <div class="field-row">
                <label for="location"><?php _e('Full Address', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('location', $location, 'Paris, France'); ?>
                </div>
            </div>
            
            <div class="field-row">
                <label for="city"><?php _e('City', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('city', $city, 'Paris'); ?>
                </div>
            </div>
            
            <div class="field-row">
                <label for="country"><?php _e('Country', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('country', $country, 'France'); ?>
                </div>
            </div>
            
            <div class="field-row">
                <label for="venue_type"><?php _e('Venue Type', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_dropdown('venue_type', $venue_type, eatisfamily_get_event_types_dropdown()); ?>
                </div>
            </div>
        </div>
        
        <!-- Map Coordinates -->
        <div class="field-group">
            <h4><?php _e('Map Coordinates', 'eatisfamily'); ?></h4>
            
            <div class="field-row">
                <label for="latitude"><?php _e('Latitude', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('latitude', $latitude, '48.8414', __('e.g., 48.8414', 'eatisfamily')); ?>
                </div>
            </div>
            
            <div class="field-row">
                <label for="longitude"><?php _e('Longitude', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('longitude', $longitude, '2.2530', __('e.g., 2.2530', 'eatisfamily')); ?>
                </div>
            </div>
        </div>
        
        <!-- Images for Venue Details Grid -->
        <div class="field-group">
            <h4><?php _e('🖼️ Images de la Grille (Venue Details)', 'eatisfamily'); ?></h4>
            <p class="description"><?php _e('Ces deux images s\'affichent côte à côte dans la grille de détails de la venue sur la carte.', 'eatisfamily'); ?></p>
            
            <div class="field-row">
                <label for="grid_image_1"><?php _e('Image Gauche (avec badge)', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_media_field('grid_image_1', $grid_image_1, __('Première image de la grille - le badge du type de venue sera affiché dessus', 'eatisfamily')); ?>
                </div>
            </div>
            
            <div class="field-row">
                <label for="grid_image_2"><?php _e('Image Droite (avec bouton fermer)', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_media_field('grid_image_2', $grid_image_2, __('Deuxième image de la grille - le bouton fermer sera affiché dessus', 'eatisfamily')); ?>
                </div>
            </div>
        </div>
        
        <!-- Logo -->
        <div class="field-group">
            <h4><?php _e('🏷️ Logo de la Venue', 'eatisfamily'); ?></h4>
            
            <div class="field-row">
                <label for="logo_url"><?php _e('Logo', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_media_field('logo_url', $logo_url, __('Logo affiché à côté du nom de la venue', 'eatisfamily')); ?>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="field-group">
            <h4><?php _e('Statistics', 'eatisfamily'); ?></h4>
            
            <div class="field-row">
                <label for="capacity"><?php _e('Capacity', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('capacity', $capacity, '20,000'); ?>
                </div>
            </div>
            
            <div class="field-row">
                <label for="staff_members"><?php _e('Staff Members', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_number_field('staff_members', $staff_members, 0, '', 1); ?>
                </div>
            </div>
            
            <div class="field-row">
                <label for="guests_served"><?php _e('Guests Served', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('guests_served', $guests_served, '15,000'); ?>
                </div>
            </div>
            
            <div class="field-row">
                <label for="recent_event"><?php _e('Recent Event', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('recent_event', $recent_event, 'Football - Paris FC vs Toulouse FC'); ?>
                </div>
            </div>
        </div>
        
        <!-- Services (Repeater) -->
        <div class="field-group">
            <h4><?php _e('Services', 'eatisfamily'); ?></h4>
            <?php eatisfamily_render_repeater_field('services', $services, __('Enter a service...', 'eatisfamily'), __('List the services offered at this venue.', 'eatisfamily')); ?>
        </div>
        
        <!-- Shops (Complex Repeater) -->
        <div class="field-group">
            <h4><?php _e('Shops / Food Outlets', 'eatisfamily'); ?></h4>
            <p class="description"><?php _e('Add the food shops and outlets available at this venue.', 'eatisfamily'); ?></p>
            <?php eatisfamily_render_shops_repeater('shops', $shops); ?>
        </div>
        
        <!-- Menu Items (Complex Repeater) -->
        <div class="field-group">
            <h4><?php _e('Menu Items', 'eatisfamily'); ?></h4>
            <p class="description"><?php _e('Add signature menu items available at this venue.', 'eatisfamily'); ?></p>
            <?php eatisfamily_render_menu_items_repeater('menu_items', $menu_items); ?>
        </div>
    </div>
    <?php
}

/**
 * Save venue meta box data
 */
function eatisfamily_save_venue_meta_box($post_id) {
    // Verify nonce
    if (!isset($_POST['eatisfamily_venue_meta_box_nonce']) || !wp_verify_nonce($_POST['eatisfamily_venue_meta_box_nonce'], 'eatisfamily_venue_meta_box')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save text fields
    $text_fields = array('venue_slug', 'location', 'city', 'country', 'venue_type', 'capacity', 'recent_event', 'guests_served', 'logo_url', 'image2', 'grid_image_1', 'grid_image_2');
    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
    
    // Save numeric fields
    if (isset($_POST['latitude'])) {
        update_post_meta($post_id, 'latitude', floatval($_POST['latitude']));
    }
    if (isset($_POST['longitude'])) {
        update_post_meta($post_id, 'longitude', floatval($_POST['longitude']));
    }
    if (isset($_POST['staff_members'])) {
        update_post_meta($post_id, 'staff_members', intval($_POST['staff_members']));
    }
    
    // Save services array
    if (isset($_POST['services'])) {
        $services = array_filter(array_map('sanitize_text_field', $_POST['services']));
        update_post_meta($post_id, 'services', $services);
    }
    
    // Save shops array (complex repeater)
    if (isset($_POST['shops'])) {
        $shops = array();
        foreach ($_POST['shops'] as $index => $shop) {
            if (!empty($shop['name'])) {
                $shops[] = array(
                    'id' => 's' . ($index + 1),
                    'name' => sanitize_text_field($shop['name']),
                    'image' => esc_url_raw($shop['image'] ?? ''),
                );
            }
        }
        update_post_meta($post_id, 'shops', $shops);
    }
    
    // Save menu items array (complex repeater)
    if (isset($_POST['menu_items'])) {
        $menu_items = array();
        foreach ($_POST['menu_items'] as $index => $item) {
            if (!empty($item['name'])) {
                $menu_items[] = array(
                    'id' => 'm' . ($index + 1),
                    'name' => sanitize_text_field($item['name']),
                    'price' => sanitize_text_field($item['price'] ?? ''),
                    'description' => sanitize_text_field($item['description'] ?? ''),
                    'thumbnail' => esc_url_raw($item['thumbnail'] ?? ''),
                );
            }
        }
        update_post_meta($post_id, 'menu_items', $menu_items);
    }
}
add_action('save_post_venue', 'eatisfamily_save_venue_meta_box');

/**
 * ============================================================================
 * ACTIVITY META BOX
 * ============================================================================
 */

/**
 * Activity meta box callback
 */
function eatisfamily_activity_meta_box_callback($post) {
    wp_nonce_field('eatisfamily_activity_meta_box', 'eatisfamily_activity_meta_box_nonce');
    
    // Get current values
    $activity_date = get_post_meta($post->ID, 'activity_date', true);
    $location = get_post_meta($post->ID, 'location', true);
    $capacity = get_post_meta($post->ID, 'capacity', true);
    $available_spots = get_post_meta($post->ID, 'available_spots', true);
    $category = get_post_meta($post->ID, 'category', true);
    $price = get_post_meta($post->ID, 'price', true);
    $duration = get_post_meta($post->ID, 'duration', true);
    $status = get_post_meta($post->ID, 'status', true);
    
    ?>
    <div class="eatisfamily-meta-box">
        <div class="field-group">
            <h4><?php _e('Activity Information', 'eatisfamily'); ?></h4>
            
            <div class="field-row">
                <label for="activity_date"><?php _e('Date & Time', 'eatisfamily'); ?></label>
                <div>
                    <input type="datetime-local" name="activity_date" id="activity_date" value="<?php echo esc_attr($activity_date ? date('Y-m-d\TH:i', strtotime($activity_date)) : ''); ?>" class="regular-text">
                </div>
            </div>
            
            <div class="field-row">
                <label for="location"><?php _e('Location', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('location', $location, 'Culinary Studio, 123 Rue de la Cuisine, Paris'); ?>
                </div>
            </div>
            
            <div class="field-row">
                <label for="category"><?php _e('Category', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_dropdown('category', $category, eatisfamily_get_activity_categories_dropdown()); ?>
                </div>
            </div>
            
            <div class="field-row">
                <label for="status"><?php _e('Status', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_dropdown('status', $status ?: 'open', eatisfamily_get_activity_status_dropdown()); ?>
                </div>
            </div>
        </div>
        
        <div class="field-group">
            <h4><?php _e('Capacity & Pricing', 'eatisfamily'); ?></h4>
            
            <div class="field-row">
                <label for="capacity"><?php _e('Total Capacity', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_number_field('capacity', $capacity, 1); ?>
                </div>
            </div>
            
            <div class="field-row">
                <label for="available_spots"><?php _e('Available Spots', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_number_field('available_spots', $available_spots, 0); ?>
                </div>
            </div>
            
            <div class="field-row">
                <label for="price"><?php _e('Price', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('price', $price, '€85'); ?>
                </div>
            </div>
            
            <div class="field-row">
                <label for="duration"><?php _e('Duration', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('duration', $duration, '3 hours'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Save activity meta box data
 */
function eatisfamily_save_activity_meta_box($post_id) {
    // Verify nonce
    if (!isset($_POST['eatisfamily_activity_meta_box_nonce']) || !wp_verify_nonce($_POST['eatisfamily_activity_meta_box_nonce'], 'eatisfamily_activity_meta_box')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save text fields
    $text_fields = array('location', 'category', 'price', 'duration', 'status');
    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
    
    // Save date
    if (isset($_POST['activity_date'])) {
        update_post_meta($post_id, 'activity_date', sanitize_text_field($_POST['activity_date']));
    }
    
    // Save numeric fields
    if (isset($_POST['capacity'])) {
        update_post_meta($post_id, 'capacity', intval($_POST['capacity']));
    }
    if (isset($_POST['available_spots'])) {
        update_post_meta($post_id, 'available_spots', intval($_POST['available_spots']));
    }
}
add_action('save_post_activity', 'eatisfamily_save_activity_meta_box');

/**
 * ============================================================================
 * EVENT META BOX
 * ============================================================================
 */

/**
 * Event meta box callback
 */
function eatisfamily_event_meta_box_callback($post) {
    wp_nonce_field('eatisfamily_event_meta_box', 'eatisfamily_event_meta_box_nonce');
    
    // Get current values
    $event_type = get_post_meta($post->ID, 'event_type', true);
    $venue_id = get_post_meta($post->ID, 'venue_id', true);
    
    ?>
    <div class="eatisfamily-meta-box">
        <div class="field-row">
            <label for="event_type"><?php _e('Event Type', 'eatisfamily'); ?></label>
            <div>
                <?php 
                $event_type_options = array(
                    '' => __('-- Select Event Type --', 'eatisfamily'),
                    'Venue Partnership' => __('Venue Partnership', 'eatisfamily'),
                    'Festival' => __('Festival', 'eatisfamily'),
                    'Sports Event' => __('Sports Event', 'eatisfamily'),
                    'Fashion Event' => __('Fashion Event', 'eatisfamily'),
                    'Concert' => __('Concert', 'eatisfamily'),
                    'Corporate Event' => __('Corporate Event', 'eatisfamily'),
                );
                eatisfamily_render_dropdown('event_type', $event_type, $event_type_options); 
                ?>
            </div>
        </div>
        
        <div class="field-row">
            <label for="venue_id"><?php _e('Related Venue', 'eatisfamily'); ?></label>
            <div>
                <?php eatisfamily_render_dropdown('venue_id', $venue_id, eatisfamily_get_venues_dropdown_options(), __('Optional: Link this event to a venue.', 'eatisfamily')); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Save event meta box data
 */
function eatisfamily_save_event_meta_box($post_id) {
    // Verify nonce
    if (!isset($_POST['eatisfamily_event_meta_box_nonce']) || !wp_verify_nonce($_POST['eatisfamily_event_meta_box_nonce'], 'eatisfamily_event_meta_box')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save fields
    if (isset($_POST['event_type'])) {
        update_post_meta($post_id, 'event_type', sanitize_text_field($_POST['event_type']));
    }
    if (isset($_POST['venue_id'])) {
        update_post_meta($post_id, 'venue_id', sanitize_text_field($_POST['venue_id']));
    }
}
add_action('save_post_event', 'eatisfamily_save_event_meta_box');

/**
 * ============================================================================
 * BLOG POST META BOX
 * ============================================================================
 */

/**
 * Blog post meta box callback
 */
function eatisfamily_blog_meta_box_callback($post) {
    wp_nonce_field('eatisfamily_blog_meta_box', 'eatisfamily_blog_meta_box_nonce');
    
    // Get current values
    $reading_time = get_post_meta($post->ID, 'reading_time', true);
    $author_name = get_post_meta($post->ID, 'author_name', true);
    $author_avatar = get_post_meta($post->ID, 'author_avatar', true);
    
    ?>
    <div class="eatisfamily-meta-box">
        <div class="field-row">
            <label for="reading_time"><?php _e('Reading Time', 'eatisfamily'); ?></label>
            <div>
                <?php eatisfamily_render_text_field('reading_time', $reading_time, '5 min read'); ?>
            </div>
        </div>
        
        <div class="field-group">
            <h4><?php _e('Custom Author (Optional)', 'eatisfamily'); ?></h4>
            <p class="description"><?php _e('Override the WordPress author with custom author information.', 'eatisfamily'); ?></p>
            
            <div class="field-row">
                <label for="author_name"><?php _e('Author Name', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_text_field('author_name', $author_name, 'John Doe'); ?>
                </div>
            </div>
            
            <div class="field-row">
                <label for="author_avatar"><?php _e('Author Avatar', 'eatisfamily'); ?></label>
                <div>
                    <?php eatisfamily_render_media_field('author_avatar', $author_avatar, __('Upload or select an avatar image.', 'eatisfamily')); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Save blog post meta box data
 */
function eatisfamily_save_blog_meta_box($post_id) {
    // Verify nonce
    if (!isset($_POST['eatisfamily_blog_meta_box_nonce']) || !wp_verify_nonce($_POST['eatisfamily_blog_meta_box_nonce'], 'eatisfamily_blog_meta_box')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save fields
    $fields = array('reading_time', 'author_name', 'author_avatar');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_post', 'eatisfamily_save_blog_meta_box');

/**
 * ============================================================================
 * TIMELINE EVENT META BOX
 * ============================================================================
 */

/**
 * Timeline event meta box callback
 */
function eatisfamily_timeline_meta_box_callback($post) {
    wp_nonce_field('eatisfamily_timeline_meta_box', 'eatisfamily_timeline_meta_box_nonce');
    
    // Get current values
    $event_date = get_post_meta($post->ID, 'event_date', true);
    $event_description = get_post_meta($post->ID, 'event_description', true);
    $display_order = get_post_meta($post->ID, 'display_order', true);
    
    ?>
    <div class="eatisfamily-meta-box">
        <p class="description"><?php _e('Timeline events appear on the About page in chronological order. Use the Featured Image for the event image.', 'eatisfamily'); ?></p>
        
        <div class="field-row">
            <label for="event_date"><?php _e('Event Date', 'eatisfamily'); ?></label>
            <div>
                <?php
                // Convert stored "DD MONTH YYYY" format to YYYY-MM-DD for the date picker
                $date_value = '';
                if ($event_date) {
                    $timestamp = strtotime($event_date);
                    if ($timestamp) {
                        $date_value = date('Y-m-d', $timestamp);
                    }
                }
                ?>
                <input type="date" name="event_date" id="event_date" value="<?php echo esc_attr($date_value); ?>" class="regular-text">
                <p class="description"><?php _e('Select the date for this timeline event.', 'eatisfamily'); ?></p>
            </div>
        </div>

        <div class="field-row">
            <label for="event_description"><?php _e('Event Description', 'eatisfamily'); ?></label>
            <div>
                <?php eatisfamily_render_wysiwyg('event_description', $event_description, __('A detailed description of this milestone or event in your company\'s history.', 'eatisfamily')); ?>
            </div>
        </div>
        
        <div class="field-row">
            <label for="display_order"><?php _e('Display Order', 'eatisfamily'); ?></label>
            <div>
                <?php eatisfamily_render_number_field('display_order', $display_order ?: 1, 1, 100, 1, __('Lower numbers appear first in the timeline.', 'eatisfamily')); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Save timeline event meta box data
 */
function eatisfamily_save_timeline_meta_box($post_id) {
    // Verify nonce
    if (!isset($_POST['eatisfamily_timeline_meta_box_nonce']) || !wp_verify_nonce($_POST['eatisfamily_timeline_meta_box_nonce'], 'eatisfamily_timeline_meta_box')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save event_date: convert from YYYY-MM-DD input to "DD MONTH YYYY" for API compatibility
    if (isset($_POST['event_date']) && !empty($_POST['event_date'])) {
        $timestamp = strtotime(sanitize_text_field($_POST['event_date']));
        if ($timestamp) {
            $display_date = strtoupper(date('d F Y', $timestamp));
            update_post_meta($post_id, 'event_date', $display_date);
        }
    } elseif (isset($_POST['event_date'])) {
        update_post_meta($post_id, 'event_date', '');
    }
    // Save event_description: use wp_kses_post to preserve HTML from WYSIWYG
    if (isset($_POST['event_description'])) {
        update_post_meta($post_id, 'event_description', wp_kses_post($_POST['event_description']));
    }
    if (isset($_POST['display_order'])) {
        update_post_meta($post_id, 'display_order', intval($_POST['display_order']));
    }
}
add_action('save_post_timeline_event', 'eatisfamily_save_timeline_meta_box');

/**
 * ============================================================================
 * ADMIN SCRIPTS AND STYLES
 * ============================================================================
 */

/**
 * Enqueue admin scripts for meta boxes
 */
function eatisfamily_admin_scripts($hook) {
    global $post_type;
    
    $allowed_post_types = array('job', 'venue', 'activity', 'event', 'post', 'timeline_event');
    
    if (($hook === 'post.php' || $hook === 'post-new.php') && in_array($post_type, $allowed_post_types)) {
        // Enqueue media uploader
        wp_enqueue_media();
        
        wp_add_inline_script('jquery', '
            jQuery(document).ready(function($) {
                // Repeater field functionality
                $(document).on("click", ".repeater-add", function() {
                    var $container = $(this).closest(".eatisfamily-repeater");
                    var $items = $container.find(".repeater-items");
                    var name = $container.data("name");
                    var placeholder = $items.find("input").first().attr("placeholder") || "";
                    
                    var $newItem = $("<div class=\"repeater-item\">" +
                        "<input type=\"text\" name=\"" + name + "[]\" value=\"\" class=\"regular-text\" placeholder=\"" + placeholder + "\">" +
                        "<button type=\"button\" class=\"button repeater-remove\" title=\"Remove\">✕</button>" +
                        "</div>");
                    
                    $items.append($newItem);
                    $newItem.find("input").focus();
                });
                
                $(document).on("click", ".repeater-remove", function() {
                    var $container = $(this).closest(".eatisfamily-repeater");
                    var $items = $container.find(".repeater-item");
                    
                    if ($items.length > 1) {
                        $(this).closest(".repeater-item").remove();
                    } else {
                        $(this).closest(".repeater-item").find("input").val("");
                    }
                });
                
                // Media upload button
                $(document).on("click", ".eatisfamily-upload-btn", function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var targetId = button.data("target");
                    
                    var frame = wp.media({
                        title: "Select Image",
                        button: { text: "Use this image" },
                        multiple: false
                    });
                    
                    frame.on("select", function() {
                        var attachment = frame.state().get("selection").first().toJSON();
                        $("#" + targetId).val(attachment.url);
                        // Update preview if exists
                        button.siblings(".media-preview").remove();
                        button.after("<div class=\"media-preview\" style=\"margin-top: 10px;\"><img src=\"" + attachment.url + "\" style=\"max-width: 150px; height: auto; border: 1px solid #ccc; padding: 2px;\"></div>");
                    });
                    
                    frame.open();
                });
                
                // Gallery field functionality
                $(document).on("click", ".eatisfamily-gallery-add", function(e) {
                    e.preventDefault();
                    var $container = $(this).closest(".eatisfamily-gallery-field");
                    var name = $container.data("name");
                    var $items = $container.find(".gallery-items");
                    
                    var frame = wp.media({
                        title: "Select Images",
                        button: { text: "Add to gallery" },
                        multiple: true
                    });
                    
                    frame.on("select", function() {
                        var attachments = frame.state().get("selection").toJSON();
                        attachments.forEach(function(attachment) {
                            var $item = $("<div class=\"gallery-item\" style=\"position: relative;\">" +
                                "<img src=\"" + attachment.url + "\" style=\"width: 100px; height: 100px; object-fit: cover; border: 1px solid #ccc;\">" +
                                "<input type=\"hidden\" name=\"" + name + "[]\" value=\"" + attachment.url + "\">" +
                                "<button type=\"button\" class=\"gallery-remove\" style=\"position: absolute; top: -5px; right: -5px; background: #d63638; color: #fff; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer; font-size: 12px;\">✕</button>" +
                                "</div>");
                            $items.append($item);
                        });
                    });
                    
                    frame.open();
                });
                
                $(document).on("click", ".gallery-remove", function() {
                    $(this).closest(".gallery-item").remove();
                });
                
                // Complex repeater (shops, menu items)
                $(document).on("click", ".complex-repeater-add", function() {
                    var $container = $(this).closest(".eatisfamily-complex-repeater");
                    var name = $container.data("name");
                    var type = $(this).data("type");
                    var $items = $container.find(".complex-repeater-items");
                    var index = $items.find(".complex-repeater-item").length;
                    
                    var $newItem;
                    if (type === "shop") {
                        var shopImageId = name + "_" + index + "_image";
                        $newItem = $("<div class=\"complex-repeater-item\" style=\"display: flex; gap: 10px; align-items: center; margin-bottom: 10px; padding: 10px; background: #fff; border: 1px solid #ddd;\">" +
                            "<div style=\"flex: 1;\"><label>Name</label><input type=\"text\" name=\"" + name + "[" + index + "][name]\" value=\"\" class=\"regular-text\" placeholder=\"Shop name...\"></div>" +
                            "<div style=\"flex: 1;\"><label>Image</label><div class=\"eatisfamily-media-field\"><input type=\"text\" name=\"" + name + "[" + index + "][image]\" id=\"" + shopImageId + "\" value=\"\" class=\"regular-text\" placeholder=\"Image URL...\"><button type=\"button\" class=\"button eatisfamily-upload-btn\" data-target=\"" + shopImageId + "\">Select Image</button></div></div>" +
                            "<button type=\"button\" class=\"button complex-repeater-remove\" style=\"color: #d63638;\">✕</button>" +
                            "</div>");
                    } else if (type === "menu") {
                        var menuThumbId = name + "_" + index + "_thumbnail";
                        $newItem = $("<div class=\"complex-repeater-item\" style=\"display: grid; grid-template-columns: 1fr 100px 2fr 200px auto auto; gap: 10px; align-items: center; margin-bottom: 10px; padding: 10px; background: #fff; border: 1px solid #ddd;\">" +
                            "<div><input type=\"text\" name=\"" + name + "[" + index + "][name]\" value=\"\" class=\"regular-text\" placeholder=\"Item name\"></div>" +
                            "<div><input type=\"text\" name=\"" + name + "[" + index + "][price]\" value=\"\" class=\"small-text\" placeholder=\"Price\"></div>" +
                            "<div><input type=\"text\" name=\"" + name + "[" + index + "][description]\" value=\"\" class=\"regular-text\" placeholder=\"Description\"></div>" +
                            "<div><input type=\"text\" name=\"" + name + "[" + index + "][thumbnail]\" id=\"" + menuThumbId + "\" value=\"\" class=\"regular-text\" placeholder=\"Thumbnail URL\"></div>" +
                            "<button type=\"button\" class=\"button eatisfamily-upload-btn\" data-target=\"" + menuThumbId + "\">Select</button>" +
                            "<button type=\"button\" class=\"button complex-repeater-remove\" style=\"color: #d63638;\">✕</button>" +
                            "</div>");
                    }
                    
                    $items.append($newItem);
                });
                
                $(document).on("click", ".complex-repeater-remove", function() {
                    var $container = $(this).closest(".eatisfamily-complex-repeater");
                    var $items = $container.find(".complex-repeater-item");
                    
                    if ($items.length > 1) {
                        $(this).closest(".complex-repeater-item").remove();
                    } else {
                        $(this).closest(".complex-repeater-item").find("input").val("");
                    }
                });
            });
        ');
        
        wp_add_inline_style('wp-admin', '
            .eatisfamily-meta-box { display: grid; gap: 20px; }
            .eatisfamily-meta-box .field-row { display: grid; grid-template-columns: 200px 1fr; gap: 10px; align-items: start; padding: 8px 0; }
            .eatisfamily-meta-box label { font-weight: 600; padding-top: 5px; }
            .eatisfamily-meta-box .field-group { border: 1px solid #c3c4c7; padding: 15px; background: #f6f7f7; border-radius: 4px; margin-top: 10px; }
            .eatisfamily-meta-box .field-group h4 { margin-top: 0; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #c3c4c7; }
            .repeater-item { display: flex; gap: 5px; margin-bottom: 8px; }
            .repeater-item input { flex: 1; }
            .repeater-remove { color: #d63638 !important; border-color: #d63638 !important; }
            .repeater-remove:hover { background: #d63638 !important; color: #fff !important; }
            .repeater-add { margin-top: 10px; }
            .eatisfamily-media-field { display: flex; gap: 10px; align-items: flex-start; flex-wrap: wrap; }
            .complex-repeater-item label { display: block; font-size: 11px; color: #666; margin-bottom: 3px; }
        ');
    }
}
add_action('admin_enqueue_scripts', 'eatisfamily_admin_scripts');
