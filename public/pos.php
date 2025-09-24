<?php
// public/pos.php
// Página POS que integra la lógica/UI de nomina.php dentro del proyecto existente.
require_once __DIR__ . '/../app/bootstrap.php';

// Inicializamos variables
$productos = [];
$total = 0;
$nombre = '';
$correo = '';

// Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'generar') {
  $nombre   = isset($_POST['nombre']) ? (is_array($_POST['nombre']) ? implode(', ', $_POST['nombre']) : (string)$_POST['nombre']) : '';
  $correo   = isset($_POST['correo']) ? (is_array($_POST['correo']) ? implode(', ', $_POST['correo']) : (string)$_POST['correo']) : '';
  $producto = isset($_POST['producto']) ? (is_array($_POST['producto']) ? implode(', ', $_POST['producto']) : (string)$_POST['producto']) : '';
  $cantidad = isset($_POST['cantidad']) ? (is_array($_POST['cantidad']) ? (int)$_POST['cantidad'][0] : (int)$_POST['cantidad']) : 0;
  $precio   = isset($_POST['precio']) ? (is_array($_POST['precio']) ? (float)$_POST['precio'][0] : (float)$_POST['precio']) : 0;

  $subtotal = $cantidad * $precio;
  $productos[] = [
    'producto' => $producto,
    'cantidad' => $cantidad,
    'precio'   => $precio,
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
  <title>Punto de Venta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
  <script>
    function imprimirTicket() {
      let contenido = document.getElementById('ticket').innerHTML;
      let ventana = window.open('', 'Imprimir Ticket', 'width=400,height=600');
      ventana.document.write(`
                <html>
                <head>
                    <title>Ticket</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { font-family: Arial, sans-serif; font-size: 14px; padding: 10px; }
                        h4 { text-align: center; margin-bottom: 10px; }
                        .table { font-size: 13px; }
                        .totales { text-align: right; margin-top: 10px; }
                    </style>
                </head>
                <body>
                    ${contenido}
                    <script>window.print();<\/script>
                </body>
                </html>
            `);
      ventana.document.close();
    }
  </script>
  </head>
  <body class="bg-light">
  <div class="container mt-4">
    <div class="card shadow-lg">
      <div class="card-header bg-primary text-white text-center">
        <h2 class="mb-0">Sistema de Punto de Venta</h2>
      </div>
      <div class="card-body">
        <!-- Formulario de venta -->
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
              <input type="number" name="cantidad" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Precio</label>
              <input type="number" step="0.01" name="precio" class="form-control" required>
            </div>
          </div>
          <button type="submit" class="btn btn-success w-100">💾 Generar Ticket</button>
        </form>

        <!-- Ticket generado -->
        <?php if (!empty($productos)) : ?>
        <div class="card border-dark" id="ticket">
          <div class="card-body">
            <h4 class="text-center text-primary">🧾 Ticket de Venta</h4>
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
                  <td><?= $item['cantidad'] ?></td>
                  <td>$<?= number_format($item['precio'], 2) ?></td>
                  <td>$<?= number_format($item['subtotal'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

            <h5 class="text-end text-danger">TOTAL: $<?= number_format($total, 2) ?></h5>
          </div>
        </div>

        <!-- Botones de acción -->
        <div class="d-flex gap-2 mt-3">
          <button class="btn btn-danger flex-fill" onclick="imprimirTicket()">📄 Imprimir / Guardar PDF</button>
          <a href="pos.php" class="btn btn-secondary flex-fill">🗑️ Eliminar Ticket</a>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  </body>
  </html>
