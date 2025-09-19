<?php
// public/carrito.php
// Punto de entrada para acciones AJAX del carrito (JSON)
require_once __DIR__ . '/../app/bootstrap.php';

CarritoController::handle();
