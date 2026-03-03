#!/bin/bash
echo ""
echo "=========================================="
echo "  🔧 FIX BDD — URLs images vers /api/"
echo "=========================================="
echo ""

cd /home/eatisfam/public_html/api

# --- DRY RUN d'abord ---
echo "[1/3] 🔍 Dry-run : voir ce qui serait changé..."
echo "---------------------------------------"

echo ""
echo "  [a] sitewordpressOriginal/wp-content/uploads → api/wp-content/uploads (https www)"
wp search-replace 'https://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run 2>&1
echo ""

echo "  [b] sitewordpressOriginal/wp-content/uploads → api/wp-content/uploads (https sans www)"
wp search-replace 'https://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run 2>&1
echo ""

echo "  [c] sitewordpressOriginal/wp-content/uploads → api/wp-content/uploads (http www)"
wp search-replace 'http://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run 2>&1
echo ""

echo "  [d] sitewordpressOriginal/wp-content/uploads → api/wp-content/uploads (http sans www)"
wp search-replace 'http://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run 2>&1
echo ""

echo "  [e] /wp-content/uploads (sans prefixe) → /api/wp-content/uploads"
wp search-replace 'https://www.eatisfamily.fr/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables --dry-run 2>&1
echo ""

echo "=========================================="
echo "  Dry-run terminé. Vérifiez les résultats."
echo "  Si OK, passez à l'étape 2."
echo "=========================================="
echo ""
read -p "  Appliquer les changements ? (o/n) : " CONFIRM
if [ "$CONFIRM" != "o" ] && [ "$CONFIRM" != "O" ]; then
  echo "  ❌ Annulé."
  exit 0
fi

# --- APPLICATION ---
echo ""
echo "[2/3] ⚡ Application des remplacements..."
echo "---------------------------------------"

wp search-replace 'https://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables 2>&1
echo ""
wp search-replace 'https://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables 2>&1
echo ""
wp search-replace 'http://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables 2>&1
echo ""
wp search-replace 'http://eatisfamily.fr/sitewordpressOriginal/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables 2>&1
echo ""
wp search-replace 'https://www.eatisfamily.fr/wp-content/uploads' 'https://www.eatisfamily.fr/api/wp-content/uploads' --all-tables 2>&1
echo ""

# --- CACHE ---
echo "[3/3] 🧹 Vidage du cache..."
echo "---------------------------------------"
wp cache flush 2>&1
echo ""

echo "=========================================="
echo "  ✅ TERMINÉ"
echo "=========================================="
echo ""
echo "  Toutes les URLs d'images pointent maintenant vers :"
echo "  https://www.eatisfamily.fr/api/wp-content/uploads/..."
echo ""
echo "  Vérifiez :"
echo "  1. https://www.eatisfamily.fr/api/wp-admin/ → Médias"
echo "  2. https://www.eatisfamily.fr/ → images du site Nuxt"
echo ""
