<?php
/**
 * Script d'importation des boutons (CTA) depuis buttons.json
 * Importe les données dans l'option WordPress 'eatisfamily_buttons'
 * 
 * SÉCURITÉ : Réservé aux administrateurs connectés.
 * Ce fichier devrait être SUPPRIMÉ en production.
 * 
 * @package EatIsFamily
 */

// Charger WordPress
if (!defined('ABSPATH')) {
    // Try multiple paths to find wp-load.php
    $wp_load_paths = array(
        $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php',
        dirname(__FILE__) . '/../../../wp-load.php',
        dirname(__FILE__) . '/../../wp-load.php',
    );
    
    $loaded = false;
    foreach ($wp_load_paths as $path) {
        if (file_exists($path)) {
            require_once($path);
            $loaded = true;
            break;
        }
    }
    
    if (!$loaded) {
        die('Impossible de charger WordPress. Vérifiez le chemin vers wp-load.php.');
    }
}

// SECURITY: Vérifier si l'utilisateur est admin connecté
if (!is_user_logged_in() || !current_user_can('manage_options')) {
    status_header(403);
    wp_die('Accès non autorisé. Veuillez vous connecter en tant qu\'administrateur.', 'Accès refusé', array('response' => 403));
}

// SECURITY: Vérifier nonce si action d'import
if (isset($_GET['action']) && $_GET['action'] === 'import') {
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'eatisfamily_import_buttons')) {
        wp_die('Vérification de sécurité échouée.', 'Erreur de sécurité', array('response' => 403));
    }
}

/**
 * Données des boutons par défaut (identiques à public/data/buttons.json)
 */
function eatisfamily_get_default_buttons_data() {
    // Essayer de charger depuis le fichier JSON d'abord
    $json_paths = array(
        ABSPATH . 'public/data/buttons.json',
        get_template_directory() . '/data/buttons.json',
        dirname(__FILE__) . '/../public/data/buttons.json',
    );
    
    foreach ($json_paths as $json_file) {
        if (file_exists($json_file)) {
            $json_content = file_get_contents($json_file);
            $data = json_decode($json_content, true);
            if (json_last_error() === JSON_ERROR_NONE && !empty($data)) {
                return $data;
            }
        }
    }
    
    // Fallback: données hardcodées
    return array(
        'header_cta' => array(
            'label' => 'Contactez nous',
            'color' => 'pink',
            'variant' => 'filled',
            'to' => '/contact',
        ),
        'header_cta_mobile' => array(
            'label' => 'Contact',
            'color' => 'pink',
            'variant' => 'filled',
            'to' => '/contact',
        ),
        'hero_primary' => array(
            'label' => 'Nos offres d\'emploi',
            'color' => 'pink',
            'variant' => 'filled',
            'to' => '/careers',
        ),
        'hero_secondary' => array(
            'label' => 'Nos concessions',
            'color' => 'transparent',
            'variant' => 'outline',
            'to' => '#mapPreview',
        ),
        'intro_cta' => array(
            'label' => 'En savoir plus sur nous',
            'color' => 'dark',
            'variant' => 'outline',
            'to' => '/about',
            'width' => '300px',
        ),
        'services_cta_even' => array(
            'label' => 'Nous contacter',
            'color' => 'pink',
            'variant' => 'filled',
            'to' => '/contact',
            'width' => '260px',
        ),
        'services_cta_odd' => array(
            'label' => 'Parlons food & beverage',
            'color' => 'yellow',
            'variant' => 'filled',
            'to' => '/contact',
            'width' => '260px',
        ),
        'examples_cta_even' => array(
            'label' => 'Voir nos concessions',
            'color' => 'dark',
            'variant' => 'outline',
            'width' => '250px',
        ),
        'examples_cta_odd' => array(
            'label' => 'Voir nos évènements',
            'color' => 'fuchsia',
            'variant' => 'filled',
            'width' => '250px',
        ),
        'homepage_cta' => array(
            'label' => 'Découvrir nos offres et postuler',
            'color' => 'yellow',
            'variant' => 'filled',
            'width' => '320px',
            'inset' => '-1px',
        ),
        'about_hero_cta' => array(
            'label' => 'Contactez nous',
            'color' => 'pink',
            'variant' => 'filled',
            'to' => '/contact',
            'width' => '220px',
            'inset' => '-3px',
        ),
        'about_mission_cta' => array(
            'label' => 'Contactez nous',
            'color' => 'pink',
            'variant' => 'filled',
            'to' => '/contact',
            'width' => '220px',
        ),
        'about_vision_cta' => array(
            'label' => 'Contactez nous',
            'color' => 'pink',
            'variant' => 'filled',
            'to' => '/contact',
            'width' => '220px',
        ),
        'careers_job_apply' => array(
            'label' => 'Postuler',
            'color' => 'pink',
            'variant' => 'filled',
            'width' => '210px',
            'inset' => '-2px',
        ),
        'careers_job_details' => array(
            'label' => 'Afficher les détails',
            'color' => 'dark',
            'variant' => 'outline',
            'width' => '250px',
        ),
        'careers_bottom_cta' => array(
            'label' => 'Trouver et postuler à des emplois',
            'color' => 'yellow',
            'variant' => 'filled',
            'to' => '/apply-activities#mapPreview',
            'width' => '330px',
            'inset' => '-2px',
        ),
        'apply_cta_apply' => array(
            'label' => 'Postuler',
            'color' => 'pink',
            'variant' => 'filled',
            'to' => '/careers',
            'width' => '250px',
        ),
        'apply_cta_explore' => array(
            'label' => 'Explorer nos sites',
            'color' => 'dark',
            'variant' => 'outline',
            'to' => '#mapPreview',
            'width' => '250px',
        ),
        'apply_cta_contact' => array(
            'label' => 'Contactez nous',
            'color' => 'dark',
            'variant' => 'outline',
            'to' => '#mapPreview',
            'width' => '250px',
        ),
        'explore_join' => array(
            'label' => 'Rejoignez-nous',
            'color' => 'fuchsia',
            'variant' => 'filled',
            'width' => '250px',
        ),
        'events_hero_cta' => array(
            'label' => 'Contactez nous',
            'color' => 'pink',
            'variant' => 'filled',
            'to' => '/contact',
            'width' => '200px',
        ),
        'ctablock_cta' => array(
            'label' => 'Contactez nous',
            'color' => 'pink',
            'variant' => 'filled',
            'to' => '/contact',
            'width' => '220px',
            'inset' => '-3px',
        ),
        'job_apply' => array(
            'label' => 'Postuler',
            'color' => 'pink',
            'variant' => 'filled',
            'width' => '200px',
        ),
        'job_share' => array(
            'label' => 'Partager cette offre d\'emploi',
            'color' => 'pink',
            'variant' => 'filled',
            'width' => '300px',
        ),
        'blog_read_article' => array(
            'label' => 'Lire l\'article',
            'color' => 'dark',
            'variant' => 'outline',
            'width' => '250px',
        ),
        'eventcard_cta' => array(
            'label' => 'Nous contacter',
            'color' => 'dark',
            'variant' => 'outline',
            'to' => '/contact',
        ),
    );
}

