function abrirModal() {
  document.getElementById("modalEditarSucursal").classList.remove("hidden");
  document
    .getElementById("modalBackgroundEditarSucursal")
    .classList.remove("hidden");
}

function cerrarModal() {
  document.getElementById("modalEditarSucursal").classList.add("hidden");
  document
    .getElementById("modalBackgroundEditarSucursal")
    .classList.add("hidden");
}

function abrirModalAgregarSucursal() {
  document.getElementById("modal_agregar_sucursal").classList.remove("hidden");
  document.getElementById("modalBackground").classList.remove("hidden");
}

function cerrarModalAgregarSucursal() {
  document.getElementById("modal_agregar_sucursal").classList.add("hidden");
  document.getElementById("modalBackground").classList.add("hidden");
}

function initEditarSucursal() {
  document.querySelectorAll(".btn-editar").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.dataset.id;
      const nombre = this.dataset.nombre;
      const direccion = this.dataset.direccion;
      const tipo = this.dataset.tipo;
      const supervisor = this.dataset.supervisor;

      document.getElementById("edit_id_sucursal").value = id;
      document.getElementById("edit_nombre_sucursal").value = nombre;
      document.getElementById("edit_direccion_sucursal").value = direccion;
      document.getElementById("edit_tipo_sucursal").value = tipo;
      document.getElementById("edit_id_supervisor").value = supervisor;

      abrirModal();
    });
  });
}

document.addEventListener("DOMContentLoaded", initEditarSucursal);

function abrirModalDetalleSucursal(datos) {
  document.getElementById("detalleNombreSucursal").textContent = datos.nombre;
  document.getElementById("detalleDireccionSucursal").textContent =
    datos.direccion;
  document.getElementById("detalleTipoSucursal").textContent = datos.tipo;
  document.getElementById("detalleSupervisorNombre").textContent =
    datos.supervisorNombre;
  document.getElementById("detalleSupervisorTelefono").textContent =
    datos.supervisorTelefono;
  document.getElementById("detalleSupervisorEmail").textContent =
    datos.supervisorEmail;
  var estadoSpan = document.getElementById("detalleEstadoSucursalSpan");
  if (datos.estado === "Activa") {
    estadoSpan.textContent = "Activa";
    estadoSpan.className =
      "block text-center text-base font-bold py-2 rounded-lg bg-green-200 text-green-900";
  } else {
    estadoSpan.textContent = "Inactiva";
    estadoSpan.className =
      "block text-center text-base font-bold py-2 rounded-lg bg-red-200 text-red-900";
  }
  document.getElementById("modalDetalleSucursal").classList.remove("hidden");
  document.getElementById("modalBackground").classList.remove("hidden");
}

function cerrarModalDetalleSucursal() {
  document.getElementById("modalDetalleSucursal").classList.add("hidden");
  document.getElementById("modalBackground").classList.add("hidden");
}

let sucursalAEliminar = null;
function eliminarSucursal(id) {
  sucursalAEliminar = id;
  document.getElementById("inputPasswordConfirm").value = "";
  document.getElementById("errorPasswordConfirm").classList.add("hidden");
  document
    .getElementById("modalConfirmarDesactivacion")
    .classList.remove("hidden");
  document.getElementById("modalBackground").classList.remove("hidden");
}
function cerrarModalConfirmarDesactivacion() {
  sucursalAEliminar = null;
  document
    .getElementById("modalConfirmarDesactivacion")
    .classList.add("hidden");
  document.getElementById("modalBackground").classList.add("hidden");
}
function confirmarDesactivacionSucursal() {
  const password = document.getElementById("inputPasswordConfirm").value;
  if (!password) {
    mostrarErrorPassword("La contraseña es obligatoria");
    return;
  }
  fetch("index.php?controller=usuario&action=validarPasswordAjax", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "password=" + encodeURIComponent(password),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.valido) {
        document.getElementById("inputEliminarSucursalId").value =
          sucursalAEliminar;
        document.getElementById("formEliminarSucursal").submit();
      } else {
        mostrarErrorPassword("Contraseña incorrecta");
      }
    })
    .catch(() => mostrarErrorPassword("Error al validar la contraseña"));
}
function mostrarErrorPassword(msg) {
  const errorDiv = document.getElementById("errorPasswordConfirm");
  errorDiv.textContent = msg;
  errorDiv.classList.remove("hidden");
}

function delegarEventosSucursales() {
  const tbody = document.getElementById("tablaSucursalesBody");
  if (!tbody) return;
  tbody.addEventListener("click", function (e) {
    if (e.target.closest(".bg-red-500")) {
      const btn = e.target.closest("button");
      if (btn && btn.onclick == null) {
        const id = btn
          .getAttribute("onclick")
          .match(/eliminarSucursal\((\d+)\)/);
        if (id && id[1]) eliminarSucursal(parseInt(id[1]));
      }
    }
    if (e.target.closest(".btn-detalle")) {
      const btn = e.target.closest(".btn-detalle");
      const datos = {
        nombre: btn.getAttribute("data-nombre"),
        direccion: btn.getAttribute("data-direccion"),
        tipo: btn.getAttribute("data-tipo"),
        supervisorNombre: btn.getAttribute("data-supervisor-nombre"),
        supervisorTelefono: btn.getAttribute("data-supervisor-telefono"),
        supervisorEmail: btn.getAttribute("data-supervisor-email"),
        estado: btn.getAttribute("data-estado"),
      };
      abrirModalDetalleSucursal(datos);
    }
  });
}

