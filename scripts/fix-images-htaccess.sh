#!/bin/bash
echo ""
echo "=========================================="
echo "  🔧 FIX IMAGES — RewriteRule .htaccess"
echo "=========================================="
echo ""

ROOT="/home/eatisfam/public_html"
HTACCESS="$ROOT/.htaccess"
BACKUP="$ROOT/.htaccess.bak-$(date +%Y%m%d%H%M%S)"

# --- ÉTAPE 1 : Backup du .htaccess ---
echo "[1/4] 💾 Sauvegarde du .htaccess..."
if [ -f "$HTACCESS" ]; then
  cp "$HTACCESS" "$BACKUP"
  echo "  ✅ Copié → $BACKUP"
else
  echo "  ⚠️  Pas de .htaccess existant, on va en créer un"
fi
echo ""

# --- ÉTAPE 2 : Vérifier si les règles existent déjà ---
echo "[2/4] 🔍 Vérification des règles existantes..."
if grep -q "sitewordpressOriginal/wp-content/uploads" "$HTACCESS" 2>/dev/null; then
  echo "  ⚠️  Les règles de redirection uploads existent déjà !"
  echo "  → Rien à faire pour cette étape"
  SKIP_HTACCESS=1
else
  echo "  ✅ Aucune règle existante, on va les ajouter"
  SKIP_HTACCESS=0
fi
echo ""

# --- ÉTAPE 3 : Ajouter les RewriteRules ---
echo "[3/4] ✏️  Ajout des RewriteRules dans $HTACCESS..."
if [ "$SKIP_HTACCESS" = "0" ]; then
  # On insère les règles AU DÉBUT du fichier, juste après RewriteEngine On
  # Créer un fichier temporaire avec les nouvelles règles
  cat > /tmp/htaccess-image-rules.txt << 'RULES'

# ============================================
# FIX IMAGES : Rediriger uploads vers sitewordpressOriginal
# Ajouté le $(date +%Y-%m-%d)
# ============================================
# Cas 1 : /wp-content/uploads/* → /sitewordpressOriginal/wp-content/uploads/*
# (URLs générées par WordPress quand home = eatisfamily.fr)
RewriteCond %{REQUEST_URI} ^/wp-content/uploads/
RewriteCond %{DOCUMENT_ROOT}/sitewordpressOriginal/wp-content/uploads/%1 -f [OR]
RewriteCond %{DOCUMENT_ROOT}/sitewordpressOriginal/wp-content/uploads/%1 -d
RewriteRule ^wp-content/uploads/(.*)$ /sitewordpressOriginal/wp-content/uploads/$1 [L]

# Cas 2 : /api/wp-content/uploads/* → /sitewordpressOriginal/wp-content/uploads/*
# (URLs générées par get_the_post_thumbnail_url quand siteurl = eatisfamily.fr/api)
# Note : le symlink gère déjà ça, mais au cas où il y a des fichiers manquants
RewriteCond %{REQUEST_URI} ^/api/wp-content/uploads/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{DOCUMENT_ROOT}/sitewordpressOriginal/wp-content/uploads/%1 -f
RewriteRule ^api/wp-content/uploads/(.*)$ /sitewordpressOriginal/wp-content/uploads/$1 [L]
# ============================================

RULES

  # Vérifier si RewriteEngine On existe déjà
  if grep -q "RewriteEngine On" "$HTACCESS" 2>/dev/null; then
    # Insérer après le premier "RewriteEngine On"
    sed -i '0,/RewriteEngine On/a\
\
# ============================================\
# FIX IMAGES : Rediriger uploads vers sitewordpressOriginal\
# ============================================\
# Cas 1 : /wp-content/uploads/* -> /sitewordpressOriginal/wp-content/uploads/*\
RewriteRule ^wp-content/uploads/(.*)$ /sitewordpressOriginal/wp-content/uploads/$1 [L]\
\
# Cas 2 : /api/wp-content/uploads/* fallback -> /sitewordpressOriginal/wp-content/uploads/*\
RewriteCond %{REQUEST_FILENAME} !-f\
RewriteRule ^api/wp-content/uploads/(.*)$ /sitewordpressOriginal/wp-content/uploads/$1 [L]\
# ============================================' "$HTACCESS"
    echo "  ✅ Règles ajoutées après RewriteEngine On"
  else
    # Ajouter RewriteEngine On + les règles au début
    {
      echo "RewriteEngine On"
      echo ""
      echo "# ============================================"
      echo "# FIX IMAGES : Rediriger uploads vers sitewordpressOriginal"
      echo "# ============================================"
      echo "# Cas 1 : /wp-content/uploads/* -> /sitewordpressOriginal/wp-content/uploads/*"
      echo "RewriteRule ^wp-content/uploads/(.*)$ /sitewordpressOriginal/wp-content/uploads/$1 [L]"
      echo ""
      echo "# Cas 2 : /api/wp-content/uploads/* fallback"
      echo "RewriteCond %{REQUEST_FILENAME} !-f"
      echo "RewriteRule ^api/wp-content/uploads/(.*)$ /sitewordpressOriginal/wp-content/uploads/$1 [L]"
      echo "# ============================================"
      echo ""
      cat "$HTACCESS"
    } > /tmp/htaccess-new.txt
    mv /tmp/htaccess-new.txt "$HTACCESS"
    echo "  ✅ RewriteEngine On + règles ajoutées au début"
  fi
