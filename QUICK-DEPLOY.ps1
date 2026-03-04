#!/usr/bin/env pwsh
# QUICK START DEPLOYMENT - Phusion Passenger
# Utilisation: .\QUICK-DEPLOY.ps1

Write-Host "🚀 Eat Is Family - Quick Deployment Setup" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

# Vérifier que nous sommes dans le bon répertoire
if (-not (Test-Path ".\.output")) {
    Write-Host "❌ ERROR: .output/ folder not found!" -ForegroundColor Red
    Write-Host "Run 'npm run build' first" -ForegroundColor Yellow
    exit 1
}

# Demander les paramètres
Write-Host "`n📋 Configuration du déploiement:" -ForegroundColor Yellow
$user = Read-Host "  SSH Username (ex: deploy)"
$host = Read-Host "  Server Hostname (ex: eafafamily.fr)"
$path = Read-Host "  Remote Path (ex: /var/www/eafafamily)"

# Résumé
Write-Host "`n📊 Résumé:" -ForegroundColor Cyan
Write-Host "  User:    $user"
Write-Host "  Host:    $host"
Write-Host "  Path:    $path"
Write-Host "  Size:    76.67 MB"
Write-Host "  Time:    ~5-10 minutes"

# Confirmation
$confirm = Read-Host "`nProceeding? (y/n)"
if ($confirm -ne 'y') {
    Write-Host "Cancelled." -ForegroundColor Yellow
    exit 0
}

# Exécuter le déploiement
Write-Host "`n🚀 Starting deployment..." -ForegroundColor Green
.\scripts\deploy-passenger.ps1 -User $user -Host $host -Path $path -Restart

Write-Host "`n✅ Deployment script finished!" -ForegroundColor Green
Write-Host "📊 Next steps:" -ForegroundColor Cyan
Write-Host "  1. Check logs: ssh $user@$host 'tail -f /var/log/nginx/eafafamily-error.log'"
Write-Host "  2. Test app: curl https://$host"
Write-Host "  3. Check status: ssh $user@$host 'passenger-status'"
Write-Host ""
