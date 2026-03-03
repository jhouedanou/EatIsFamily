#!/bin/bash
echo ""
echo "=========================================="
echo "  🔍 DRY-RUN — Aucune modification"
echo "=========================================="
echo ""

# --- ÉTAPE 1 ---
echo "[1/4] 📂 État du dossier uploads SOURCE..."
echo "---------------------------------------"
if [ -d /home/eatisfam/public_html/sitewordpressOriginal/wp-content/uploads ]; then
  SOURCE_COUNT=$(find /home/eatisfam/public_html/sitewordpressOriginal/wp-content/uploads -type f 2>/dev/null | wc -l)
  SOURCE_SIZE=$(du -sh /home/eatisfam/public_html/sitewordpressOriginal/wp-content/uploads 2>/dev/null | cut -f1)
  echo "  ✅ Existe"
  echo "  → $SOURCE_COUNT fichiers"
  echo "  → $SOURCE_SIZE d'espace"
  echo "  → Aperçu :"
  ls /home/eatisfam/public_html/sitewordpressOriginal/wp-content/uploads/ 2>/dev/null | head -10
else
  echo "  ❌ N'EXISTE PAS — le script ne peut pas fonctionner"
fi
echo ""

# --- ÉTAPE 2 ---
echo "[2/4] 📂 État du dossier uploads DESTINATION (api)..."
echo "---------------------------------------"
if [ -L /home/eatisfam/public_html/api/wp-content/uploads ]; then
  TARGET=$(readlink -f /home/eatisfam/public_html/api/wp-content/uploads)
  echo "  🔗 C'est DÉJÀ un symlink → pointe vers : $TARGET"
  echo "  → Action prévue : supprimer le symlink et en recréer un"
elif [ -d /home/eatisfam/public_html/api/wp-content/uploads ]; then
  DEST_COUNT=$(find /home/eatisfam/public_html/api/wp-content/uploads -type f 2>/dev/null | wc -l)
  DEST_SIZE=$(du -sh /home/eatisfam/public_html/api/wp-content/uploads 2>/dev/null | cut -f1)
  echo "  📁 C'est un VRAI dossier"
  echo "  → $DEST_COUNT fichiers"
  echo "  → $DEST_SIZE d'espace"
  echo "  → Action prévue : renommer en uploads_backup puis créer symlink"
  echo ""
  echo "  ⚠️  Espace libéré après suppression du backup : ~$DEST_SIZE"
elif [ -d /home/eatisfam/public_html/api/wp-content ]; then
  echo "  📁 Le dossier wp-content existe mais pas uploads"
  echo "  → Action prévue : créer le symlink directement"
else
  echo "  ❌ /home/eatisfam/public_html/api/wp-content n'existe pas"
  echo "  → Il faudra d'abord le créer"
fi
echo ""

# --- ÉTAPE 3 ---
echo "[3/4] 🔗 Symlink qui sera créé..."
echo "---------------------------------------"
echo "  ln -s /home/eatisfam/public_html/sitewordpressOriginal/wp-content/uploads"
echo "        /home/eatisfam/public_html/api/wp-content/uploads"
echo ""
echo "  Cela signifie :"
echo "  https://www.eatisfamily.fr/api/wp-content/uploads/..."
echo "  → servira les fichiers de /sitewordpressOriginal/wp-content/uploads/..."
echo "  → 0 octet d'espace disque supplémentaire"
echo ""

# --- ÉTAPE 4 ---
echo "[4/4] 🔍 Dry-run search-replace BDD..."
echo "---------------------------------------"
cd /home/eatisfam/public_html/api

echo ""
echo "  [a] https://www.eatisfamily.fr/wp-content/uploads → .../api/wp-content/uploads"
wp search-replace 'https://www.eatisfamily.fr/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run 2>&1
echo ""

echo "  [b] https://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads → .../api/wp-content/uploads"
wp search-replace 'https://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run 2>&1
echo ""

echo "  [c] https://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads → .../api/wp-content/uploads"
wp search-replace 'https://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run 2>&1
echo ""

echo "  [d] http://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads → .../api/wp-content/uploads"
wp search-replace 'http://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run 2>&1
echo ""

echo "  [e] http://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads → .../api/wp-content/uploads"
wp search-replace 'http://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run 2>&1
echo ""

echo "=========================================="
echo "  ✅ DRY-RUN TERMINÉ — Rien n'a été modifié"
echo "=========================================="
echo ""
echo "  Si tout semble correct, lancez le vrai script :"
echo "  bash ~/symlink-uploads.sh"
echo ""
