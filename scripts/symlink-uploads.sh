#!/bin/bash
echo ""
echo "=========================================="
echo "  🚀 MIGRATION UPLOADS — SYMLINK"
echo "=========================================="
echo ""

# --- ÉTAPE 1 ---
echo "[1/6] 📂 Vérification de l'état actuel..."
echo "---------------------------------------"
echo "  Source (sitewordpressOriginal) :"
SOURCE_COUNT=$(find /home/eatisfam/public_html/sitewordpressOriginal/wp-content/uploads -type f 2>/dev/null | wc -l)
echo "    → $SOURCE_COUNT fichiers"
echo "  Destination (api) :"
if [ -L /home/eatisfam/public_html/api/wp-content/uploads ]; then
  echo "    → C'est déjà un symlink"
elif [ -d /home/eatisfam/public_html/api/wp-content/uploads ]; then
  DEST_COUNT=$(find /home/eatisfam/public_html/api/wp-content/uploads -type f 2>/dev/null | wc -l)
  echo "    → Dossier réel avec $DEST_COUNT fichiers"
else
  echo "    → N'existe pas"
fi
echo ""

# --- ÉTAPE 2 ---
echo "[2/6] 🔗 Création du symlink..."
echo "---------------------------------------"
if [ -d /home/eatisfam/public_html/api/wp-content/uploads ] && [ ! -L /home/eatisfam/public_html/api/wp-content/uploads ]; then
  echo "  📦 Renommage uploads → uploads_backup..."
  mv /home/eatisfam/public_html/api/wp-content/uploads /home/eatisfam/public_html/api/wp-content/uploads_backup
  echo "  ✅ Backup créé"
elif [ -L /home/eatisfam/public_html/api/wp-content/uploads ]; then
  rm /home/eatisfam/public_html/api/wp-content/uploads
  echo "  🗑️  Ancien symlink supprimé"
fi
ln -s /home/eatisfam/public_html/sitewordpressOriginal/wp-content/uploads /home/eatisfam/public_html/api/wp-content/uploads
echo "  ✅ Symlink créé :"
ls -la /home/eatisfam/public_html/api/wp-content/uploads
LINK_COUNT=$(find /home/eatisfam/public_html/api/wp-content/uploads -type f 2>/dev/null | wc -l)
echo "  → $LINK_COUNT fichiers accessibles via le lien"
echo ""

# --- ÉTAPE 3 ---
echo "[3/6] 🔧 Configuration Apache FollowSymLinks..."
echo "---------------------------------------"
HTACCESS="/home/eatisfam/public_html/api/.htaccess"
if [ -f "$HTACCESS" ]; then
  if grep -q "FollowSymLinks" "$HTACCESS"; then
    echo "  ✅ FollowSymLinks déjà présent dans .htaccess"
  else
    echo "  📝 Ajout de Options +FollowSymLinks dans .htaccess..."
    # Ajouter au début du fichier
    sed -i '1i\# Symlinks pour uploads\nOptions +FollowSymLinks' "$HTACCESS"
    echo "  ✅ Ajouté"
  fi
else
  echo "  📝 Création du .htaccess avec FollowSymLinks..."
  echo -e "# Symlinks pour uploads\nOptions +FollowSymLinks" > "$HTACCESS"
  echo "  ✅ Créé"
fi
cat "$HTACCESS" | head -5
echo ""

# --- ÉTAPE 4 ---
echo "[4/6] 🔍 Dry-run search-replace (lecture seule)..."
echo "---------------------------------------"
cd /home/eatisfam/public_html/api
wp search-replace 'https://www.eatisfamily.fr/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run 2>&1 | tail -2
wp search-replace 'https://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run 2>&1 | tail -2
wp search-replace 'https://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run 2>&1 | tail -2
wp search-replace 'http://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run 2>&1 | tail -2
wp search-replace 'http://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run 2>&1 | tail -2
echo ""

# --- ÉTAPE 5 ---
echo "[5/6] ⚡ Application des remplacements dans la BDD..."
echo "---------------------------------------"
cd /home/eatisfam/public_html/api
wp search-replace 'https://www.eatisfamily.fr/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables 2>&1 | tail -1
wp search-replace 'https://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables 2>&1 | tail -1
wp search-replace 'https://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables 2>&1 | tail -1
wp search-replace 'http://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables 2>&1 | tail -1
wp search-replace 'http://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables 2>&1 | tail -1
wp cache flush
echo "  ✅ BDD mise à jour + cache vidé"
echo ""

# --- ÉTAPE 6 ---
echo "[6/6] ✅ Vérification finale..."
echo "---------------------------------------"
ls -la /home/eatisfam/public_html/api/wp-content/ | grep uploads
echo ""
TEST_FILE=$(find /home/eatisfam/public_html/api/wp-content/uploads -type f \( -name "*.jpg" -o -name "*.png" -o -name "*.webp" \) 2>/dev/null | head -1)
if [ -n "$TEST_FILE" ]; then
  REL=${TEST_FILE#/home/eatisfam/public_html/}
  echo "  🌐 Test image : https://www.eatisfamily.fr/$REL"
  curl -sI "https://www.eatisfamily.fr/$REL" | head -2
fi
echo ""
echo "=========================================="
echo "  🎉 TERMINÉ — 0 espace disque utilisé"
echo "=========================================="
echo ""
echo "  Tests d'images connues :"
echo "  ---"
echo "  🔗 Avec /api/ :"
curl -sI "https://www.eatisfamily.fr/api/wp-content/uploads/2026/01/eat-is-family-blanc.png" | head -1
echo "  🔗 Sans /api/ (doit aussi marcher grâce au search-replace) :"
echo "  → Les URLs en BDD pointent maintenant vers /api/wp-content/uploads/"
echo ""
echo "  Pour libérer 852M d'espace :"
echo "  rm -rf /home/eatisfam/public_html/api/wp-content/uploads_backup"
echo "=========================================="
