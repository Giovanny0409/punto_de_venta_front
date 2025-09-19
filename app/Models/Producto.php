<?php
// app/Models/Producto.php
// Modelo simple para exponer productos de ejemplo.
// En producción, reemplaza self::all() por consultas a base de datos usando PDO.

class Producto
{
    public static function all(): array
    {
        return [
            // Nota: rutas relativas a la carpeta public actual
            ['id' => 1, 'nombre' => 'Monitor 24"', 'descripcion' => 'Monitor Full HD 24 pulgadas', 'precio' => 2999.00, 'stock' => 10, 'imagen' => 'assets/img/monitor.jpg'],
            ['id' => 2, 'nombre' => 'Teclado mecánico', 'descripcion' => 'RGB, switches azules', 'precio' => 1299.00, 'stock' => 15, 'imagen' => 'assets/img/teclado.jpg'],
            ['id' => 3, 'nombre' => 'Mouse gaming', 'descripcion' => 'Sensor 16000 DPI', 'precio' => 799.00, 'stock' => 20, 'imagen' => 'assets/img/mouse.jpg'],
        ];
    }

    public static function find(int $id): ?array
    {
        foreach (self::all() as $p) {
            if ($p['id'] === $id) return $p;
        }
        return null;
    }
}
