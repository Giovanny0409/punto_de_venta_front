<?php
// public/index.php
// Página principal: lista de productos y botón para ver el carrito.
require_once __DIR__ . '/../app/bootstrap.php';

$productos = ProductoController::lista();
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tienda - Carrito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="index.php">Mi Tienda</a>
        <div class="d-flex gap-2">
          <a class="btn btn-outline-light" href="email.php">Enviar factura</a>
          <a class="btn btn-outline-light" href="pos.php">POS</a>
          <button class="btn btn-outline-light position-relative" id="btnVerCarrito">
            Carrito <span class="badge bg-danger" id="contadorCarrito">0</span>
          </button>
        </div>
      </div>
    </nav>

    <main class="container my-4">
      <div class="row" id="productosGrid">
        <?php foreach($productos as $p): ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100">
            <img src="<?= htmlspecialchars($p['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['nombre']) ?>">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($p['nombre']) ?></h5>
              <p class="card-text small"><?= htmlspecialchars($p['descripcion']) ?></p>
              <div class="mt-auto">
                <p class="fw-bold">$
                  <?= number_format($p['precio'],2) ?></p>
                <button class="btn btn-primary w-100 btn-agregar" data-id="<?= $p['id'] ?>">Agregar</button>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </main>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCarrito">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">Tu carrito</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body" id="carritoBody"></div>
      <div class="p-3 border-top">
        <a href="checkout.php" class="btn btn-success w-100">Ir a Checkout</a>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
  </body>
  </html>
