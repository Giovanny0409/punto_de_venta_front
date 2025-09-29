<?php include __DIR__ . '/layouts/main.php'; ?>
<div class="row">
  <div class="col-md-3">
    <h5 class="mb-3">Categorías</h5>
    <div class="list-group category-list">
      <?php foreach($cats as $c): ?>
        <a class="list-group-item list-group-item-action" href="index.php?page=category&id=<?=$c['id']?>"><?=htmlspecialchars($c['name'])?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="col-md-9">
    <h4 class="mb-4">Productos destacados</h4>
    <div class="row g-3">
      <?php foreach($products as $p): ?>
        <div class="col-md-3">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($p['image'])): ?>
              <img src="<?=htmlspecialchars($p['image'])?>" class="card-img-top" alt="<?=htmlspecialchars($p['name'])?>" style="height:160px;object-fit:cover;">
            <?php else: ?>
              <img src="https://via.placeholder.com/300x160?text=Producto" class="card-img-top" alt="Sin imagen" style="height:160px;object-fit:cover;">
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h6 class="card-title"><?=htmlspecialchars($p['name'])?></h6>
              <p class="card-text mb-1"><small class="text-muted"><?=htmlspecialchars($p['category_name'])?></small></p>
              <p class="mb-2"><strong>$<?=number_format($p['price'],2)?></strong></p>
              <button class="btn btn-primary btn-sm btn-add-cart mt-auto" data-id="<?=$p['id']?>">Agregar</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.btn-add-cart').forEach(btn => {
  btn.onclick = function() {
    let id = this.dataset.id;
    fetch('index.php?page=add_to_cart&id='+id, {headers:{'X-Requested-With':'XMLHttpRequest'}})
      .then(r=>r.json())
      .then(data=>{
        if(data.ok) {
          document.querySelectorAll('.cart-count').forEach(e => e.textContent = data.cart_count);
          this.textContent = "Agregado";
          setTimeout(()=>{this.textContent="Agregar"}, 900);
        }
      });
  }
});
</script>
</body>
</html>