else
  echo "  ⏭️  Ignoré (règles déjà présentes)"
fi
echo ""

# --- ÉTAPE 4 : Copier les fichiers manquants ---
echo "[4/4] 📂 Copier les fichiers manquants de uploads_backup vers sitewordpressOriginal..."
BACKUP_DIR="$ROOT/api/wp-content/uploads_backup"
TARGET_DIR="$ROOT/sitewordpressOriginal/wp-content/uploads"

if [ -d "$BACKUP_DIR" ]; then
  # Compter les fichiers qui sont UNIQUEMENT dans uploads_backup
  UNIQUE_COUNT=0
  COPY_COUNT=0
  
  echo "  🔍 Recherche des fichiers présents UNIQUEMENT dans uploads_backup..."
  
  # Utiliser rsync en dry-run pour voir ce qui serait copié
  DELTA=$(rsync -avn --ignore-existing "$BACKUP_DIR/" "$TARGET_DIR/" 2>/dev/null | grep -c "^[^.]")
  echo "  → $DELTA fichiers à copier (absents de sitewordpressOriginal)"
  
  if [ "$DELTA" -gt 0 ]; then
    # Estimer la taille
    DELTA_SIZE=$(rsync -avn --ignore-existing "$BACKUP_DIR/" "$TARGET_DIR/" 2>/dev/null | tail -1)
    echo "  → Taille estimée : $DELTA_SIZE"
    echo ""
    echo "  📋 Copie en cours (--ignore-existing = n'écrase rien)..."
    rsync -av --ignore-existing "$BACKUP_DIR/" "$TARGET_DIR/" 2>&1 | tail -5
    echo "  ✅ Copie terminée"
  else
    echo "  ✅ Aucun fichier manquant — tout est déjà dans sitewordpressOriginal"
  fi
else
  echo "  ⚠️  uploads_backup n'existe pas à $BACKUP_DIR"
  echo "  → Pas de fichiers à copier"
fi
echo ""

# --- VÉRIFICATION FINALE ---
echo "=========================================="
echo "  🧪 VÉRIFICATION"
echo "=========================================="
echo ""

# Test quelques images
echo "Test image /wp-content/uploads/..."
curl -sI "https://www.eatisfamily.fr/wp-content/uploads/2023/01/IMG_6430.jpg" 2>/dev/null | head -3
echo ""

echo "Test image /api/wp-content/uploads/..."
curl -sI "https://www.eatisfamily.fr/api/wp-content/uploads/2025/03/la-restauration-dans-les-stades-e1742310254449.webp" 2>/dev/null | head -3
echo ""

echo "Test image /sitewordpressOriginal/wp-content/uploads/..."
curl -sI "https://www.eatisfamily.fr/sitewordpressOriginal/wp-content/uploads/2023/01/IMG_6430.jpg" 2>/dev/null | head -3
echo ""

echo "=========================================="
echo "  ✅ TERMINÉ !"
echo "=========================================="
echo ""
echo "  Les 3 tests ci-dessus doivent montrer HTTP/2 200"
echo "  Si c'est bon, va vérifier :"
echo "  https://www.eatisfamily.fr/blog/la-restauration-dans-les-stades-en-france"
echo ""
echo "  ⚠️  Ctrl+Shift+R pour forcer le rafraîchissement (pas de cache)"
echo ""
