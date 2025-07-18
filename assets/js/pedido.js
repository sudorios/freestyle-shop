const filasPorPagina = 10;
let paginaActual = 1;

function initTablaPedido() {
  document
    .getElementById("buscadorPedido")
    .addEventListener("input", function () {
      paginaActual = 1;
      mostrarPaginaTabla(
        "tbody",
        "buscadorPedido",
        filasPorPagina,
        paginaActual,
        "paginacionPedidos"
      );
    });
  mostrarPaginaTabla(
    "tbody",
    "buscadorPedido",
    filasPorPagina,
    paginaActual,
    "paginacionPedidos"
  );
}

document.addEventListener("DOMContentLoaded", function () {
    initTablaPedido();
});

  
let formParaCancelar = null;

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-cancelar-pedido').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = btn.closest('form');
            const action = form.action;
            const id = form.querySelector('input[name="id_pedido"]').value;
            abrirModalConfirmar({
                mensaje: '¿Seguro que deseas cancelar este pedido? Esta acción devolverá el stock.',
                action: action,
                id: id,
                idField: 'id_pedido'
            });
        });
    });
});
