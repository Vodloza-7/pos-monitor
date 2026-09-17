$ErrorActionPreference = "Stop"

$sourceRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$xamppRoot = "C:\xampp"
$targetRoot = Join-Path $xamppRoot "htdocs\monitor"
$mysql = Join-Path $xamppRoot "mysql\bin\mysql.exe"
$configPath = Join-Path $targetRoot "config.local.php"

if (-not (Test-Path $mysql)) {
    throw "XAMPP MySQL was not found at $mysql. Install XAMPP first or update xamppRoot."
}

if (-not (Test-Path (Join-Path $xamppRoot "apache\bin\httpd.exe"))) {
    throw "XAMPP Apache was not found at $xamppRoot. Install XAMPP first or update xamppRoot."
}

if ($sourceRoot -ne $targetRoot) {
    New-Item -ItemType Directory -Force -Path $targetRoot | Out-Null
    Get-ChildItem -Path $sourceRoot -Force | Where-Object {
        $_.Name -notin @("config.local.php", ".git", ".gitignore")
    } | Copy-Item -Destination $targetRoot -Recurse -Force
}

$dbPassword = Read-Host "Enter the MySQL root password for the onpos database"
$config = @"
<?php
define("MONITOR_DB_HOST", "localhost");
define("MONITOR_DB_USER", "root");
define("MONITOR_DB_PASSWORD", "$dbPassword");
define("MONITOR_DB_NAME", "onpos");
?>
"@
Set-Content -Path $configPath -Value $config -Encoding ASCII

$sqlPath = Join-Path $targetRoot "monitor-database.sql"
if (-not (Test-Path $sqlPath)) {
    throw "Missing database setup file: $sqlPath"
}

$sql = Get-Content -Path $sqlPath -Raw
$oldMysqlPassword = $env:MYSQL_PWD
$env:MYSQL_PWD = $dbPassword
try {
    $sql | & $mysql --protocol=TCP -u root onpos
    if ($LASTEXITCODE -ne 0) {
        throw "The monitor database setup failed. Verify that MySQL is running and the onpos database exists."
    }
} finally {
    if ($null -eq $oldMysqlPassword) {
        Remove-Item Env:MYSQL_PWD -ErrorAction SilentlyContinue
    } else {
        $env:MYSQL_PWD = $oldMysqlPassword
    }
}

$xamppStart = Join-Path $xamppRoot "xampp_start.exe"
if (Test-Path $xamppStart) {
    Start-Process -FilePath $xamppStart
}

Write-Host "Monitor installed at $targetRoot"
Write-Host "Create the first account at http://localhost/monitor/create_user.php"
Start-Process "http://localhost/monitor/create_user.php"
