<?php
/**
 * Script de diagnostic des données Homepage
 * Réservé aux administrateurs connectés.
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

// SECURITY: Require admin authentication
if (!is_user_logged_in() || !current_user_can('manage_options')) {
    status_header(403);
    wp_die(__('Accès non autorisé. Vous devez être connecté en tant qu\'administrateur.', 'eatisfamily'), 'Accès refusé', array('response' => 403));
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic Homepage Data</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #f4f4f4; }
        .ok { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warn { color: #ffc107; font-weight: bold; }
        pre { background: #f4f4f4; padding: 10px; overflow: auto; max-height: 300px; }
        .btn { display: inline-block; padding: 10px 20px; background: #0073aa; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
        .btn:hover { background: #005a87; }
        .btn-success { background: #28a745; }
    </style>
</head>
<body>
    <h1>📊 Diagnostic des données Homepage</h1>
    
    <?php
    // Get all relevant options
    $pages_content = get_option('eatisfamily_pages_content', array());
    $sustainability = get_option('eatisfamily_sustainability', array());
    $services = get_option('eatisfamily_services', array());
    $partners = get_option('eatisfamily_partners', array());
    $gallery = get_option('eatisfamily_gallery', array());
    $homepage_extended = get_option('eatisfamily_homepage_extended', array());
    
    $homepage = $pages_content['homepage'] ?? array();
    ?>
    
    <h2>🔍 Tableau des sources de données</h2>
    <table>
        <tr>
            <th>Section</th>
            <th>Clé API</th>
            <th>Option WordPress</th>
            <th>Statut</th>
            <th>Données</th>
        </tr>
        <tr>
            <td>Hero Section</td>
            <td><code>hero_section</code></td>
            <td><code>eatisfamily_homepage_extended</code></td>
            <td><?php echo !empty($homepage_extended['hero_section']) ? '<span class="ok">✅ OK</span>' : '<span class="warn">⚠️ JSON fallback</span>'; ?></td>
            <td><?php echo !empty($homepage['hero_section']) ? 'Présent' : 'Absent'; ?></td>
        </tr>
        <tr>
            <td>Intro Section</td>
            <td><code>intro_section</code></td>
            <td><code>eatisfamily_homepage_extended</code></td>
            <td><?php echo !empty($homepage_extended['intro_section']) ? '<span class="ok">✅ OK</span>' : '<span class="warn">⚠️ JSON fallback</span>'; ?></td>
            <td><?php echo !empty($homepage['intro_section']) ? 'Présent' : 'Absent'; ?></td>
        </tr>
        <tr>
            <td>Services Section</td>
            <td><code>services_section</code></td>
            <td><code>eatisfamily_services</code></td>
            <td><?php echo !empty($services['services']) ? '<span class="ok">✅ OK ('.count($services['services']).' services)</span>' : '<span class="warn">⚠️ JSON fallback</span>'; ?></td>
            <td><?php echo !empty($homepage['services_section']) ? 'Présent' : 'Absent'; ?></td>
        </tr>
        <tr>
            <td>CTA Section</td>
            <td><code>cta_section</code></td>
            <td><code>eatisfamily_homepage_extended</code></td>
            <td><?php echo !empty($homepage_extended['cta_section']) ? '<span class="ok">✅ OK</span>' : '<span class="warn">⚠️ JSON fallback</span>'; ?></td>
            <td><?php echo !empty($homepage['cta_section']) ? 'Présent' : 'Absent'; ?></td>
        </tr>
        <tr>
            <td>Gallery Section</td>
            <td><code>gallery_section</code></td>
            <td><code>eatisfamily_gallery</code></td>
            <td><?php echo !empty($gallery['homepage']['images']) ? '<span class="ok">✅ OK ('.count($gallery['homepage']['images']).' images)</span>' : '<span class="warn">⚠️ JSON fallback</span>'; ?></td>
            <td><?php echo !empty($homepage['gallery_section']) ? 'Présent' : 'Absent'; ?></td>
        </tr>
        <tr style="background: #fff3cd;">
            <td><strong>Sustainable Service Title</strong></td>
            <td><code>sustainable_service_title</code></td>
            <td><code>eatisfamily_sustainability['title']</code></td>
            <td><?php echo !empty($sustainability['title']) ? '<span class="ok">✅ OK</span>' : '<span class="warn">⚠️ JSON fallback</span>'; ?></td>
            <td><?php echo !empty($homepage['sustainable_service_title']) ? 'Présent' : 'Absent'; ?></td>
        </tr>
        <tr style="background: #f8d7da;">
            <td><strong>🔴 Sustainable Service Items</strong></td>
            <td><code>sustainable_service</code></td>
            <td><code>eatisfamily_sustainability['items']</code></td>
            <td><?php 
                if (!empty($sustainability['items'])) {
                    echo '<span class="ok">✅ OK ('.count($sustainability['items']).' items)</span>';
                } else {
                    echo '<span class="error">❌ VIDE - Données absentes!</span>';
                }
            ?></td>
            <td><?php echo !empty($homepage['sustainable_service']) ? 'Présent ('.count($homepage['sustainable_service']).' items)' : '<span class="error">ABSENT</span>'; ?></td>
        </tr>
        <tr>
            <td>Beautiful Moments</td>
            <td><code>beautiful</code></td>
            <td><em>Pas de page admin</em></td>
            <td><span class="warn">⚠️ JSON uniquement</span></td>
            <td><?php echo !empty($homepage['beautiful']['title']) ? 'Présent' : 'Absent/Vide'; ?></td>
        </tr>
        <tr>
            <td>Examples</td>
            <td><code>examples</code></td>
            <td><em>Pas de page admin</em></td>
            <td><span class="warn">⚠️ JSON uniquement</span></td>
            <td><?php echo !empty($homepage['examples']) ? 'Présent ('.count($homepage['examples']).' items)' : 'Absent'; ?></td>
        </tr>
        <tr>
            <td>Partners</td>
            <td><code>partners_title</code>, <code>partners</code></td>
            <td><code>eatisfamily_partners</code></td>
            <td><?php echo !empty($partners['partners']) ? '<span class="ok">✅ OK ('.count($partners['partners']).' partenaires)</span>' : '<span class="warn">⚠️ JSON fallback</span>'; ?></td>
            <td><?php echo !empty($homepage['partners']) ? 'Présent' : 'Absent'; ?></td>
        </tr>
        <tr>
            <td>Homepage CTA</td>
            <td><code>homepageCTA</code></td>
            <td><em>Pas de page admin</em></td>
            <td><span class="warn">⚠️ JSON uniquement</span></td>
            <td><?php echo !empty($homepage['homepageCTA']['title']) ? 'Présent' : 'Absent/Vide'; ?></td>
        </tr>
    </table>
    
    <h2>📦 Détail de l'option <code>eatisfamily_sustainability</code></h2>
    <pre><?php print_r($sustainability); ?></pre>
    
    <h2>📦 Détail de <code>pages_content['homepage']['sustainable_service']</code></h2>
    <pre><?php print_r($homepage['sustainable_service'] ?? 'Non défini'); ?></pre>
    
    <h2>🔧 Actions</h2>
    <p>
        <a href="<?php echo home_url('/wp-content/themes/eatisfamily/import-sustainability.php'); ?>" class="btn btn-success">
            📥 Importer les données Sustainability
        </a>
        <a href="<?php echo admin_url('admin.php?page=eatisfamily-sustainability'); ?>" class="btn">
            ⚙️ Page Admin Sustainability
        </a>
        <a href="<?php echo home_url('/wp-json/eatisfamily/v1/pages-content'); ?>" class="btn" target="_blank">
            🔗 Voir l'API pages-content
        </a>
        <a href="<?php echo admin_url(); ?>" class="btn">
            ← Retour Dashboard
        </a>
    </p>
    
    <hr>
    <h2>📋 Instructions</h2>
    <ol>
        <li><strong>Pour corriger sustainable_service :</strong> Cliquez sur "Importer les données Sustainability" ou éditez depuis la page admin Sustainability</li>
        <li><strong>Sections sans page admin</strong> (beautiful, examples, homepageCTA) : Ces données viennent uniquement du fichier JSON local et ne sont pas encore éditables depuis WordPress</li>
    </ol>
</body>
</html>
