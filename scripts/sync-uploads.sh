#!/bin/bash
# ============================================
# Copie des uploads de sitewordpressOriginal vers /api/
# Sans écraser les fichiers existants
# Réversible : les fichiers copiés sont loggués pour rollback
# ============================================

LOG_FILE="/home/eatisfam/uploads-sync-$(date +%Y%m%d%H%M%S).log"
SRC="/home/eatisfam/public_html/sitewordpressOriginal/wp-content/uploads/"
DST="/home/eatisfam/public_html/api/wp-content/uploads/"

echo ""
echo "=========================================="
echo "  📂 Sync uploads: sitewordpressOriginal → api"
echo "=========================================="
echo ""
echo "  Source : $SRC"
echo "  Dest   : $DST"
echo "  Log    : $LOG_FILE"
echo ""

# Vérifier que les dossiers existent
if [ ! -d "$SRC" ]; then
  echo "  ❌ Dossier source introuvable : $SRC"
  exit 1
fi

if [ ! -d "$DST" ]; then
  echo "  ❌ Dossier destination introuvable : $DST"
  exit 1
fi

# ============================================
# ÉTAPE 1 : Dry run
# ============================================
echo "  🔍 DRY RUN — fichiers qui seraient copiés :"
echo ""
rsync -avn --ignore-existing "$SRC" "$DST" 2>&1 | tail -n +2 | head -50
echo ""

COUNT=$(rsync -avn --ignore-existing "$SRC" "$DST" 2>&1 | grep -c "^[^s]")
echo "  📊 ~$COUNT fichiers/dossiers à copier"
echo ""

read -p "  ▶ Continuer avec la copie réelle ? (o/N) : " CONFIRM
if [ "$CONFIRM" != "o" ] && [ "$CONFIRM" != "O" ]; then
  echo "  ⏹ Annulé."
  exit 0
fi

# ============================================
# ÉTAPE 2 : Copie réelle + log des fichiers copiés
# ============================================
echo ""
echo "  ⏳ Copie en cours..."
rsync -av --ignore-existing "$SRC" "$DST" 2>&1 | tee "$LOG_FILE"

echo ""
echo "  ✅ Copie terminée !"
echo "  📄 Log sauvegardé : $LOG_FILE"
echo ""
echo "  ⚠️  Pour ROLLBACK (supprimer uniquement les fichiers copiés) :"
echo "  bash ~/mon_app_nuxt/scripts/rollback-uploads-sync.sh $LOG_FILE"
echo ""
