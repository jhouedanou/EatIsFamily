#!/bin/bash
# ============================================
# Nettoyage .htaccess : supprime toutes les règles
# de fallback images (sitewordpressOriginal <-> api)
# ============================================

HTACCESS="/home/eatisfam/public_html/.htaccess"

echo ""
echo "=========================================="
echo "  🧹 Nettoyage .htaccess"
echo "=========================================="
echo ""

# Backup
cp "$HTACCESS" "${HTACCESS}.bak-$(date +%Y%m%d%H%M%S)" 2>/dev/null

# Supprimer les lignes de fallback images (les 2 blocs)
sed -i '/# Fallback images/d' "$HTACCESS"
sed -i '/# Fallback : si image absente/d' "$HTACCESS"
sed -i '/RewriteCond %{REQUEST_URI} \^\/api\/wp-content\/uploads/d' "$HTACCESS"
sed -i '/RewriteCond %{DOCUMENT_ROOT}\/api\/wp-content\/uploads/d' "$HTACCESS"
sed -i '/RewriteRule \^api\/wp-content\/uploads/d' "$HTACCESS"
sed -i '/RewriteCond %{REQUEST_URI} \^\/sitewordpressOriginal\/wp-content\/uploads/d' "$HTACCESS"
sed -i '/RewriteCond %{REQUEST_FILENAME} !-f/d' "$HTACCESS"
sed -i '/RewriteRule \^sitewordpressOriginal\/wp-content\/uploads/d' "$HTACCESS"

# Supprimer le RewriteEngine On s'il ne sert plus à rien
# (vérifier s'il reste des RewriteRule)
if ! grep -q "RewriteRule" "$HTACCESS" 2>/dev/null; then
  sed -i '/RewriteEngine On/d' "$HTACCESS"
  echo "  ✅ RewriteEngine On supprimé (plus aucune règle)"
fi

# Nettoyer les lignes vides consécutives
sed -i '/^$/N;/^\n$/d' "$HTACCESS"

echo "  ✅ Règles de fallback images supprimées"
echo ""
echo "  📄 .htaccess actuel :"
cat "$HTACCESS" | sed 's/^/    /'
echo ""
echo "=========================================="
echo "  📋 Prochaine étape :"
echo "  Le client augmente l'espace disque, puis :"
echo "  cp -rn /home/eatisfam/public_html/sitewordpressOriginal/wp-content/uploads/* /home/eatisfam/public_html/api/wp-content/uploads/"
echo "=========================================="
echo ""
