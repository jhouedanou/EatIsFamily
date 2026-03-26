# =============================================================
# BUILD TARBALL - Cree une archive tar.gz prete a deployer
# Usage: powershell -ExecutionPolicy Bypass -File scripts\build-tarball.ps1
# Options: -SkipBuild  (ne pas relancer le build)
#          -OutputName  (nom personnalise pour l'archive)
# =============================================================

param(
    [switch]$SkipBuild,
    [string]$OutputName = ""
)

$ErrorActionPreference = "Stop"

$projectRoot = $PSScriptRoot | Split-Path -Parent
Push-Location $projectRoot

Write-Host ""
Write-Host "--- Eat Is Family - Build Tarball ---" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan

# -- 1. Build Nuxt (sauf si -SkipBuild) --
if (-not $SkipBuild) {
    Write-Host "`n[BUILD] Lancement du build Nuxt..." -ForegroundColor Yellow
    npm run build
    if ($LASTEXITCODE -ne 0) {
        Write-Host "[ERREUR] Le build a echoue !" -ForegroundColor Red
        Pop-Location
        exit 1
    }
    Write-Host "[OK] Build termine avec succes" -ForegroundColor Green
} else {
    Write-Host "`n[SKIP] Build ignore (-SkipBuild)" -ForegroundColor Yellow
}

# Verifier que .output existe
if (-not (Test-Path ".output")) {
    Write-Host "[ERREUR] Le dossier .output/ n'existe pas. Lancez d'abord 'npm run build'" -ForegroundColor Red
    Pop-Location
    exit 1
}

# -- 2. Preparer le dossier temporaire --
$timestamp = Get-Date -Format "yyyy-MM-dd-HHmm"
$stagingDir = Join-Path $env:TEMP "eatisfamily-deploy-$timestamp"

if (Test-Path $stagingDir) {
    Remove-Item -Recurse -Force $stagingDir
}
New-Item -ItemType Directory -Path $stagingDir | Out-Null

Write-Host "`n[COPIE] Copie des fichiers necessaires..." -ForegroundColor Yellow

# -- 3. Copier les fichiers de deploiement --
Copy-Item -Recurse ".output" (Join-Path $stagingDir ".output")
Write-Host "  + .output/" -ForegroundColor Gray

Copy-Item "app.cjs" (Join-Path $stagingDir "app.cjs")
Write-Host "  + app.cjs (wrapper Passenger)" -ForegroundColor Gray

Copy-Item "app.js" (Join-Path $stagingDir "app.js")
Write-Host "  + app.js" -ForegroundColor Gray

Copy-Item "package-for-deploy.json" (Join-Path $stagingDir "package.json")
Write-Host "  + package.json (version deploy)" -ForegroundColor Gray

if (Test-Path "uploads.ini") {
    Copy-Item "uploads.ini" (Join-Path $stagingDir "uploads.ini")
    Write-Host "  + uploads.ini" -ForegroundColor Gray
}

if (Test-Path "config") {
    New-Item -ItemType Directory -Path (Join-Path $stagingDir "config") -Force | Out-Null
    Copy-Item -Recurse "config\*" (Join-Path $stagingDir "config")
    Write-Host "  + config/" -ForegroundColor Gray
}

# server/data/ (recipients.json, etc. - requis par les API routes)
if (Test-Path "server\data") {
    $destServerData = Join-Path $stagingDir "server\data"
    New-Item -ItemType Directory -Path $destServerData -Force | Out-Null
    Copy-Item -Recurse "server\data\*" $destServerData
    Write-Host "  + server/data/ (recipients.json, applications/)" -ForegroundColor Gray
}

# server/uploads/ (dossiers d'upload requis au runtime)
$uploadsContact = Join-Path $stagingDir "server\uploads\contact"
$uploadsApps = Join-Path $stagingDir "server\uploads\applications"
New-Item -ItemType Directory -Path $uploadsContact -Force | Out-Null
New-Item -ItemType Directory -Path $uploadsApps -Force | Out-Null
# Ajouter un .gitkeep pour que les dossiers vides soient inclus dans l'archive
New-Item -ItemType File -Path (Join-Path $uploadsContact ".gitkeep") -Force | Out-Null
New-Item -ItemType File -Path (Join-Path $uploadsApps ".gitkeep") -Force | Out-Null
Write-Host "  + server/uploads/ (contact/, applications/)" -ForegroundColor Gray

# -- 4. Creer l'archive tar.gz --
if ($OutputName -eq "") {
    $OutputName = "eatisfamily-deploy-$timestamp"
}
$tarFile = Join-Path $projectRoot "$OutputName.tar.gz"

Write-Host "`n[ARCHIVE] Creation de $OutputName.tar.gz..." -ForegroundColor Yellow

tar -czf $tarFile -C $stagingDir .

if ($LASTEXITCODE -ne 0) {
    Write-Host "[ERREUR] La creation de l'archive a echoue !" -ForegroundColor Red
    Remove-Item -Recurse -Force $stagingDir
    Pop-Location
    exit 1
}

# -- 5. Resume --
$size = (Get-Item $tarFile).Length
$sizeMB = [math]::Round($size / 1MB, 2)

Write-Host "`n[OK] Archive creee avec succes !" -ForegroundColor Green
Write-Host ""
Write-Host "  Fichier : $tarFile" -ForegroundColor White
Write-Host "  Taille  : $sizeMB MB" -ForegroundColor White
Write-Host ""
Write-Host "-- Deploiement sur le serveur --" -ForegroundColor Cyan
Write-Host ""
Write-Host "  # 1. Transferer l'archive" -ForegroundColor Gray
Write-Host "  scp $OutputName.tar.gz user@serveur:/var/www/eatisfamily/" -ForegroundColor White
Write-Host ""
Write-Host "  # 2. Sur le serveur, extraire" -ForegroundColor Gray
Write-Host "  ssh user@serveur" -ForegroundColor White
Write-Host "  cd /var/www/eatisfamily" -ForegroundColor White
Write-Host "  tar -xzf $OutputName.tar.gz" -ForegroundColor White
Write-Host ""
Write-Host "  # 3. Redemarrer Passenger" -ForegroundColor Gray
Write-Host "  touch /var/www/eatisfamily/tmp/restart.txt" -ForegroundColor White
Write-Host ""

# -- 6. Nettoyage --
Remove-Item -Recurse -Force $stagingDir
Pop-Location
