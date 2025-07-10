// Función global para actualizar el contador del carrito en el header por AJAX
function actualizarContadorCarritoAjax() {
    fetch('carrito_contador.php')
      .then(res => res.json())
      .then(data => {
        const badge = document.getElementById('carrito-contador');
        if (badge) {
          badge.textContent = data.total;
          badge.style.display = data.total > 0 ? 'inline-block' : 'none';
        }
      });
} 