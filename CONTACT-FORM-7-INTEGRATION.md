# Intégration Contact Form 7 - EatIsFamily

Ce guide explique comment configurer l'intégration entre le formulaire de contact du site Nuxt et Contact Form 7 dans WordPress.

## Prérequis

1. **Plugin Contact Form 7** installé et activé sur WordPress
2. **Plugin Contact Form 7 REST API** (optionnel mais recommandé pour une meilleure intégration)

## Étape 1 : Créer le formulaire dans Contact Form 7

1. Allez dans **Contact > Ajouter** dans WordPress
2. Créez un nouveau formulaire avec les champs suivants :

```
[text* your-name placeholder "Votre nom"]

[email* your-email placeholder "Votre email"]

[text event-type placeholder "Type d'événement"]

[text location placeholder "Lieu"]

[date event-date placeholder "Date de l'événement"]

[text guests placeholder "Nombre d'invités"]

[textarea* your-message placeholder "Votre message"]

[submit "Envoyer"]
```

### Correspondance des champs

| Champ Nuxt | Champ CF7 |
|------------|-----------|
| `name` | `your-name` |
| `email` | `your-email` |
| `eventType` | `event-type` |
| `location` | `location` |
| `date` | `event-date` |
| `guests` | `guests` |
| `message` | `your-message` |

## Étape 2 : Configurer l'email

Dans l'onglet "Mail" de votre formulaire CF7 :

```
De: [your-name] <[your-email]>
Objet: Nouvelle demande de contact - [event-type]

Nom: [your-name]
Email: [your-email]
Type d'événement: [event-type]
Lieu: [location]
Date: [event-date]
Nombre d'invités: [guests]

Message:
[your-message]
```

## Étape 3 : Récupérer l'ID du formulaire

1. Allez dans **Contact > Formulaires de contact**
2. Trouvez votre formulaire
3. Notez le **shortcode** : `[contact-form-7 id="123" title="Contact"]`
4. L'ID est le numéro (ex: `123`)

## Étape 4 : Configurer dans EatIsFamily

1. Allez dans **EatIsFamily > Forms & Labels**
2. Cliquez sur l'onglet **📧 Contact**
3. Dans la section "Intégration Contact Form 7"
4. Entrez l'ID du formulaire
5. Cliquez sur **Enregistrer**

## Configuration CORS (Important!)

Pour permettre au frontend Nuxt d'envoyer des requêtes à l'API CF7, ajoutez ce code dans votre `functions.php` :

```php
// Ajouter les headers CORS pour Contact Form 7 REST API
add_filter('wpcf7_feedback_response', function($response, $result) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    return $response;
}, 10, 2);

// Gérer les requêtes OPTIONS (preflight)
add_action('init', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        exit(0);
    }
});
```

## API Endpoint

Le formulaire est soumis via l'API REST de Contact Form 7 :

```
POST /wp-json/contact-form-7/v1/contact-forms/{form_id}/feedback
```

### Format de la requête

```javascript
const formData = new FormData()
formData.append('your-name', 'John Doe')
formData.append('your-email', 'john@example.com')
formData.append('event-type', 'Wedding')
formData.append('location', 'Paris')
formData.append('event-date', '2026-06-15')
formData.append('guests', '150')
formData.append('your-message', 'Hello, I would like to...')

fetch('/wp-json/contact-form-7/v1/contact-forms/123/feedback', {
  method: 'POST',
  body: formData
})
```

### Réponse

```json
{
  "status": "mail_sent",
  "message": "Merci pour votre message. Il a bien été envoyé.",
  "posted_data_hash": "abc123..."
}
```

### Statuts possibles

- `mail_sent` : Email envoyé avec succès
- `mail_failed` : Échec de l'envoi de l'email
- `validation_failed` : Erreurs de validation (champs manquants, etc.)
- `spam` : Message détecté comme spam
- `aborted` : Requête abandonnée

## Dépannage

### Le formulaire ne s'envoie pas

1. Vérifiez que Contact Form 7 est activé
2. Vérifiez que l'ID du formulaire est correct
3. Vérifiez les logs d'erreur WordPress
4. Testez l'endpoint directement avec Postman

### Erreur CORS

1. Ajoutez le code CORS ci-dessus dans functions.php
2. Ou installez un plugin comme "CORS" pour WordPress
3. Vérifiez que votre hébergeur ne bloque pas les requêtes cross-origin

### Emails non reçus

1. Vérifiez la configuration SMTP de WordPress
2. Installez un plugin comme "WP Mail SMTP"
3. Vérifiez le dossier spam

## Fichiers modifiés

- `app/composables/useContactForm.ts` : Composable pour l'intégration CF7
- `app/pages/contact.vue` : Page de contact avec soumission CF7
- `wordpress-theme/inc/admin-pages-v5.php` : Champ d'ID CF7 dans l'admin
- `wordpress-theme/functions.php` : Configuration CF7 dans les settings API
