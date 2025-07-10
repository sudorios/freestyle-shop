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
