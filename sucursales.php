<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once './conexion/cone.php';
include_once './views/sucursales/sucursales_queries.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (!$conn) {
    die('Error de conexión: ' . pg_last_error($conn));
}

$sql = getAllSucursalesQuery();
$result = pg_query($conn, $sql);

if (!$result) {
    die('Error en la consulta: ' . pg_last_error($conn));
}
?>
<!DOCTYPE html>
<html lang="es">

<?php include_once './includes/head.php'; ?>

<body id="main-content" class="ml-72 mt-20">
    <?php include_once './includes/header.php'; ?>
    <main>
        <div class="container mx-auto px-4 mt-6">
            <?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
                <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Operación realizada con éxito</span>
                </div>
                <meta http-equiv="refresh" content="3;url=sucursales.php">
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Ocurrió un error. Código: <?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
                <meta http-equiv="refresh" content="3;url=sucursales.php">
            <?php endif; ?>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Gestión de Sucursales</h3>
                <div class="flex gap-2 items-center">
                    <input type="text" id="buscadorSucursal" class="border rounded px-3 py-2" placeholder="Buscar sucursal...">
                    <a href="#" onclick="abrirModalAgregarSucursal()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Agregar Sucursal
                    </a>
                </div>
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="bg-white rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Sucursal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaSucursalesBody" class="bg-white divide-y divide-gray-200">
                            <?php while ($row = pg_fetch_assoc($result)) { 
                                $activa = ($row['estado_sucursal'] === true || $row['estado_sucursal'] === 't' || $row['estado_sucursal'] === 1 || $row['estado_sucursal'] === '1');
                            ?>
                                <tr class="sucursal-row">
                                    <td class="px-6 py-4 whitespace-nowrap nombre-sucursal"><?php echo htmlspecialchars($row['id_sucursal']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap nombre-sucursal"><?php echo htmlspecialchars($row['nombre_sucursal']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo $activa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                            <?php echo $activa ? 'Activa' : 'Inactiva'; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                                        <button class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded btn-editar"
                                            data-id="<?php echo htmlspecialchars($row['id_sucursal']); ?>"
                                            data-nombre="<?php echo htmlspecialchars($row['nombre_sucursal']); ?>"
                                            data-direccion="<?php echo htmlspecialchars($row['direccion_sucursal']); ?>"
                                            data-tipo="<?php echo htmlspecialchars($row['tipo_sucursal']); ?>"
                                            data-supervisor="<?php echo htmlspecialchars($row['id_supervisor']); ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if ($activa) { ?>
                                            <button class="cursor-pointer bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded" onclick="eliminarSucursal(<?php echo $row['id_sucursal']; ?>);">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php } else { ?>
                                            <button class="cursor-pointer bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded" onclick="activarSucursal(<?php echo $row['id_sucursal']; ?>);" title="Activar">
                                                <i class="fas fa-toggle-on"></i>
                                            </button>
                                        <?php } ?>
                                        <button class="cursor-pointer bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded btn-detalle"
                                            title="Ver Detalle"
                                            data-id="<?php echo htmlspecialchars($row['id_sucursal']); ?>"
                                            data-nombre="<?php echo htmlspecialchars($row['nombre_sucursal']); ?>"
                                            data-direccion="<?php echo htmlspecialchars($row['direccion_sucursal']); ?>"
                                            data-tipo="<?php echo htmlspecialchars($row['tipo_sucursal']); ?>"
                                            data-supervisor-nombre="<?php echo htmlspecialchars($row['supervisor_nombre'] ?? 'Sin asignar'); ?>"
                                            data-supervisor-telefono="<?php echo htmlspecialchars($row['supervisor_telefono'] ?? ''); ?>"
                                            data-supervisor-email="<?php echo htmlspecialchars($row['supervisor_email'] ?? ''); ?>"
                                            data-estado="<?php echo $activa ? 'Activa' : 'Inactiva'; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="flex justify-center mt-4" id="paginacionSucursales"></div>
        </div>
    </main>
    <?php include 'views/sucursales/modals/modal_agregar_sucursal.php'; ?>
    <div id="modalBackground" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
    <div id="modalDetalleSucursal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="relative mx-auto p-6 border w-[550px] shadow-xl rounded-xl bg-white">
            <div class="mt-3">
                <h3 class="text-xl font-semibold leading-6 text-gray-900 mb-6 text-center">Detalle de Sucursal</h3>
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Columna de detalles de la sucursal -->
                    <div class="flex-1 flex flex-col gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre de la Sucursal</label>
                            <div class="mt-1 block w-full rounded-md border border-gray-300 px-4 py-3 bg-gray-100" id="detalleNombreSucursal"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo de Sucursal</label>
                            <div class="mt-1 block w-full rounded-md border border-gray-300 px-4 py-3 bg-gray-100 uppercase" id="detalleTipoSucursal"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Teléfono Supervisor</label>
                            <div class="mt-1 block w-full rounded-md border border-gray-300 px-4 py-3 bg-gray-100" id="detalleSupervisorTelefono"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <div class="mt-1 block w-full">
                                <span id="detalleEstadoSucursalSpan" class="block text-center text-base font-semibold py-2 rounded-lg"></span>
                            </div>
                        </div>
                    </div>
                    <!-- Columna de dirección y datos del supervisor -->
                    <div class="flex-1 flex flex-col gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dirección</label>
                            <div class="mt-1 block w-full rounded-md border border-gray-300 px-4 py-3 bg-gray-100" id="detalleDireccionSucursal"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Supervisor</label>
                            <div class="mt-1 block w-full rounded-md border border-gray-300 px-4 py-3 bg-gray-100" id="detalleSupervisorNombre"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email Supervisor</label>
                            <div class="mt-1 block w-full rounded-md border border-gray-300 px-4 py-3 bg-gray-100" id="detalleSupervisorEmail"></div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-8">
                    <button type="button" onclick="cerrarModalDetalleSucursal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <form id="formEliminarSucursal" action="views/sucursales/sucursal_eliminar.php" method="POST" style="display:none;">
        <input type="hidden" name="id_sucursal" id="inputEliminarSucursalId">
    </form>
    <div id="modalConfirmarDesactivacion" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="relative mx-auto p-6 border w-[350px] shadow-xl rounded-xl bg-white">
            <h3 class="text-lg font-semibold mb-4 text-center">Confirmar desactivación</h3>
            <p class="mb-4 text-center">Por favor, ingresa tu contraseña para confirmar la desactivación de la sucursal.</p>
            <input type="password" id="inputPasswordConfirm" class="w-full mb-4 px-3 py-2 border rounded focus:outline-none focus:ring" placeholder="Contraseña" autocomplete="current-password">
            <div id="errorPasswordConfirm" class="text-red-600 text-sm mb-2 hidden text-center"></div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="cerrarModalConfirmarDesactivacion()" class="bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded">Cancelar</button>
                <button type="button" onclick="confirmarDesactivacionSucursal()" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">Confirmar</button>
            </div>
        </div>
    </div>
    <form id="formActivarSucursal" action="views/sucursales/sucursal_activar.php" method="POST" style="display:none;">
        <input type="hidden" name="id_sucursal" id="inputActivarSucursalId">
    </form>
    <div id="modalConfirmarActivacion" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="relative mx-auto p-6 border w-[350px] shadow-xl rounded-xl bg-white">
            <h3 class="text-lg font-semibold mb-4 text-center">Confirmar activación</h3>
            <p class="mb-4 text-center">Por favor, ingresa tu contraseña para confirmar la activación de la sucursal.</p>
            <input type="password" id="inputPasswordConfirmActivar" class="w-full mb-4 px-3 py-2 border rounded focus:outline-none focus:ring" placeholder="Contraseña" autocomplete="current-password">
            <div id="errorPasswordConfirmActivar" class="text-red-600 text-sm mb-2 hidden text-center"></div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="cerrarModalConfirmarActivacion()" class="bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded">Cancelar</button>
                <button type="button" onclick="confirmarActivacionSucursal()" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">Confirmar</button>
            </div>
        </div>
    </div>
    <script src="assets/js/sucursales.js"></script>
    <?php include_once './views/sucursales/modals/modal_editar_sucursal.php'; ?>
    <?php include_once './views/sucursales/modals/modal_agregar_sucursal.php'; ?>
    <?php include './includes/modal_confirmar.php'; ?>
    <?php include_once './includes/footer.php'; ?>
    <script>
    let sucursalAActivar = null;
    function activarSucursal(id) {
        sucursalAActivar = id;
        document.getElementById('inputPasswordConfirmActivar').value = '';
        document.getElementById('errorPasswordConfirmActivar').classList.add('hidden');
        document.getElementById('modalConfirmarActivacion').classList.remove('hidden');
        document.getElementById('modalBackground').classList.remove('hidden');
    }
    function cerrarModalConfirmarActivacion() {
        sucursalAActivar = null;
        document.getElementById('modalConfirmarActivacion').classList.add('hidden');
        document.getElementById('modalBackground').classList.add('hidden');
    }
    function confirmarActivacionSucursal() {
        const password = document.getElementById('inputPasswordConfirmActivar').value;
        if (!password) {
            mostrarErrorPasswordActivar('La contraseña es obligatoria');
            return;
        }
        fetch('conexion/validar_password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'password=' + encodeURIComponent(password)
        })
        .then(res => res.json())
        .then(data => {
            if (data.valido) {
                document.getElementById('inputActivarSucursalId').value = sucursalAActivar;
                document.getElementById('formActivarSucursal').submit();
            } else {
                mostrarErrorPasswordActivar('Contraseña incorrecta');
            }
        })
        .catch(() => mostrarErrorPasswordActivar('Error al validar la contraseña'));
    }
    function mostrarErrorPasswordActivar(msg) {
        const errorDiv = document.getElementById('errorPasswordConfirmActivar');
        errorDiv.textContent = msg;
        errorDiv.classList.remove('hidden');
    }
    </script>
</body>

</html>