// app.js - comportamiento básico del carrito (fetch AJAX)
document.addEventListener('DOMContentLoaded', ()=>{
  const updateCounter = (n)=>{
    document.getElementById('contadorCarrito').textContent = n;
  };

  // Add buttons
  document.querySelectorAll('.btn-agregar').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const id = btn.dataset.id;
      fetch('carrito.php?action=add&id='+encodeURIComponent(id))
        .then(r=>r.json())
        .then(data=>{
          if(data.ok) updateCounter(data.totalItems);
          else alert('Error al agregar');
        });
    });
  });

  // Ver carrito button
  const offcanvasEl = document.getElementById('offcanvasCarrito');
  const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
  document.getElementById('btnVerCarrito').addEventListener('click', ()=>{
    // load items
    fetch('carrito.php?action=list')
      .then(r=>r.json())
      .then(data=>{
        if(!data.ok) return;
        updateCounter(data.totalItems);
        const body = document.getElementById('carritoBody');
        body.innerHTML = '';
        if(data.items.length === 0) body.innerHTML = '<div class="text-center">Carrito vacío</div>';
        data.items.forEach(it=>{
          const p = it.producto;
          const div = document.createElement('div');
          div.className = 'carrito-item mb-3 d-flex align-items-center p-2 border rounded shadow-sm';
          div.innerHTML = `
            <img src="${p.imagen}" class="carrito-img me-3" alt="${p.nombre}">
            <div class="flex-grow-1">
              <strong class="carrito-nombre">${p.nombre}</strong><br>
              <span class="carrito-precio">$${p.precio.toFixed(2)}</span>
            </div>
            <div class="text-end ms-3">
              <input type="number" min="1" value="${it.cantidad}" data-id="${p.id}" class="form-control form-control-sm cantidadInput mb-2" style="width:80px;">
              <button class="btn btn-sm btn-danger btn-remover w-100" data-id="${p.id}">Eliminar</button>
            </div>
          `;
          body.appendChild(div);
        });
        // attach events
        body.querySelectorAll('.cantidadInput').forEach(inp=>{
          inp.addEventListener('change', ()=>{
            const id = inp.dataset.id; const qty = inp.value;
            fetch('carrito.php?action=update&id='+id+'&cantidad='+qty).then(r=>r.json()).then(d=>{ if(d.ok) updateCounter(d.totalItems); });
          });
        });
        body.querySelectorAll('.btn-remover').forEach(b=>{
          b.addEventListener('click', ()=>{
            const id = b.dataset.id;
            fetch('carrito.php?action=remove&id='+id).then(r=>r.json()).then(d=>{ if(d.ok){ updateCounter(d.totalItems); b.closest('div.d-flex').remove(); } });
          });
        });
        offcanvas.show();
      });
  });
});
