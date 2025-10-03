<?php include __DIR__ . '/../layouts/main.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card p-4">
      <h4>Registro</h4>
      <?php if (!empty($error)): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input class="form-control" name="name" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input class="form-control" name="email" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Contraseña</label>
          <input class="form-control" type="password" name="password" required>
        </div>
        <div class="mb-3">
          <label class="form-label">RFC (opcional)</label>
          <input class="form-control" name="rfc">
        </div>
        <button class="btn btn-success">Crear cuenta</button>
      </form>
    </div>
  </div>
</div>
