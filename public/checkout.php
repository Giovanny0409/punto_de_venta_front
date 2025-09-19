<?php
// public/checkout.php
// Muestra el contenido del carrito y total.
require_once __DIR__ . '/../app/bootstrap.php';

Carrito::init();
$items = Carrito::items();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name='viewport' content='width=device-width,initial-scale=1'>
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h3>Checkout</h3>
  <?php if(empty($items)): ?>
    <div class="alert alert-info">Tu carrito está vacío.</div>
  <a href="index.php" class="btn btn-primary">Seguir comprando</a>
  <?php else: ?>
    <table class="table">
      <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio unitario</th><th>Subtotal</th></tr></thead>
      <tbody>
      <?php $total=0; foreach($items as $id=>$qty):
         $p = Producto::find((int)$id); if(!$p) continue;
         $sub = $p['precio']*$qty; $total += $sub;
      ?>
      <tr>
         <td><?= htmlspecialchars($p['nombre']) ?></td>
         <td><?= (int)$qty ?></td>
         <td>$<?= number_format($p['precio'],2) ?></td>
         <td>$<?= number_format($sub,2) ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <div class="d-flex justify-content-end">
      <h4>Total: $<?= number_format($total,2) ?></h4>
    </div>
    <div class="mt-3">
      <button class="btn btn-success">Confirmar compra (simulado)</button>
  <a href="index.php" class="btn btn-secondary">Seguir comprando</a>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
