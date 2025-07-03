const filasPorPagina = 10;
let paginaActual = 1;

function cerrarModalConteo() {
  modalConteo.classList.add("hidden");
}

function verComentario(texto) {
  document.getElementById("comentarioTexto").textContent =
    texto || "Sin comentario";
  document.getElementById("modalComentario").classList.remove("hidden");
}

function cerrarModalComentario() {
  document.getElementById("modalComentario").classList.add("hidden");
}

function abrirModalEditarConteo() {
  document.getElementById("modalEditarConteo").classList.remove("hidden");
  document.getElementById("bg-editarConteo").classList.remove("hidden");
}
function cerrarModalEditarConteo() {
  document.getElementById("modalEditarConteo").classList.add("hidden");
  document.getElementById("bg-editarConteo").classList.add("hidden");
}

function initEditarConteo() {
  const buttons = document.querySelectorAll(".btn-editar");
  buttons.forEach((button) => {
    button.addEventListener("click", function () {
      const id = button.getAttribute("data-id");
      const real = button.getAttribute("data-real");
      const sistema = button.getAttribute("data-sistema");
      const usuario = button.getAttribute("data-usuario");
      const diferencia = button.getAttribute("data-diferencia");
      const fecha = button.getAttribute("data-fecha");
      const estado = button.getAttribute("data-estado");
      const producto = button.getAttribute("data-producto");
      const sucursal = button.getAttribute("data-sucursal");
      const comentarios = button.getAttribute("data-comentarios");

      if(document.getElementById("editar_id_conteo")) document.getElementById("editar_id_conteo").value = id;
      if(document.getElementById("editar_cantidad_real")) document.getElementById("editar_cantidad_real").value = real;
      if(document.getElementById("editar_cantidad_sistema")) document.getElementById("editar_cantidad_sistema").value = sistema;
      if(document.getElementById("editar_usuario_id")) document.getElementById("editar_usuario_id").value = usuario;
      if(document.getElementById("editar_diferencia")) document.getElementById("editar_diferencia").value = diferencia;
      if(document.getElementById("editar_estado_conteo")) document.getElementById("editar_estado_conteo").value = estado;
      if(document.getElementById("editar_fecha_conteo")) document.getElementById("editar_fecha_conteo").value = fecha;
      if(document.getElementById("editar_id_producto")) document.getElementById("editar_id_producto").value = producto;
      if(document.getElementById("editar_id_sucursal")) document.getElementById("editar_id_sucursal").value = sucursal;
      if(document.getElementById("editar_comentarios")) document.getElementById("editar_comentarios").value = comentarios || "";

      abrirModalEditarConteo();
    });
  });
}

function initTablaProducto() {
  document.getElementById('buscadorConteo').addEventListener('input', function() {
    paginaActual = 1;
    mostrarPaginaTabla('tbody', 'buscadorConteo', filasPorPagina, paginaActual, 'paginacionConteo');
  });
  mostrarPaginaTabla('tbody', 'buscadorConteo', filasPorPagina, paginaActual, 'paginacionConteo');
}

document.addEventListener("DOMContentLoaded", function () {
  initEditarConteo();
  initTablaProducto();
});

