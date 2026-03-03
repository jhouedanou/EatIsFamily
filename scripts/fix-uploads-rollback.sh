#!/bin/bash
echo ""
echo "=========================================="
echo "  🔧 ROLLBACK UPLOADS — Restauration"
echo "=========================================="
echo ""

API_WP="/home/eatisfam/public_html/api/wp-content"
UPLOADS="$API_WP/uploads"
BACKUP="$API_WP/uploads_backup"
SOURCE="/home/eatisfam/public_html/sitewordpressOriginal/wp-content/uploads"
HTACCESS="/home/eatisfam/public_html/api/.htaccess"

# --- ÉTAPE 1 : Diagnostic ---
echo "[1/5] 🔍 Diagnostic de l'état actuel..."
echo "---------------------------------------"
if [ -L "$UPLOADS" ]; then
  echo "  🔗 uploads est un SYMLINK → $(readlink -f "$UPLOADS")"
  echo "  → C'est LE problème. Le symlink pointe vers sitewordpressOriginal"
  echo "    mais les fichiers 2026 ne sont pas là."
elif [ -d "$UPLOADS" ]; then
  COUNT=$(find "$UPLOADS" -type f 2>/dev/null | wc -l)
  echo "  📁 uploads est un vrai dossier ($COUNT fichiers)"
fi

if [ -d "$BACKUP" ]; then
  BKP_COUNT=$(find "$BACKUP" -type f 2>/dev/null | wc -l)
  BKP_SIZE=$(du -sh "$BACKUP" 2>/dev/null | cut -f1)
  echo "  📁 uploads_backup existe : $BKP_COUNT fichiers ($BKP_SIZE)"
  echo "  → Ce sont vos VRAIS uploads avec les fichiers 2026"
else
  echo "  ⚠️  uploads_backup N'EXISTE PAS"
fi

if [ -d "$SOURCE" ]; then
  SRC_COUNT=$(find "$SOURCE" -type f 2>/dev/null | wc -l)
  SRC_SIZE=$(du -sh "$SOURCE" 2>/dev/null | cut -f1)
  echo "  📁 sitewordpressOriginal/uploads : $SRC_COUNT fichiers ($SRC_SIZE)"
else
  echo "  ⚠️  sitewordpressOriginal/uploads N'EXISTE PAS"
fi
echo ""

# --- ÉTAPE 2 : Supprimer le symlink ---
echo "[2/5] 🗑️  Suppression du symlink..."
echo "---------------------------------------"
if [ -L "$UPLOADS" ]; then
  rm "$UPLOADS"
  echo "  ✅ Symlink supprimé"
else
  echo "  ⚠️  Pas de symlink à supprimer (uploads est un vrai dossier ou n'existe pas)"
fi
echo ""

# --- ÉTAPE 3 : Restaurer uploads_backup → uploads ---
echo "[3/5] 📂 Restauration du dossier uploads..."
echo "---------------------------------------"
if [ -d "$BACKUP" ] && [ ! -e "$UPLOADS" ]; then
  mv "$BACKUP" "$UPLOADS"
  COUNT=$(find "$UPLOADS" -type f 2>/dev/null | wc -l)
  echo "  ✅ uploads_backup renommé en uploads ($COUNT fichiers)"
elif [ -d "$BACKUP" ] && [ -d "$UPLOADS" ]; then
  echo "  ⚠️  uploads existe déjà ET uploads_backup existe"
  echo "  → Renommage de uploads_backup → uploads impossible, le dossier uploads existe."
  echo "  → Supprimez manuellement ou renommez l'un des deux."
elif [ ! -d "$BACKUP" ] && [ ! -e "$UPLOADS" ]; then
  echo "  ❌ ERREUR : ni uploads ni uploads_backup n'existent !"
  echo "  → Intervention manuelle nécessaire."
  exit 1
else
  echo "  ✅ uploads existe déjà en tant que vrai dossier"
fi
echo ""

# --- ÉTAPE 4 : Nettoyer le .htaccess ---
echo "[4/4] 🔧 Nettoyage du .htaccess..."
echo "---------------------------------------"
if [ -f "$HTACCESS" ]; then
  # Sauvegarder d'abord
  cp "$HTACCESS" "${HTACCESS}.bak-$(date +%Y%m%d%H%M%S)"
  echo "  💾 Backup créé"
  
  # Supprimer les lignes ajoutées par symlink-uploads.sh
  # On retire "# Symlinks pour uploads" et "Options +FollowSymLinks"
  sed -i '/^# Symlinks pour uploads$/d' "$HTACCESS"
  sed -i '/^Options +FollowSymLinks$/d' "$HTACCESS"
  
  # Nettoyer les lignes vides en trop au début
  sed -i '/./,$!d' "$HTACCESS"
  
  echo "  ✅ Lignes 'Options +FollowSymLinks' et commentaire supprimés"
  echo ""
  echo "  📄 .htaccess actuel :"
  cat "$HTACCESS" | head -20 | sed 's/^/    /'
else
  echo "  ⚠️  Pas de .htaccess trouvé"
fi
echo ""

# --- Vérification finale ---
echo "=========================================="
echo "  ✅ ROLLBACK TERMINÉ"
echo "=========================================="
echo ""
echo "  📂 État final :"
ls -la "$API_WP/" | grep upload | sed 's/^/    /'
echo ""
FINAL_COUNT=$(find "$UPLOADS" -type f 2>/dev/null | wc -l)
FINAL_SIZE=$(du -sh "$UPLOADS" 2>/dev/null | cut -f1)
echo "  → $FINAL_COUNT fichiers dans uploads ($FINAL_SIZE)"
echo ""

# Test rapide d'un fichier
TEST_FILE=$(find "$UPLOADS" -type f \( -name "*.jpg" -o -name "*.png" -o -name "*.webp" -o -name "*.svg" \) 2>/dev/null | head -1)
if [ -n "$TEST_FILE" ]; then
  REL=${TEST_FILE#/home/eatisfam/public_html/}
  echo "  🌐 Test HTTP : https://www.eatisfamily.fr/$REL"
  HTTP_CODE=$(curl -sI "https://www.eatisfamily.fr/$REL" | head -1)
  echo "  → $HTTP_CODE"
fi
echo ""
echo "  📄 .htaccess /api/ final :"
echo "  -------"
cat "$HTACCESS" 2>/dev/null | sed 's/^/    /'
echo ""
