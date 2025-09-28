<?php include __DIR__ . '/../layouts/main.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow" style="border:2px solid #ffb347;background:#fffbe6;">
      <div class="card-body">
        <h3 class="mb-3 text-center" style="color:#ff9800;"><i class="bi bi-person-circle"></i> Iniciar sesión</h3>
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><?=$error?></div>
        <?php endif; ?>
        <form method="post" action="index.php?page=login<?=isset($_GET['redirect']) ? '&redirect='.urlencode($_GET['redirect']) : ''?>">
          <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button class="btn" style="background:#ff9800;color:#fff;width:100%;">Entrar</button>
        </form>
        <div class="mt-3 text-center">
          <a href="index.php?page=register<?=isset($_GET['redirect']) ? '&redirect='.urlencode($_GET['redirect']) : ''?>" style="color:#388e3c;text-decoration:underline;font-weight:500;">
            <i class="bi bi-paw"></i> ¿No tienes cuenta? Regístrate
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</body>
</html>
