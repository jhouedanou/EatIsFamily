#!/bin/bash
echo ""
echo "=========================================="
echo "  🔍 DRY-RUN FIX IMAGES — Aucune modification"
echo "=========================================="
echo ""

ROOT="/home/eatisfam/public_html"
HTACCESS="$ROOT/.htaccess"

# --- ÉTAPE 1 : État du .htaccess ---
echo "[1/5] 📄 État du .htaccess racine..."
echo "---------------------------------------"
if [ -f "$HTACCESS" ]; then
  echo "  ✅ Existe → $(wc -l < "$HTACCESS") lignes"
  echo ""
  echo "  🔍 Contenu actuel (20 premières lignes) :"
  head -20 "$HTACCESS" | sed 's/^/    /'
  echo ""
  
  if grep -q "RewriteEngine" "$HTACCESS"; then
    echo "  ✅ RewriteEngine trouvé"
  else
    echo "  ⚠️  RewriteEngine absent — il faudra l'ajouter"
  fi
  
  if grep -q "sitewordpressOriginal/wp-content/uploads" "$HTACCESS"; then
    echo "  ⚠️  Règles de redirection uploads DÉJÀ présentes"
  else
    echo "  → Action prévue : ajouter 2 RewriteRules pour redirections uploads"
  fi
else
  echo "  ❌ PAS de .htaccess racine — il faudra en créer un"
fi
echo ""

# --- ÉTAPE 2 : Règles qui seraient ajoutées ---
echo "[2/5] ✏️  RewriteRules qui seraient ajoutées..."
echo "---------------------------------------"
echo "  # Cas 1 : /wp-content/uploads/* → /sitewordpressOriginal/wp-content/uploads/*"
echo "  RewriteRule ^wp-content/uploads/(.*)\$ /sitewordpressOriginal/wp-content/uploads/\$1 [L]"
echo ""
echo "  # Cas 2 : /api/wp-content/uploads/* fallback → /sitewordpressOriginal/wp-content/uploads/*"
echo "  RewriteCond %{REQUEST_FILENAME} !-f"
echo "  RewriteRule ^api/wp-content/uploads/(.*)\$ /sitewordpressOriginal/wp-content/uploads/\$1 [L]"
echo ""

# --- ÉTAPE 3 : État des dossiers ---
echo "[3/5] 📂 État des dossiers uploads..."
echo "---------------------------------------"

# Source principale : sitewordpressOriginal
SRC="$ROOT/sitewordpressOriginal/wp-content/uploads"
if [ -d "$SRC" ]; then
  SRC_COUNT=$(find "$SRC" -type f 2>/dev/null | wc -l)
  SRC_SIZE=$(du -sh "$SRC" 2>/dev/null | cut -f1)
  echo "  📁 sitewordpressOriginal/uploads : $SRC_COUNT fichiers ($SRC_SIZE)"
else
  echo "  ❌ sitewordpressOriginal/uploads : N'EXISTE PAS"
fi

# Backup : api/wp-content/uploads_backup
BKP="$ROOT/api/wp-content/uploads_backup"
if [ -d "$BKP" ]; then
  BKP_COUNT=$(find "$BKP" -type f 2>/dev/null | wc -l)
  BKP_SIZE=$(du -sh "$BKP" 2>/dev/null | cut -f1)
  echo "  📁 api/uploads_backup : $BKP_COUNT fichiers ($BKP_SIZE)"
else
  echo "  ⚠️  api/uploads_backup : N'EXISTE PAS (pas de fichiers à copier)"
fi

# Symlink actuel
SYM="$ROOT/api/wp-content/uploads"
if [ -L "$SYM" ]; then
  echo "  🔗 api/uploads : symlink → $(readlink -f "$SYM")"
elif [ -d "$SYM" ]; then
  SYM_COUNT=$(find "$SYM" -type f 2>/dev/null | wc -l)
  SYM_SIZE=$(du -sh "$SYM" 2>/dev/null | cut -f1)
  echo "  📁 api/uploads : vrai dossier, $SYM_COUNT fichiers ($SYM_SIZE)"
else
  echo "  ❌ api/uploads : N'EXISTE PAS"
fi
echo ""

