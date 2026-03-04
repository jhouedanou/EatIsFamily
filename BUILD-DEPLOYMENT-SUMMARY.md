# 🚀 Build Production - Prêt pour Phusion Passenger

**Date** : 4 mars 2026  
**Version** : Nuxt 4.2.2 + Nitro 2.12.9  
**Statut** : ✅ Build réussi

---

## 📦 Artefacts générés

### Structure `.output/`

```
.output/
├── server/              # Code serveur Node.js (Nitro/SSR)
│   ├── index.mjs        # Point d'entrée principal
│   ├── chunks/          # Code routeur et API
│   └── ...
├── public/              # Fichiers statiques
│   ├── index.html       # Page d'accueil
│   ├── favicon.*        # Favicons (RealFaviconGenerator)
│   ├── _nuxt/           # Assets client
│   └── api/             # Données statiques fallback
└── nitro.json           # Configuration du serveur
```

**Total fichiers** : 773  
**Taille estimée** : ~150-200 MB (avec `node_modules`)

---

## 🎯 Prérequis Phusion Passenger

### Sur le serveur

- ✅ **Node.js 18+** (vérifier avec `node --version`)
- ✅ **Phusion Passenger** intégré à Nginx ou Apache
- ✅ **npm** ou **yarn** pour les dépendances
- ✅ **Certificat SSL** (Let's Encrypt recommandé)

### Commande d'installation (Linux)

```bash
# Ubuntu/Debian
sudo apt-get install -y nodejs npm
sudo gem install passenger
sudo passenger-install-nginx-module

# Ou si Passenger est déjà installé
passenger-install-nginx-module --auto
```

---

## 📋 Options de déploiement

### **Option 1 : Via le script PowerShell (Windows)**

```powershell
cd C:\Users\socialmedia\Documents\EatIsFriday
.\scripts\deploy-passenger.ps1 -User "deploy" -Host "eatisfamily.fr" -Path "/var/www/eatisfamily" -Restart
```

**Avantages** :
- Automatise tout en 1 commande
- Gère SCP, SSH, permissions
- Redémarrage automatique

### **Option 2 : Via le script Bash (Linux/Mac)**

```bash
cd ~/path/to/EatIsFriday
chmod +x scripts/deploy-passenger.sh
./scripts/deploy-passenger.sh -u deploy -h eatisfamily.fr -p /var/www/eatisfamily -r
```

### **Option 3 : Déploiement manuel**

Voir [PASSENGER-DEPLOYMENT.md](./PASSENGER-DEPLOYMENT.md) pour les instructions détaillées.

---

## 🔧 Configuration requise

### 1. **Variables d'environnement** (`.env.production`)

```env
NODE_ENV=production
NUXT_PUBLIC_API_BASE=https://eatisfamily.fr/api/wp-json/eatisfamily/v1
NUXT_PUBLIC_USE_LOCAL_FALLBACK=false
PORT=3000
HOST=0.0.0.0
```

### 2. **Configuration Nginx** (recommandée)

```nginx
server {
    listen 443 ssl http2;
    server_name eatisfamily.fr www.eatisfamily.fr;
    
    passenger_enabled on;
    passenger_app_type node;
    passenger_startup_file .output/server/index.mjs;
    passenger_app_root /var/www/eatisfamily;
    
    root /var/www/eatisfamily/.output/public;
    
    # Cache des assets statiques
    location ~* ^/(images|fonts|_nuxt)/ {
        expires 365d;
        add_header Cache-Control "public, immutable";
    }
}
```

### 3. **Configuration Apache** (alternative)

```apache
<VirtualHost *:443>
    ServerName eatisfamily.fr
    
    PassengerAppRoot /var/www/eatisfamily
    PassengerAppType node
    PassengerStartupFile .output/server/index.mjs
    
    DocumentRoot /var/www/eatisfamily/.output/public
</VirtualHost>
```

---

## 📊 Taille du build

| Composant | Taille | Comprimé |
|-----------|--------|----------|
| `.output/server/` | ~5-8 MB | ~2-3 MB |
| `.output/public/` | ~10-15 MB | ~2-3 MB |
| `node_modules/` | ~200+ MB | N/A |
| **Total deployé** | ~220+ MB | N/A |

**Conseil** : Pour réduire le déploiement, ne transférez que `.output/` + dépendances requises via `npm ci --production`.

---

## ✅ Checklist de déploiement

- [ ] Vérifier Node.js sur le serveur (`node -v`)
- [ ] Vérifier Passenger (`passenger-status`)
- [ ] Exécuter le script de déploiement (`deploy-passenger.ps1` ou `.sh`)
- [ ] Vérifier les logs : `tail -f /var/log/nginx/eatisfamily-error.log`
- [ ] Tester l'application : `curl https://eatisfamily.fr`
- [ ] Vérifier les favicons : `curl -I https://eatisfamily.fr/favicon.ico`
- [ ] Tester Open Graph : Partager un lien article sur les réseaux
- [ ] Vérifier les perfs : `passenger-status`

---

## 🔍 Commandes de diagnostic

### Sur le serveur de déploiement

```bash
# Vérifier le statut Passenger
passenger-status

# Afficher les logs Nginx
tail -f /var/log/nginx/eatisfamily-error.log
tail -f /var/log/nginx/eatisfamily-access.log

# Vérifier les ports
netstat -tulpn | grep :3000

# Tester le serveur Node
curl http://localhost:3000/

# Redémarrer Passenger
touch /var/www/eatisfamily/tmp/restart.txt
# Ou
sudo systemctl reload nginx
```

---

## 🚨 Dépannage courant

### **Erreur : "Port 3000 déjà utilisé"**

```bash
sudo lsof -i :3000
sudo kill -9 <PID>
```

### **Erreur : "Cannot find module"**

```bash
cd /var/www/eatisfamily
npm ci --production
# Ou
yarn install --production
```

### **Erreur 502 Bad Gateway**

Vérifiez que Node.js est accessible :

```bash
which node
# Si vide, installer Node.js
```

### **Fichiers ne se mettent pas à jour**

Touchez le fichier restart.txt :

```bash
touch /var/www/eatisfamily/tmp/restart.txt
```

---

## 📝 Scripts disponibles

| Script | Usage | Description |
|--------|-------|-------------|
| `npm run build` | Local | Générer le build production |
| `npm run dev` | Local | Démarrer en développement |
| `deploy-passenger.ps1` | Windows | Déployer automatiquement |
| `deploy-passenger.sh` | Linux/Mac | Déployer automatiquement |

---

## 🌐 URLs importantes

| Ressource | URL |
|-----------|-----|
| **Production** | https://eafafamily.fr |
| **API WordPress** | https://eatisfamily.fr/api/wp-json/eatisfamily/v1 |
| **Logs Nginx** | `/var/log/nginx/` |
| **App folder** | `/var/www/eatisfamily/` |
| **Passenger status** | `passenger-status` |

---

## 📚 Documentation

- [PASSENGER-DEPLOYMENT.md](./PASSENGER-DEPLOYMENT.md) — Guide complet de déploiement
- [Passenger Docs](https://www.phusionpassenger.com/docs/) — Documentation officielle
- [Nuxt Deployment](https://nuxt.com/docs/getting-started/deployment) — Guide Nuxt

---

## ✨ Recap des modifications récentes

- ✅ **Favicon** : RealFaviconGenerator installé
- ✅ **Open Graph** : Configuration pour articles blog (`useSeoMeta`)
- ✅ **Build Production** : Nuxt 4 compilé avec Nitro SSR
- ✅ **Scripts de déploiement** : PowerShell + Bash automatisés
- ✅ **Documentation** : Guide complet Passenger

---

**Prêt pour le déploiement ! 🚀**
