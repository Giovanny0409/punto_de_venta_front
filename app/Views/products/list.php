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
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title"><?=htmlspecialchars($p['name'])?></h5>
                <p class="card-text"><?=htmlspecialchars($p['description'])?></p>
                <p class="text-muted small"><?=htmlspecialchars($p['category_name'])?></p>
                <p><strong>$<?=number_format($p['price'],2)?></strong></p>
                <a class="btn btn-primary" href="index.php?page=add_to_cart&id=<?=$p['id']?>">Agregar al carrito</a>
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
