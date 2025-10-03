<?php include __DIR__ . '/../layouts/main.php'; ?>
<style>
  /* Local styles for checkout form */
  #card-element { border: none; }
  .card .form-control, .card .form-select { border-radius:8px; }
  /* Visual payment card tweaks */
  .payment-card { border-radius:12px; overflow:hidden; }
  .payment-card .card { background:linear-gradient(135deg,#0b8f8f 0%, #2db5b0 100%); }
  @media(max-width:575px){ .payment-card .card { padding:12px!important } }
</style>
<?php
// Load stripe keys from backend helpers (compute project-root relative path)
$stripePath = dirname(__DIR__, 4) . '/backend/app/Helpers/stripe_keys.php';
if (file_exists($stripePath)) {
  require_once $stripePath;
} else {
  // Log or show a friendly message in dev
  error_log("stripe_keys.php not found at: $stripePath");
  // Avoid fatal error: define empty constants so view doesn't crash
  if (!defined('STRIPE_SECRET_KEY')) define('STRIPE_SECRET_KEY', '');
  if (!defined('STRIPE_PUBLISHABLE_KEY')) define('STRIPE_PUBLISHABLE_KEY', '');
}
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card p-4">
      <h4 class="mb-3">Finalizar compra</h4>
      <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger"><?=htmlspecialchars($_SESSION['flash_error'])?></div>
        <?php unset($_SESSION['flash_error']); ?>
      <?php endif; ?>
      <form method="post" id="checkoutForm">
        <!-- Tarjeta visual compacta al inicio -->
        <div class="mb-2">
          <div class="card p-2" style="background:linear-gradient(135deg,#0b8f8f 0%, #2db5b0 100%); color:#fff; border-radius:10px; min-height:110px; max-width:340px; margin:auto; box-shadow:0 2px 8px rgba(11,143,143,0.10);">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div style="font-weight:700;letter-spacing:0.6px;font-size:1.1em;">Clinivet</div>
              <div id="card-brand" style="font-size:18px;font-weight:700">&nbsp;</div>
            </div>
            <div class="card p-1 mb-1" style="background:rgba(255,255,255,0.10); border-radius:8px;">
              <div id="card-element" style="min-height:32px; padding:4px; color:#fff;"></div>
            </div>
            <div class="d-flex justify-content-between" style="font-size:13px;opacity:0.92;">
              <div><small>Nombre</small> <span><?=htmlspecialchars($_SESSION['user']['name'] ?? '')?></span></div>
              <div><small>Expira</small> <span>MM/AA</span></div>
            </div>
            <div id="card-errors" role="alert" class="form-text text-warning mt-1" style="margin-bottom:2px;"></div>
          </div>
        </div>
        <!-- Datos del cliente -->
        <div class="mb-2">
          <label class="form-label">Nombre</label>
          <input class="form-control" name="name" value="<?=htmlspecialchars($_SESSION['user']['name'] ?? '')?>" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Email</label>
          <input class="form-control" name="email" value="<?=htmlspecialchars($_SESSION['user']['email'] ?? '')?>" required>
        </div>
        <div class="mb-2">
          <label class="form-label">RFC (opcional)</label>
          <input class="form-control" name="rfc" value="<?=htmlspecialchars($_SESSION['user']['rfc'] ?? '')?>">
        </div>
        <input type="hidden" name="payment_method" value="card">
        <input type="hidden" id="stripeToken" name="stripeToken" value="">

        <div class="d-flex align-items-center mt-3">
          <button class="btn btn-primary" id="payBtn" type="submit">
            <span id="payBtnText">Pagar</span>
            <span id="paySpinner" class="spinner-border spinner-border-sm ms-2" role="status" style="display:none" aria-hidden="true"></span>
          </button>
          <span id="payLoader" class="ms-3 text-muted" style="display:none">Procesando pago...</span>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
  // Attempt to expose publishable key if available
  $pk = '';
  $stripeKeysFile = dirname(__DIR__, 4) . '/backend/app/Helpers/stripe_keys.php';
  if (file_exists($stripeKeysFile)) {
    include_once $stripeKeysFile;
    if (defined('STRIPE_PUBLISHABLE_KEY')) $pk = STRIPE_PUBLISHABLE_KEY;
  }
?>
<?php if ($pk): ?>
  <script src="https://js.stripe.com/v3/"></script>
  <script>
    var stripe = Stripe('<?=htmlspecialchars($pk)?>');
    var elements = stripe.elements();
    var style = { base: { fontSize: '16px', color: '#32325d' } };
    var card = elements.create('card', { style: style });
    card.mount('#card-element');
    card.on('change', function(event) {
      var displayError = document.getElementById('card-errors');
      displayError.textContent = event.error ? event.error.message : '';
      // Card brand detection
      var brandEl = document.getElementById('card-brand');
      if (event.brand) {
        var b = event.brand;
        var icon = '';
        if (b === 'visa') icon = '<img src="https://cdn.jsdelivr.net/gh/edent/SuperTinyIcons/images/svg/visa.svg" style="height:20px;filter:invert(1);">';
        else if (b === 'mastercard') icon = '<img src="https://cdn.jsdelivr.net/gh/edent/SuperTinyIcons/images/svg/mastercard.svg" style="height:20px;filter:invert(1);">';
        else if (b === 'amex') icon = '<span style="font-weight:700">AMEX</span>';
        else icon = b.toUpperCase();
        brandEl.innerHTML = icon;
      } else {
        brandEl.innerHTML = '&nbsp;';
      }
    });
    var form = document.getElementById('checkoutForm');
    form.addEventListener('submit', function(ev) {
      ev.preventDefault();
      document.getElementById('payBtn').disabled = true;
      document.getElementById('paySpinner').style.display = 'inline-block';
      document.getElementById('payLoader').style.display = 'inline-block';
      stripe.createToken(card).then(function(result) {
        if (result.error || !result.token) {
          document.getElementById('card-errors').textContent = result.error ? result.error.message : 'No se pudo generar el token de pago. Verifica los datos de la tarjeta.';
          document.getElementById('payBtn').disabled = false;
          document.getElementById('paySpinner').style.display = 'none';
          document.getElementById('payLoader').style.display = 'none';
        } else {
          document.getElementById('stripeToken').value = result.token.id;
          // Solo envía si el token existe
          form.submit();
        }
      });
    });
  </script>
<?php else: ?>
  <div class="alert alert-warning">STRIPE_PUBLISHABLE_KEY no está configurada. Para aceptar pagos reales con tarjeta configura STRIPE_PUBLISHABLE_KEY y STRIPE_SECRET_KEY en el servidor o crea un archivo <code>backend/.env</code> con estas claves.</div>
<?php endif; ?>
