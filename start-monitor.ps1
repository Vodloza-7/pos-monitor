$ErrorActionPreference = "Stop"

# Adjust this path if ngrok.exe is installed somewhere else.
$xamppRoot = "C:\xampp"
$ngrokCommand = "ngrok"
$siteUrl = "http://127.0.0.1/monitor/login.php"
$logPath = Join-Path $PSScriptRoot "ngrok.log"

function Test-WebSite {
    try {
        $response = Invoke-WebRequest -Uri $siteUrl -UseBasicParsing -TimeoutSec 3
        return $response.StatusCode -ge 200 -and $response.StatusCode -lt 500
    } catch {
        return $false
    }
}

if (-not (Test-WebSite)) {
    $xamppStart = Join-Path $xamppRoot "xampp_start.exe"
    if (-not (Test-Path $xamppStart)) {
        throw "Could not find $xamppStart. Start Apache and MySQL in XAMPP, or update xamppRoot."
    }

    Start-Process -FilePath $xamppStart
    $deadline = (Get-Date).AddSeconds(30)
    while ((Get-Date) -lt $deadline -and -not (Test-WebSite)) {
        Start-Sleep -Seconds 1
    }
}

if (-not (Test-WebSite)) {
    throw "The monitor site did not become available at $siteUrl."
}

if (Get-Process -Name "ngrok" -ErrorAction SilentlyContinue) {
    Write-Host "ngrok is already running."
    exit 0
}

Start-Process -FilePath $ngrokCommand `
    -ArgumentList "http 80 --log `"$logPath`"" `
    -WorkingDirectory $PSScriptRoot

Write-Host "Monitor started. Local URL: $siteUrl"
Write-Host "ngrok log: $logPath"