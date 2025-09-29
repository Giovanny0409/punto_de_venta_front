<?php include __DIR__ . '/../layouts/main.php'; ?>
<h3 class="mb-4"><i class="bi bi-receipt"></i> Mis facturas electrónicas</h3>
<?php
$pdo = \App\Helpers\DB::get();
$userId = $_SESSION['user']['id'] ?? null;
if (!$userId) {
  echo '<div class="alert alert-warning">Debes iniciar sesión para ver tus facturas.</div>';
  exit;
}
$stmt = $pdo->prepare("SELECT i.id, i.uuid, i.xml_path, i.timbre_status, i.created_at, o.total FROM invoices i JOIN orders o ON i.order_id = o.id WHERE o.user_id = ? ORDER BY i.id DESC");
$stmt->execute([$userId]);
$facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$facturas) {
  echo '<div class="alert alert-info">Aún no tienes facturas generadas.</div>';
} else {
  echo '<div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>UUID</th><th>Fecha</th><th>Total</th><th>Estado</th><th>Descargar XML</th></tr></thead><tbody>';
  foreach ($facturas as $f) {
    $xmlUrl = ltrim(str_replace(['\\', $_SERVER['DOCUMENT_ROOT']], ['/', ''], $f['xml_path']), '/');
    $pdfBtn = '';
    if (!empty($f['pdf_path']) && file_exists($f['pdf_path'])) {
      $pdfUrl = ltrim(str_replace(['\\', $_SERVER['DOCUMENT_ROOT']], ['/', ''], $f['pdf_path']), '/');
      $pdfBtn = '<a href="/' . $pdfUrl . '" class="btn btn-sm btn-success" download><i class="bi bi-file-earmark-pdf"></i> PDF</a>';
    }
    echo '<tr>';
    echo '<td style="font-size:.95em">' . htmlspecialchars($f['uuid']) . '</td>';
    echo '<td>' . htmlspecialchars($f['created_at']) . '</td>';
    echo '<td>$' . number_format($f['total'],2) . '</td>';
    echo '<td>' . htmlspecialchars($f['timbre_status']) . '</td>';
  // No mostrar PDF
    echo '<td><a href="/' . $xmlUrl . '" class="btn btn-sm btn-warning" download><i class="bi bi-download"></i> XML</a></td>';
    echo '</tr>';
  }
  echo '</tbody></table></div>';
}
?>
<a class="btn btn-primary mt-3" href="index.php">Volver al catálogo</a>
</div>
</body>
</html>
