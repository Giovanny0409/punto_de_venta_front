<?php
namespace App\Controllers;
use App\Helpers\DB;
class ProductController {
    public function home() {
        $pdo = DB::get();
        $cats = $pdo->query("SELECT * FROM categories")->fetchAll(\PDO::FETCH_ASSOC);
        $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id LIMIT 8");
        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        include __DIR__ . '/../Views/home.php';
    }
    public function list() {
        $pdo = DB::get();
        $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id");
        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $cats = $pdo->query("SELECT * FROM categories")->fetchAll(\PDO::FETCH_ASSOC);
        include __DIR__ . '/../Views/products/list.php';
    }
    public function listByCategory($categoryId) {
        $pdo = DB::get();
        $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.category_id = ?");
        $stmt->execute([$categoryId]);
        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $cats = $pdo->query("SELECT * FROM categories")->fetchAll(\PDO::FETCH_ASSOC);
        include __DIR__ . '/../Views/products/list.php';
    }
    public function search($term, $categoryId = null) {
        $pdo = DB::get();
        $sql = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE (p.name LIKE ? OR p.description LIKE ?)";
        $params = ["%$term%","%$term%"];
        if ($categoryId) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $cats = $pdo->query("SELECT * FROM categories")->fetchAll(\PDO::FETCH_ASSOC);
        include __DIR__ . '/../Views/products/list.php';
    }
    public function addToCart($id) {
        $pdo = DB::get();
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$product) {
            if ($this->isAjax()) {
                http_response_code(404);
                echo json_encode(['ok'=>false, 'msg'=>'Producto no encontrado']);
                exit;
            }
            header('Location: index.php');
            exit;
        }
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if (!isset($_SESSION['cart'][(int)$id])) $_SESSION['cart'][(int)$id] = 0;
        $_SESSION['cart'][(int)$id]++;
        if ($this->isAjax()) {
            echo json_encode([
                'ok'=>true,
                'cart_count'=>array_sum($_SESSION['cart']),
                'product_id'=>$id,
                'qty'=>$_SESSION['cart'][(int)$id]
            ]);
            exit;
        }
        // Si no es AJAX, no redirige, solo termina.
    }

    public function updateCart($id, $action) {
        if (!isset($_SESSION['cart'][(int)$id])) {
            echo json_encode(['ok'=>false, 'msg'=>'No existe']);
            exit;
        }
        if ($action === 'inc') {
            $_SESSION['cart'][(int)$id]++;
        } elseif ($action === 'dec') {
            $_SESSION['cart'][(int)$id]--;
            if ($_SESSION['cart'][(int)$id] < 1) unset($_SESSION['cart'][(int)$id]);
        } elseif ($action === 'del') {
            unset($_SESSION['cart'][(int)$id]);
        }
        echo json_encode([
            'ok'=>true,
            'cart_count'=>array_sum($_SESSION['cart']),
            'product_id'=>$id,
            'qty'=>$_SESSION['cart'][(int)$id] ?? 0
        ]);
        exit;
    }

    private function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    public function cart() {
        $cart = $_SESSION['cart'] ?? [];
        $items = [];
        if ($cart && count($cart) > 0) {
            $pdo = DB::get();
            $ids = array_keys($cart);
            $ids = array_filter($ids, 'is_numeric');
            if (count($ids) > 0) {
                $ids = array_map('intval', $ids);
                $in = implode(',', array_fill(0, count($ids), '?'));
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
                $stmt->execute($ids);
                $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($rows as $r) {
                    $r['quantity'] = $cart[$r['id']];
                    $items[] = $r;
                }
            }
        }
        include __DIR__ . '/../Views/products/cart.php';
    }
}
