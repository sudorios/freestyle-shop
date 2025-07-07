const filasPorPagina = 10;
let paginaActual = 1;

function initTablaTransferencia() {
  document
    .getElementById("buscadorTransferencia")
    .addEventListener("input", function () {
      paginaActual = 1;
      mostrarPaginaTabla(
        "tbody",
        "buscadorTransferencia",
        filasPorPagina,
        paginaActual,
        "paginacionTransferencia"
      );
    });
  mostrarPaginaTabla(
    "tbody",
    "buscadorTransferencia",
    filasPorPagina,
    paginaActual,
    "paginacionTransferencia"
  );
}

document.addEventListener("DOMContentLoaded", function () {
    initTablaTransferencia();
});