# --- ÉTAPE 4 : Fichiers à copier ---
echo "[4/5] 🔄 Fichiers de uploads_backup absents de sitewordpressOriginal..."
echo "---------------------------------------"
if [ -d "$BKP" ] && [ -d "$SRC" ]; then
  DELTA=$(rsync -avn --ignore-existing "$BKP/" "$SRC/" 2>/dev/null | grep -v "^$" | grep -v "^sending" | grep -v "^sent " | grep -v "^total " | grep -v "^$" | head -30)
  DELTA_COUNT=$(rsync -avn --ignore-existing "$BKP/" "$SRC/" 2>/dev/null | grep -c "^[^s].*[^/]$")
  echo "  → $DELTA_COUNT fichiers seraient copiés (--ignore-existing)"
  if [ "$DELTA_COUNT" -gt 0 ]; then
    echo "  → Aperçu des 15 premiers :"
    rsync -avn --ignore-existing "$BKP/" "$SRC/" 2>/dev/null | grep -v "^sending" | grep -v "^sent " | grep -v "^total " | grep -v "^$" | head -15 | sed 's/^/    /'
    echo ""
    DELTA_SIZE=$(rsync -avn --ignore-existing --stats "$BKP/" "$SRC/" 2>/dev/null | grep "Total transferred" | head -1)
    echo "  → $DELTA_SIZE"
  fi
elif [ ! -d "$BKP" ]; then
  echo "  → uploads_backup n'existe pas, rien à copier"
else
  echo "  → sitewordpressOriginal/uploads n'existe pas"
fi
echo ""

# --- ÉTAPE 5 : Test des URLs actuelles ---
echo "[5/5] 🧪 Test des URLs d'images (état actuel)..."
echo "---------------------------------------"
echo ""

echo "  [a] /wp-content/uploads/... (URLs WordPress dynamiques)"
HTTP1=$(curl -sI -o /dev/null -w "%{http_code}" "https://www.eatisfamily.fr/wp-content/uploads/2023/01/IMG_6430.jpg" 2>/dev/null)
echo "    → IMG_6430.jpg : HTTP $HTTP1"

echo ""
echo "  [b] /api/wp-content/uploads/... (featured images)"
HTTP2=$(curl -sI -o /dev/null -w "%{http_code}" "https://www.eatisfamily.fr/api/wp-content/uploads/2025/03/la-restauration-dans-les-stades-e1742310254449.webp" 2>/dev/null)
echo "    → la-restauration-dans-les-stades : HTTP $HTTP2"

HTTP2B=$(curl -sI -o /dev/null -w "%{http_code}" "https://www.eatisfamily.fr/api/wp-content/uploads/2026/01/eat-is-family-blanc.png" 2>/dev/null)
echo "    → eat-is-family-blanc.png : HTTP $HTTP2B"

echo ""
echo "  [c] /sitewordpressOriginal/wp-content/uploads/... (URLs corrigées en BDD)"
HTTP3=$(curl -sI -o /dev/null -w "%{http_code}" "https://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads/2023/01/IMG_6430.jpg" 2>/dev/null)
echo "    → IMG_6430.jpg : HTTP $HTTP3"

HTTP3B=$(curl -sI -o /dev/null -w "%{http_code}" "https://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads/2025/03/restauration-dans-les-stades-en-france.webp" 2>/dev/null)
echo "    → restauration-dans-les-stades : HTTP $HTTP3B"

echo ""
echo "=========================================="
echo "  ✅ DRY-RUN TERMINÉ — Rien n'a été modifié"
echo "=========================================="
echo ""
echo "  📊 Résumé :"
echo "  [a] /wp-content/uploads/      → HTTP $HTTP1 (doit être 200 après fix)"
echo "  [b] /api/wp-content/uploads/  → HTTP $HTTP2 (doit être 200 après fix)"
echo "  [c] /sitewordpressOriginal/   → HTTP $HTTP3 (source, doit déjà être 200)"
echo ""
echo "  Si [a] ou [b] montrent 404, le script fix va résoudre ça."
echo ""
echo "  👉 Pour appliquer : bash ~/fix-images-htaccess.sh"
echo ""
