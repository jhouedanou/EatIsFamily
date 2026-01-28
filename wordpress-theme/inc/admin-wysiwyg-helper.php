<?php
/**
 * EIF Backend - WYSIWYG Helper Functions
 * Provides helper functions for rendering WordPress WYSIWYG editors in admin pages
 *
 * @package EIFBackend
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render a WYSIWYG editor field with WordPress TinyMCE
 * 
 * @param string $id       Field ID and name
 * @param string $value    Current value
 * @param array  $options  Optional settings
 * @return void
 */
function eatisfamily_wysiwyg_editor($id, $value = '', $options = array()) {
    $defaults = array(
        'textarea_rows' => 8,
        'media_buttons' => true,
        'teeny' => false,
        'quicktags' => true,
        'tinymce' => array(
            'toolbar1' => 'formatselect,bold,italic,underline,strikethrough,|,bullist,numlist,|,link,unlink,|,forecolor,|,removeformat,|,undo,redo',
            'toolbar2' => '',
            'block_formats' => 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6',
            'relative_urls' => false,
            'remove_script_host' => false,
            'convert_urls' => false,
        ),
        'wpautop' => true,
        'drag_drop_upload' => true,
    );
    
    $settings = wp_parse_args($options, $defaults);
    
    // Add custom class to wrapper
    echo '<div class="eatisfamily-wysiwyg-wrapper" data-editor-id="' . esc_attr($id) . '">';
    
    wp_editor(
        wp_kses_post($value),
        $id,
        $settings
    );
    
    echo '</div>';
}

/**
 * Render a simple/mini WYSIWYG editor for short content
 * Less buttons, smaller editor
 * 
 * @param string $id       Field ID and name
 * @param string $value    Current value
 * @param array  $options  Optional settings override
 * @return void
 */
function eatisfamily_mini_wysiwyg_editor($id, $value = '', $options = array()) {
    $defaults = array(
        'textarea_rows' => 4,
        'media_buttons' => false,
        'teeny' => true,
        'quicktags' => array(
            'buttons' => 'strong,em,link,ul,ol,li,close'
        ),
        'tinymce' => array(
            'toolbar1' => 'bold,italic,underline,|,link,unlink,|,forecolor,|,removeformat',
            'toolbar2' => '',
            'relative_urls' => false,
            'remove_script_host' => false,
            'convert_urls' => false,
        ),
        'wpautop' => false,
        'drag_drop_upload' => false,
    );
    
    $settings = wp_parse_args($options, $defaults);
    
    echo '<div class="eatisfamily-wysiwyg-wrapper eatisfamily-mini-wysiwyg" data-editor-id="' . esc_attr($id) . '">';
    
    wp_editor(
        wp_kses_post($value),
        $id,
        $settings
    );
    
    echo '</div>';
}

/**
 * Add admin styles for WYSIWYG editors
 */
function eatisfamily_wysiwyg_admin_styles() {
    $screen = get_current_screen();
    
    // Only on our admin pages
    if (strpos($screen->id, 'eatisfamily') === false) {
        return;
    }
    
    ?>
    <style>
    .eatisfamily-wysiwyg-wrapper {
        max-width: 100%;
        margin-bottom: 10px;
    }
    
    .eatisfamily-wysiwyg-wrapper .wp-editor-container {
        border: 1px solid #dcdcde;
        border-radius: 4px;
    }
    
    .eatisfamily-wysiwyg-wrapper .wp-editor-area {
        border: 0;
    }
    
    .eatisfamily-mini-wysiwyg .wp-editor-container {
        max-width: 100%;
    }
    
    .eatisfamily-mini-wysiwyg .wp-editor-area {
        min-height: 80px !important;
    }
    
    /* Fix for tab content visibility with TinyMCE */
    .tab-content .eatisfamily-wysiwyg-wrapper {
        display: block;
    }
    
    /* Badge for WYSIWYG fields */
    .wysiwyg-label-badge {
        display: inline-block;
        background: #2271b1;
        color: white;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 3px;
        margin-left: 5px;
        font-weight: normal;
        vertical-align: middle;
    }
    
    /* Styling for form-table with WYSIWYG */
    .form-table td .eatisfamily-wysiwyg-wrapper {
        margin-top: 5px;
    }
    </style>
    <?php
}
add_action('admin_head', 'eatisfamily_wysiwyg_admin_styles');

/**
 * JavaScript helper to sync TinyMCE content before form submission
 * This is critical for AJAX form submissions
 */
function eatisfamily_wysiwyg_admin_scripts() {
    $screen = get_current_screen();
    
    // Only on our admin pages
    if (strpos($screen->id, 'eatisfamily') === false) {
        return;
    }
    
    ?>
    <script>
    (function($) {
        'use strict';
        
        /**
         * Sync all TinyMCE editors content to their textareas
         * Must be called before collecting form data
         */
        window.eatisfamilySyncEditors = function() {
            // Sync all TinyMCE editors
            if (typeof tinymce !== 'undefined' && tinymce.editors) {
                tinymce.editors.forEach(function(editor) {
                    if (editor && !editor.isHidden()) {
                        editor.save();
                    }
                });
            }
            
            // Also trigger change on textareas
            $('.eatisfamily-wysiwyg-wrapper textarea.wp-editor-area').each(function() {
                $(this).trigger('change');
            });
        };
        
        /**
         * Reinitialize TinyMCE editors when tab becomes visible
         * Needed because TinyMCE doesn't render correctly in hidden tabs
         */
        window.eatisfamilyRefreshEditors = function() {
            if (typeof tinymce !== 'undefined' && tinymce.editors) {
                tinymce.editors.forEach(function(editor) {
                    if (editor) {
                        // Remove and re-add to fix rendering issues
                        var editorId = editor.id;
                        var content = editor.getContent();
                        
                        // Only refresh if the editor is in a visible container
                        var $wrapper = $('#' + editorId).closest('.tab-content');
                        if ($wrapper.length && $wrapper.is(':visible')) {
                            tinymce.execCommand('mceRemoveEditor', false, editorId);
                            tinymce.execCommand('mceAddEditor', false, editorId);
                            
                            // Restore content
                            var newEditor = tinymce.get(editorId);
                            if (newEditor) {
                                newEditor.setContent(content);
                            }
                        }
                    }
                });
            }
        };
        
        /**
         * Get all WYSIWYG field values as an object
         */
        window.eatisfamilyGetWysiwygValues = function() {
            var values = {};
            
            $('.eatisfamily-wysiwyg-wrapper').each(function() {
                var editorId = $(this).data('editor-id');
                var editor = null;
                
                if (typeof tinymce !== 'undefined') {
                    editor = tinymce.get(editorId);
                }
                
                if (editor && !editor.isHidden()) {
                    values[editorId] = editor.getContent();
                } else {
                    values[editorId] = $('#' + editorId).val();
                }
            });
            
            return values;
        };
        
        // Sync editors before any form submission
        $(document).on('submit', 'form', function() {
            window.eatisfamilySyncEditors();
        });
        
        // Handle tab switching to refresh editors
        $(document).on('click', '.nav-tab', function() {
            // Small delay to let the tab become visible
            setTimeout(function() {
                window.eatisfamilyRefreshEditors();
            }, 100);
        });
        
    })(jQuery);
    </script>
    <?php
}
add_action('admin_footer', 'eatisfamily_wysiwyg_admin_scripts');
