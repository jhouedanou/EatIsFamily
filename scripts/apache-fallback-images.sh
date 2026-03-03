#!/bin/bash
# ============================================
# Apache fallback : si image absente dans /api/wp-content/uploads/,
# redirige vers /sitewordpressOriginal/wp-content/uploads/
# (pour les anciens fichiers qui n'ont pas été copiés)
# ============================================

HTACCESS="/home/eatisfam/public_html/.htaccess"

echo ""
echo "=========================================="
echo "  🔧 Apache fallback images"
echo "=========================================="
echo ""

# Backup
cp "$HTACCESS" "${HTACCESS}.bak-$(date +%Y%m%d%H%M%S)" 2>/dev/null

# Vérifier si les règles existent déjà
if grep -q "Fallback images" "$HTACCESS" 2>/dev/null; then
  echo "  ⚠️  Règles déjà présentes, rien à faire."
  exit 0
fi

# Vérifier si RewriteEngine existe
if grep -q "RewriteEngine On" "$HTACCESS" 2>/dev/null; then
  # Ajouter après RewriteEngine On
  sed -i '/RewriteEngine On/a\
\
# Fallback images : /api/wp-content/uploads -> /sitewordpressOriginal (anciens fichiers)\
RewriteCond %{REQUEST_URI} ^/api/wp-content/uploads/\
RewriteCond %{DOCUMENT_ROOT}/api/wp-content/uploads/$1 !-f\
RewriteRule ^api/wp-content/uploads/(.*)$ /sitewordpressOriginal/wp-content/uploads/$1 [L]' "$HTACCESS"
  echo "  ✅ Règle fallback ajoutée après RewriteEngine On"
else
  # Ajouter RewriteEngine + règle au début (avant Passenger)
  TEMP=$(mktemp)
  cat > "$TEMP" << 'EOF'
RewriteEngine On

# Fallback images : /api/wp-content/uploads -> /sitewordpressOriginal (anciens fichiers)
RewriteCond %{REQUEST_URI} ^/api/wp-content/uploads/
RewriteCond %{DOCUMENT_ROOT}/api/wp-content/uploads/$1 !-f
RewriteRule ^api/wp-content/uploads/(.*)$ /sitewordpressOriginal/wp-content/uploads/$1 [L]

EOF
  cat "$HTACCESS" >> "$TEMP"
  mv "$TEMP" "$HTACCESS"
  echo "  ✅ RewriteEngine On + règle fallback ajoutés"
fi

echo ""
echo "  📄 .htaccess actuel :"
cat "$HTACCESS" | head -20 | sed 's/^/    /'
echo ""
