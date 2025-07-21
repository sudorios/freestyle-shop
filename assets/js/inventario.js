const filasPorPagina = 10;
let paginaActual = 1;

function initTablaProducto() {
  document
    .getElementById("buscadorInventario")
    .addEventListener("input", function () {
      paginaActual = 1;
      mostrarPaginaTabla(
        "tbody",
        "buscadorInventario",
        filasPorPagina,
        paginaActual,
        "paginacionInventario"
      );
    });
  mostrarPaginaTabla(
    "tbody",
    "buscadorInventario",
    filasPorPagina,
    paginaActual,
    "paginacionInventario"
  );
}

document.addEventListener("DOMContentLoaded", function () {
  initTablaProducto();
});
