# ============================================
# EatIsFamily - WordPress Local Setup Script
# ============================================
# This script sets up a local WordPress environment using Docker
# and configures the theme for development.

param(
    [switch]$Start,
    [switch]$Stop,
    [switch]$Restart,
    [switch]$Reset,
    [switch]$Logs,
    [switch]$Status,
    [switch]$Help
)

$ErrorActionPreference = "Stop"
$ProjectRoot = Split-Path -Parent (Split-Path -Parent $MyInvocation.MyCommand.Path)

# Colors for output
function Write-Success { param($Message) Write-Host "✅ $Message" -ForegroundColor Green }
function Write-Info { param($Message) Write-Host "ℹ️  $Message" -ForegroundColor Cyan }
function Write-Warning { param($Message) Write-Host "⚠️  $Message" -ForegroundColor Yellow }
function Write-Error { param($Message) Write-Host "❌ $Message" -ForegroundColor Red }

function Show-Help {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "  EatIsFamily WordPress Local Setup" -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Usage: .\setup-wordpress-local.ps1 [options]"
    Write-Host ""
    Write-Host "Options:"
    Write-Host "  -Start    Start WordPress containers"
    Write-Host "  -Stop     Stop WordPress containers"
    Write-Host "  -Restart  Restart WordPress containers"
    Write-Host "  -Reset    Reset everything (delete volumes and restart)"
    Write-Host "  -Logs     Show container logs"
    Write-Host "  -Status   Show container status"
    Write-Host "  -Help     Show this help message"
    Write-Host ""
    Write-Host "URLs after starting:"
    Write-Host "  WordPress:  http://localhost:8080"
    Write-Host "  WP Admin:   http://localhost:8080/wp-admin"
    Write-Host "  phpMyAdmin: http://localhost:8081"
    Write-Host "  API:        http://localhost:8080/wp-json/eatisfamily/v1/"
    Write-Host ""
}

function Test-Docker {
    try {
        $null = docker --version
        return $true
    } catch {
        return $false
    }
}

function Start-WordPress {
    Write-Info "Starting WordPress containers..."
    
    # Check if Docker is running
    if (-not (Test-Docker)) {
        Write-Error "Docker is not installed or not running. Please install Docker Desktop first."
        Write-Host "Download: https://www.docker.com/products/docker-desktop/" -ForegroundColor Yellow
        exit 1
    }

    Set-Location $ProjectRoot

    # Create uploads directory if it doesn't exist
    if (-not (Test-Path "wordpress-uploads")) {
        New-Item -ItemType Directory -Path "wordpress-uploads" -Force | Out-Null
        Write-Info "Created wordpress-uploads directory"
    }

    # Start containers
    docker-compose up -d

    Write-Host ""
    Write-Success "WordPress is starting up!"
    Write-Host ""
    Write-Host "Please wait 30-60 seconds for the first startup, then visit:" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "  🌐 WordPress:    http://localhost:8080" -ForegroundColor Cyan
    Write-Host "  🔐 WP Admin:     http://localhost:8080/wp-admin" -ForegroundColor Cyan
    Write-Host "  🗄️  phpMyAdmin:   http://localhost:8081" -ForegroundColor Cyan
    Write-Host "  📡 API Endpoint: http://localhost:8080/wp-json/eatisfamily/v1/" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "First time setup:" -ForegroundColor Yellow
    Write-Host "  1. Complete WordPress installation at http://localhost:8080"
    Write-Host "  2. Go to Appearance > Themes and activate 'Eat Is Family'"
    Write-Host "  3. Go to Settings > Permalinks and select 'Post name'"
    Write-Host "  4. Access the API at http://localhost:8080/wp-json/eatisfamily/v1/"
    Write-Host ""
}

function Stop-WordPress {
    Write-Info "Stopping WordPress containers..."
    Set-Location $ProjectRoot
    docker-compose down
    Write-Success "WordPress containers stopped."
}

function Restart-WordPress {
    Stop-WordPress
    Start-Sleep -Seconds 2
    Start-WordPress
}

function Reset-WordPress {
    Write-Warning "This will delete all WordPress data and start fresh!"
    $confirm = Read-Host "Are you sure? (yes/no)"
    
    if ($confirm -eq "yes") {
        Write-Info "Resetting WordPress..."
        Set-Location $ProjectRoot
        docker-compose down -v
        
        if (Test-Path "wordpress-uploads") {
            Remove-Item -Recurse -Force "wordpress-uploads"
        }
        
        Start-Sleep -Seconds 2
        Start-WordPress
    } else {
        Write-Info "Reset cancelled."
    }
}

function Show-Logs {
    Set-Location $ProjectRoot
    docker-compose logs -f
}

function Show-Status {
    Set-Location $ProjectRoot
    Write-Host ""
    Write-Host "Container Status:" -ForegroundColor Cyan
    Write-Host ""
    docker-compose ps
    Write-Host ""
}

# Main execution
if ($Help -or (-not $Start -and -not $Stop -and -not $Restart -and -not $Reset -and -not $Logs -and -not $Status)) {
    Show-Help
} elseif ($Start) {
    Start-WordPress
} elseif ($Stop) {
    Stop-WordPress
} elseif ($Restart) {
    Restart-WordPress
} elseif ($Reset) {
    Reset-WordPress
} elseif ($Logs) {
    Show-Logs
} elseif ($Status) {
    Show-Status
}
