#!/bin/bash
# ===========================================================
#  Script de migration des uploads WordPress
#  sitewordpressOriginal → api
# ===========================================================

set -e

echo "============================================"
echo "  ÉTAPE 1 : Vérification avant copie"
echo "============================================"
echo ""
echo "📂 Comptage des fichiers SOURCE (sitewordpressOriginal) :"
SOURCE_COUNT=$(find /home/eatisfam/public_html/sitewordpressOriginal/wp-content/uploads -type f 2>/dev/null | wc -l)
echo "   → $SOURCE_COUNT fichiers trouvés"
echo ""
echo "📂 Comptage des fichiers DESTINATION (api) AVANT copie :"
DEST_BEFORE=$(find /home/eatisfam/public_html/api/wp-content/uploads -type f 2>/dev/null | wc -l)
echo "   → $DEST_BEFORE fichiers existants"
echo ""

echo "============================================"
echo "  ÉTAPE 2 : Copie des uploads (sans écraser)"
echo "============================================"
echo ""
echo "🔄 Copie en cours avec cp -rvn (verbose, no-clobber)..."
echo "   Source : /home/eatisfam/public_html/sitewordpressOriginal/wp-content/uploads/"
echo "   Dest   : /home/eatisfam/public_html/api/wp-content/uploads/"
echo ""
cp -rvn /home/eatisfam/public_html/sitewordpressOriginal/wp-content/uploads/* /home/eatisfam/public_html/api/wp-content/uploads/ 2>&1 | tee /tmp/cp-uploads.log | tail -20
TOTAL_COPIED=$(grep -c "^'" /tmp/cp-uploads.log 2>/dev/null || echo "0")
echo ""
echo "   📋 $TOTAL_COPIED lignes de copie au total (20 dernières affichées ci-dessus)"
echo "   📋 Log complet : /tmp/cp-uploads.log"
echo ""

echo "============================================"
echo "  ÉTAPE 3 : Vérification après copie"
echo "============================================"
echo ""
DEST_AFTER=$(find /home/eatisfam/public_html/api/wp-content/uploads -type f 2>/dev/null | wc -l)
COPIED=$((DEST_AFTER - DEST_BEFORE))
echo "📊 Résultat de la copie :"
echo "   → Fichiers source       : $SOURCE_COUNT"
echo "   → Fichiers dest AVANT   : $DEST_BEFORE"
echo "   → Fichiers dest APRÈS   : $DEST_AFTER"
echo "   → Fichiers ajoutés      : $COPIED"
echo ""
if [ "$DEST_AFTER" -ge "$SOURCE_COUNT" ]; then
  echo "✅ Copie réussie ! La destination contient au moins autant de fichiers que la source."
else
  echo "⚠️  Attention : la destination a moins de fichiers que la source."
  echo "   Cela peut être normal si certains dossiers existaient déjà."
fi
echo ""

echo "============================================"
echo "  ÉTAPE 4 : Search-Replace BDD (dry-run)"
echo "============================================"
echo ""
cd /home/eatisfam/public_html/api
echo "📍 Répertoire courant : $(pwd)"
echo ""

echo "🔍 [4a] Dry-run : www.eatisfamily.fr/wp-content/uploads → www.eatisfamily.fr/api/wp-content/uploads"
wp search-replace 'https://www.eatisfamily.fr/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run
echo ""

echo "🔍 [4b] Dry-run : eatisfamily.fr/sitewordpressOriginal/wp-content/uploads (https)"
wp search-replace 'https://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run
echo ""

echo "🔍 [4c] Dry-run : www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads (https)"
wp search-replace 'https://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run
echo ""

echo "🔍 [4d] Dry-run : variantes http (sans s)"
wp search-replace 'http://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run
wp search-replace 'http://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run
echo ""

echo "============================================"
echo "  ÉTAPE 5 : Appliquer les remplacements"
echo "============================================"
echo ""
echo "⚡ Les commandes suivantes vont MODIFIER la base de données."
echo "   Copiez-collez chaque ligne une par une :"
echo ""
echo "   wp search-replace 'https://www.eatisfamily.fr/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables"
echo ""
echo "   wp search-replace 'https://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables"
echo ""
echo "   wp search-replace 'https://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables"
echo ""
echo "   wp search-replace 'http://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables"
echo ""
echo "   wp search-replace 'http://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables"
echo ""

echo "============================================"
echo "  ÉTAPE 6 : Vider le cache WordPress"
echo "============================================"
echo ""
echo "   Après les remplacements, exécutez :"
echo "   wp cache flush"
echo ""
echo "============================================"
echo "  SCRIPT TERMINÉ ✅"
echo "============================================"
