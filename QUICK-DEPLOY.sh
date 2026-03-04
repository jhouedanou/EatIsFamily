#!/bin/bash
# QUICK START DEPLOYMENT - Phusion Passenger
# Usage: bash QUICK-DEPLOY.sh

set -e

echo ""
echo "🚀 Eat Is Family - Quick Deployment Setup"
echo "=========================================="

# Vérifier que nous sommes dans le bon répertoire
if [[ ! -d "./.output" ]]; then
    echo "❌ ERROR: .output/ folder not found!"
    echo "Run 'npm run build' first"
    exit 1
fi

# Demander les paramètres
echo ""
echo "📋 Configuration du déploiement:"
read -p "  SSH Username (ex: deploy): " user
read -p "  Server Hostname (ex: eafafamily.fr): " host
read -p "  Remote Path (ex: /var/www/eafafamily): " path

# Résumé
echo ""
echo "📊 Résumé:"
echo "  User:    $user"
echo "  Host:    $host"
echo "  Path:    $path"
echo "  Size:    76.67 MB"
echo "  Time:    ~5-10 minutes"

# Confirmation
echo ""
read -p "Proceeding? (y/n): " confirm
if [[ "$confirm" != "y" ]]; then
    echo "Cancelled."
    exit 0
fi

# Exécuter le déploiement
echo ""
echo "🚀 Starting deployment..."
chmod +x ./scripts/deploy-passenger.sh
./scripts/deploy-passenger.sh -u "$user" -h "$host" -p "$path" -r

echo ""
echo "✅ Deployment script finished!"
echo "📊 Next steps:"
echo "  1. Check logs: ssh $user@$host 'tail -f /var/log/nginx/eafafamily-error.log'"
echo "  2. Test app: curl https://$host"
echo "  3. Check status: ssh $user@$host 'passenger-status'"
echo ""
