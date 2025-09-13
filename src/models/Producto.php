<?php
// Producto.php - modelo simplificado
class Producto {
    public static function all() {
        // Datos de ejemplo: cambia por consultas a BD si lo deseas
        return [
            ['id'=>1,'nombre'=>'Monitor 24"','descripcion'=>'Monitor Full HD 24 pulgadas','precio'=>2999.00,'stock'=>10,'imagen'=>'assets/img/monitor.jpg'],
            ['id'=>2,'nombre'=>'Teclado mecánico','descripcion'=>'RGB, switches azules','precio'=>1299.00,'stock'=>15,'imagen'=>'assets/img/teclado.jpg'],
            ['id'=>3,'nombre'=>'Mouse gaming','descripcion'=>'Sensor 16000 DPI','precio'=>799.00,'stock'=>20,'imagen'=>'assets/img/mouse.jpg'],
        ];
    }

    public static function find($id) {
        foreach(self::all() as $p) {
            if($p['id']==$id) return $p;
        }
        return null;
    }
}
