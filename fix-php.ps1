# Simple PHP.ini Fix
$phpIniPath = "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.ini"

Write-Host "Fixing PHP configuration..." -ForegroundColor Cyan

# Backup
Copy-Item $phpIniPath "$phpIniPath.backup2" -Force

# Read and fix
$content = Get-Content $phpIniPath
$newContent = @()

foreach ($line in $content) {
    if ($line -match "^extension=mysqli\s+extension=mysqli") {
        $newContent += "extension=mysqli"
        Write-Host "Fixed duplicate mysqli" -ForegroundColor Green
    }
    elseif ($line -match "^;extension=pdo_mysql") {
        $newContent += "extension=pdo_mysql"
        Write-Host "Enabled pdo_mysql" -ForegroundColor Green
    }
    else {
        $newContent += $line
    }
}

# Write
$newContent | Set-Content $phpIniPath -Force

Write-Host "Done! Testing..." -ForegroundColor Green
php -v
Write-Host ""
Write-Host "PDO Extensions:" -ForegroundColor Cyan
php -m | Select-String pdo

pause
