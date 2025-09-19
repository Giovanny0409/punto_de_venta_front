<?php
// public/pos.php
// Página POS sencilla para generar ticket simulado (basado en nomina.php).
require_once __DIR__ . '/../app/bootstrap.php';

$productos = [];
$total = 0;
$nombre = '';
$correo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'generar') {
    $nombre = (string)($_POST['nombre'] ?? '');
    $correo = (string)($_POST['correo'] ?? '');
    $producto = (string)($_POST['producto'] ?? '');
    $cantidad = (int)($_POST['cantidad'] ?? 0);
    $precio = (float)($_POST['precio'] ?? 0);

    $subtotal = $cantidad * $precio;
    $productos[] = [
        'producto' => $producto,
        'cantidad' => $cantidad,
        'precio' => $precio,
        'subtotal' => $subtotal,
    ];
    $total = $subtotal;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POS - Punto de Venta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
  <script>
    function imprimirTicket() {
      const contenido = document.getElementById('ticket').innerHTML;
      const w = window.open('', 'Imprimir Ticket', 'width=400,height=600');
      w.document.write(`
      <html><head>
        <title>Ticket</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>body{font-family:Arial,sans-serif;font-size:14px;padding:10px}h4{text-align:center;margin-bottom:10px}.table{font-size:13px}.totales{text-align:right;margin-top:10px}</style>
      </head><body>
        ${contenido}
        <script>window.print();<\/script>
      </body></html>`);
      w.document.close();
    }
  </script>
  </head>
  <body class="bg-light">
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="mb-0">Sistema de Punto de Venta</h2>
      <div>
        <a href="index.php" class="btn btn-secondary">Tienda</a>
        <a href="email.php" class="btn btn-outline-primary">Enviar factura</a>
      </div>
    </div>
    <div class="card shadow-sm">
      <div class="card-body">
        <form method="POST" class="mb-4">
          <input type="hidden" name="accion" value="generar">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Nombre del Cliente</label>
              <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Correo</label>
              <input type="email" name="correo" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Producto</label>
              <input type="text" name="producto" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Cantidad</label>
              <input type="number" name="cantidad" class="form-control" min="1" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Precio</label>
              <input type="number" step="0.01" name="precio" class="form-control" min="0" required>
            </div>
          </div>
          <button type="submit" class="btn btn-success w-100">Generar Ticket</button>
        </form>

        <?php if (!empty($productos)) : ?>
        <div class="card border-dark" id="ticket">
          <div class="card-body">
            <h4 class="text-center text-primary">Ticket de Venta</h4>
            <p><strong>Cliente:</strong> <?= htmlspecialchars($nombre) ?></p>
            <p><strong>Correo:</strong> <?= htmlspecialchars($correo) ?></p>
            <table class="table table-striped table-bordered">
              <thead class="table-dark">
                <tr>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>Precio</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($productos as $item): ?>
                <tr>
                  <td><?= htmlspecialchars($item['producto']) ?></td>
                  <td><?= (int)$item['cantidad'] ?></td>
                  <td>$<?= number_format((float)$item['precio'], 2) ?></td>
                  <td>$<?= number_format((float)$item['subtotal'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <h5 class="text-end text-danger">TOTAL: $<?= number_format((float)$total, 2) ?></h5>
          </div>
        </div>
        <div class="d-flex gap-2 mt-3">
          <button class="btn btn-danger flex-fill" onclick="imprimirTicket()">Imprimir / Guardar PDF</button>
          <a href="pos.php" class="btn btn-secondary flex-fill">Eliminar Ticket</a>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  </body>
  </html>
