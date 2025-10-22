<?php
// Compute base URL relative to current script (e.g., /punto_de_venta_front/frontend/public)
$baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
if ($baseUrl === '/' || $baseUrl === '\\') { $baseUrl = ''; }
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Clinivet - Tienda para mascotas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --primary:#0b8f8f; /* teal */
      --primary-dark:#086f6f;
      --accent:#ffd166; /* warm accent */
      --muted:#7b8b8b;
      --card-bg: #ffffff;
      --page-bg: #f6fbfb;
    }
    html,body{height:100%;}
    body { font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; background:var(--page-bg); color:#133; }
    .topbar{ background:linear-gradient(90deg,var(--primary) 0%, #2db5b0 100%); box-shadow:0 4px 14px rgba(11,143,143,0.12); }
    .topbar .container{ display:flex; align-items:center; gap:16px; padding:14px 18px; }
    .brand { color:#fff; font-weight:700; font-size:22px; display:flex; align-items:center; gap:10px; }
    .brand .logo-badge{ background:rgba(255,255,255,0.12); padding:8px; border-radius:10px; display:flex; align-items:center; justify-content:center; }
    .search-bar{ flex:1; max-width:920px; }
    .search-bar .form-control, .search-bar .form-select{ border-radius:10px; }
    .nav-actions{ display:flex; align-items:center; gap:8px; }
    .btn-primary, .btn-success{ background:var(--primary); border:none; color:#fff; border-radius:8px; }
    .btn-primary:hover, .btn-success:hover{ background:var(--primary-dark); }
    .btn-outline-primary{ color:var(--primary); border-color:rgba(11,143,143,0.12); }
    .cart-badge{ background: #fff; color:var(--primary); padding:4px 9px; border-radius:12px; font-weight:600; }
    .content-wrap{ padding:28px 0; }
    .card{ border-radius:12px; box-shadow:0 6px 18px rgba(22,46,46,0.06); }
    .product-card .price{ color:var(--primary); font-weight:700; }
    footer.site-footer{ margin-top:48px; padding:28px 0; background:linear-gradient(180deg, rgba(11,143,143,0.04), transparent); color:var(--muted); }
    .hero-note{ background:linear-gradient(90deg, rgba(11,143,143,0.06), rgba(45,181,176,0.04)); border-left:4px solid var(--primary); padding:12px 16px; border-radius:8px; color:var(--muted); }
    @media(max-width:767px){ .search-bar{ max-width:100%; } .topbar .container{ flex-direction:column; align-items:stretch; gap:10px; } }
  </style>
</head>
<body>
<div class="topbar">
  <div class="container">
    <div class="brand">
      <div class="logo-badge"><i class="bi bi-heart-pulse-fill" style="font-size:20px;color:#fff"></i></div>
      Clinivet <small style="opacity:0.9;font-weight:600;margin-left:6px">Tienda y servicios</small>
    </div>
    <div class="search-bar">
      <form method="get" action="index.php" class="d-flex">
        <input type="hidden" name="page" value="products">
        <input class="form-control me-2" type="search" name="q" placeholder="Buscar productos, p.ej. alimento para perros" aria-label="Buscar" value="<?=htmlspecialchars($_GET['q'] ?? '')?>">
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
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
      </form>
    </div>
    <div class="nav-actions ms-auto">
      <?php if (!empty($_SESSION['user'])): ?>
        <div class="d-flex align-items-center gap-2">
          <span style="color:#fff; opacity:0.95; font-weight:600;"><i class="bi bi-person-circle" style="margin-right:6px"></i><?=htmlspecialchars($_SESSION['user']['name'])?></span>
          <a href="index.php?page=invoices" class="btn btn-outline-primary btn-sm"> <i class="bi bi-receipt"></i> Mis facturas</a>
          <a href="index.php?page=logout" class="btn btn-danger btn-sm">Salir</a>
          <?php if (!empty($_SESSION['user']['is_admin'])): ?>
            <a href="index.php?page=admin_products" class="btn btn-warning btn-sm"><i class="bi bi-gear"></i> Admin</a>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <a href="index.php?page=login" class="btn btn-outline-primary btn-sm"><i class="bi bi-box-arrow-in-right"></i> Iniciar sesión</a>
      <?php endif; ?>
      <a href="index.php?page=cart" class="btn btn-success ms-2">
        <i class="bi bi-cart" style="margin-right:6px"></i>
        <span class="d-none d-sm-inline">Carrito</span>
        <span class="cart-badge ms-2"><?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']):0 ?></span>
      </a>
    </div>
  </div>
</div>
<div class="container content-wrap">
  <!-- page content will be injected by views below this file -->
  
</div> <!-- /.container content-wrap -->

<!-- Minimal footer removed per request. Keep Bootstrap JS for components -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




















