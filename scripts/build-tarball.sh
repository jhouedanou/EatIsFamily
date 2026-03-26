#!/bin/bash
# =============================================================
# BUILD TARBALL - Crée une archive tar.gz prête à déployer
# Usage: bash scripts/build-tarball.sh [--skip-build] [--name custom-name]
# =============================================================

set -euo pipefail

SKIP_BUILD=false
OUTPUT_NAME=""

# Parse des arguments
while [[ $# -gt 0 ]]; do
    case "$1" in
        --skip-build) SKIP_BUILD=true; shift ;;
        --name)       OUTPUT_NAME="$2"; shift 2 ;;
        *)            echo "Usage: $0 [--skip-build] [--name archive-name]"; exit 1 ;;
    esac
done

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"
cd "$PROJECT_ROOT"

echo ""
echo "📦 Eat Is Family - Build Tarball"
echo "================================="

# ── 1. Build Nuxt ───────────────────────────────────────────
if [ "$SKIP_BUILD" = false ]; then
    echo ""
    echo "🔨 Lancement du build Nuxt..."
    npm run build
    echo "✅ Build terminé avec succès"
else
    echo ""
    echo "⏭️  Build ignoré (--skip-build)"
fi

# Vérifier que .output existe
if [ ! -d ".output" ]; then
    echo "❌ Le dossier .output/ n'existe pas. Lancez d'abord 'npm run build'"
    exit 1
fi

# ── 2. Préparer le dossier temporaire ──────────────────────
TIMESTAMP=$(date +"%Y-%m-%d-%H%M")
STAGING_DIR=$(mktemp -d)

echo ""
echo "📂 Copie des fichiers nécessaires..."

# ── 3. Copier les fichiers de déploiement ──────────────────
# .output/ (le build Nuxt complet)
cp -r .output "$STAGING_DIR/.output"
echo "  ✓ .output/"

# app.cjs (wrapper Passenger CommonJS → ESM)
cp app.cjs "$STAGING_DIR/app.cjs"
echo "  ✓ app.cjs"

# app.js (wrapper Passenger alternatif)
cp app.js "$STAGING_DIR/app.js"
echo "  ✓ app.js"

# package.json de production (minimaliste)
cp package-for-deploy.json "$STAGING_DIR/package.json"
echo "  ✓ package.json (version deploy)"

# uploads.ini si présent
if [ -f "uploads.ini" ]; then
    cp uploads.ini "$STAGING_DIR/uploads.ini"
    echo "  ✓ uploads.ini"
fi

# Config Nginx
if [ -d "config" ]; then
    cp -r config "$STAGING_DIR/config"
    echo "  ✓ config/"
fi
# server/data/ (recipients.json, etc. - requis par les API routes)
if [ -d "server/data" ]; then
    mkdir -p "$STAGING_DIR/server/data"
    cp -r server/data/* "$STAGING_DIR/server/data/"
    echo "  + server/data/ (recipients.json, applications/)"
fi

# server/uploads/ (dossiers d'upload requis au runtime)
mkdir -p "$STAGING_DIR/server/uploads/contact"
mkdir -p "$STAGING_DIR/server/uploads/applications"
touch "$STAGING_DIR/server/uploads/contact/.gitkeep"
touch "$STAGING_DIR/server/uploads/applications/.gitkeep"
echo "  + server/uploads/ (contact/, applications/)"
# ── 4. Créer l'archive tar.gz ─────────────────────────────
if [ -z "$OUTPUT_NAME" ]; then
    OUTPUT_NAME="eatisfamily-deploy-$TIMESTAMP"
fi
TAR_FILE="$PROJECT_ROOT/$OUTPUT_NAME.tar.gz"

echo ""
echo "📦 Création de l'archive $OUTPUT_NAME.tar.gz..."

tar -czf "$TAR_FILE" -C "$STAGING_DIR" .

# ── 5. Résumé ─────────────────────────────────────────────
SIZE=$(du -sh "$TAR_FILE" | cut -f1)

echo ""
echo "✅ Archive créée avec succès !"
echo ""
echo "  📁 Fichier : $TAR_FILE"
echo "  📊 Taille  : $SIZE"
echo ""
echo "── Déploiement sur le serveur ──"
echo ""
echo "  # 1. Transférer l'archive"
echo "  scp $OUTPUT_NAME.tar.gz user@serveur:/var/www/eatisfamily/"
echo ""
echo "  # 2. Sur le serveur, extraire"
echo "  ssh user@serveur"
echo "  cd /var/www/eatisfamily"
echo "  tar -xzf $OUTPUT_NAME.tar.gz"
echo ""
echo "  # 3. Redémarrer Passenger"
echo "  touch /var/www/eatisfamily/tmp/restart.txt"
echo ""

# ── 6. Nettoyage ──────────────────────────────────────────
rm -rf "$STAGING_DIR"
