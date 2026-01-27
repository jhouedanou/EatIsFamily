# JSON Data Import - Eat Is Family Theme

## Overview

Le thème WordPress "Eat Is Family" inclut une fonctionnalité d'importation automatique des données JSON lors de l'activation du thème.

## Fichiers JSON Supportés

| Fichier | Description | Post Type / Option |
|---------|-------------|-------------------|
| `activities.json` | Liste des activités | CPT `activity` |
| `events.json` | Liste des événements | CPT `event` |
| `jobs.json` | Offres d'emploi | CPT `job` |
| `venues.json` | Stades et restaurants | CPT `venue` |
| `blog-posts.json` | Articles de blog | Post `post` |
| `site-content.json` | Contenu global du site | Option `eatisfamily_site_content` |
| `pages-content.json` | Contenu des pages | Option `eatisfamily_pages_content` |

## Import Automatique

### À l'activation du thème

Lorsque le thème est activé pour la première fois :
1. Les Custom Post Types sont enregistrés
2. Les règles de réécriture sont générées
3. Tous les fichiers JSON sont importés automatiquement
4. Un flag `eatisfamily_data_imported` est créé pour éviter les imports multiples

### Comportement

- Les éléments existants ne sont **pas** écrasés (vérification par `original_id`)
- Les images distantes sont téléchargées et attachées comme featured images
- Les images locales (commençant par `/`) sont stockées en meta `featured_image_url`

## Import Manuel

### Via l'Admin WordPress

1. Aller dans **Apparence > Import JSON Data**
2. Cliquer sur **Import / Re-import JSON Data**

### Via WP-CLI (si disponible)

```bash
wp option delete eatisfamily_data_imported
wp eval "eatisfamily_import_all_json_data();"
```

## Réinitialisation des données

⚠️ **Attention** : Cette action supprime définitivement toutes les données importées !

1. Aller dans **Apparence > Import JSON Data**
2. Cliquer sur **Delete All Imported Data**

## Structure JSON Attendue

### activities.json
```json
[
  {
    "id": 1,
    "title": { "rendered": "Nom de l'activité" },
    "description": "Description courte",
    "content": { "rendered": "<p>Contenu HTML</p>" },
    "slug": "nom-activite",
    "date": "2025-01-15",
    "location": "Paris",
    "capacity": 50,
    "featured_media": "/images/activity.jpg",
    "status": "open"
  }
]
```

### events.json
```json
{
  "events": [
    {
      "id": 1,
      "title": { "rendered": "Nom de l'événement" },
      "description": "Description",
      "content": { "rendered": "" },
      "slug": "nom-event",
      "date": "2025-03-15",
      "time": "19:00",
      "venue": { "name": "Parc des Princes", "address": "Paris" },
      "price": "150€",
      "tickets_url": "https://...",
      "featured_media": "/images/event.jpg"
    }
  ]
}
```

### jobs.json
```json
[
  {
    "id": 1,
    "title": { "rendered": "Poste" },
    "department": "Service",
    "location": "Paris",
    "type": "CDI",
    "description": "Description du poste",
    "requirements": ["Req 1", "Req 2"],
    "featured_media": "/images/job.jpg",
    "slug": "poste-slug"
  }
]
```

### venues.json
```json
{
  "venues": [
    {
      "id": 1,
      "name": "Parc des Princes",
      "slug": "parc-des-princes",
      "address": { "street": "24 Rue du Commandant Guilbaud", "city": "Paris" },
      "coordinates": { "lat": 48.8414, "lng": 2.2530 },
      "image": "/images/venue.jpg",
      "logo": "/images/logo.svg"
    }
  ]
}
```

### blog-posts.json
```json
[
  {
    "id": 1,
    "title": { "rendered": "Titre de l'article" },
    "excerpt": { "rendered": "Résumé" },
    "content": { "rendered": "<p>Contenu complet</p>" },
    "date": "2025-01-10",
    "slug": "titre-article",
    "featured_media": "/images/post.jpg",
    "author": { "name": "Auteur", "avatar": "/images/avatar.jpg" },
    "reading_time": "5 min"
  }
]
```

## Hooks disponibles

```php
// Avant l'import
do_action('eatisfamily_before_import');

// Après l'import
do_action('eatisfamily_after_import');

// Filtrer les données avant import
add_filter('eatisfamily_import_activity_data', function($data) {
    // Modifier $data
    return $data;
});
```

## Troubleshooting

### L'import ne se lance pas
- Vérifier que les fichiers JSON sont dans `/wp-content/themes/eatisfamily/data/`
- Vérifier les permissions de lecture des fichiers

### Données dupliquées
- L'import vérifie le meta `original_id` pour éviter les doublons
- Supprimer les données via l'admin avant de réimporter

### Images non importées
- Les URLs externes doivent être accessibles
- Les images locales (commençant par `/`) sont stockées en meta uniquement

## Mise à jour des données

Pour mettre à jour les données :
1. Modifier les fichiers JSON dans `/data/`
2. Supprimer les éléments concernés dans WordPress
3. Réimporter via **Apparence > Import JSON Data**

---

*Généré automatiquement - Eat Is Family Theme*
