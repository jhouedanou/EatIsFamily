# Intégration Contact Form 7 - Formulaire de Candidature

Ce guide explique comment configurer l'intégration entre le formulaire de candidature (Job Application) du site Nuxt et Contact Form 7 dans WordPress.

## Prérequis

1. **Plugin Contact Form 7** installé et activé sur WordPress
2. **Plugin Contact Form 7 REST API** (optionnel mais recommandé)

## Étape 1 : Créer le formulaire dans Contact Form 7

1. Allez dans **Contact > Ajouter** dans WordPress
2. Créez un nouveau formulaire avec les champs suivants :

```
<label>Nom complet *
[text* your-name placeholder "Votre nom complet"]</label>

<label>Email *
[email* your-email placeholder "Votre adresse email"]</label>

<label>Téléphone *
[tel* your-phone placeholder "+33 6 00 00 00 00"]</label>

<label>Profil LinkedIn
[url your-linkedin placeholder "https://linkedin.com/in/votreprofil"]</label>

<label>CV (PDF, DOC, DOCX - max 5Mo) *
[file* your-resume limit:5mb filetypes:pdf|doc|docx]</label>

<label>Lettre de motivation
[textarea your-message placeholder "Dites-nous pourquoi vous seriez parfait pour ce poste..."]</label>

[hidden job-title default:get]
[hidden job-location default:get]
[hidden job-slug default:get]

[submit "Envoyer ma candidature"]
```

**⚠️ Important** : Les champs `[hidden]` avec `default:get` permettent de recevoir les valeurs envoyées par le frontend.

### Correspondance des champs

| Champ Nuxt | Champ CF7 |
|------------|-----------|
| `name` | `your-name` |
| `email` | `your-email` |
| `phone` | `your-phone` |
| `linkedin` | `your-linkedin` |
| `resume` (fichier) | `your-resume` |
| `coverLetter` | `your-message` |
| `jobTitle` | `job-title` |
| `jobLocation` | `job-location` |
| `jobSlug` | `job-slug` |

## Étape 2 : Configurer l'email

Dans l'onglet "Mail" de votre formulaire CF7 :

```
De: [your-name] <[your-email]>
Objet: Nouvelle candidature - [job-title]

Nouvelle candidature reçue pour le poste : [job-title]

Informations du candidat :
━━━━━━━━━━━━━━━━━━━━━━━━━━━
Nom complet : [your-name]
Email : [your-email]
Téléphone : [your-phone]
LinkedIn : [your-linkedin]

Poste recherché :
━━━━━━━━━━━━━━━━━━━━━━━━━━━
Titre : [job-title]
Lieu : [job-location]
Référence : [job-slug]

Lettre de motivation :
━━━━━━━━━━━━━━━━━━━━━━━━━━━
[your-message]

━━━━━━━━━━━━━━━━━━━━━━━━━━━
CV en pièce jointe : [your-resume]
```

### Configuration des pièces jointes

Dans la section "File Attachments" de l'onglet Mail :
```
[your-resume]
```

## Étape 3 : Récupérer l'ID du formulaire

1. Allez dans **Contact > Formulaires de contact**
2. Trouvez votre formulaire de candidature
3. Notez le **shortcode** : `[contact-form-7 id="456" title="Candidature"]`
4. L'ID est le numéro (ex: `456`)

## Étape 4 : Configurer dans EatIsFamily

1. Allez dans **EatIsFamily > Forms & Labels**
2. Cliquez sur l'onglet **💼 Job Application**
3. Dans la section "Intégration Contact Form 7 - Candidatures"
4. Entrez l'ID du formulaire
5. Cliquez sur **Enregistrer**

## API Endpoint

Le formulaire est soumis via l'API REST de Contact Form 7 :

```
POST /wp-json/contact-form-7/v1/contact-forms/{form_id}/feedback
```

### Format de la requête

```javascript
const formData = new FormData()
formData.append('your-name', 'Jean Dupont')
formData.append('your-email', 'jean.dupont@example.com')
formData.append('your-phone', '+33 6 12 34 56 78')
formData.append('your-linkedin', 'https://linkedin.com/in/jeandupont')
formData.append('your-message', 'Je suis très intéressé par ce poste...')
formData.append('job-title', 'Chef de cuisine')
formData.append('job-location', 'Paris')
formData.append('job-slug', 'chef-de-cuisine-paris')
formData.append('your-resume', fileObject) // Fichier CV

fetch('/wp-json/contact-form-7/v1/contact-forms/456/feedback', {
  method: 'POST',
  body: formData
})
```

### Réponse

```json
{
  "status": "mail_sent",
  "message": "Merci pour votre candidature. Elle a bien été envoyée.",
  "posted_data_hash": "abc123..."
}
```

### Statuts possibles

- `mail_sent` : Email envoyé avec succès
- `mail_failed` : Échec de l'envoi de l'email
- `validation_failed` : Erreurs de validation (champs manquants, fichier invalide, etc.)
- `spam` : Message détecté comme spam
- `aborted` : Requête abandonnée

## Configuration CORS

La configuration CORS est déjà incluse dans le thème WordPress (voir `CONTACT-FORM-7-INTEGRATION.md`).

## Validation côté client

Le composable `useJobApplicationForm.ts` valide :

- **Nom** : Requis
- **Email** : Requis, format valide
- **Téléphone** : Requis, format français (+33 ou 0X XX XX XX XX)
- **CV** : Requis, formats acceptés (PDF, DOC, DOCX), taille max 5 Mo
- **Consentement** : Requis

## Fichiers modifiés

- `app/composables/useJobApplicationForm.ts` : Composable pour l'intégration CF7
- `app/components/JobApplyModal.vue` : Modal de candidature avec soumission CF7 (en français)
- `wordpress-theme/inc/admin-pages-v5.php` : Champ d'ID CF7 dans l'admin
- `wordpress-theme/functions.php` : Configuration CF7 dans les settings API

## Dépannage

### Le formulaire ne s'envoie pas

1. Vérifiez que Contact Form 7 est activé
2. Vérifiez que l'ID du formulaire est correct
3. Vérifiez les logs d'erreur WordPress
4. Testez l'endpoint directement avec Postman

### Le fichier CV n'est pas reçu

1. Vérifiez la taille max de upload dans WordPress (php.ini) :
   - `upload_max_filesize = 5M`
   - `post_max_size = 8M`
2. Vérifiez que le champ `[file* your-resume]` est présent dans le formulaire CF7
3. Vérifiez que `[your-resume]` est dans "File Attachments" de l'onglet Mail
4. Vérifiez les permissions du dossier `wp-content/uploads/wpcf7_uploads/`
5. Vérifiez dans la console du navigateur que le fichier est bien envoyé (logs `[JobApplicationForm]`)

### Les champs cachés (job-title, etc.) sont vides

1. Utilisez `[hidden job-title default:get]` au lieu de `[hidden job-title]`
2. Vérifiez dans la console du navigateur que les valeurs sont envoyées
3. Testez avec Postman en envoyant les champs manuellement

### Erreurs de validation

Les erreurs de validation CF7 sont affichées dans le modal. Vérifiez :
- Que tous les champs requis sont remplis
- Que le format du fichier est accepté
- Que la taille du fichier ne dépasse pas 5 Mo

### Emails non reçus

1. Vérifiez la configuration SMTP de WordPress
2. Installez un plugin comme "WP Mail SMTP"
3. Vérifiez le dossier spam
