# 📚 INDEX - Documentation Déploiement Phusion Passenger

## 🎯 Par où commencer ?

### ⚡ Déploiement rapide (5 min)
1. Exécuter **`QUICK-DEPLOY.ps1`** (Windows) ou **`QUICK-DEPLOY.sh`** (Linux/Mac)
2. Remplir les 3 paramètres (user, host, path)
3. Laisser le script faire le travail

**Fichier** : [QUICK-DEPLOY.ps1](./QUICK-DEPLOY.ps1) | [QUICK-DEPLOY.sh](./QUICK-DEPLOY.sh)

---

## 📖 Documentation détaillée

### 📋 Étape 1 : Comprendre le build
**Fichier** : [BUILD-DEPLOYMENT-SUMMARY.md](./BUILD-DEPLOYMENT-SUMMARY.md)

Contient :
- Explication de la structure `.output/`
- Prérequis serveur
- Options de déploiement
- Checklist de déploiement

### 📋 Étape 2 : Utiliser les scripts
**Fichier** : [DEPLOYMENT-SCRIPTS-GUIDE.md](./DEPLOYMENT-SCRIPTS-GUIDE.md)

Contient :
- Guide d'utilisation PowerShell (`deploy-passenger.ps1`)
- Guide d'utilisation Bash (`deploy-passenger.sh`)
- Exemples avancés
- Dépannage

### 📋 Étape 3 : Configuration serveur
**Fichier** : [PASSENGER-DEPLOYMENT.md](./PASSENGER-DEPLOYMENT.md)

Contient :
- Installation prérequis
- Configuration Nginx et Apache
- Activations de sites
- Dépannage avancé

### 📋 Étape 4 : Configuration Nginx
**Fichier** : [config/nginx-passenger-eafafamily.conf](./config/nginx-passenger-eafafamily.conf)

Contient :
- Configuration Nginx complète et prête
- SSL/HTTPS
- Headers de sécurité
- Compression Gzip
- Cache des assets

---

## 🔧 Scripts et outils

### Scripts de déploiement

| Script | Système | Usage |
|--------|---------|-------|
| [`QUICK-DEPLOY.ps1`](./QUICK-DEPLOY.ps1) | Windows | Déploiement assisté rapide |
| [`QUICK-DEPLOY.sh`](./QUICK-DEPLOY.sh) | Linux/Mac | Déploiement assisté rapide |
| [`scripts/deploy-passenger.ps1`](./scripts/deploy-passenger.ps1) | Windows | Déploiement avec plus d'options |
| [`scripts/deploy-passenger.sh`](./scripts/deploy-passenger.sh) | Linux/Mac | Déploiement avec plus d'options |

### Fichiers de configuration

| Fichier | Contenu |
|---------|---------|
| [`config/nginx-passenger-eafafamily.conf`](./config/nginx-passenger-eafafamily.conf) | Configuration Nginx complète |

---

## 📊 État du projet

### ✅ Complété
- [x] Build Nuxt 4 production
- [x] Favicons RealFaviconGenerator
- [x] Open Graph pour articles blog
- [x] Scripts de déploiement automatisés
- [x] Documentation complète

### Build generé
- `.output/server/` — Code serveur Node.js
- `.output/public/` — Fichiers statiques
- Taille totale : **76.67 MB**
- Fichiers : **773**

---

## 🚀 Processus de déploiement simplifié

```
1. Lancer le script quickdeploy
   ↓
2. Entrer user, host, path
   ↓
3. Script exécute automatiquement:
   - Build local (npm run build)
   - Upload .output/ via SCP
   - Upload public/ et package.json
   - npm install sur serveur
   - Créer .env.production
   - Redémarrer Passenger
   ↓
4. Application accessible en HTTPS
```

---

## 📋 Paramètres d'exemple

Pour un déploiement typique :

```
User:        deploy
Host:        eafafamily.fr
Path:        /var/www/eafafamily
```

Cela créera :
```
/var/www/eafafamily/
├── .output/
├── public/
├── node_modules/
├── package.json
└── .env.production
```

---

## 🔍 Vérification après déploiement

```bash
# 1. Tester l'accès
curl https://eafafamily.fr

# 2. Vérifier les favicons
curl -I https://eafafamily.fr/favicon.ico

# 3. Vérifier Passenger
ssh deploy@eafafamily.fr 'passenger-status'

# 4. Vérifier les logs
ssh deploy@eafafamily.fr 'tail -f /var/log/nginx/eafafamily-error.log'
```

