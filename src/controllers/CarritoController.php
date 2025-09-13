<?php
require_once __DIR__ . '/../models/Carrito.php';
require_once __DIR__ . '/../models/Producto.php';

class CarritoController {
    public static function handle() {
        session_start();
        $action = $_GET['action'] ?? '';
        switch($action) {
            case 'add':
                $id = $_GET['id'] ?? null;
                $id = (int)$id;
                if(!$id) {
                    echo json_encode(['ok'=>false, 'msg'=>'ID inválido']);
                    return;
                }
                $producto = Producto::find($id);
                if(!$producto) {
                    echo json_encode(['ok'=>false, 'msg'=>'Producto no existe']);
                    return;
                }
                $totalItems = Carrito::add($id);
                echo json_encode(['ok'=>true, 'totalItems'=>$totalItems]);
                return;
            case 'list':
                $items = Carrito::items();
                $out = [];
                foreach($items as $id=>$qty) {
                    $p = Producto::find($id);
                    if($p) $out[] = ['producto'=>$p,'cantidad'=>$qty];
                }
                echo json_encode(['ok'=>true,'items'=>$out,'totalItems'=>Carrito::totalItems()]);
                return;
            case 'update':
                $id = $_REQUEST['id'] ?? 0; $cantidad = $_REQUEST['cantidad'] ?? 0;
                Carrito::update($id,$cantidad);
                echo json_encode(['ok'=>true,'totalItems'=>Carrito::totalItems()]);
                return;
            case 'remove':
                $id = $_REQUEST['id'] ?? 0;
                Carrito::remove($id);
                echo json_encode(['ok'=>true,'totalItems'=>Carrito::totalItems()]);
                return;
            default:
                echo json_encode(['ok'=>false]);
                return;
        }
    }
}
