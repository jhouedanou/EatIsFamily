<?php
/**
 * Fix Venue Images with FALSE values
 * 
 * This script finds and fixes venue images that have boolean false instead of empty string
 * 
 * Usage: Navigate to: yourdomain.com/wp-content/themes/eatisfamily/fix-venue-images.php
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
    <title>Fix Venue Images</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 50px auto; padding: 20px; }
        .success { color: green; padding: 10px; margin: 10px 0; background: #e7f7e7; border-left: 4px solid green; }
        .error { color: red; padding: 10px; margin: 10px 0; background: #ffe7e7; border-left: 4px solid red; }
        .warning { color: orange; padding: 10px; margin: 10px 0; background: #fff4e7; border-left: 4px solid orange; }
        .info { color: blue; padding: 10px; margin: 10px 0; background: #e7f3ff; border-left: 4px solid blue; }
        h1 { color: #333; border-bottom: 3px solid #FF4D6D; padding-bottom: 10px; }
        .stats { background: #f5f5f5; padding: 20px; margin: 20px 0; border-radius: 5px; }
        button { background: #FF4D6D; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #e63d5d; }
        .venue-card { background: white; padding: 15px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>🔧 Fix Venue Image Values</h1>
    
    <?php
    if (isset($_POST['fix_images'])) {
        echo '<div class="info">Starting fix process...</div>';
        
        // Get all venues
        $venues = get_posts(array(
            'post_type' => 'venue',
            'posts_per_page' => -1,
            'post_status' => 'any'
        ));
        
        $fixed = 0;
        $checked = 0;
        
        foreach ($venues as $venue) {
            $checked++;
            $venue_id = $venue->ID;
            $venue_name = get_the_title($venue_id);
            
            // Check image field
            $image = get_post_meta($venue_id, 'image', true);
            $image2 = get_post_meta($venue_id, 'image2', true);
            
            $needs_fix = false;
            
            // Fix image if it's boolean false
            if ($image === false || $image === 'false' || (is_string($image) && trim($image) === 'false')) {
                update_post_meta($venue_id, 'image', '');
                echo '<div class="warning">⚠️ Fixed image field for: ' . $venue_name . '</div>';
                $needs_fix = true;
            }
            
            // Fix image2 if it's boolean false
            if ($image2 === false || $image2 === 'false' || (is_string($image2) && trim($image2) === 'false')) {
                update_post_meta($venue_id, 'image2', '');
                echo '<div class="warning">⚠️ Fixed image2 field for: ' . $venue_name . '</div>';
                $needs_fix = true;
            }
            
            if ($needs_fix) {
                $fixed++;
            }
        }
        
        echo '<div class="stats">';
        echo '<h2>📊 Fix Summary</h2>';
        echo '<p><strong>Total venues checked:</strong> ' . $checked . '</p>';
        echo '<p><strong>Venues fixed:</strong> ' . $fixed . '</p>';
        echo '<p><strong>Venues OK:</strong> ' . ($checked - $fixed) . '</p>';
        echo '</div>';
        
        if ($fixed > 0) {
            echo '<div class="success">✅ Fixed ' . $fixed . ' venue(s)! The 404 errors for "false" should be resolved now.</div>';
        } else {
            echo '<div class="success">✅ All venues are already OK! No fixes needed.</div>';
        }
        
    } else {
        // Show venues with potential issues
        $venues = get_posts(array(
            'post_type' => 'venue',
            'posts_per_page' => -1,
            'post_status' => 'any'
        ));
        
        echo '<div class="info">📋 Found ' . count($venues) . ' venues. Checking for image issues...</div>';
        
        $problematic = 0;
        
        echo '<h2>Venues Status:</h2>';
        foreach ($venues as $venue) {
            $venue_id = $venue->ID;
            $venue_name = get_the_title($venue_id);
            $image = get_post_meta($venue_id, 'image', true);
            $image2 = get_post_meta($venue_id, 'image2', true);
            
            $has_issue = false;
            
            if ($image === false || $image === 'false' || (is_string($image) && trim($image) === 'false')) {
                $has_issue = true;
            }
            
            if ($image2 === false || $image2 === 'false' || (is_string($image2) && trim($image2) === 'false')) {
                $has_issue = true;
            }
            
            if ($has_issue) {
                echo '<div class="venue-card warning">';
                echo '<h3>⚠️ ' . $venue_name . '</h3>';
                echo '<p><strong>Image:</strong> ' . var_export($image, true) . '</p>';
                echo '<p><strong>Image2:</strong> ' . var_export($image2, true) . '</p>';
                echo '</div>';
                $problematic++;
            }
        }
        
        if ($problematic === 0) {
            echo '<div class="success">✅ All venues look good! No image issues found.</div>';
        } else {
            echo '<div class="warning">⚠️ Found ' . $problematic . ' venue(s) with image issues.</div>';
            echo '<form method="post" style="margin: 30px 0;">';
            echo '<button type="submit" name="fix_images" value="1">🔧 Fix All Image Issues</button>';
            echo '</form>';
        }
    }
    ?>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
        <p><a href="<?php echo admin_url(); ?>">← Back to WordPress Admin</a></p>
    </div>
</body>
</html>