---

## 🆘 Aide rapide

### Erreur lors du déploiement ?

1. Vérifier la connexion SSH : `ssh user@host "echo OK"`
2. Vérifier l'espace disque : `ssh user@host "df -h"`
3. Consulter [PASSENGER-DEPLOYMENT.md](./PASSENGER-DEPLOYMENT.md) section "Dépannage"

### Erreur après déploiement ?

1. Vérifier les logs : `tail -f /var/log/nginx/eafafamily-error.log`
2. Redémarrer Passenger : `touch /var/www/eafafamily/tmp/restart.txt`
3. Consulter [PASSENGER-DEPLOYMENT.md](./PASSENGER-DEPLOYMENT.md) section "Dépannage"

### Plus d'aide ?

Consulter la [documentation officielle Passenger](https://www.phusionpassenger.com/docs/).

---

## 📚 Arborescence du projet

```
EatIsFriday/
│
├── 📁 .output/                          ← Build production (76.67 MB)
│   ├── server/                          ← Code serveur Node.js
│   │   └── index.mjs                    ← Point d'entrée
│   └── public/                          ← Fichiers statiques
│
├── 📁 scripts/
│   ├── deploy-passenger.ps1             ← Script PowerShell
│   └── deploy-passenger.sh              ← Script Bash
│
├── 📁 config/
│   └── nginx-passenger-eafafamily.conf ← Config Nginx prête
│
├── 📄 QUICK-DEPLOY.ps1                 ← Déploiement rapide (Windows)
├── 📄 QUICK-DEPLOY.sh                  ← Déploiement rapide (Linux/Mac)
│
├── 📄 DEPLOYMENT-REPORT.md             ← Rapport final build
├── 📄 BUILD-DEPLOYMENT-SUMMARY.md      ← Résumé technique
├── 📄 DEPLOYMENT-SCRIPTS-GUIDE.md      ← Guide scripts
├── 📄 PASSENGER-DEPLOYMENT.md          ← Guide complet
│
└── 📄 INDEX.md                         ← Ce fichier
```

---

## ✨ Modifications récentes

### Favicons
- ✅ favicon.svg, favicon-96x96.png, favicon.ico
- ✅ apple-touch-icon.png
- ✅ web-app-manifest-192x192.png, 512x512.png
- ✅ Balises HTML dans nuxt.config.ts
- ✅ Manifest PWA mis à jour

### Open Graph
- ✅ useSeoMeta() dans `app/pages/blog/[slug].vue`
- ✅ og:title (titre article)
- ✅ og:description (résumé article)
- ✅ og:image (image article)
- ✅ twitter:card support

### Déploiement
- ✅ Scripts PowerShell et Bash
- ✅ Configuration Nginx complète
- ✅ Documentation exhaustive
- ✅ Quick start scripts

---

## 🎯 Prochaines étapes

1. **Immédiat**
   - Exécuter `QUICK-DEPLOY.ps1` ou `QUICK-DEPLOY.sh`
   - Tester l'application

2. **Après déploiement**
   - Vérifier favicons
   - Tester Open Graph (partage article)
   - Vérifier les logs

3. **Optimisations (optionnel)**
   - Ajouter CDN
   - Configurer monitoring
   - Activer rate limiting

---

## 📞 Support

### Documentation
- 📖 [Passenger Docs](https://www.phusionpassenger.com/docs/)
- 📖 [Nuxt Docs](https://nuxt.com/docs/)
- 📖 [Nginx Docs](https://nginx.org/en/docs/)

### Fichiers
- Pour des questions : Consulter les fichiers de doc correspondants
- Pour des erreurs : Vérifier sections "Dépannage"

---

## 🎉 Résumé

**Status** : ✅ Prêt au déploiement

```
Build:      76.67 MB    ✅
Favicons:   Installés   ✅
Open Graph: Configuré   ✅
Scripts:    Créés       ✅
Docs:       Complètes   ✅
```

**Déploiement rapide** : 2 minutes avec les scripts  
**Déploiement manuel** : ~15 minutes avec documentation

---

**Bon déploiement ! 🚀**

Pour commencer : Exécuter [`QUICK-DEPLOY.ps1`](./QUICK-DEPLOY.ps1) ou [`QUICK-DEPLOY.sh`](./QUICK-DEPLOY.sh)
