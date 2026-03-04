#!/bin/bash
# Script de déploiement Nuxt 4 sur Phusion Passenger (Linux/Mac -> Linux/Unix)
# Usage: ./deploy-passenger.sh -u user -h hostname -p /var/www/eatisfamily

set -e

USER=""
HOST=""
PATH=""
SKIP_BUILD=false
SKIP_DEPS=false
RESTART=false

usage() {
    echo "Usage: ./deploy-passenger.sh -u USER -h HOST -p PATH [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  -u, --user USER       SSH user (required)"
    echo "  -h, --host HOST       Hostname/IP (required)"
    echo "  -p, --path PATH       Remote application path (required)"
    echo "  -s, --skip-build      Skip npm build"
    echo "  --skip-deps           Skip npm install"
    echo "  -r, --restart         Restart Passenger after deploy"
    echo ""
    echo "Example:"
    echo "  ./deploy-passenger.sh -u deploy -h eatisfamily.fr -p /var/www/eatisfamily -r"
    exit 1
}

while [[ $# -gt 0 ]]; do
    case $1 in
        -u|--user) USER="$2"; shift 2 ;;
        -h|--host) HOST="$2"; shift 2 ;;
        -p|--path) PATH="$2"; shift 2 ;;
        -s|--skip-build) SKIP_BUILD=true; shift ;;
        --skip-deps) SKIP_DEPS=true; shift ;;
        -r|--restart) RESTART=true; shift ;;
        *) echo "Unknown option: $1"; usage ;;
    esac
done

if [[ -z "$USER" ]] || [[ -z "$HOST" ]] || [[ -z "$PATH" ]]; then
    echo "❌ Error: Missing required arguments"
    usage
fi

echo ""
echo "🚀 Nuxt 4 -> Phusion Passenger Deployment Script"
echo "=================================================="
echo "User: $USER"
echo "Host: $HOST"
echo "Path: $PATH"
echo ""

# 1. Build local si non skippé
if [[ "$SKIP_BUILD" != true ]]; then
    echo "📦 Building application..."
    npm run build
    if [[ $? -ne 0 ]]; then
        echo "❌ Build failed!"
        exit 1
    fi
    echo "✅ Build completed!"
fi

# 2. Vérifier la connexion SSH
echo ""
echo "🔗 Testing SSH connection..."
if ! ssh -o ConnectTimeout=5 -n "${USER}@${HOST}" "echo OK" &>/dev/null; then
    echo "❌ SSH connection failed!"
    exit 1
fi
echo "✅ SSH connection successful!"

# 3. Créer le dossier distant si nécessaire
echo ""
echo "📁 Ensuring remote directory exists..."
ssh -n "${USER}@${HOST}" "mkdir -p '${PATH}' && echo Created"
echo "✅ Remote directory ready!"

# 4. Copier .output (build)
echo ""
echo "📤 Uploading .output directory..."
scp -r "./.output" "${USER}@${HOST}:${PATH}/"
if [[ $? -ne 0 ]]; then
    echo "❌ SCP upload failed!"
    exit 1
fi
echo "✅ .output directory uploaded!"

# 5. Copier package.json et lock file
echo ""
echo "📤 Uploading package files..."
scp "./package.json" "${USER}@${HOST}:${PATH}/"
scp "./package-lock.json" "${USER}@${HOST}:${PATH}/" 2>/dev/null || true
scp "./yarn.lock" "${USER}@${HOST}:${PATH}/" 2>/dev/null || true
echo "✅ Package files uploaded!"

# 6. Copier public assets
echo ""
echo "📤 Uploading public assets..."
scp -r "./public" "${USER}@${HOST}:${PATH}/"
echo "✅ Public assets uploaded!"

# 7. Installer les dépendances si non skippé
if [[ "$SKIP_DEPS" != true ]]; then
    echo ""
    echo "⚙️  Installing dependencies on remote server..."
    ssh -n "${USER}@${HOST}" "cd '${PATH}' && npm ci --production" || {
        echo "⚠️  npm ci --production failed, trying npm install..."
        ssh -n "${USER}@${HOST}" "cd '${PATH}' && npm install --production"
    }
    echo "✅ Dependencies installed!"
fi

# 8. Créer/mettre à jour .env.production
echo ""
echo "🔐 Setting up environment file..."
cat > /tmp/.env.production << 'EOF'
NODE_ENV=production
NUXT_PUBLIC_API_BASE=https://eatisfamily.fr/api/wp-json/eatisfamily/v1
NUXT_PUBLIC_USE_LOCAL_FALLBACK=false
PORT=3000
HOST=0.0.0.0
EOF
scp /tmp/.env.production "${USER}@${HOST}:${PATH}/.env.production"
rm /tmp/.env.production
echo "✅ Environment file configured!"

# 9. Définir les permissions
echo ""
echo "🔒 Setting file permissions..."
ssh -n "${USER}@${HOST}" "cd '${PATH}' && chmod -R 755 . && chmod 644 .env.production"
echo "✅ Permissions set!"

# 10. Redémarrer Passenger
if [[ "$RESTART" == true ]]; then
    echo ""
    echo "🔄 Restarting Passenger..."
    ssh -n "${USER}@${HOST}" "touch '${PATH}/tmp/restart.txt' 2>/dev/null || sudo systemctl reload nginx || sudo systemctl reload apache2" || true
    sleep 2
    echo "✅ Passenger restarted!"
fi

echo ""
echo "✅ Deployment completed successfully!"
echo "=================================================="
echo "🌐 Application URL: https://eatisfamily.fr"
echo "📊 Check logs on server:"
echo "   tail -f /var/log/nginx/eatisfamily-error.log"
echo "   tail -f /var/log/apache2/eatisfamily-error.log"
echo ""
