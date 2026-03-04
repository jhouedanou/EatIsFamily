# 📖 Guide d'utilisation des scripts de déploiement

## Vue d'ensemble

Deux scripts automatisés sont disponibles pour déployer sur Phusion Passenger :

| Script | Système | Usage |
|--------|--------|-------|
| `deploy-passenger.ps1` | PowerShell (Windows) | `.\deploy-passenger.ps1 [options]` |
| `deploy-passenger.sh` | Bash (Linux/Mac) | `./deploy-passenger.sh [options]` |

---

## 🪟 Script PowerShell (`deploy-passenger.ps1`)

### Usage basique

```powershell
cd C:\Users\socialmedia\Documents\EatIsFriday

# Déploiement complet avec redémarrage
.\scripts\deploy-passenger.ps1 -User "deploy" -Host "eafafamily.fr" -Path "/var/www/eafafamily" -Restart

# Déploiement sans redémarrage
.\scripts\deploy-passenger.ps1 -User "deploy" -Host "eafafamily.fr" -Path "/var/www/eafafamily"

# Déploiement sans rebuild
.\scripts\deploy-passenger.ps1 -User "deploy" -Host "eafafamily.fr" -Path "/var/www/eafafamily" -SkipBuild -Restart
```

### Paramètres

```powershell
-User <string>          # SSH username (obligatoire)
-Host <string>          # Hostname/IP (obligatoire)
-Path <string>          # Path distant (obligatoire)
-SkipBuild              # Passer la phase build
-SkipInstallDeps        # Passer l'installation npm
-Restart                # Redémarrer Passenger après déploiement
```

### Exemples avancés

```powershell
# Déploiement sans dépendances (si déjà installées)
.\scripts\deploy-passenger.ps1 -User "deploy" -Host "eafafamily.fr" -Path "/var/www/eafafamily" -SkipInstallDeps -Restart

# Redéploiement rapide (build existant)
.\scripts\deploy-passenger.ps1 -User "deploy" -Host "eafafamily.fr" -Path "/var/www/eafafamily" -SkipBuild -SkipInstallDeps -Restart
```

### Prérequis

- PowerShell 5.1+ (inclus dans Windows 10+)
- **SSH** configuré et accessible
- **SCP** disponible (Git Bash ou Windows OpenSSH)

### Dépannage PowerShell

```powershell
# Si les scripts sont bloqués
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser

# Vérifier les permissions
Get-ExecutionPolicy

# Tester la connexion SSH
ssh -n "deploy@eafafamily.fr" "echo OK"
```

---

## 🐧 Script Bash (`deploy-passenger.sh`)

### Usage basique

```bash
cd ~/path/to/EatIsFriday

# Rendre le script exécutable (une seule fois)
chmod +x scripts/deploy-passenger.sh

# Déploiement complet avec redémarrage
./scripts/deploy-passenger.sh -u deploy -h eafafamily.fr -p /var/www/eafafamily -r

# Déploiement sans redémarrage
./scripts/deploy-passenger.sh -u deploy -h eafafamily.fr -p /var/www/eafafamily

# Déploiement sans rebuild
./scripts/deploy-passenger.sh -u deploy -h eafafamily.fr -p /var/www/eafafamily --skip-build -r
```

### Paramètres

```bash
-u, --user USER       # SSH user (obligatoire)
-h, --host HOST       # Hostname/IP (obligatoire)
-p, --path PATH       # Path distant (obligatoire)
-s, --skip-build      # Passer la phase build
--skip-deps           # Passer l'installation npm
-r, --restart         # Redémarrer Passenger après déploiement
```

### Exemples avancés

```bash
# Format long (plus lisible)
./scripts/deploy-passenger.sh \
  --user deploy \
  --host eafafamily.fr \
  --path /var/www/eafafamily \
  --restart

# Redéploiement rapide
./scripts/deploy-passenger.sh -u deploy -h eafafamily.fr -p /var/www/eafafamily -s --skip-deps -r
```

### Prérequis

- Bash 4.0+
- **SSH** configuré et accessible
- **SCP** disponible

### Dépannage Bash

```bash
# Vérifier les droits d'exécution
ls -la scripts/deploy-passenger.sh

# Rendre exécutable
chmod +x scripts/deploy-passenger.sh

# Tester SSH
ssh -n "deploy@eafafamily.fr" "echo OK"

# Exécuter en debug mode
bash -x scripts/deploy-passenger.sh -u deploy -h eafafamily.fr -p /var/www/eafafamily
```

---

## 📊 Processus de déploiement détaillé

Les scripts exécutent automatiquement ces étapes :

### 1️⃣ Build local (optionnel)
```bash
npm run build
```
Génère `.output/` avec le serveur compilé.

### 2️⃣ Vérification SSH
```bash
ssh -n "user@host" "echo OK"
```
S'assure que la connexion est établie.

### 3️⃣ Création du dossier distant
```bash
mkdir -p /var/www/eafafamily
```

### 4️⃣ Upload `.output/`
```bash
scp -r ./.output user@host:/var/www/eafafamily/
```
Transfère le build compilé (5-15 MB).

### 5️⃣ Upload `package.json`
```bash
scp ./package.json user@host:/var/www/eafafamily/
```

