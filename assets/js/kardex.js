const filasPorPagina = 10;
let paginaActual = 1;

function initTablaKardex() {
  document
    .getElementById("buscadorKardex")
    .addEventListener("input", function () {
      paginaActual = 1;
      mostrarPaginaTabla(
        "tbody",
        "buscadorKardex",
        filasPorPagina,
        paginaActual,
        "paginacionKardex"
      );
    });
  mostrarPaginaTabla(
    "tbody",
    "buscadorKardex",
    filasPorPagina,
    paginaActual,
    "paginacionKardex"
  );
}

document.addEventListener("DOMContentLoaded", function () {
    initTablaKardex();
});


