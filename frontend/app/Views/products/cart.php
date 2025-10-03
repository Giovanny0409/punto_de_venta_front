<?php include __DIR__ . '/../layouts/main.php'; ?>
<div class="row justify-content-center">
  <div class="col-lg-10 col-md-12">
    <div class="card shadow-sm" style="border-radius:12px;">
      <div class="card-body">
        <h3 class="mb-4 text-center"><i class="bi bi-cart4"></i> Carrito de compras</h3>
        <?php if (empty($items)): ?>
          <div class="alert alert-info">Tu carrito está vacío.</div>
          <a href="index.php" class="btn btn-secondary mt-3">Volver al catálogo</a>
        <?php else: ?>
        <div class="table-responsive">
          <table class="table align-middle" style="background:rgba(255,255,255,0.95);border-radius:12px;overflow:hidden;">
            <thead style="background:rgba(11,143,143,0.06);">
              <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php $sum = 0; foreach($items as $item): $sum += $item['price'] * $item['quantity']; ?>
              <tr data-id="<?=$item['id']?>" style="vertical-align:middle;">
                <td>
                  <div class="d-flex align-items-center">
                    <?php if (!empty($item['image'])): ?>
                      <img src="<?=htmlspecialchars($item['image'])?>" alt="" style="width:56px;height:56px;object-fit:cover;border-radius:10px;margin-right:12px;border:2px solid rgba(11,143,143,0.12);">
                    <?php else: ?>
                      <img src="https://cdn-icons-png.flaticon.com/128/616/616408.png" alt="" style="width:60px;height:60px;object-fit:cover;border-radius:12px;margin-right:12px;border:2px solid #ffe082;background:#fffbe6;">
                    <?php endif; ?>
                    <span style="font-weight:500;color:#388e3c;">
                      <?=htmlspecialchars($item['name'])?>
                    </span>
                  </div>
                </td>
                <td style="color:var(--primary);font-weight:600;">$<?=number_format($item['price'],2)?></td>
                <td>
                  <button class="btn btn-sm btn-outline-primary btn-cart-dec" data-id="<?=$item['id']?>"><i class="bi bi-dash"></i></button>
                  <span class="cart-qty mx-2" style="font-size:1.1em;"><?=$item['quantity']?></span>
                  <button class="btn btn-sm btn-outline-primary btn-cart-inc" data-id="<?=$item['id']?>"><i class="bi bi-plus"></i></button>
                </td>
                <td style="color:var(--primary);font-weight:600;">$<?=number_format($item['price'] * $item['quantity'],2)?></td>
                <td>
                  <button class="btn btn-sm btn-danger btn-cart-del" data-id="<?=$item['id']?>"><i class="bi bi-trash"></i></button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="3" class="text-end" style="color:#388e3c;font-size:1.1em;">Total</th>
                <th style="color:#ff9800;font-size:1.2em;">$<?=number_format($sum,2)?></th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="d-flex justify-content-between mt-4">
          <a href="index.php" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Volver al catálogo</a>
          <a href="index.php?page=checkout" class="btn btn-primary"><i class="bi bi-credit-card"></i> Proceder a pago</a>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<script>
function updateCartRow(id, qty, cartCount) {
  let row = document.querySelector('tr[data-id="'+id+'"]');
  if (!row) return;
  if (qty <= 0) {
    row.remove();
  } else {
    row.querySelector('.cart-qty').textContent = qty;
  }
  document.querySelectorAll('.cart-count').forEach(e => e.textContent = cartCount);
  if (document.querySelectorAll('tbody tr[data-id]').length === 0) {
    location.reload();
  } else {
    location.reload(); // Para actualizar totales
  }
}
document.querySelectorAll('.btn-cart-inc').forEach(btn => {
  btn.onclick = function() {
    let id = this.dataset.id;
    fetch('index.php?page=update_cart&id='+id+'&action=inc', {headers:{'X-Requested-With':'XMLHttpRequest'}})
      .then(r=>r.json()).then(data=>{ if(data.ok) updateCartRow(id, data.qty, data.cart_count); });
  }
});
document.querySelectorAll('.btn-cart-dec').forEach(btn => {
  btn.onclick = function() {
    let id = this.dataset.id;
    fetch('index.php?page=update_cart&id='+id+'&action=dec', {headers:{'X-Requested-With':'XMLHttpRequest'}})
      .then(r=>r.json()).then(data=>{ if(data.ok) updateCartRow(id, data.qty, data.cart_count); });
  }
});
document.querySelectorAll('.btn-cart-del').forEach(btn => {
  btn.onclick = function() {
    let id = this.dataset.id;
    fetch('index.php?page=update_cart&id='+id+'&action=del', {headers:{'X-Requested-With':'XMLHttpRequest'}})
      .then(r=>r.json()).then(data=>{ if(data.ok) updateCartRow(id, 0, data.cart_count); });
  }
});
</script>
</body>
</html>
