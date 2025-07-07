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

function abrirModalAgregarIngreso() {
    document.getElementById('modal_agregar_ingreso').classList.remove('hidden');
    document.getElementById('modalBackground').classList.remove('hidden');
}

function cerrarModalAgregarIngreso() {
    document.getElementById('modal_agregar_ingreso').classList.add('hidden');
    document.getElementById('modalBackground').classList.add('hidden');
}

function mostrarCostos(data) {
    document.getElementById('costos_ref').textContent = data.ref;
    document.getElementById('costos_producto').textContent = data.nombre_producto;
    document.getElementById('costos_sucursal').textContent = data.nombre_sucursal;
    document.getElementById('costos_cantidad').textContent = data.cantidad;
    document.getElementById('costos_usuario').textContent = data.usuario;
    document.getElementById('costos_precio_costo_igv').textContent = Number(data.precio_costo_igv).toFixed(2);
    document.getElementById('costos_precio_venta').textContent = Number(data.precio_venta).toFixed(2);
    document.getElementById('costos_utilidad_esperada').textContent = Number(data.utilidad_esperada).toFixed(2);
    document.getElementById('costos_utilidad_neta').textContent = Number(data.utilidad_neta).toFixed(2);
    document.getElementById('modal_costos_ingreso').classList.remove('hidden');
    document.getElementById('modalBackgroundCostos').classList.remove('hidden');
}

function cerrarModalCostos() {
    document.getElementById('modal_costos_ingreso').classList.add('hidden');
    document.getElementById('modalBackgroundCostos').classList.add('hidden');
}

function abrirModalEditarIngreso() {
    document.getElementById('modalEditarIngreso').classList.remove('hidden');
    document.getElementById('bg-editarIngreso').classList.remove('hidden');
}
function cerrarModalEditarIngreso() {
    document.getElementById('modalEditarIngreso').classList.add('hidden');
    document.getElementById('bg-editarIngreso').classList.add('hidden');
}

function abrirModalEditarIngresoDesdeTabla(data) {
    document.getElementById('editar_id_ingreso').value = data.id;
    document.getElementById('editar_ref_ingreso').value = data.ref;
    document.getElementById('editar_producto_ingreso').value = data.nombre_producto;
    document.getElementById('editar_sucursal_ingreso').value = data.nombre_sucursal;
    document.getElementById('editar_fecha_ingreso').value = data.fecha_ingreso;
    document.getElementById('editar_cantidad_ingreso').value = data.cantidad;
    document.getElementById('editar_precio_costo_igv').value = data.precio_costo_igv;
    document.getElementById('editar_precio_venta').value = data.precio_venta;
    abrirModalEditarIngreso();
}
document.addEventListener("DOMContentLoaded", function () {
    initTablaIngreso();
    window.abrirModalAgregarIngreso = abrirModalAgregarIngreso;
    window.cerrarModalAgregarIngreso = cerrarModalAgregarIngreso;
    window.mostrarCostos = mostrarCostos;
    window.cerrarModalCostos = cerrarModalCostos;
    window.abrirModalEditarIngreso = abrirModalEditarIngreso;
    window.cerrarModalEditarIngreso = cerrarModalEditarIngreso;
    window.abrirModalEditarIngresoDesdeTabla = abrirModalEditarIngresoDesdeTabla;
});