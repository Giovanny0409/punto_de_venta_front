<?php include __DIR__ . '/../layouts/main.php'; ?>
<div class="alert mt-4" style="background:#e3f2fd;border:2px solid #81d4fa;">
  <h4 class="alert-heading" style="color:#388e3c;"><i class="bi bi-check-circle"></i> ¡Tu pedido fue realizado con éxito!</h4>
  <p>¡Gracias por tu compra! Puedes descargar tu factura electrónica (XML timbrado) usando el botón de abajo.<br>
  También puedes consultar tus facturas y pedidos desde tu perfil.</p>
  <?php
  // Buscar el XML más reciente del usuario para mostrar el botón de descarga
  $lastXml = null;
  if (!empty($_SESSION['user']['id'])) {
    $uid = $_SESSION['user']['id'];
    $pdo = \App\Helpers\DB::get();
    $stmt = $pdo->prepare("SELECT i.xml_path FROM invoices i JOIN orders o ON i.order_id = o.id WHERE o.user_id = ? ORDER BY i.id DESC LIMIT 1");
    $stmt->execute([$uid]);
    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
    if ($row && file_exists($row['xml_path'])) {
      $lastXml = $row['xml_path'];
    }
  }
  if ($lastXml):
    $xmlUrl = str_replace($_SERVER['DOCUMENT_ROOT'], '', $lastXml);
    $xmlUrl = ltrim(str_replace('\\','/',$xmlUrl),'/');
  ?>
      <a href="/<?=$xmlUrl?>" class="btn" style="background:#ff9800;color:#fff;" download>
        <i class="bi bi-download"></i> Descargar factura XML
      </a>
  <?php endif; ?>
</div>
<a class="btn btn-primary mt-3" style="background:#388e3c;border:none;" href="index.php">Volver al catálogo</a>
</div>
</body>
</html>
