# Guide PillButton

Composant unifie pour tous les boutons pilule du site Eat Is Family.

## Props

| Prop | Type | Defaut | Description |
|------|------|--------|-------------|
| `color` | `'pink' \| 'yellow' \| 'white' \| 'transparent' \| 'fuchsia' \| 'dark' \| 'light'` | `'pink'` | Couleur du bouton |
| `variant` | `'filled' \| 'outline'` | `'filled'` | Plein (clip-path) ou contour (SVG) |
| `width` | `string` | - | Largeur personnalisee (ex: `"300px"`) |
| `inset` | `string` | `'-4px'` | Epaisseur de la bordure noire (filled uniquement) |
| `to` | `string` | - | URL de destination (NuxtLink) |
| `label` | `string` | - | Texte du bouton (ou via slot) |
| `disabled` | `boolean` | `false` | Desactive le bouton |

## Couleurs disponibles (variant="filled")

| Couleur | Background | Texte |
|---------|-----------|-------|
| `pink` | `#f9375b` | blanc |
| `yellow` | `#FFE600` | noir |
| `white` | `#ffffff` | noir |
| `transparent` | transparent | blanc |
| `fuchsia` | `#ff2e84` | blanc |

## Variantes outline (variant="outline")

| Couleur | Stroke | Texte |
|---------|--------|-------|
| `light` (defaut) | `#fff` | blanc |
| `dark` | `#000` | noir |

## Exemples d'utilisation

### Bouton filled basique

```vue
<PillButton color="pink" to="/contact" label="Nous contacter" />
```

### Bouton avec slot

```vue
<PillButton color="yellow" to="/register">S'inscrire</PillButton>
```

### Bouton outline

```vue
<PillButton variant="outline" to="/about" label="En savoir plus" />
```

### Bouton outline sombre

```vue
<PillButton variant="outline" color="dark" to="/info" label="Plus d'infos" />
```

### Largeur personnalisee

```vue
<PillButton color="pink" width="350px" to="/test" label="Plus large" />
<PillButton color="yellow" width="180px" to="/test" label="Plus petit" />
```

### Epaisseur de bordure (inset)

Le prop `inset` controle l'epaisseur de la bordure noire autour du bouton filled.
Plus la valeur negative est grande, plus la bordure est epaisse.

```vue
<!-- Bordure fine -->
<PillButton color="pink" inset="-2px" to="#" label="Fine" />

<!-- Defaut -->
<PillButton color="pink" to="#" label="Normal" />

<!-- Bordure epaisse -->
<PillButton color="pink" inset="-6px" to="#" label="Epaisse" />
```

### Bouton desactive

```vue
<PillButton color="yellow" to="/test" label="Desactive" disabled />
```

### Alternance de couleurs dans une boucle

```vue
<PillButton
  :color="index % 2 === 0 ? 'pink' : 'yellow'"
  :to="item.link"
  label="En savoir plus"
/>
```

### Combinaison filled + outline

```vue
<PillButton color="pink" to="/careers" label="Nos offres d'emploi" />
<PillButton variant="outline" to="#map" label="Nos concessions" />
```

## Architecture

Le composant `PillButton.vue` remplace les 6 anciens composants :

- `PillButtonPink` → `<PillButton color="pink" />`
- `PillButtonYellow` → `<PillButton color="yellow" />`
- `PillButtonWhite` → `<PillButton color="white" />`
- `PillButtonTransparent` → `<PillButton color="transparent" />`
- `PillButtonOutline` → `<PillButton variant="outline" />`
- `PillButtonOutlineDark` → `<PillButton variant="outline" color="dark" />`

### Fonctionnement interne

- **Filled** : utilise un `clip-path` polygon genere aleatoirement au montage pour l'effet "sketchy". Le fond noir (`.btn-outline-bg`) deborde via `inset` pour creer la bordure.
- **Outline** : utilise un SVG `<path>` genere aleatoirement pour dessiner le contour organique.

### Fichier source

```
app/components/PillButton.vue
```
