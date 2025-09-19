<?php
// public/email.php
// Formulario para enviar factura por correo y manejo del POST.
require_once __DIR__ . '/../app/bootstrap.php';

$sent = null; $error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo  = $_POST['correo'] ?? '';
    $asunto  = $_POST['asunto'] ?? 'Factura de su compra';
    $mensaje = $_POST['mensaje'] ?? '';
    $archivoTmp = $_FILES['archivo']['tmp_name'] ?? null;
    $archivoNombre = $_FILES['archivo']['name'] ?? null;

    $ok = MailService::send($correo, $asunto, nl2br($mensaje), $archivoTmp, $archivoNombre);
    $sent = $ok; $error = $ok ? null : 'No se pudo enviar el correo.';
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Enviar Factura por Correo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card shadow-lg border-0 rounded-3">
          <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">📧 Envío de Factura</h4>
          </div>
          <div class="card-body p-4">
            <?php if($sent === true): ?>
              <div class="alert alert-success">✅ Factura enviada con éxito</div>
            <?php elseif($sent === false): ?>
              <div class="alert alert-danger">❌ Error al enviar: <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form action="" method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="correo" class="form-label">Correo del Cliente</label>
                <input type="email" class="form-control" id="correo" name="correo" placeholder="cliente@ejemplo.com" required>
              </div>
              <div class="mb-3">
                <label for="asunto" class="form-label">Asunto</label>
                <input type="text" class="form-control" id="asunto" name="asunto" value="Factura de su compra" required>
              </div>
              <div class="mb-3">
                <label for="mensaje" class="form-label">Mensaje</label>
                <textarea class="form-control" id="mensaje" name="mensaje" rows="4" placeholder="Estimado cliente, adjuntamos su factura."></textarea>
              </div>
              <div class="mb-3">
                <label for="archivo" class="form-label">Adjuntar Factura (PDF)</label>
                <input class="form-control" type="file" id="archivo" name="archivo" accept="application/pdf" required>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg">Enviar Factura</button>
              </div>
            </form>
          </div>
          <div class="card-footer text-muted text-center">
            <a href="index.php" class="btn btn-link">Volver a la tienda</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
