<?php
// app/Models/Carrito.php
// Manejo de carrito usando sesión del lado del servidor.

class Carrito
{
    public static function init(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
    }

    public static function add(int $id, int $cantidad = 1): int
    {
        self::init();
        $cantidad = max(1, $cantidad);
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id] += $cantidad;
        } else {
            $_SESSION['carrito'][$id] = $cantidad;
        }
        return self::totalItems();
    }

    public static function remove(int $id): void
    {
        self::init();
        unset($_SESSION['carrito'][$id]);
    }

    public static function update(int $id, int $cantidad): void
    {
        self::init();
        $cantidad = max(0, $cantidad);
        if ($cantidad === 0) unset($_SESSION['carrito'][$id]);
        else $_SESSION['carrito'][$id] = $cantidad;
    }

    public static function items(): array
    {
        self::init();
        return $_SESSION['carrito'];
    }

    public static function totalItems(): int
    {
        self::init();
        return (int)array_sum($_SESSION['carrito']);
    }
}
