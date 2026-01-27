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

$ErrorActionPreference = "Continue"
$ProjectRoot = $PSScriptRoot | Split-Path -Parent

# Colors for output
function Write-SuccessMsg { param($Message) Write-Host "[OK] $Message" -ForegroundColor Green }
function Write-InfoMsg { param($Message) Write-Host "[INFO] $Message" -ForegroundColor Cyan }
function Write-WarningMsg { param($Message) Write-Host "[WARN] $Message" -ForegroundColor Yellow }
function Write-ErrorMsg { param($Message) Write-Host "[ERROR] $Message" -ForegroundColor Red }

function Show-HelpInfo {
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

function Test-DockerInstalled {
    try {
        $null = docker --version 2>$null
        return $true
    } catch {
        return $false
    }
}

function Start-WPContainers {
    Write-InfoMsg "Starting WordPress containers..."
    
    # Check if Docker is running
    if (-not (Test-DockerInstalled)) {
        Write-ErrorMsg "Docker is not installed or not running. Please install Docker Desktop first."
        Write-Host "Download: https://www.docker.com/products/docker-desktop/" -ForegroundColor Yellow
        return
    }

    Set-Location $ProjectRoot

    # Create uploads directory if it doesn't exist
    if (-not (Test-Path "wordpress-uploads")) {
        New-Item -ItemType Directory -Path "wordpress-uploads" -Force | Out-Null
        Write-InfoMsg "Created wordpress-uploads directory"
    }

    # Start containers
    docker-compose up -d

    Write-Host ""
    Write-SuccessMsg "WordPress is starting up!"
    Write-Host ""
    Write-Host "Please wait 30-60 seconds for the first startup, then visit:" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "  WordPress:    http://localhost:8080" -ForegroundColor Cyan
    Write-Host "  WP Admin:     http://localhost:8080/wp-admin" -ForegroundColor Cyan
    Write-Host "  phpMyAdmin:   http://localhost:8081" -ForegroundColor Cyan
    Write-Host "  API Endpoint: http://localhost:8080/wp-json/eatisfamily/v1/" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "First time setup:" -ForegroundColor Yellow
    Write-Host "  1. Complete WordPress installation at http://localhost:8080"
    Write-Host "  2. Go to Appearance > Themes and activate 'Eat Is Family'"
    Write-Host "  3. Go to Settings > Permalinks and select 'Post name'"
    Write-Host "  4. Access the API at http://localhost:8080/wp-json/eatisfamily/v1/"
    Write-Host ""
}

function Stop-WPContainers {
    Write-InfoMsg "Stopping WordPress containers..."
    Set-Location $ProjectRoot
    docker-compose down
    Write-SuccessMsg "WordPress containers stopped."
}

function Restart-WPContainers {
    Stop-WPContainers
    Start-Sleep -Seconds 2
    Start-WPContainers
}

function Reset-WPContainers {
    Write-WarningMsg "This will delete all WordPress data and start fresh!"
    $confirm = Read-Host "Are you sure? (yes/no)"
    
    if ($confirm -eq "yes") {
        Write-InfoMsg "Resetting WordPress..."
        Set-Location $ProjectRoot
        docker-compose down -v
        
        if (Test-Path "wordpress-uploads") {
            Remove-Item -Recurse -Force "wordpress-uploads"
        }
        
        Start-Sleep -Seconds 2
        Start-WPContainers
    } else {
        Write-InfoMsg "Reset cancelled."
    }
}

function Show-WPLogs {
    Set-Location $ProjectRoot
    docker-compose logs -f
}

function Show-WPStatus {
    Set-Location $ProjectRoot
    Write-Host ""
    Write-Host "Container Status:" -ForegroundColor Cyan
    Write-Host ""
    docker-compose ps
    Write-Host ""
}

# Main execution
if ($Help -or (-not $Start -and -not $Stop -and -not $Restart -and -not $Reset -and -not $Logs -and -not $Status)) {
    Show-HelpInfo
} elseif ($Start) {
    Start-WPContainers
} elseif ($Stop) {
    Stop-WPContainers
} elseif ($Restart) {
    Restart-WPContainers
} elseif ($Reset) {
    Reset-WPContainers
} elseif ($Logs) {
    Show-WPLogs
} elseif ($Status) {
    Show-WPStatus
}
