<?php
/**
 * Script d'importation des données de durabilité
 * Accéder via: http://localhost:8080/wp-content/themes/eatisfamily/import-sustainability.php
 */

// Charger WordPress
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

// Vérifier si l'utilisateur est admin
if (!current_user_can('manage_options')) {
    wp_die('Accès non autorisé. Veuillez vous connecter en tant qu\'administrateur.');
}

// Données de durabilité à importer
$sustainability_data = array(
    'title' => 'Notre engagement pour une restauration plus responsable',
    'items' => array(
        array(
            'background' => 'sus1.svg',
            'icone' => '/images/recycle-icon.png',
            'title' => 'Des solutions reutilisables',
            'description' => 'Nous utilisons des contenants réutilisables et consignés pour minimiser les déchets à usage unique.'
        ),
        array(
            'background' => 'sus2.svg',
            'icone' => '/images/leaf-icon.png',
            'title' => 'Un approvisionnement responsable',
            'description' => 'Nous privilégions les produits locaux et de saison pour réduire notre empreinte carbone.'
        ),
        array(
            'background' => 'sus3.svg',
            'icone' => '/images/box-icon.png',
            'title' => 'Des emballages eco-concus',
            'description' => 'Nos emballages sont conçus pour être recyclables ou compostables.'
        )
    )
);

// Action: importer ou vérifier
$action = isset($_GET['action']) ? $_GET['action'] : 'view';

if ($action === 'import') {
    // Sauvegarder l'option
    $result = update_option('eatisfamily_sustainability', $sustainability_data);
    
    if ($result) {
        echo '<div style="background: #d4edda; color: #155724; padding: 20px; margin: 20px; border-radius: 5px;">';
        echo '<h2>✅ Données importées avec succès!</h2>';
        echo '</div>';
    } else {
        // Vérifier si les données existaient déjà
        $existing = get_option('eatisfamily_sustainability');
        if ($existing === $sustainability_data) {
            echo '<div style="background: #fff3cd; color: #856404; padding: 20px; margin: 20px; border-radius: 5px;">';
            echo '<h2>⚠️ Les données étaient déjà à jour.</h2>';
            echo '</div>';
        } else {
            echo '<div style="background: #f8d7da; color: #721c24; padding: 20px; margin: 20px; border-radius: 5px;">';
            echo '<h2>❌ Erreur lors de l\'importation.</h2>';
            echo '</div>';
        }
    }
}

// Afficher les données actuelles
$current_data = get_option('eatisfamily_sustainability');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Import Sustainability Data</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow: auto; }
        .btn { display: inline-block; padding: 10px 20px; background: #0073aa; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px 10px 0; }
        .btn:hover { background: #005a87; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        h1 { color: #333; }
        h2 { color: #666; }
    </style>
</head>
<body>
    <h1>🌱 Import des données de durabilité</h1>
    
    <h2>Données actuelles dans WordPress:</h2>
    <pre><?php print_r($current_data ?: 'Aucune donnée'); ?></pre>
    
    <h2>Données à importer:</h2>
    <pre><?php print_r($sustainability_data); ?></pre>
    
    <p>
        <a href="?action=import" class="btn btn-success" onclick="return confirm('Voulez-vous importer ces données?');">
            📥 Importer les données
        </a>
        <a href="<?php echo admin_url('admin.php?page=eatisfamily-sustainability'); ?>" class="btn">
            ⚙️ Page Admin Sustainability
        </a>
        <a href="<?php echo home_url('/wp-json/eatisfamily/v1/pages-content'); ?>" class="btn" target="_blank">
            🔗 Voir l'API
        </a>
    </p>
    
    <hr>
    <p><small>
        <a href="<?php echo admin_url(); ?>">← Retour au tableau de bord WordPress</a>
    </small></p>
</body>
</html>
