# watch-and-rebuild.ps1
# Monitora alterações no código e executa docker-compose build automaticamente

$path = Get-Location
Write-Host "Monitorando alterações em: $path"

$filter = '*.*'
$watcher = New-Object System.IO.FileSystemWatcher $path, $filter -Property @{ 
    IncludeSubdirectories = $true
    NotifyFilter = [System.IO.NotifyFilters]'FileName, LastWrite, DirectoryName'
}

$action = {
    Write-Host "Alteração detectada em $($Event.SourceEventArgs.FullPath). Rebuildando Docker..."
    docker-compose build
}

Register-ObjectEvent $watcher 'Changed' -Action $action | Out-Null
Register-ObjectEvent $watcher 'Created' -Action $action | Out-Null
Register-ObjectEvent $watcher 'Deleted' -Action $action | Out-Null
Register-ObjectEvent $watcher 'Renamed' -Action $action | Out-Null

Write-Host "Aguardando alterações. Pressione Ctrl+C para sair."
while ($true) { Start-Sleep -Seconds 1 }
