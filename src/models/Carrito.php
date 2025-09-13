<?php
// Carrito.php - manejo simple de carrito en sesión
class Carrito {
    public static function init() {
        if(session_status() !== PHP_SESSION_ACTIVE) session_start();
        if(!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
    }

    public static function add($id, $cantidad=1) {
        self::init();
        $id = (int)$id;
        $cantidad = max(1,(int)$cantidad);
        if(isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id] += $cantidad;
        } else {
            $_SESSION['carrito'][$id] = $cantidad;
        }
        return self::totalItems();
    }

    public static function remove($id) {
        self::init();
        $id = (int)$id;
        unset($_SESSION['carrito'][$id]);
    }

    public static function update($id, $cantidad) {
        self::init();
        $id = (int)$id;
        $cantidad = max(0,(int)$cantidad);
        if($cantidad === 0) unset($_SESSION['carrito'][$id]);
        else $_SESSION['carrito'][$id] = $cantidad;
    }

    public static function items() {
        self::init();
        return $_SESSION['carrito'];
    }

    public static function totalItems() {
        self::init();
        return array_sum($_SESSION['carrito']);
    }
}