const filasPorPaginaSuc = 10;
let paginaActualSuc = 1;

function mostrarPaginaSucursal(pagina) {
  const filas = Array.from(document.querySelectorAll('#tablaSucursalesBody .sucursal-row'));
  const filtro = document.getElementById('buscadorSucursal').value.toLowerCase();
  const filasFiltradas = filas.filter(tr => tr.textContent.toLowerCase().includes(filtro));
  filas.forEach(tr => tr.style.display = 'none');
  const inicio = (pagina - 1) * filasPorPaginaSuc;
  const fin = inicio + filasPorPaginaSuc;
  filasFiltradas.slice(inicio, fin).forEach(tr => tr.style.display = '');
  actualizarPaginacionSucursal(filasFiltradas.length, pagina);
}

function actualizarPaginacionSucursal(totalFilas, pagina) {
  const totalPaginas = Math.ceil(totalFilas / filasPorPaginaSuc) || 1;
  const paginacion = document.getElementById('paginacionSucursales');
  paginacion.innerHTML = '';
  if (totalPaginas <= 1) return;
  const btnPrev = document.createElement('button');
  btnPrev.textContent = 'Anterior';
  btnPrev.disabled = pagina === 1;
  btnPrev.className = 'px-3 py-1 rounded bg-gray-200 mx-1 disabled:opacity-50' + (btnPrev.disabled ? '' : ' cursor-pointer');
  btnPrev.onclick = () => { paginaActualSuc--; mostrarPaginaSucursal(paginaActualSuc); };
  paginacion.appendChild(btnPrev);
  for (let i = 1; i <= totalPaginas; i++) {
    const btn = document.createElement('button');
    btn.textContent = i;
    btn.className = 'px-3 py-1 rounded mx-1 ' + (i === pagina ? 'bg-blue-500 text-white' : 'bg-gray-200 cursor-pointer');
    btn.onclick = () => { paginaActualSuc = i; mostrarPaginaSucursal(paginaActualSuc); };
    paginacion.appendChild(btn);
  }
  const btnNext = document.createElement('button');
  btnNext.textContent = 'Siguiente';
  btnNext.disabled = pagina === totalPaginas;
  btnNext.className = 'px-3 py-1 rounded bg-gray-200 mx-1 disabled:opacity-50' + (btnNext.disabled ? '' : ' cursor-pointer');
  btnNext.onclick = () => { paginaActualSuc++; mostrarPaginaSucursal(paginaActualSuc); };
  paginacion.appendChild(btnNext);
}

function initBuscadorSucursal() {
  document.getElementById('buscadorSucursal').addEventListener('input', function() {
    paginaActualSuc = 1;
    mostrarPaginaSucursal(paginaActualSuc);
  });
}

let sucursalAActivar = null;

function activarSucursal(id) {
  sucursalAActivar = id;
  document.getElementById("inputPasswordConfirmActivar").value = "";
  document.getElementById("errorPasswordConfirmActivar").classList.add("hidden");
  document
    .getElementById("modalConfirmarActivacion")
    .classList.remove("hidden");
  document.getElementById("modalBackground").classList.remove("hidden");
}

function cerrarModalConfirmarActivacion() {
  sucursalAActivar = null;
  document
    .getElementById("modalConfirmarActivacion")
    .classList.add("hidden");
  document.getElementById("modalBackground").classList.add("hidden");
}

function confirmarActivacionSucursal() {
  const password = document.getElementById("inputPasswordConfirmActivar").value;
  if (!password) {
    mostrarErrorPasswordActivar("La contraseña es obligatoria");
    return;
  }
  fetch("index.php?controller=usuario&action=validarPasswordAjax", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "password=" + encodeURIComponent(password),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.valido) {
        document.getElementById("inputActivarSucursalId").value =
          sucursalAActivar;
        document.getElementById("formActivarSucursal").submit();
      } else {
        mostrarErrorPasswordActivar("Contraseña incorrecta");
      }
    })
    .catch(() => mostrarErrorPasswordActivar("Error al validar la contraseña"));
}

function mostrarErrorPasswordActivar(msg) {
  const errorDiv = document.getElementById("errorPasswordConfirmActivar");
  errorDiv.textContent = msg;
  errorDiv.classList.remove("hidden");
}

document.addEventListener('DOMContentLoaded', function () {
  if (document.getElementById('tablaSucursalesBody')) {
    mostrarPaginaSucursal(paginaActualSuc);
    initBuscadorSucursal();
    delegarEventosSucursales();
  }
});
