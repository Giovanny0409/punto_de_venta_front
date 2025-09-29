<?php
require_once __DIR__ . '/../app/Helpers/DB.php';
require_once __DIR__ . '/../app/Helpers/PACClient.php';
require_once __DIR__ . '/../app/Controllers/ProductController.php';
require_once __DIR__ . '/../app/Controllers/OrderController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
use App\Controllers\ProductController;
use App\Controllers\OrderController;
use App\Controllers\AuthController;
session_start();
$page = $_GET['page'] ?? 'home';
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$q = $_GET['q'] ?? null;
$cat = $_GET['cat'] ?? null;
$pc = new ProductController();
$oc = new OrderController();
$ac = new AuthController();

if ($page === 'register') {
    $ac->register();
} elseif ($page === 'login') {
    $ac->login();
} elseif ($page === 'logout') {
    $ac->logout();
} elseif ($page === 'invoices') {
    include __DIR__ . '/../app/Views/auth/invoices.php';
} elseif ($page === 'home') {
    $pc->home();
} elseif ($page === 'category' && $id) {
    $pc->listByCategory($id);
} elseif ($page === 'products') {
    if ($q) $pc->search($q, $cat ?: null);
    else $pc->list();
} elseif ($page === 'add_to_cart' && $id) {
    $pc->addToCart($id);
} elseif ($page === 'update_cart' && $id && isset($_GET['action'])) {
    $pc->updateCart($id, $_GET['action']);
} elseif ($page === 'cart') {
    $pc->cart();
} elseif ($page === 'edit_product' && $id) {
    include __DIR__ . '/../app/Views/products/edit.php';
} elseif ($page === 'checkout') {
    // Forzar login antes de checkout
    if (empty($_SESSION['user'])) {
        header('Location: index.php?page=login&redirect=checkout');
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $oc->placeOrder();
    } else {
        $oc->checkoutForm();
    }
} else {
    $pc->home();
}
