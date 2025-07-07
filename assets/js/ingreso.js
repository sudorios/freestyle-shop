const filasPorPagina = 10;
let paginaActual = 1;

function initTablaIngreso() {
  document
    .getElementById("buscadorIngreso")
    .addEventListener("input", function () {
      paginaActual = 1;
      mostrarPaginaTabla(
        "tbody",
        "buscadorIngreso",
        filasPorPagina,
        paginaActual,
        "paginacionIngreso"
      );
    });
  mostrarPaginaTabla(
    "tbody",
    "buscadorIngreso",
    filasPorPagina,
    paginaActual,
    "paginacionIngreso"
  );
}

document.addEventListener("DOMContentLoaded", function () {
    initTablaIngreso();
});
