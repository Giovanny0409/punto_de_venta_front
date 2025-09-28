<?php include __DIR__ . '/../layouts/main.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow" style="border:2px solid #6ec6ff;background:linear-gradient(135deg,#ffe082 0%,#b2dfdb 100%);">
      <div class="card-body">
        <h3 class="mb-3 text-center" style="color:#388e3c;"><i class="bi bi-person-plus"></i> Crear cuenta</h3>
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><?=$error?></div>
        <?php endif; ?>
        <form method="post" action="index.php?page=register<?=isset($_GET['redirect']) ? '&redirect='.urlencode($_GET['redirect']) : ''?>">
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">RFC (opcional)</label>
            <input type="text" name="rfc" class="form-control" placeholder="XAXX010101000">
          </div>
          <button class="btn" style="background:#388e3c;color:#fff;width:100%;"><i class="bi bi-paw"></i> Registrarme</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</body>
</html>