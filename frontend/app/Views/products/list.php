<?php include __DIR__ . '/../layouts/main.php'; ?>
<div class="row">
  <div class="col-md-3">
    <h5>Categorías</h5>
    <div class="list-group category-list">
      <?php foreach($cats as $c): ?>
        <a class="list-group-item list-group-item-action" href="index.php?page=category&id=<?=$c['id']?>"><?=htmlspecialchars($c['name'])?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="col-md-9">
    <h4>Productos</h4>
    <?php if(empty($products)): ?>
      <div class="alert alert-info">No se encontraron productos.</div>
      <?php else: ?>
      <div class="row">
        <?php foreach($products as $p): ?>
          <div class="col-md-4 mb-3">
            <div class="card h-100 product-card">
              <?php if (!empty($p['image'])): ?>
                <img src="/<?=htmlspecialchars($p['image'])?>" class="card-img-top" alt="<?=htmlspecialchars($p['name'])?>" style="height:140px;object-fit:cover;">
              <?php else: ?>
                <img src="https://via.placeholder.com/300x140?text=Producto" class="card-img-top" alt="Sin imagen" style="height:140px;object-fit:cover;">
              <?php endif; ?>
              <div class="card-body" style="padding:14px">
                <h6 class="card-title mb-2"><?=htmlspecialchars($p['name'])?></h6>
                <p class="card-text small text-truncate" style="max-height:40px"><?=htmlspecialchars($p['description'])?></p>
                <p class="text-muted small mb-1"><?=htmlspecialchars($p['category_name'])?></p>
                <p class="mb-2"><strong class="price">$<?=number_format($p['price'],2)?></strong></p>
                <a class="btn btn-primary btn-sm" href="index.php?page=add_to_cart&id=<?=$p['id']?>">Agregar</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
