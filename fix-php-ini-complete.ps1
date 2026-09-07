# Complete PHP.ini Fix
# UniSIRAJ Attendance System

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Complete PHP.ini Fix" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$phpIniPath = "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.ini"

# Check if file exists
if (-Not (Test-Path $phpIniPath)) {
    Write-Host "ERROR: php.ini file not found" -ForegroundColor Red
    pause
    exit
}

Write-Host "Reading php.ini file..." -ForegroundColor Yellow
$content = Get-Content $phpIniPath

# Create backup
$backupPath = "$phpIniPath.backup-" + (Get-Date -Format "yyyyMMdd-HHmmss")
Write-Host "Creating backup: $backupPath" -ForegroundColor Yellow
Copy-Item $phpIniPath $backupPath -Force

# Fix issues
$newContent = @()
$lineNumber = 0
$fixed = @()

foreach ($line in $content) {
    $lineNumber++
    
    # Fix line 773 - Remove duplicate mysqli
    if ($line -match "^extension=mysqli\s+extension=mysqli") {
        $fixed += "Line $lineNumber: Fixed duplicate mysqli extension"
        $newContent += "extension=mysqli"
    }
    # Uncomment pdo_mysql if commented
    elseif ($line -match "^;extension=pdo_mysql") {
        $fixed += "Line $lineNumber: Enabled pdo_mysql"
        $newContent += "extension=pdo_mysql"
    }
    # Keep other lines as-is
    else {
        $newContent += $line
    }
}

# Write fixed content
Write-Host "Writing fixes to php.ini..." -ForegroundColor Yellow
$newContent | Set-Content $phpIniPath -Force

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "SUCCESS! PHP.ini Fixed" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

if ($fixed.Count -gt 0) {
    Write-Host "Changes made:" -ForegroundColor Cyan
    foreach ($fix in $fixed) {
        Write-Host "  ✓ $fix" -ForegroundColor Green
    }
} else {
    Write-Host "No changes needed - already fixed!" -ForegroundColor Green
}

Write-Host ""
Write-Host "Backup created: $backupPath" -ForegroundColor Yellow
Write-Host ""

# Verify PHP can load
Write-Host "Verifying PHP configuration..." -ForegroundColor Yellow
$phpTest = & php -v 2>&1
if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ PHP loads successfully!" -ForegroundColor Green
} else {
    Write-Host "✗ PHP has errors:" -ForegroundColor Red
    Write-Host $phpTest -ForegroundColor Red
}

Write-Host ""
Write-Host "Checking PDO extensions..." -ForegroundColor Yellow
$extensions = & php -m 2>&1 | Select-String -Pattern "pdo"
Write-Host "PDO Extensions loaded:" -ForegroundColor Cyan
$extensions | ForEach-Object { Write-Host "  ✓ $_" -ForegroundColor Green }

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Next Steps:" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "1. Check database exists" -ForegroundColor White
Write-Host "2. Start PHP server" -ForegroundColor White
Write-Host "3. Open http://localhost:8000" -ForegroundColor White
Write-Host ""
Write-Host "Press any key to continue..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
