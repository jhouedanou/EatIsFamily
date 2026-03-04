#!/usr/bin/env pwsh
# Script de déploiement Nuxt 4 sur Phusion Passenger (Windows -> Linux/Unix)
# Usage: .\deploy-passenger.ps1 -User "user" -Host "hostname" -Path "/var/www/eatisfamily"

param(
    [Parameter(Mandatory=$true)]
    [string]$User,
    
    [Parameter(Mandatory=$true)]
    [string]$Host,
    
    [Parameter(Mandatory=$true)]
    [string]$Path,
    
    [switch]$SkipBuild,
    [switch]$SkipInstallDeps,
    [switch]$Restart
)

$ErrorActionPreference = "Stop"

Write-Host "🚀 Nuxt 4 -> Phusion Passenger Deployment Script" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan

# 1. Build local si non skippé
if (-not $SkipBuild) {
    Write-Host "`n📦 Building application..." -ForegroundColor Yellow
    npm run build
    if ($LASTEXITCODE -ne 0) {
        Write-Host "❌ Build failed!" -ForegroundColor Red
        exit 1
    }
    Write-Host "✅ Build completed!" -ForegroundColor Green
}

# 2. Vérifier la connexion SSH
Write-Host "`n🔗 Testing SSH connection..." -ForegroundColor Yellow
ssh -n "${User}@${Host}" "echo OK" | Out-Null
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ SSH connection failed!" -ForegroundColor Red
    exit 1
}
Write-Host "✅ SSH connection successful!" -ForegroundColor Green

# 3. Créer le dossier distant si nécessaire
Write-Host "`n📁 Ensuring remote directory exists..." -ForegroundColor Yellow
ssh -n "${User}@${Host}" "mkdir -p '${Path}' && echo Created"
Write-Host "✅ Remote directory ready!" -ForegroundColor Green

# 4. Copier .output (build)
Write-Host "`n📤 Uploading .output directory..." -ForegroundColor Yellow
scp -r ".\.output" "${User}@${Host}:${Path}/"
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ SCP upload failed!" -ForegroundColor Red
    exit 1
}
Write-Host "✅ .output directory uploaded!" -ForegroundColor Green

# 5. Copier package.json et lock file
Write-Host "`n📤 Uploading package files..." -ForegroundColor Yellow
scp ".\package.json" "${User}@${Host}:${Path}/"
scp ".\package-lock.json" "${User}@${Host}:${Path}/" -ErrorAction SilentlyContinue
scp ".\yarn.lock" "${User}@${Host}:${Path}/" -ErrorAction SilentlyContinue
Write-Host "✅ Package files uploaded!" -ForegroundColor Green

# 6. Copier public assets
Write-Host "`n📤 Uploading public assets..." -ForegroundColor Yellow
scp -r ".\public" "${User}@${Host}:${Path}/"
Write-Host "✅ Public assets uploaded!" -ForegroundColor Green

# 7. Installer les dépendances si non skippé
if (-not $SkipInstallDeps) {
    Write-Host "`n⚙️  Installing dependencies on remote server..." -ForegroundColor Yellow
    ssh -n "${User}@${Host}" "cd '${Path}' && npm ci --production"
    if ($LASTEXITCODE -ne 0) {
        Write-Host "⚠️  npm ci --production failed, trying npm install..." -ForegroundColor Yellow
        ssh -n "${User}@${Host}" "cd '${Path}' && npm install --production"
    }
    Write-Host "✅ Dependencies installed!" -ForegroundColor Green
}

# 8. Créer/mettre à jour .env.production
Write-Host "`n🔐 Setting up environment file..." -ForegroundColor Yellow
$envContent = @"
NODE_ENV=production
NUXT_PUBLIC_API_BASE=https://eatisfamily.fr/api/wp-json/eatisfamily/v1
NUXT_PUBLIC_USE_LOCAL_FALLBACK=false
PORT=3000
HOST=0.0.0.0
"@

# Créer un fichier temp et l'uploader
$tempEnv = New-TemporaryFile
Set-Content -Path $tempEnv -Value $envContent -Encoding UTF8
scp $tempEnv "${User}@${Host}:${Path}/.env.production"
Remove-Item $tempEnv
Write-Host "✅ Environment file configured!" -ForegroundColor Green

# 9. Définir les permissions
Write-Host "`n🔒 Setting file permissions..." -ForegroundColor Yellow
ssh -n "${User}@${Host}" "cd '${Path}' && chmod -R 755 . && chmod 644 .env.production"
Write-Host "✅ Permissions set!" -ForegroundColor Green

# 10. Redémarrer Passenger
if ($Restart) {
    Write-Host "`n🔄 Restarting Passenger..." -ForegroundColor Yellow
    ssh -n "${User}@${Host}" "touch '${Path}/tmp/restart.txt' 2>/dev/null || sudo systemctl reload nginx"
    Start-Sleep -Seconds 2
    Write-Host "✅ Passenger restarted!" -ForegroundColor Green
}

Write-Host "`n" -ForegroundColor Cyan
Write-Host "✅ Deployment completed successfully!" -ForegroundColor Green
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "🌐 Application URL: https://eatisfamily.fr" -ForegroundColor Cyan
Write-Host "📊 Check logs on server: tail -f /var/log/nginx/eatisfamily-error.log" -ForegroundColor Cyan
Write-Host "`n"