### 6️⃣ Upload assets publics
```bash
scp -r ./public user@host:/var/www/eafafamily/
```

### 7️⃣ Installation des dépendances (optionnel)
```bash
npm ci --production
```
Installe uniquement les dépendances de prod.

### 8️⃣ Configuration `.env.production`
Crée automatiquement avec les bonnes variables.

### 9️⃣ Permissions
```bash
chmod -R 755 /var/www/eafafamily
chmod 644 .env.production
```

### 🔟 Redémarrage Passenger (optionnel)
```bash
touch /var/www/eafafamily/tmp/restart.txt
# Ou
sudo systemctl reload nginx
```

---

## 🕐 Durée estimée

| Étape | Durée |
|-------|-------|
| Build local | 2-5 min |
| Upload (15MB) | 10-30 sec |
| npm install | 2-5 min |
| Redémarrage | 5-10 sec |
| **Total** | **5-10 min** |

---

## 🔒 Authentification SSH

Les scripts présument que vous avez une **clé SSH** configurée.

### Si ce n'est pas le cas

```bash
# Générer une clé SSH
ssh-keygen -t ed25519 -C "deploy@eafafamily.fr"

# Copier sur le serveur
ssh-copy-id -i ~/.ssh/id_ed25519.pub deploy@eafafamily.fr

# Tester
ssh deploy@eafafamily.fr "echo OK"
```

### Authentification par mot de passe (moins sûr)

Si vous devez utiliser un mot de passe :

```powershell
# PowerShell - Installer posh-ssh
Install-Module -Name Posh-SSH -Force

# Ou utiliser Git Bash pour SSH interactif
```

---

## 📋 Checklist avant déploiement

- [ ] Build local réussi (`npm run build`)
- [ ] Fichier `.output/` généré
- [ ] Connexion SSH testée (`ssh user@host`)
- [ ] Dossier distant accessible
- [ ] Permissions SSH correctes
- [ ] `.env.production` configuré
- [ ] Certificat SSL sur le serveur
- [ ] Espace disque suffisant (~250MB)

---

## 🔄 Redéploiement rapide

Après le premier déploiement, redéployez plus vite :

```powershell
# PowerShell - Passer build et dépendances
.\scripts\deploy-passenger.ps1 -User "deploy" -Host "eafafamily.fr" -Path "/var/www/eafafamily" -SkipBuild -SkipInstallDeps -Restart
```

```bash
# Bash - Même chose
./scripts/deploy-passenger.sh -u deploy -h eafafamily.fr -p /var/www/eafafamily -s --skip-deps -r
```

**Durée** : ~30 secondes seulement !

---

## 📊 Vérifier le déploiement

Après le script :

```bash
# Sur le serveur distant
ssh deploy@eafafamily.fr

# Vérifier les fichiers
ls -la /var/www/eafafamily/.output/

# Vérifier Passenger
passenger-status

# Vérifier les logs
tail -f /var/log/nginx/eafafamily-error.log

# Tester l'application
curl https://eafafamily.fr
```

---

## 🐛 Erreurs courantes

### ❌ "SSH connection failed"
```powershell
# Vérifier SSH
ssh -n "user@host" "echo OK"

# Vérifier les droits utilisateur
ssh user@host "whoami"
```

### ❌ "SCP upload failed"
```bash
# Vérifier que le dossier distant existe
ssh user@host "ls -la /var/www/"

# Vérifier les droits d'écriture
ssh user@host "test -w /var/www/ && echo OK || echo NO"
```

### ❌ "npm install failed"
```bash
# Redéployer avec plus d'infos
ssh user@host "cd /var/www/eafafamily && npm install --production --verbose"
```

### ❌ "Passenger timeout"
```bash
# Augmenter le timeout dans nginx.conf
passenger_timeout 300;  # Default: 180 sec
```

---

## 💡 Tips & Tricks

### Déploiement sans attendre

```powershell
# Lancer en arrière-plan (PowerShell)
Start-Job { .\scripts\deploy-passenger.ps1 -User deploy -Host eafafamily.fr -Path /var/www/eafafamily -Restart }

# Vérifier le statut
Get-Job
Receive-Job -Id 1
```

### Aliaser le script

```bash
# Dans ~/.bashrc ou ~/.zshrc
alias deploy-eatisfamily='./scripts/deploy-passenger.sh -u deploy -h eafafamily.fr -p /var/www/eafafamily -r'

# Utilisation
deploy-eatisfamily
```

### Notifications

Modifier le script pour envoyer une notification après déploiement :

```powershell
# À la fin du script
Write-Host "Sending notification..." -ForegroundColor Yellow
# Ajouter votre service (Slack, Email, etc.)
```

---

## 📚 Documentation associée

- [PASSENGER-DEPLOYMENT.md](./PASSENGER-DEPLOYMENT.md) — Guide complet
- [nginx-passenger-eafafamily.conf](./config/nginx-passenger-eafafamily.conf) — Config Nginx prête
- [BUILD-DEPLOYMENT-SUMMARY.md](./BUILD-DEPLOYMENT-SUMMARY.md) — Résumé du build

---

**Les scripts font 99% du travail pour vous ! 🚀**
