# Fix PDO MySQL Extension
# UniSIRAJ Attendance System

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Fixing PDO MySQL Extension" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$phpIniPath = "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.ini"

# Check if file exists
if (-Not (Test-Path $phpIniPath)) {
    Write-Host "ERROR: php.ini file not found at: $phpIniPath" -ForegroundColor Red
    Write-Host "Please check your Laragon installation path." -ForegroundColor Yellow
    pause
    exit
}

Write-Host "Found php.ini at: $phpIniPath" -ForegroundColor Green
Write-Host ""

# Read the file
Write-Host "Reading php.ini file..." -ForegroundColor Yellow
$content = Get-Content $phpIniPath

# Find and uncomment pdo_mysql
$modified = $false
$newContent = @()

foreach ($line in $content) {
    if ($line -match "^;extension=pdo_mysql") {
        Write-Host "Found: $line" -ForegroundColor Yellow
        $newLine = $line -replace "^;", ""
        Write-Host "Changing to: $newLine" -ForegroundColor Green
        $newContent += $newLine
        $modified = $true
    } else {
        $newContent += $line
    }
}

if ($modified) {
    # Backup original file
    $backupPath = "$phpIniPath.backup"
    Write-Host ""
    Write-Host "Creating backup: $backupPath" -ForegroundColor Yellow
    Copy-Item $phpIniPath $backupPath -Force
    
    # Write modified content
    Write-Host "Writing changes to php.ini..." -ForegroundColor Yellow
    $newContent | Set-Content $phpIniPath -Force
    
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "SUCCESS! PDO MySQL Extension Enabled" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "Changes made:" -ForegroundColor Cyan
    Write-Host "  - Enabled: extension=pdo_mysql" -ForegroundColor Green
    Write-Host "  - Backup created: php.ini.backup" -ForegroundColor Green
    Write-Host ""
    Write-Host "Next steps:" -ForegroundColor Cyan
    Write-Host "  1. Restart Laragon (or just restart Apache/PHP)" -ForegroundColor White
    Write-Host "  2. Run: php -m | findstr pdo" -ForegroundColor White
    Write-Host "  3. Start server: php -S localhost:8000 -t public" -ForegroundColor White
    Write-Host "  4. Open browser: http://localhost:8000" -ForegroundColor White
    Write-Host ""
} else {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Yellow
    Write-Host "PDO MySQL Extension Already Enabled!" -ForegroundColor Yellow
    Write-Host "========================================" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "No changes needed. The extension is already uncommented." -ForegroundColor Green
    Write-Host ""
}

Write-Host "Press any key to continue..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
