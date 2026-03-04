# Déploiement sur Phusion Passenger

## 📋 Prérequis

- **Node.js 18+** installé sur le serveur
- **Phusion Passenger** configuré avec votre serveur web (Apache/Nginx)
- **Accès SSH** au serveur de déploiement
- **Yarn ou npm** sur le serveur

---

## 🚀 Instructions de déploiement

### 1. **Préparer l'application en local**

Le build a été généré dans le dossier `.output/` :

```bash
npm run build
```

Cela crée :
- `.output/server/` — Code côté serveur Node.js
- `.output/public/` — Fichiers statiques
- `.output/nitro.json` — Configuration du serveur

### 2. **Transférer les fichiers sur le serveur**

Utilisez **SCP** ou **SFTP** pour copier l'application :

```bash
# Option 1 : Copier tout le projet
scp -r . user@hostname:/var/www/eatisfamily/

# Option 2 : Copier uniquement le dossier .output et les dépendances
scp -r .output/ user@hostname:/var/www/eatisfamily/
scp -r node_modules/ user@hostname:/var/www/eatisfamily/
scp package.json package-lock.json user@hostname:/var/www/eatisfamily/
```

### 3. **Configurer Passenger**

#### Pour **Nginx** :

Créez/modifiez `/etc/nginx/sites-available/eatisfamily.conf` :

```nginx
server {
    listen 80;
    server_name eatisfamily.fr www.eatisfamily.fr;

    # Redirection HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name eatisfamily.fr www.eatisfamily.fr;

    # Certificats SSL
    ssl_certificate /etc/letsencrypt/live/eatisfamily.fr/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/eatisfamily.fr/privkey.pem;

    # Passenger config
    passenger_enabled on;
    passenger_app_type node;
    passenger_startup_file .output/server/index.mjs;
    passenger_app_root /var/www/eatisfamily;

    # Fichiers statiques
    root /var/www/eatisfamily/.output/public;

    # Fichiers publics statiques (pas de traitement Node)
    location ~* ^/(images|fonts|_nuxt)/.*\.(js|css|png|jpg|jpeg|gif|svg|woff|woff2|ttf|eot|ico)$ {
        expires 365d;
        add_header Cache-Control "public, immutable";
    }

    # Page d'erreur personnalisée
    error_page 404 /404.html;
    error_page 500 502 503 504 /500.html;
}
```

Activez le site :

```bash
sudo ln -s /etc/nginx/sites-available/eatisfamily.conf /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

#### Pour **Apache** :

Créez `/etc/apache2/sites-available/eatisfamily.conf` :

```apache
<VirtualHost *:80>
    ServerName eatisfamily.fr
    ServerAlias www.eatisfamily.fr
    Redirect permanent / https://eatisfamily.fr/
</VirtualHost>

<VirtualHost *:443>
    ServerName eatisfamily.fr
    ServerAlias www.eatisfamily.fr

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/eatisfamily.fr/cert.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/eatisfamily.fr/privkey.pem
    SSLCertificateChainFile /etc/letsencrypt/live/eatisfamily.fr/chain.pem

    PassengerAppRoot /var/www/eatisfamily
    PassengerAppType node
    PassengerStartupFile .output/server/index.mjs
    PassengerNodePath /usr/bin/node
    PassengerAppEnv production

    <Directory /var/www/eatisfamily>
        Allow from all
        Options -MultiViews
        Require all granted
    </Directory>

    DocumentRoot /var/www/eatisfamily/.output/public
    ErrorLog ${APACHE_LOG_DIR}/eatisfamily-error.log
    CustomLog ${APACHE_LOG_DIR}/eatisfamily-access.log combined
</VirtualHost>
```

Activez le site :

```bash
sudo a2ensite eatisfamily.conf
sudo a2enmod passenger
sudo apache2ctl configtest
sudo systemctl reload apache2
```

### 4. **Installer les dépendances sur le serveur**

```bash
cd /var/www/eatisfamily
npm ci --production  # Ou : yarn install --production
```

### 5. **Définir les variables d'environnement**

Créez `/var/www/eatisfamily/.env.production` :

```env
NODE_ENV=production
NUXT_PUBLIC_API_BASE=https://eatisfamily.fr/api/wp-json/eatisfamily/v1
NUXT_PUBLIC_USE_LOCAL_FALLBACK=false
PORT=3000
HOST=0.0.0.0
```

### 6. **Redémarrer Passenger**

```bash
# Nginx
sudo systemctl restart nginx

# Apache
sudo systemctl restart apache2

# Ou toucher le restart.txt (si configuré)
touch /var/www/eatisfamily/tmp/restart.txt
```

---

## 🔍 Vérification du déploiement

Vérifiez que l'application est accessible :

```bash
curl https://eatisfamily.fr
```

Vérifiez les logs Passenger :

```bash
# Nginx
sudo tail -f /var/log/nginx/eatisfamily-error.log

# Apache
sudo tail -f /var/log/apache2/eatisfamily-error.log
```

Vérifiez le statut Node/Passenger :

```bash
passenger-status
```

---

## 📦 Structure attendue après déploiement

```
/var/www/eatisfamily/
├── .output/
│   ├── server/               # Code serveur (Node.js)
│   │   └── index.mjs
│   ├── public/               # Fichiers statiques
│   │   ├── index.html
│   │   ├── favicon.ico
│   │   ├── api/
│   │   └── images/
│   └── nitro.json
├── node_modules/            # Dépendances
├── .env.production          # Variables d'env
├── package.json
└── package-lock.json
```

---

## 🔒 Sécurité

- Gardez `node_modules` **hors du web** (Nginx/Apache le gère automatiquement)
- Utilisez **HTTPS obligatoire** avec certificat Let's Encrypt
- Activez **gzip compression** sur les fichiers statiques
- Définissez les bonnes **permissions** :

```bash
sudo chown -R www-data:www-data /var/www/eatisfamily
sudo chmod 755 /var/www/eatisfamily
```

---

## 🚨 Dépannage

### Port déjà utilisé

```bash
sudo lsof -i :3000
sudo kill -9 <PID>
```

### Passenger ne démarre pas

Vérifiez les droits d'accès et les logs :

```bash
sudo systemctl status passenger
sudo journalctl -u passenger -n 50
```

### Erreur "EADDRINUSE"

Port utilisé par une autre application. Changez le PORT dans `.env.production`.

---

## 📝 Notes additionnelles

- **Auto-restart** : Passenger redémarre automatiquement l'app après modification
- **Logs** : Consultez `/var/log/nginx/` ou `/var/log/apache2/`
- **Cache** : Réduire les cache de Passenger si besoin : `PassengerMaxPoolSize 5`
- **Performances** : Augmenter pour production : `PassengerMaxPoolSize 20`

---

## ✅ Checklist de déploiement

- [ ] Build généré (`npm run build`)
- [ ] Fichiers transférés via SCP/SFTP
- [ ] Dépendances installées (`npm ci --production`)
- [ ] Variables d'environnement définies
- [ ] Configuration Passenger en place
- [ ] Certificat SSL activé (HTTPS)
- [ ] Logs Passenger vérifiés
- [ ] Application accessible et responsive

---

**Besoin d'aide ?** Consultez la [documentation officielle Passenger](https://www.phusionpassenger.com/docs/).
