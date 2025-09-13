<?php
require_once __DIR__ . '/../models/Producto.php';
class ProductoController {
    public static function lista() {
        return Producto::all();
    }
}
