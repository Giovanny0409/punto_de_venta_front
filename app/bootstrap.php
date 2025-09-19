<?php
// bootstrap.php
// Punto único de arranque para la aplicación.
// - Define rutas base
// - Registra un autoloader simple para Models, Controllers y Services
// - Expone una función config() para cargar configuraciones

// Ruta base del proyecto (carpeta punto_de_venta_linux)
define('BASE_PATH', dirname(__DIR__));

// Autoload básico por carpetas conocidas
spl_autoload_register(function ($class) {
    $locations = [
        BASE_PATH . '/app/Models/' . $class . '.php',
        BASE_PATH . '/app/Controllers/' . $class . '.php',
        BASE_PATH . '/app/Services/' . $class . '.php',
    ];
    foreach ($locations as $file) {
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

// Cargador de configuraciones con caché en memoria del proceso
function config(string $name)
{
    static $cache = [];
    if (isset($cache[$name])) return $cache[$name];
    $path = BASE_PATH . '/config/' . $name . '.php';
    if (!is_file($path)) return null;
    $cache[$name] = require $path;
    return $cache[$name];
}
