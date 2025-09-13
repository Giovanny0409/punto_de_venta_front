<?php
// Punto de entrada AJAX para carrito
require_once __DIR__ . '/../src/controllers/CarritoController.php';
require_once __DIR__ . '/../src/controllers/ProductoController.php';
// Autoloading simple
spl_autoload_register(function($c){
    $path = __DIR__ . '/../src/controllers/' . $c . '.php';
    if(file_exists($path)) require_once $path;
});
// Actually handle
CarritoController::handle();
