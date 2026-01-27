# 🐳 WordPress Local Setup avec Docker

Ce guide explique comment configurer un environnement WordPress local pour développer et tester le thème EatIsFamily.

## 📋 Prérequis

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installé et lancé
- PowerShell (Windows) ou Terminal (Mac/Linux)

## 🚀 Démarrage rapide

### Option 1 : Utiliser le script (Recommandé)

```powershell
# Démarrer WordPress
.\scripts\setup-wordpress-local.ps1 -Start

# Arrêter WordPress
.\scripts\setup-wordpress-local.ps1 -Stop

# Voir les logs
.\scripts\setup-wordpress-local.ps1 -Logs

# Voir le statut
.\scripts\setup-wordpress-local.ps1 -Status

# Reset complet (efface toutes les données)
.\scripts\setup-wordpress-local.ps1 -Reset
```

### Option 2 : Commandes Docker directes

```powershell
# Démarrer
docker-compose up -d

# Arrêter
docker-compose down

# Voir les logs
docker-compose logs -f

# Reset complet
docker-compose down -v
```

## 🌐 URLs après démarrage

| Service | URL | Description |
|---------|-----|-------------|
| **WordPress** | http://localhost:8080 | Site WordPress |
| **WP Admin** | http://localhost:8080/wp-admin | Administration |
| **phpMyAdmin** | http://localhost:8081 | Gestion base de données |
| **API** | http://localhost:8080/wp-json/eatisfamily/v1/ | Endpoints REST |

## 🔧 Configuration initiale (première fois)

1. **Ouvrez** http://localhost:8080
2. **Complétez** l'installation WordPress :
   - Langue : Français
   - Titre du site : EatIsFamily Local
   - Identifiant : admin
   - Mot de passe : (choisissez un mot de passe)
   - Email : votre@email.com

3. **Activez le thème** :
   - Allez dans `Apparence > Thèmes`
   - Activez "Eat Is Family"

4. **Configurez les permaliens** (IMPORTANT pour l'API) :
   - Allez dans `Réglages > Permaliens`
   - Sélectionnez "Nom de l'article"
   - Cliquez sur "Enregistrer les modifications"

5. **Testez l'API** :
   - Visitez http://localhost:8080/wp-json/eatisfamily/v1/
   - Vous devriez voir les routes disponibles

## 🔄 Basculer entre Local et Production

### Développement local (WordPress Docker)

Créez ou modifiez `.env.local` :

```env
NUXT_PUBLIC_API_BASE=http://localhost:8080/wp-json/eatisfamily/v1
NUXT_PUBLIC_USE_LOCAL_FALLBACK=true
```

### Production

Créez ou modifiez `.env.production` :

```env
NUXT_PUBLIC_API_BASE=https://bigfive.dev/eatisfamily/wp-json/eatisfamily/v1
NUXT_PUBLIC_USE_LOCAL_FALLBACK=false
```

### Lancer Nuxt avec l'environnement souhaité

```powershell
# Avec l'API locale
npm run dev

# Le fichier .env.local est automatiquement chargé en développement
```

## 📁 Structure des fichiers

```
EatIsFriday/
├── docker-compose.yml          # Configuration Docker
├── .env.example                 # Template de configuration
├── .env.local                   # Config locale (non commitée)
├── .env.production              # Config production
├── scripts/
│   └── setup-wordpress-local.ps1  # Script de gestion
├── wordpress-theme/             # Thème WordPress (monté dans Docker)
└── wordpress-uploads/           # Uploads WP (créé automatiquement)
```

## 🗄️ Accès à la base de données

### Via phpMyAdmin

- URL : http://localhost:8081
- Serveur : db
- Utilisateur : wordpress
- Mot de passe : wordpress_password

### Via ligne de commande

```powershell
docker exec -it eatisfamily-db mysql -u wordpress -pwordpress_password eatisfamily_db
```

## 🔍 Dépannage

### L'API retourne une erreur 404

1. Vérifiez que le thème est activé
2. Allez dans `Réglages > Permaliens` et cliquez "Enregistrer" (même sans modification)

### Les conteneurs ne démarrent pas

```powershell
# Vérifiez que Docker Desktop est lancé
docker info

# Vérifiez les logs
docker-compose logs
```

### Réinitialiser complètement

```powershell
# Arrêter et supprimer les volumes
docker-compose down -v

# Supprimer le dossier uploads
Remove-Item -Recurse -Force wordpress-uploads

# Redémarrer
docker-compose up -d
```

## 📡 Endpoints API disponibles

| Endpoint | Description |
|----------|-------------|
| `/activities` | Liste des activités |
| `/blog-posts` | Articles de blog |
| `/events` | Événements |
| `/jobs` | Offres d'emploi |
| `/venues` | Lieux / Stades |
| `/site-content` | Contenu global du site |
| `/pages-content` | Contenu des pages |

## 🔐 Identifiants par défaut

| Service | Utilisateur | Mot de passe |
|---------|-------------|--------------|
| WordPress Admin | (à définir) | (à définir) |
| MySQL | wordpress | wordpress_password |
| MySQL Root | root | root_password |

---

*Pour plus d'informations sur le thème WordPress, consultez [wordpress-theme/README.md](wordpress-theme/README.md)*
