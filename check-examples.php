<?php
require '/var/www/html/wp-load.php';

// Charger les examples depuis le JSON
$json_file = '/var/www/html/wp-content/themes/eatisfamily/data/pages-content.json';
if (!file_exists($json_file)) {
    $json_file = '/var/www/html/api/pages-content.json';
}

$json_data = array();
if (file_exists($json_file)) {
    $json_content = file_get_contents($json_file);
    $json_data = json_decode($json_content, true);
}

// Récupérer les examples du JSON
$json_examples = $json_data['homepage']['examples'] ?? array();

echo "=== EXAMPLES FROM JSON ===\n";
print_r($json_examples);

// Récupérer l'option WordPress actuelle
$wp_data = get_option('eatisfamily_pages_content', array());

echo "\n=== EXAMPLES FROM WP OPTION ===\n";
print_r($wp_data['homepage']['examples'] ?? 'NOT FOUND or EMPTY');

// Importer les examples si vides dans WordPress mais présents dans JSON
if (empty($wp_data['homepage']['examples']) && !empty($json_examples)) {
    if (!isset($wp_data['homepage'])) {
        $wp_data['homepage'] = array();
    }
    $wp_data['homepage']['examples'] = $json_examples;
    update_option('eatisfamily_pages_content', $wp_data);
    echo "\n=== IMPORTED EXAMPLES FROM JSON TO WORDPRESS ===\n";
    echo "Done! " . count($json_examples) . " examples imported.\n";
}
