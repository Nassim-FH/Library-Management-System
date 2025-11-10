# Library Management System - Quick Setup Script
# Run this script after installing composer dependencies

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Library Management System - Setup" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if vendor directory exists
if (-Not (Test-Path "vendor")) {
    Write-Host "Installing composer dependencies..." -ForegroundColor Yellow
    composer install
    Write-Host ""
}

# Create database
Write-Host "Creating database..." -ForegroundColor Yellow
php bin/console doctrine:database:create --if-not-exists
Write-Host ""

# Run migrations
Write-Host "Running migrations..." -ForegroundColor Yellow
$migrationFiles = Get-ChildItem -Path "migrations" -Filter "*.php" -ErrorAction SilentlyContinue
if ($migrationFiles) {
    php bin/console doctrine:migrations:migrate --no-interaction
} else {
    Write-Host "No migrations found. Creating migrations..." -ForegroundColor Yellow
    php bin/console make:migration --no-interaction
    php bin/console doctrine:migrations:migrate --no-interaction
}
Write-Host ""

# Load fixtures
Write-Host "Loading sample data..." -ForegroundColor Yellow
php bin/console doctrine:fixtures:load --no-interaction
Write-Host ""

# Clear cache
Write-Host "Clearing cache..." -ForegroundColor Yellow
php bin/console cache:clear
Write-Host ""

Write-Host "========================================" -ForegroundColor Green
Write-Host "Setup Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Sample Credentials:" -ForegroundColor Cyan
Write-Host "  Admin:" -ForegroundColor White
Write-Host "    Email: admin@library.com" -ForegroundColor White
Write-Host "    Password: admin123" -ForegroundColor White
Write-Host ""
Write-Host "  User:" -ForegroundColor White
Write-Host "    Email: john@example.com" -ForegroundColor White
Write-Host "    Password: user123" -ForegroundColor White
Write-Host ""
Write-Host "To start the server, run:" -ForegroundColor Yellow
Write-Host "  symfony server:start" -ForegroundColor White
Write-Host "  OR" -ForegroundColor White
Write-Host "  php -S localhost:8000 -t public" -ForegroundColor White
Write-Host ""
Write-Host "Then visit: http://localhost:8000" -ForegroundColor Cyan
Write-Host ""
