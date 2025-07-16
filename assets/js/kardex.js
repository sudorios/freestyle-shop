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

// Utilidad para manejo de filtros de fechas en Kardex
// (puedes expandir aquí si necesitas lógica JS para el filtro de fechas)
