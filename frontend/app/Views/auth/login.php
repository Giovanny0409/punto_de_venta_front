<?php include __DIR__ . '/../layouts/main.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card p-4">
      <h4>Iniciar sesión</h4>
      <?php if (!empty($error)): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input class="form-control" name="email" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Contraseña</label>
          <input class="form-control" type="password" name="password" required>
        </div>
        <button class="btn btn-primary">Entrar</button>
      </form>
      <div class="mt-3">
        <?php
          $redirect = isset($_GET['redirect']) ? urlencode($_GET['redirect']) : '';
          $regUrl = 'index.php?page=register' . ($redirect ? '&redirect=' . $redirect : '');
        ?>
        <small>¿No tienes cuenta? <a href="<?=htmlspecialchars($regUrl)?>">Regístrate</a></small>
      </div>
    </div>
  </div>
</div>
