#!/bin/bash
# ============================================
# ROLLBACK : Supprime les fichiers copiés par sync-uploads.sh
# Usage : bash rollback-uploads-sync.sh <fichier-log>
# ============================================

DST="/home/eatisfam/public_html/api/wp-content/uploads"

if [ -z "$1" ]; then
  echo ""
  echo "  ❌ Usage : bash rollback-uploads-sync.sh <fichier-log>"
  echo "  Exemple : bash rollback-uploads-sync.sh ~/uploads-sync-20260303220000.log"
  echo ""
  exit 1
fi

LOG_FILE="$1"

if [ ! -f "$LOG_FILE" ]; then
  echo "  ❌ Fichier log introuvable : $LOG_FILE"
  exit 1
fi

echo ""
echo "=========================================="
echo "  🔄 ROLLBACK uploads sync"
echo "=========================================="
echo ""
echo "  Log : $LOG_FILE"
echo ""

# Extraire les fichiers (pas les dossiers) du log rsync
# Les lignes rsync ressemblent à : 2025/01/image.jpg
# On ignore les lignes avec / à la fin (dossiers), les lignes vides, et les métadonnées
FILES=$(grep -E "^[0-9]{4}/" "$LOG_FILE" | grep -v "/$")

if [ -z "$FILES" ]; then
  echo "  ⚠️  Aucun fichier trouvé dans le log."
  exit 0
fi

COUNT=$(echo "$FILES" | wc -l)
echo "  📊 $COUNT fichiers à supprimer"
echo ""
echo "  Aperçu (10 premiers) :"
echo "$FILES" | head -10 | sed 's/^/    /'
echo ""

read -p "  ▶ Supprimer ces $COUNT fichiers de $DST ? (o/N) : " CONFIRM
if [ "$CONFIRM" != "o" ] && [ "$CONFIRM" != "O" ]; then
  echo "  ⏹ Annulé."
  exit 0
fi

echo ""
DELETED=0
ERRORS=0

while IFS= read -r FILE; do
  FULL_PATH="$DST/$FILE"
  if [ -f "$FULL_PATH" ]; then
    rm -f "$FULL_PATH"
    if [ $? -eq 0 ]; then
      DELETED=$((DELETED + 1))
    else
      echo "  ⚠️  Erreur suppression : $FULL_PATH"
      ERRORS=$((ERRORS + 1))
    fi
  fi
done <<< "$FILES"

echo "  ✅ Rollback terminé : $DELETED fichiers supprimés, $ERRORS erreurs"
echo ""
