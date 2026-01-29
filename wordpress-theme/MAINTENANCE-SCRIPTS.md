# 🔧 Scripts de Maintenance - EatIsFamily

Ce document explique comment utiliser les scripts de maintenance pour gérer les données WordPress.

## 📋 Scripts Disponibles

### 1. Import Events (`import-events.php`)

**URL**: `https://bigfive.dev/eatisfamily/wp-content/themes/eatisfamily/import-events.php`

**Fonction**: Importe les événements du fichier JSON local vers WordPress

**Utilisation**:
1. Connectez-vous à WordPress en tant qu'administrateur
2. Accédez à l'URL ci-dessus
3. Prévisualisez les événements qui seront importés
4. Cliquez sur "Import All Events to WordPress"
5. Les événements seront créés comme posts de type 'event'

**Ce que fait le script**:
- Lit le fichier `/public/api/events.json`
- Crée des posts de type 'event' dans WordPress
- Met à jour les posts existants s'ils existent déjà
- Ajoute les métadonnées : `event_type`, `image`, `event_order`

### 2. Fix Venue Images (`fix-venue-images.php`)

**URL**: `https://bigfive.dev/eatisfamily/wp-content/themes/eatisfamily/fix-venue-images.php`

**Fonction**: Corrige les valeurs d'images incorrectes (false) dans les venues

**Utilisation**:
1. Connectez-vous à WordPress en tant qu'administrateur
2. Accédez à l'URL ci-dessus
3. Le script affiche les venues avec des problèmes d'images
4. Cliquez sur "Fix All Image Issues"
5. Les valeurs `false` sont remplacées par des chaînes vides

**Ce que fait le script**:
- Trouve tous les venues avec `image` ou `image2` = `false`
- Remplace ces valeurs par des chaînes vides `''`
- Résout les erreurs 404 pour "/false" et "/1x"

## 🚀 Procédure Complète de Configuration

### Étape 1: Importer les Events
```
1. Aller sur: https://bigfive.dev/eatisfamily/wp-content/themes/eatisfamily/import-events.php
2. Cliquer sur "Import All Events to WordPress"
3. Vérifier que tous les événements sont importés
```

### Étape 2: Corriger les Images des Venues
```
1. Aller sur: https://bigfive.dev/eatisfamily/wp-content/themes/eatisfamily/fix-venue-images.php
2. Cliquer sur "Fix All Image Issues"
3. Vérifier qu'il n'y a plus d'erreurs
```

### Étape 3: Vérifier les APIs
```
1. Events API: https://bigfive.dev/eatisfamily/index.php/wp-json/eatisfamily/v1/events
   ✅ Devrait retourner un tableau d'événements (pas vide)

2. Venues API: https://bigfive.dev/eatisfamily/index.php/wp-json/eatisfamily/v1/venues
   ✅ Devrait retourner les venues avec images correctes
```

### Étape 4: Tester le Site
```
1. Page Events: https://votresite.com/events
   ✅ Devrait afficher tous les événements

2. ExploreSection (Homepage): https://votresite.com/
   ✅ Cliquer sur les markers de la carte
   ✅ Les détails des venues devraient s'afficher avec shops et menus
   ✅ Plus d'erreurs 404 dans la console
```

## ⚠️ Problèmes Résolus

### Problème 1: Page /events vide
- **Cause**: Pas de posts 'event' dans WordPress
- **Solution**: Utiliser `import-events.php`

### Problème 2: Venues sans détails
- **Cause**: Venues existent mais sans shops/menus
- **Solution**: Les données sont déjà dans l'API, pas de problème

### Problème 3: Erreurs 404 pour "false" et "1x"
- **Cause**: Champs image avec valeur booléenne `false`
- **Solution**: Utiliser `fix-venue-images.php`

## 📝 Notes Importantes

- Les scripts nécessitent les permissions d'administrateur WordPress
- Les scripts peuvent être exécutés plusieurs fois sans danger (ils mettent à jour plutôt que de dupliquer)
- Les fichiers JSON locaux dans `/public/api/` servent uniquement de référence maintenant
- Toutes les données proviennent maintenant de l'API WordPress

## 🔄 Maintenance Future

Si vous ajoutez de nouveaux événements:
1. Ajoutez-les dans le JSON local
2. Réexécutez `import-events.php`

Si vous modifiez des venues:
1. Modifiez-les directement dans WordPress Admin
2. Ou mettez à jour via l'API WordPress

## 🆘 Support

Si vous rencontrez des problèmes:
1. Vérifiez les logs de la console du navigateur
2. Vérifiez les logs PHP de WordPress
3. Testez les URLs API directement dans le navigateur
