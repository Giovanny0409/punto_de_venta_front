// assets/js/app.js
// Comportamiento del carrito en el frontend (AJAX hacia public/carrito.php)

document.addEventListener('DOMContentLoaded', () => {
  const updateCounter = (n) => {
    const el = document.getElementById('contadorCarrito');
    if (el) el.textContent = n;
  };

  // Botones "Agregar"
  document.querySelectorAll('.btn-agregar').forEach((btn) => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      fetch('carrito.php?action=add&id=' + encodeURIComponent(id))
        .then((r) => r.json())
        .then((data) => {
          if (data.ok) updateCounter(data.totalItems);
          else alert('Error al agregar');
        });
    });
  });

  // Ver carrito (offcanvas)
  const offcanvasEl = document.getElementById('offcanvasCarrito');
  if (!offcanvasEl) return;
  const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
  document.getElementById('btnVerCarrito').addEventListener('click', () => {
    fetch('carrito.php?action=list')
      .then((r) => r.json())
      .then((data) => {
        if (!data.ok) return;
        updateCounter(data.totalItems);
        const body = document.getElementById('carritoBody');
        body.innerHTML = '';
        if (data.items.length === 0) body.innerHTML = '<div class="text-center">Carrito vacío</div>';
        data.items.forEach((it) => {
          const p = it.producto;
          const div = document.createElement('div');
          div.className = 'd-flex align-items-center mb-3';
          div.innerHTML = `
            <img src="${p.imagen}" style="width:64px;height:48px;object-fit:cover;margin-right:10px;border-radius:4px;">
            <div class="flex-grow-1">
              <strong>${p.nombre}</strong><br><small>$${Number(p.precio).toFixed(2)}</small>
            </div>
            <div class="text-end">
              <input type="number" min="1" value="${it.cantidad}" data-id="${p.id}" class="form-control form-control-sm cantidadInput" style="width:80px;margin-bottom:6px;">
              <button class="btn btn-sm btn-danger btn-remover" data-id="${p.id}">Eliminar</button>
            </div>
          `;
          body.appendChild(div);
        });
        // Eventos para actualizar/eliminar
        body.querySelectorAll('.cantidadInput').forEach((inp) => {
          inp.addEventListener('change', () => {
            const id = inp.dataset.id;
            const qty = inp.value;
            fetch('carrito.php?action=update&id=' + id + '&cantidad=' + qty)
              .then((r) => r.json())
              .then((d) => {
                if (d.ok) updateCounter(d.totalItems);
              });
          });
        });
        body.querySelectorAll('.btn-remover').forEach((b) => {
          b.addEventListener('click', () => {
            const id = b.dataset.id;
            fetch('carrito.php?action=remove&id=' + id)
              .then((r) => r.json())
              .then((d) => {
                if (d.ok) {
                  updateCounter(d.totalItems);
                  b.closest('div.d-flex').remove();
                }
              });
          });
        });
        offcanvas.show();
      });
  });
});
