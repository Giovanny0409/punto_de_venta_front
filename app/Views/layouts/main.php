<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MiTienda - Demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background: #fffbe6; }
    .topbar { background: #ffb347; padding: 10px 0; box-shadow: 0 2px 4px rgba(0,0,0,0.08); }
    .logo { font-weight:700; color:#388e3c; font-size:24px; margin-left:10px; }
    .search-bar { max-width:800px; margin:0 auto; }
    .category-list a { text-decoration:none; color:#333; }
    .navbar-user { margin-left: 20px; }
    .btn-primary, .btn-success { background:#388e3c; border:none; }
    .btn-primary:hover, .btn-success:hover { background:#2e7031; }
    .btn-outline-dark { border-color:#ff9800; color:#ff9800; }
    .btn-outline-dark:hover { background:#ff9800; color:#fff; }
    .btn-warning { background:#ff9800; border:none; }
    .btn-warning:hover { background:#e68900; }
  </style>
</head>
<body>
<div class="topbar">
  <div class="container d-flex align-items-center">
    <div class="logo"><i class="bi bi-shop"></i> MiTienda</div>
    <div class="flex-grow-1 search-bar">
      <form method="get" action="index.php" class="d-flex">
        <input type="hidden" name="page" value="products">
        <input class="form-control me-2" type="search" name="q" placeholder="Buscar productos, p.ej. comida para gatos" aria-label="Buscar" value="<?=htmlspecialchars($_GET['q'] ?? '')?>">
        <select name="cat" class="form-select me-2" style="max-width:180px">
          <option value="">Todas las categorías</option>
          <?php
            $catsLocal = (isset($cats)) ? $cats : [];
            foreach($catsLocal as $c) {
              $sel = (isset($_GET['cat']) && $_GET['cat']==$c['id']) ? 'selected' : '';
              echo "<option value=\"{$c['id']}\" $sel>" . htmlspecialchars($c['name']) . "</option>";
            }
          ?>
        </select>
        <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i> Buscar</button>
      </form>
    </div>
    <div class="ms-3 navbar-user">
      <?php if (!empty($_SESSION['user'])): ?>
        <span class="me-2"><i class="bi bi-person-circle"></i> <?=htmlspecialchars($_SESSION['user']['name'])?></span>
        <a href="index.php?page=invoices" class="btn btn-outline-primary btn-sm ms-2"><i class="bi bi-receipt"></i> Mis facturas</a>
        <a href="index.php?page=logout" class="btn btn-outline-secondary btn-sm ms-2">Salir</a>
        <?php if (!empty($_SESSION['user']['is_admin'])): ?>
          <a href="index.php?page=admin" class="btn btn-warning btn-sm ms-2"><i class="bi bi-gear"></i> Admin</a>
        <?php endif; ?>
      <?php else: ?>
        <a href="index.php?page=login" class="btn btn-outline-primary btn-sm"><i class="bi bi-box-arrow-in-right"></i> Iniciar sesión</a>
      <?php endif; ?>
      <a href="index.php?page=cart" class="btn btn-outline-dark ms-2">
        <i class="bi bi-cart"></i> Carrito (<span class="cart-count"><?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?></span>)
      </a>
    </div>
  </div>
</div>
<div class="container mt-4">
