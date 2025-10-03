# Launches the PHP built-in server for local testing
param(
  [int]$Port = 8080
)

$pub = Join-Path $PSScriptRoot '..' | Join-Path -ChildPath 'public'
if (!(Test-Path $pub)) { Write-Error "No se encontró la carpeta 'public'"; exit 1 }

$php = 'c:\xampp\php\php.exe'
if (!(Test-Path $php)) { Write-Error "No se encontró PHP en $php. Ajusta la ruta si es necesario."; exit 1 }

Write-Host "Iniciando servidor PHP en http://localhost:$Port/ (raíz: $pub)"
Push-Location $pub
& $php -S "localhost:$Port"
Pop-Location
