<?php
// app/Controllers/ProductoController.php
// Controlador de productos: actualmente sólo expone la lista.

class ProductoController
{
    public static function lista(): array
    {
        return Producto::all();
    }
}