// Action: importer ou afficher
$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'view';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Import Boutons - EatIsFamily</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; max-width: 1000px; margin: 40px auto; padding: 0 20px; background: #f1f1f1; }
        .card { background: white; border-radius: 8px; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        h1 { color: #1d2327; }
        h2 { color: #2271b1; margin-top: 0; }
        .success { background: #d4edda; color: #155724; padding: 15px 20px; border-radius: 5px; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; color: #721c24; padding: 15px 20px; border-radius: 5px; border-left: 4px solid #dc3545; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px 20px; border-radius: 5px; border-left: 4px solid #17a2b8; }
        .warning { background: #fff3cd; color: #856404; padding: 15px 20px; border-radius: 5px; border-left: 4px solid #ffc107; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; }
        th { background: #f8fafc; font-weight: 600; color: #475569; }
        tr:hover { background: #f8fafc; }
        .btn { display: inline-block; padding: 10px 24px; border-radius: 5px; text-decoration: none; font-weight: 600; cursor: pointer; border: none; font-size: 14px; }
        .btn-primary { background: #2271b1; color: white; }
        .btn-primary:hover { background: #135e96; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; }
        .badge-pink { background: #f9375b; color: white; }
        .badge-yellow { background: #FFE600; color: #333; }
        .badge-dark { background: #333; color: white; }
        .badge-fuchsia { background: #ff2e84; color: white; }
        .badge-transparent { background: #eee; color: #333; }
        .badge-white { background: #fff; color: #333; border: 1px solid #ddd; }
        .badge-filled { background: #e2e8f0; color: #475569; }
        .badge-outline { background: transparent; color: #475569; border: 1px solid #94a3b8; }
        .count { font-size: 13px; color: #64748b; }
    </style>
</head>
<body>
    <h1>🎯 Import des Boutons (CTA) - EatIsFamily</h1>

<?php
if ($action === 'import') {
    $buttons_data = eatisfamily_get_default_buttons_data();
    
    if (empty($buttons_data)) {
        echo '<div class="error"><strong>❌ Erreur :</strong> Impossible de charger les données des boutons.</div>';
    } else {
        $result = update_option('eatisfamily_buttons', $buttons_data);
        $count = count($buttons_data);
        
        if ($result) {
            echo '<div class="success">';
            echo "<strong>✅ Import réussi !</strong> {$count} boutons importés dans WordPress.";
            echo '</div>';
        } else {
            // Check if data already exists and is identical
            $existing = get_option('eatisfamily_buttons', array());
            if (!empty($existing) && $existing === $buttons_data) {
                echo '<div class="info">';
                echo "<strong>ℹ️ Données identiques :</strong> Les {$count} boutons sont déjà à jour dans WordPress.";
                echo '</div>';
            } else {
                echo '<div class="error">';
                echo '<strong>❌ Erreur :</strong> L\'import a échoué. Vérifiez les permissions WordPress.';
                echo '</div>';
            }
        }
    }
}

// Afficher l'état actuel
$existing_buttons = get_option('eatisfamily_buttons', array());
$default_buttons = eatisfamily_get_default_buttons_data();
?>

    <div class="card">
        <h2>📊 État actuel</h2>
        <?php if (!empty($existing_buttons)): ?>
            <div class="success">
                <strong>✅ Boutons configurés :</strong> <?php echo count($existing_buttons); ?> boutons dans WordPress
            </div>
        <?php else: ?>
            <div class="warning">
                <strong>⚠️ Aucun bouton configuré</strong> dans WordPress. Cliquez sur "Importer" pour les charger.
            </div>
        <?php endif; ?>
        
        <p class="count">Boutons disponibles dans buttons.json : <?php echo count($default_buttons); ?></p>
    </div>

    <div class="card">
        <h2>📋 Aperçu des boutons à importer</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Label</th>
                    <th>Couleur</th>
                    <th>Variante</th>
                    <th>Lien</th>
                    <th>Largeur</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($default_buttons as $id => $btn): ?>
                <tr>
                    <td><code><?php echo esc_html($id); ?></code></td>
                    <td><strong><?php echo esc_html($btn['label']); ?></strong></td>
                    <td><span class="badge badge-<?php echo esc_attr($btn['color']); ?>"><?php echo esc_html($btn['color']); ?></span></td>
                    <td><span class="badge badge-<?php echo esc_attr(isset($btn['variant']) ? $btn['variant'] : 'filled'); ?>"><?php echo esc_html(isset($btn['variant']) ? $btn['variant'] : 'filled'); ?></span></td>
                    <td><?php echo esc_html(isset($btn['to']) ? $btn['to'] : '—'); ?></td>
                    <td><?php echo esc_html(isset($btn['width']) ? $btn['width'] : '—'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>🚀 Actions</h2>
        <p>
            <a href="<?php echo esc_url(wp_nonce_url('?action=import', 'eatisfamily_import_buttons')); ?>" class="btn btn-primary" onclick="return confirm('Importer les <?php echo count($default_buttons); ?> boutons dans WordPress ?');">
                📥 Importer les boutons
            </a>
        </p>
        <p style="color: #64748b; font-size: 13px;">
            ⚠️ L'import écrasera les boutons existants dans WordPress avec les données du fichier buttons.json.<br>
            🔒 Ce script est accessible uniquement aux administrateurs connectés.
        </p>
    </div>

    <?php if (!empty($existing_buttons)): ?>
    <div class="card">
        <h2>🔍 Boutons actuellement en base WordPress</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Label</th>
                    <th>Couleur</th>
                    <th>Lien</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($existing_buttons as $id => $btn): ?>
                <tr>
                    <td><code><?php echo esc_html($id); ?></code></td>
                    <td><strong><?php echo esc_html(isset($btn['label']) ? $btn['label'] : '(vide)'); ?></strong></td>
                    <td><span class="badge badge-<?php echo esc_attr(isset($btn['color']) ? $btn['color'] : 'dark'); ?>"><?php echo esc_html(isset($btn['color']) ? $btn['color'] : '—'); ?></span></td>
                    <td><?php echo esc_html(isset($btn['to']) ? $btn['to'] : '—'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <div class="card">
        <h2>📖 Utilisation</h2>
        <div class="info">
            <p><strong>Endpoint REST API :</strong> <code>GET /wp-json/eatisfamily/v1/buttons</code></p>
            <p><strong>Option WordPress :</strong> <code>eatisfamily_buttons</code></p>
            <p><strong>Admin WordPress :</strong> EatIsFamily → Boutons (CTA)</p>
            <p style="margin-bottom: 0;"><strong>Composable Nuxt :</strong> <code>useButtons().getButton('hero_primary')</code></p>
        </div>
    </div>

</body>
</html>
