<?php
// app/Controllers/CarritoController.php
// Controlador REST sencillo para manejar acciones del carrito vía query params.

class CarritoController
{
    public static function handle(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $action = $_REQUEST['action'] ?? null;
        header('Content-Type: application/json; charset=utf-8');

        if ($action === 'add') {
            $id = (int)($_REQUEST['id'] ?? 0);
            $cantidad = (int)($_REQUEST['cantidad'] ?? 1);
            $prod = Producto::find($id);
            if (!$prod) {
                echo json_encode(['ok' => false, 'message' => 'Producto no existe']);
                return;
            }
            $total = Carrito::add($id, $cantidad);
            echo json_encode(['ok' => true, 'totalItems' => $total]);
            return;
        }

        if ($action === 'list') {
            $items = Carrito::items();
            $out = [];
            foreach ($items as $id => $qty) {
                $p = Producto::find((int)$id);
                if ($p) $out[] = ['producto' => $p, 'cantidad' => (int)$qty];
            }
            echo json_encode(['ok' => true, 'items' => $out, 'totalItems' => Carrito::totalItems()]);
            return;
        }

        if ($action === 'update') {
            $id = (int)($_REQUEST['id'] ?? 0);
            $cantidad = (int)($_REQUEST['cantidad'] ?? 0);
            Carrito::update($id, $cantidad);
            echo json_encode(['ok' => true, 'totalItems' => Carrito::totalItems()]);
            return;
        }

        if ($action === 'remove') {
            $id = (int)($_REQUEST['id'] ?? 0);
            Carrito::remove($id);
            echo json_encode(['ok' => true, 'totalItems' => Carrito::totalItems()]);
            return;
        }

        echo json_encode(['ok' => false, 'message' => 'Acción inválida']);
    }
}
