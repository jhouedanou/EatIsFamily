# ðŸ”§ Scripts de Maintenance - EatIsFamily

Ce document explique comment utiliser les scripts de maintenance pour gÃ©rer les donnÃ©es WordPress.

## ðŸ“‹ Scripts Disponibles

### 1. Import Events (`import-events.php`)

**URL**: `https://www.eatisfamily.fr/api/wp-content/themes/eatisfamily/import-events.php`

**Fonction**: Importe les Ã©vÃ©nements du fichier JSON local vers WordPress

**Utilisation**:
1. Connectez-vous Ã  WordPress en tant qu'administrateur
2. AccÃ©dez Ã  l'URL ci-dessus
3. PrÃ©visualisez les Ã©vÃ©nements qui seront importÃ©s
4. Cliquez sur "Import All Events to WordPress"
5. Les Ã©vÃ©nements seront crÃ©Ã©s comme posts de type 'event'

**Ce que fait le script**:
- Lit le fichier `/public/api/events.json`
- CrÃ©e des posts de type 'event' dans WordPress
- Met Ã  jour les posts existants s'ils existent dÃ©jÃ 
- Ajoute les mÃ©tadonnÃ©es : `event_type`, `image`, `event_order`

### 2. Fix Venue Images (`fix-venue-images.php`)

**URL**: `https://www.eatisfamily.fr/api/wp-content/themes/eatisfamily/fix-venue-images.php`

**Fonction**: Corrige les valeurs d'images incorrectes (false) dans les venues

**Utilisation**:
1. Connectez-vous Ã  WordPress en tant qu'administrateur
2. AccÃ©dez Ã  l'URL ci-dessus
3. Le script affiche les venues avec des problÃ¨mes d'images
4. Cliquez sur "Fix All Image Issues"
5. Les valeurs `false` sont remplacÃ©es par des chaÃ®nes vides

**Ce que fait le script**:
- Trouve tous les venues avec `image` ou `image2` = `false`
- Remplace ces valeurs par des chaÃ®nes vides `''`
- RÃ©sout les erreurs 404 pour "/false" et "/1x"

## ðŸš€ ProcÃ©dure ComplÃ¨te de Configuration

### Ã‰tape 1: Importer les Events
```
1. Aller sur: https://www.eatisfamily.fr/api/wp-content/themes/eatisfamily/import-events.php
2. Cliquer sur "Import All Events to WordPress"
3. VÃ©rifier que tous les Ã©vÃ©nements sont importÃ©s
```

### Ã‰tape 2: Corriger les Images des Venues
```
1. Aller sur: https://www.eatisfamily.fr/api/wp-content/themes/eatisfamily/fix-venue-images.php
2. Cliquer sur "Fix All Image Issues"
3. VÃ©rifier qu'il n'y a plus d'erreurs
```

### Ã‰tape 3: VÃ©rifier les APIs
```
1. Events API: https://www.eatisfamily.fr/api/index.php/wp-json/eatisfamily/v1/events
   âœ… Devrait retourner un tableau d'Ã©vÃ©nements (pas vide)

2. Venues API: https://www.eatisfamily.fr/api/index.php/wp-json/eatisfamily/v1/venues
   âœ… Devrait retourner les venues avec images correctes
```

### Ã‰tape 4: Tester le Site
```
1. Page Events: https://votresite.com/events
   âœ… Devrait afficher tous les Ã©vÃ©nements

2. ExploreSection (Homepage): https://votresite.com/
   âœ… Cliquer sur les markers de la carte
   âœ… Les dÃ©tails des venues devraient s'afficher avec shops et menus
   âœ… Plus d'erreurs 404 dans la console
```

## âš ï¸ ProblÃ¨mes RÃ©solus

### ProblÃ¨me 1: Page /events vide
- **Cause**: Pas de posts 'event' dans WordPress
- **Solution**: Utiliser `import-events.php`

### ProblÃ¨me 2: Venues sans dÃ©tails
- **Cause**: Venues existent mais sans shops/menus
- **Solution**: Les donnÃ©es sont dÃ©jÃ  dans l'API, pas de problÃ¨me

### ProblÃ¨me 3: Erreurs 404 pour "false" et "1x"
- **Cause**: Champs image avec valeur boolÃ©enne `false`
- **Solution**: Utiliser `fix-venue-images.php`

## ðŸ“ Notes Importantes

- Les scripts nÃ©cessitent les permissions d'administrateur WordPress
- Les scripts peuvent Ãªtre exÃ©cutÃ©s plusieurs fois sans danger (ils mettent Ã  jour plutÃ´t que de dupliquer)
- Les fichiers JSON locaux dans `/public/api/` servent uniquement de rÃ©fÃ©rence maintenant
- Toutes les donnÃ©es proviennent maintenant de l'API WordPress

## ðŸ”„ Maintenance Future

Si vous ajoutez de nouveaux Ã©vÃ©nements:
1. Ajoutez-les dans le JSON local
2. RÃ©exÃ©cutez `import-events.php`

Si vous modifiez des venues:
1. Modifiez-les directement dans WordPress Admin
2. Ou mettez Ã  jour via l'API WordPress

## ðŸ†˜ Support

Si vous rencontrez des problÃ¨mes:
1. VÃ©rifiez les logs de la console du navigateur
2. VÃ©rifiez les logs PHP de WordPress
3. Testez les URLs API directement dans le navigateur
