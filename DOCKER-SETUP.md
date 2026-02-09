# ðŸ³ WordPress Local Setup avec Docker

Ce guide explique comment configurer un environnement WordPress local pour dÃ©velopper et tester le thÃ¨me EatIsFamily.

## ðŸ“‹ PrÃ©requis

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installÃ© et lancÃ©
- PowerShell (Windows) ou Terminal (Mac/Linux)

## ðŸš€ DÃ©marrage rapide

### Option 1 : Utiliser le script (RecommandÃ©)

```powershell
# DÃ©marrer WordPress
.\scripts\setup-wordpress-local.ps1 -Start

# ArrÃªter WordPress
.\scripts\setup-wordpress-local.ps1 -Stop

# Voir les logs
.\scripts\setup-wordpress-local.ps1 -Logs

# Voir le statut
.\scripts\setup-wordpress-local.ps1 -Status

# Reset complet (efface toutes les donnÃ©es)
.\scripts\setup-wordpress-local.ps1 -Reset
```

### Option 2 : Commandes Docker directes

```powershell
# DÃ©marrer
docker-compose up -d

# ArrÃªter
docker-compose down

# Voir les logs
docker-compose logs -f

# Reset complet
docker-compose down -v
```

## ðŸŒ URLs aprÃ¨s dÃ©marrage

| Service | URL | Description |
|---------|-----|-------------|
| **WordPress** | http://localhost:8080 | Site WordPress |
| **WP Admin** | http://localhost:8080/wp-admin | Administration |
| **phpMyAdmin** | http://localhost:8081 | Gestion base de donnÃ©es |
| **API** | http://localhost:8080/wp-json/eatisfamily/v1/ | Endpoints REST |

## ðŸ”§ Configuration initiale (premiÃ¨re fois)

1. **Ouvrez** http://localhost:8080
2. **ComplÃ©tez** l'installation WordPress :
   - Langue : FranÃ§ais
   - Titre du site : EatIsFamily Local
   - Identifiant : admin
   - Mot de passe : (choisissez un mot de passe)
   - Email : votre@email.com

3. **Activez le thÃ¨me** :
   - Allez dans `Apparence > ThÃ¨mes`
   - Activez "Eat Is Family"

4. **Configurez les permaliens** (IMPORTANT pour l'API) :
   - Allez dans `RÃ©glages > Permaliens`
   - SÃ©lectionnez "Nom de l'article"
   - Cliquez sur "Enregistrer les modifications"

5. **Testez l'API** :
   - Visitez http://localhost:8080/wp-json/eatisfamily/v1/
   - Vous devriez voir les routes disponibles

## ðŸ”„ Basculer entre Local et Production

### DÃ©veloppement local (WordPress Docker)

CrÃ©ez ou modifiez `.env.local` :

```env
NUXT_PUBLIC_API_BASE=http://localhost:8080/wp-json/eatisfamily/v1
NUXT_PUBLIC_USE_LOCAL_FALLBACK=true
```

### Production

CrÃ©ez ou modifiez `.env.production` :

```env
NUXT_PUBLIC_API_BASE=https://www.eatisfamily.fr/api/wp-json/eatisfamily/v1
NUXT_PUBLIC_USE_LOCAL_FALLBACK=false
```

### Lancer Nuxt avec l'environnement souhaitÃ©

```powershell
# Avec l'API locale
npm run dev

# Le fichier .env.local est automatiquement chargÃ© en dÃ©veloppement
```

## ðŸ“ Structure des fichiers

```
EatIsFriday/
â”œâ”€â”€ docker-compose.yml          # Configuration Docker
â”œâ”€â”€ .env.example                 # Template de configuration
â”œâ”€â”€ .env.local                   # Config locale (non commitÃ©e)
â”œâ”€â”€ .env.production              # Config production
â”œâ”€â”€ scripts/
â”‚   â””â”€â”€ setup-wordpress-local.ps1  # Script de gestion
â”œâ”€â”€ wordpress-theme/             # ThÃ¨me WordPress (montÃ© dans Docker)
â””â”€â”€ wordpress-uploads/           # Uploads WP (crÃ©Ã© automatiquement)
```

## ðŸ—„ï¸ AccÃ¨s Ã  la base de donnÃ©es

### Via phpMyAdmin

- URL : http://localhost:8081
- Serveur : db
- Utilisateur : wordpress
- Mot de passe : wordpress_password

### Via ligne de commande

```powershell
docker exec -it eatisfamily-db mysql -u wordpress -pwordpress_password eatisfamily_db
```

## ðŸ” DÃ©pannage

### L'API retourne une erreur 404

1. VÃ©rifiez que le thÃ¨me est activÃ©
2. Allez dans `RÃ©glages > Permaliens` et cliquez "Enregistrer" (mÃªme sans modification)

### Les conteneurs ne dÃ©marrent pas

```powershell
# VÃ©rifiez que Docker Desktop est lancÃ©
docker info

# VÃ©rifiez les logs
docker-compose logs
```

### RÃ©initialiser complÃ¨tement

```powershell
# ArrÃªter et supprimer les volumes
docker-compose down -v

# Supprimer le dossier uploads
Remove-Item -Recurse -Force wordpress-uploads

# RedÃ©marrer
docker-compose up -d
```

## ðŸ“¡ Endpoints API disponibles

| Endpoint | Description |
|----------|-------------|
| `/activities` | Liste des activitÃ©s |
| `/blog-posts` | Articles de blog |
| `/events` | Ã‰vÃ©nements |
| `/jobs` | Offres d'emploi |
| `/venues` | Lieux / Stades |
| `/site-content` | Contenu global du site |
| `/pages-content` | Contenu des pages |

## ðŸ” Identifiants par dÃ©faut

| Service | Utilisateur | Mot de passe |
|---------|-------------|--------------|
| WordPress Admin | (Ã  dÃ©finir) | (Ã  dÃ©finir) |
| MySQL | wordpress | wordpress_password |
| MySQL Root | root | root_password |

---

*Pour plus d'informations sur le thÃ¨me WordPress, consultez [wordpress-theme/README.md](wordpress-theme/README.md)*
