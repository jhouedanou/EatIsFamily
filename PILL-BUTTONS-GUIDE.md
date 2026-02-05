# Guide des Boutons Pilule (Pill Buttons)

## Description

Les boutons pilule sont des composants Vue stylisés avec des bordures irrégulières "sketchy" générées aléatoirement. Ils offrent un look organique et artisanal parfait pour un design moderne et décontracté.

## Composants disponibles

| Composant | Couleur de fond | Code couleur | Couleur de bordure | Utilisation recommandée |
|-----------|-----------------|--------------|-------------------|------------------------|
| `PillButtonWhite` | Blanc | `#FFFFFF` | Noir (#000) | Fonds clairs |
| `PillButtonYellow` | Jaune | `#FFE600` | Noir (#000) | CTA principaux, actions importantes |
| `PillButtonPink` | Rose/Rouge | `#F9375B` | Noir (#000) | Actions secondaires, alertes, promotions |
| `PillButtonTransparent` | Transparent | - | Blanc (#FFF) | Fonds sombres |

## Props

| Prop | Type | Obligatoire | Défaut | Description |
|------|------|-------------|--------|-------------|
| `to` | `string` | ✅ Oui | - | URL de destination (compatible NuxtLink) |
| `label` | `string` | ❌ Non | - | Texte du bouton (alternative au slot) |
| `disabled` | `boolean` | ❌ Non | `false` | Désactive le bouton |

## Exemples d'utilisation

### Utilisation basique avec slot

```vue
<template>
  <PillButtonWhite to="/contact">
    Contactez nous
  </PillButtonWhite>
</template>
```

### Bouton rose (Pink)

```vue
<template>
  <PillButtonPink to="/promo">
    Découvrir l'offre
  </PillButtonPink>
</template>
```

### Utilisation avec la prop label

```vue
<template>
  <PillButtonYellow to="/register" label="S'inscrire" />
</template>
```

### Bouton désactivé

```vue
<template>
  <PillButtonWhite to="/submit" disabled>
    Envoyer
  </PillButtonWhite>
</template>
```

### Sur un fond sombre

```vue
<template>
  <div class="dark-section">
    <PillButtonTransparent to="/about">
      En savoir plus
    </PillButtonTransparent>
  </div>
</template>

<style scoped>
.dark-section {
  background: #1a1a2e;
  padding: 3rem;
}
</style>
```

### Combinaison de plusieurs boutons

```vue
<template>
  <div class="button-group">
    <PillButtonYellow to="/primary-action">
      Action principale
    </PillButtonYellow>
    
    <PillButtonWhite to="/secondary-action">
      Action secondaire
    </PillButtonWhite>
  </div>
</template>

<style scoped>
.button-group {
  display: flex;
  gap: 1.5rem;
  flex-wrap: wrap;
}
</style>
```

## Liens externes

Pour les liens externes, utilisez le préfixe complet :

```vue
<template>
  <PillButtonYellow to="https://example.com">
    Visiter le site
  </PillButtonYellow>
</template>
```

## Personnalisation

### Styles par défaut

- **Font size**: 1.2rem
- **Padding**: 0.9rem 2rem
- **Font style**: italic, bold
- **Transition**: 0.2s ease

### Effets au survol

- Translation vers le haut de 3px
- Légère augmentation de luminosité

### Effet au clic

- Translation vers le bas de 1px

## Fonctionnement technique

Chaque bouton génère automatiquement un `clip-path` polygonal aléatoire au montage du composant. Ce polygone crée l'effet de bordure irrégulière "sketchy" caractéristique.

Le même `clip-path` est appliqué :
1. À l'élément de bordure (`.btn-outline`) positionné avec un décalage de -4px
2. Au bouton lui-même (`.btn`)

Cela crée l'illusion d'une bordure épaisse avec des bords irréguliers.

## Fichiers des composants

- `app/components/PillButtonWhite.vue` - Bouton blanc (#FFFFFF)
- `app/components/PillButtonYellow.vue` - Bouton jaune (#FFE600)
- `app/components/PillButtonPink.vue` - Bouton rose (#F9375B)
- `app/components/PillButtonTransparent.vue` - Bouton transparent

## Palette de couleurs

| Couleur | Code Hex | Composant | Aperçu |
|---------|----------|-----------|--------|
| Blanc | `#FFFFFF` | PillButtonWhite | ⬜ |
| Jaune | `#FFE600` | PillButtonYellow | 🟨 |
| Rose/Rouge | `#F9375B` | PillButtonPink | 🔴 |
| Transparent | - | PillButtonTransparent | ⬛ |

## Notes

- Les bordures sont générées aléatoirement à chaque montage du composant
- Pour un effet cohérent, vous pouvez wrapper plusieurs boutons dans le même composant parent
- Les composants utilisent `NuxtLink` pour la navigation, compatible avec le routeur Nuxt
