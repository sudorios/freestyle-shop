const filasPorPagina = 10;
let paginaActual = 1;
let ordenAscendente = true;

function generarReferenciaAleatoria() {
  return Math.floor(10000000 + Math.random() * 90000000).toString();
}

function abrirModalAgregarProducto() {
  document.getElementById("modalAgregarProducto").classList.remove("hidden");
  document.getElementById("modalBackgroundAgregarProducto").classList.remove("hidden");
  const refInput = document.getElementById("agregar_ref_producto");
  if (refInput) {
    refInput.value = generarReferenciaAleatoria();
  }
}
function cerrarModalAgregarProducto() {
  document.getElementById("modalAgregarProducto").classList.add("hidden");
  document.getElementById("modalBackgroundAgregarProducto").classList.add("hidden");
}

function abrirModal() {
  document.getElementById("modalEditarProducto").classList.remove("hidden");
  document.getElementById("bg-editarProducto").classList.remove("hidden");
}
function cerrarModal() {
  document.getElementById("modalEditarProducto").classList.add("hidden");
  document.getElementById("bg-editarProducto").classList.add("hidden");
}

function initEditarProducto() {
  document.querySelectorAll(".btn-editar").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.dataset.id;
      const ref = this.dataset.ref;
      const nombre = this.dataset.nombre;
      const subcategoria = this.dataset.subcategoria;
      const talla = this.dataset.talla;

      document.getElementById("editar_id_producto").value = id;
      document.getElementById("editar_ref_producto").value = ref;
      document.getElementById("editar_nombre_producto").value = nombre;
      document.getElementById("editar_id_subcategoria").value = subcategoria;
      document.getElementById("editar_talla_producto").value = talla;

      abrirModal();
    });
  });
}

function mostrarCodigoBarrasProducto(ref) {
  document.getElementById("modalCodigoBarras").classList.remove("hidden");
  document.getElementById("modalBackground").classList.remove("hidden");
  JsBarcode("#barcode", ref, {
    format: "CODE128",
    lineColor: "#000",
    width: 2,
    height: 80,
    displayValue: true,
  });
  document.getElementById("descargarBarcode").onclick = function () {
    var svg = document.getElementById("barcode");
    var serializer = new XMLSerializer();
    var source = serializer.serializeToString(svg);
    var printWindow = window.open('', '_blank');
    printWindow.document.write('<html><head><title>Imprimir Código de Barras</title></head><body style="display:flex;justify-content:center;align-items:center;height:100vh;">' +
      '<div>' + source + '</div>' +
      '<script>window.onload = function() { window.print(); }<\/script>' +
      '</body></html>');
    printWindow.document.close();
  };
}

function cerrarModalCodigoBarras() {
  document.getElementById("modalCodigoBarras").classList.add("hidden");
  document.getElementById("modalBackground").classList.add("hidden");
  document.getElementById("barcode").innerHTML = "";
}

function abrirModalImagenesProducto(idProducto) {
  document.getElementById("modalImagenesProducto").classList.remove("hidden");
  document
    .getElementById("modalBackgroundImagenesProducto")
    .classList.remove("hidden");
  document.getElementById("listaImagenesProducto").innerHTML =
    '<p class="text-gray-500">Cargando imágenes...</p>';
  document.getElementById("formNuevaImagenProducto").classList.add("hidden");
  document.getElementById("btnMostrarFormImagen").classList.add("hidden");
  fetch(
    "views/productos/obtener_imagenes_producto.php?id_producto=" + idProducto
  )
    .then((response) => response.text())
    .then((html) => {
      document.getElementById("listaImagenesProducto").innerHTML = html;
      document.getElementById("idProductoImagenForm").value = idProducto;
      if (html.includes("No hay imágenes para este producto")) {
        document
          .getElementById("formNuevaImagenProducto")
          .classList.remove("hidden");
        document.getElementById("btnMostrarFormImagen").classList.add("hidden");
      } else {
        document
          .getElementById("formNuevaImagenProducto")
          .classList.add("hidden");
        document
          .getElementById("btnMostrarFormImagen")
          .classList.remove("hidden");
      }
    })
    .catch(() => {
      document.getElementById("listaImagenesProducto").innerHTML =
        '<p class="text-red-500">Error al cargar las imágenes.</p>';
      document
        .getElementById("formNuevaImagenProducto")
        .classList.add("hidden");
      document.getElementById("btnMostrarFormImagen").classList.add("hidden");
    });
}

function cerrarModalImagenesProducto() {
  document.getElementById("modalImagenesProducto").classList.add("hidden");
  document
    .getElementById("modalBackgroundImagenesProducto")
    .classList.add("hidden");
}


function initOrdenarProducto() {
    document.getElementById('thOrdenar').addEventListener('click', function() {
        const tbody = document.querySelector('tbody');
        const filas = Array.from(tbody.querySelectorAll('tr'));
        filas.sort((a, b) => {
            const idA = parseInt(a.children[0].textContent.trim());
            const idB = parseInt(b.children[0].textContent.trim());
            return ordenAscendente ? idA - idB : idB - idA;
        });
        filas.forEach(tr => tbody.appendChild(tr));
        ordenAscendente = !ordenAscendente;
        document.getElementById('iconoOrden').textContent = ordenAscendente ? '↑' : '↓';
        paginaActualSub = 1;
        mostrarPaginaSubcategoria(paginaActualSub);
    });
}


document.addEventListener("DOMContentLoaded", function () {
  initEditarProducto();
  initOrdenarProducto();
  const catAgregar = document.getElementById('id_categoria');
  if (catAgregar) {
    catAgregar.addEventListener('change', function() {
      cargarSubcategoriasPorCategoria(this.value, 'id_subcategoria');
    });
  }

  const catEditar = document.getElementById('editar_id_categoria');
  if (catEditar) {
    catEditar.addEventListener('change', function() {
      cargarSubcategoriasPorCategoria(this.value, 'editar_id_subcategoria');
    });
  }
});


