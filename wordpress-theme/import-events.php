<?php
/**
 * Import Events from JSON to WordPress
 * 
 * This file imports events from the JSON file to WordPress custom post type 'event'
 * 
 * Usage: Navigate to: yourdomain.com/wp-content/themes/eatisfamily/import-events.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user has permission
if (!current_user_can('manage_options')) {
    die('Access denied. You must be an administrator.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Import Events</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 50px auto; padding: 20px; }
        .success { color: green; padding: 10px; margin: 10px 0; background: #e7f7e7; border-left: 4px solid green; }
        .error { color: red; padding: 10px; margin: 10px 0; background: #ffe7e7; border-left: 4px solid red; }
        .warning { color: orange; padding: 10px; margin: 10px 0; background: #fff4e7; border-left: 4px solid orange; }
        .info { color: blue; padding: 10px; margin: 10px 0; background: #e7f3ff; border-left: 4px solid blue; }
        h1 { color: #333; border-bottom: 3px solid #FF4D6D; padding-bottom: 10px; }
        .stats { background: #f5f5f5; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .event-card { background: white; padding: 15px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        .event-card img { max-width: 100px; height: auto; }
        button { background: #FF4D6D; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #e63d5d; }
    </style>
</head>
<body>
    <h1>🎉 Import Events to WordPress</h1>
    
    <?php
    if (isset($_POST['import_events'])) {
        echo '<div class="info">Starting import process...</div>';
        
        // Path to the JSON file
        $json_file = dirname(__FILE__) . '/../../../public/api/events.json';
        
        if (!file_exists($json_file)) {
            echo '<div class="error">❌ Error: events.json file not found at: ' . $json_file . '</div>';
        } else {
            // Read and decode JSON
            $json_content = file_get_contents($json_file);
            $events = json_decode($json_content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo '<div class="error">❌ Error parsing JSON: ' . json_last_error_msg() . '</div>';
            } else {
                echo '<div class="success">✅ JSON file loaded successfully! Found ' . count($events) . ' events.</div>';
                
                $imported = 0;
                $updated = 0;
                $skipped = 0;
                
                foreach ($events as $event) {
                    // Check if event already exists by title
                    $existing = get_posts(array(
                        'post_type' => 'event',
                        'title' => $event['title'],
                        'posts_per_page' => 1,
                        'post_status' => 'any'
                    ));
                    
                    if (!empty($existing)) {
                        // Update existing event
                        $post_id = $existing[0]->ID;
                        wp_update_post(array(
                            'ID' => $post_id,
                            'post_content' => $event['description']
                        ));
                        
                        // Update meta fields
                        update_post_meta($post_id, 'event_type', $event['event_type']);
                        update_post_meta($post_id, 'image', $event['image']);
                        update_post_meta($post_id, 'event_order', $event['id']);
                        
                        echo '<div class="warning">⚠️ Updated existing event: ' . $event['title'] . ' (ID: ' . $post_id . ')</div>';
                        $updated++;
                    } else {
                        // Create new event
                        $post_data = array(
                            'post_title' => $event['title'],
                            'post_content' => $event['description'],
                            'post_status' => 'publish',
                            'post_type' => 'event',
                            'menu_order' => $event['id']
                        );
                        
                        $post_id = wp_insert_post($post_data);
                        
                        if (is_wp_error($post_id)) {
                            echo '<div class="error">❌ Error creating event: ' . $event['title'] . ' - ' . $post_id->get_error_message() . '</div>';
                            $skipped++;
                        } else {
                            // Add meta fields
                            update_post_meta($post_id, 'event_type', $event['event_type']);
                            update_post_meta($post_id, 'image', $event['image']);
                            update_post_meta($post_id, 'event_order', $event['id']);
                            
                            echo '<div class="success">✅ Created new event: ' . $event['title'] . ' (ID: ' . $post_id . ')</div>';
                            $imported++;
                        }
                    }
                }
                
                echo '<div class="stats">';
                echo '<h2>📊 Import Summary</h2>';
                echo '<p><strong>Total events in JSON:</strong> ' . count($events) . '</p>';
                echo '<p><strong>New events created:</strong> ' . $imported . '</p>';
                echo '<p><strong>Existing events updated:</strong> ' . $updated . '</p>';
                echo '<p><strong>Skipped/Errors:</strong> ' . $skipped . '</p>';
                echo '</div>';
            }
        }
    } else {
        // Show preview of events
        $json_file = dirname(__FILE__) . '/../../../public/api/events.json';
        
        if (file_exists($json_file)) {
            $json_content = file_get_contents($json_file);
            $events = json_decode($json_content, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                echo '<div class="info">📋 Found ' . count($events) . ' events in JSON file ready to import.</div>';
                
                echo '<h2>Preview of Events:</h2>';
                foreach ($events as $event) {
                    echo '<div class="event-card">';
                    echo '<h3>' . htmlspecialchars($event['title']) . '</h3>';
                    if (!empty($event['image'])) {
                        echo '<img src="' . htmlspecialchars($event['image']) . '" alt="Event image">';
                    }
                    echo '<p><strong>Type:</strong> ' . htmlspecialchars($event['event_type']) . '</p>';
                    echo '<p><strong>Description:</strong> ' . htmlspecialchars(substr($event['description'], 0, 150)) . '...</p>';
                    echo '</div>';
                }
                
                echo '<form method="post" style="margin: 30px 0;">';
                echo '<button type="submit" name="import_events" value="1">🚀 Import All Events to WordPress</button>';
                echo '</form>';
            } else {
                echo '<div class="error">❌ Error parsing JSON file</div>';
            }
        } else {
            echo '<div class="error">❌ events.json file not found</div>';
        }
    }
    ?>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
        <p><a href="<?php echo admin_url(); ?>">← Back to WordPress Admin</a></p>
    </div>
</body>
</html>
