
<?php include __DIR__ . '/../layouts/main.php'; ?>
<?php require_once __DIR__ . '/../../Helpers/stripe_keys.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card shadow-lg" style="border:2px solid #81d4fa;background:linear-gradient(135deg,#fffde7 0%,#b2ebf2 100%);">
      <div class="card-body">
        <h3 class="mb-4 text-center" style="color:#388e3c;font-weight:700;">
          <i class="bi bi-credit-card"></i> Finaliza tu compra peluda
        </h3>
        <p class="text-center mb-4" style="color:#607d8b;">Ingresa tus datos y paga seguro para consentir a tu mascota.<br><span style="font-size:1.2em;">🐾</span></p>
        <form id="checkout-form" method="post" action="index.php?page=checkout">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input class="form-control" name="name" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <input class="form-control" name="email" type="email" required>
              </div>
              <div class="mb-3">
                <label class="form-label">RFC (opcional para CFDI)</label>
                <input class="form-control" name="rfc" placeholder="XAXX010101000">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Tarjeta de crédito/débito</label>
              <div class="mb-2" id="card-visual" style="width:100%;max-width:340px;margin:auto;">
                <div style="background:linear-gradient(135deg,#b2ebf2 60%,#388e3c 100%);border-radius:16px;padding:18px 22px;box-shadow:0 2px 8px #0001;min-height:120px;position:relative;transition:box-shadow .2s;">
                  <div style="position:absolute;top:18px;right:22px;font-size:2em;color:#fff;"><i class="bi bi-credit-card"></i></div>
                  <div style="color:#fff;font-size:1.3em;letter-spacing:2px;font-family:monospace;min-height:28px;" id="card-num-preview">0000 0000 0000 0000</div>
                  <div class="d-flex justify-content-between mt-2" style="color:#fff;font-size:.95em;">
                    <div>Válida hasta <span id="card-exp-preview">MM/AA</span></div>
                    <div>CVV <span id="card-cvv-preview">***</span></div>
                  </div>
                </div>
              </div>
              <div class="input-group mt-3">
                <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                <input type="text" class="form-control" name="fake_card" id="fake_card" maxlength="19" placeholder="0000 0000 0000 0000" required pattern="[0-9 ]{15,19}">
              </div>
              <div class="row g-2 mt-2">
                <div class="col-6">
                  <input type="text" class="form-control" name="fake_exp" id="fake_exp" maxlength="5" placeholder="MM/AA" required pattern="\d{2}/\d{2}">
                </div>
                <div class="col-6">
                  <input type="text" class="form-control" name="fake_cvv" id="fake_cvv" maxlength="4" placeholder="CVV" required pattern="\d{3,4}">
                </div>
              </div>
              <div id="card-errors" class="text-danger mt-2"></div>
            </div>
          </div>
          <input type="hidden" name="stripeToken" id="stripeToken" value="tok_simulado">
          <button class="btn mt-4 w-100" style="background:#388e3c;color:#fff;font-size:1.2em;">
            <i class="bi bi-shield-check"></i> Pagar y consentir a mi mascota
          </button>
          </form>
                </div>
              </div>
            </div>
          </div>
          <script>
          // Simulación de validación de tarjeta (solo formato, no Stripe)
          function luhnCheck(val) {
            let sum = 0;
            let shouldDouble = false;
            for (let i = val.length - 1; i >= 0; i--) {
              let digit = parseInt(val.charAt(i));
              if (shouldDouble) {
                if ((digit *= 2) > 9) digit -= 9;
              }
              sum += digit;
              shouldDouble = !shouldDouble;
            }
            return (sum % 10) === 0;
          }
          function isValidExp(exp) {
            if (!/^\d{2}\/\d{2}$/.test(exp)) return false;
            let [mm, yy] = exp.split('/').map(Number);
            if (mm < 1 || mm > 12) return false;
            let now = new Date();
            let year = now.getFullYear() % 100;
            let month = now.getMonth() + 1;
            if (yy < year || (yy === year && mm < month)) return false;
            return true;
          }
          document.getElementById('checkout-form').addEventListener('submit', function(e) {
            var cardInput = document.getElementById('fake_card');
            var cardVal = cardInput.value.replace(/\s+/g, '');
            var expInput = document.getElementById('fake_exp');
            var expVal = expInput.value.trim();
            var cvvInput = document.getElementById('fake_cvv');
            var cvvVal = cvvInput.value.trim();
            var errorDiv = document.getElementById('card-errors');
            if (!/^\d{15,19}$/.test(cardVal) || !luhnCheck(cardVal)) {
              errorDiv.textContent = 'Ingresa un número de tarjeta válido.';
              cardInput.focus();
              e.preventDefault();
              return false;
            }
            if (!isValidExp(expVal)) {
              errorDiv.textContent = 'Fecha de expiración inválida.';
              expInput.focus();
              e.preventDefault();
              return false;
            }
            if (!/^\d{3,4}$/.test(cvvVal)) {
              errorDiv.textContent = 'CVV inválido.';
              cvvInput.focus();
              e.preventDefault();
              return false;
            }
            errorDiv.textContent = '';
          });
          // Formato automático de tarjeta y animación
          document.getElementById('fake_card').addEventListener('input', function(e) {
            let val = this.value.replace(/\D/g, '').slice(0, 16);
            this.value = val.replace(/(.{4})/g, '$1 ').trim();
            document.getElementById('card-num-preview').textContent = this.value.padEnd(19, '0');
          });
          document.getElementById('fake_exp').addEventListener('input', function(e) {
            let val = this.value.replace(/\D/g, '').slice(0, 4);
            if (val.length > 2) val = val.slice(0,2) + '/' + val.slice(2);
            this.value = val;
            document.getElementById('card-exp-preview').textContent = val.padEnd(5, 'M');
          });
          document.getElementById('fake_cvv').addEventListener('input', function(e) {
            let val = this.value.replace(/\D/g, '').slice(0, 4);
            this.value = val;
            document.getElementById('card-cvv-preview').textContent = val.padEnd(3, '*');
          });
          </script>
          </div>
          </body>
          </html>
