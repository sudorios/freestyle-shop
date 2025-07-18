<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once './conexion/cone.php';
include_once 'views/conteos_ciclicos/conteos_ciclicos_queries.php';

$id_producto = isset($_GET['id_producto']) ? intval($_GET['id_producto']) : 0;
$id_sucursal = isset($_GET['id_sucursal']) ? intval($_GET['id_sucursal']) : 0;

if ($id_producto <= 0 || $id_sucursal <= 0) {
    die('Parámetros inválidos.');
}

$sql_prod = getNombreProductoByIdQuery();
$res_prod = pg_query_params($conn, $sql_prod, array($id_producto));
$nombre_producto = '';
if ($res_prod && pg_num_rows($res_prod) > 0) {
    $nombre_producto = pg_fetch_result($res_prod, 0, 'nombre_producto');
}

$sql_suc = getNombreSucursalByIdQuery();
$res_suc = pg_query_params($conn, $sql_suc, array($id_sucursal));
$nombre_sucursal = '';
if ($res_suc && pg_num_rows($res_suc) > 0) {
    $nombre_sucursal = pg_fetch_result($res_suc, 0, 'nombre_sucursal');
}

$sql_inv = getCantidadInventarioByProductoSucursalQuery();
$res_inv = pg_query_params($conn, $sql_inv, array($id_producto, $id_sucursal));
$cantidad_sistema = '';
if ($res_inv && pg_num_rows($res_inv) > 0) {
    $cantidad_sistema = pg_fetch_result($res_inv, 0, 'cantidad');
}

$nombre_usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';

?>

<?php include_once './includes/head.php'; ?>


<body id="main-content" class="ml-72 mt-20">
    <?php include_once './includes/header.php'; ?>
    <main>
        <div class="container mx-auto px-4 mt-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="mb-6 p-6 rounded-lg flex flex-col md:flex-row md:items-center md:justify-between gap-4 border border-gray-200 bg-white">
                    <div>
                        <div class="text-lg text-gray-700 font-semibold uppercase tracking-wider">Producto</div>
                        <div class="text-2xl font-bold text-gray-900"> <?php echo htmlspecialchars($nombre_producto); ?> </div>
                    </div>
                    <div>
                        <div class="text-lg text-gray-700 font-semibold uppercase tracking-wider">Sucursal</div>
                        <div class="text-2xl font-bold text-gray-900"> <?php echo htmlspecialchars($nombre_sucursal); ?> </div>
                    </div>
                </div>
                <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-xl font-semibold">Historial de Conteos Cíclicos</h4>
                    <div class="flex gap-2 items-center">
                        <button id="btnNuevoConteo" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition">+ Nuevo Conteo</button>
                        <form method="get" action="views/conteos_ciclicos/exportar_excel.php" style="display:inline;">
                                       <input type="text" id="buscadorConteo" placeholder="Buscar Conteo..." class="border rounded px-2 py-1">
             <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($id_producto); ?>">
                            <input type="hidden" name="id_sucursal" value="<?php echo htmlspecialchars($id_sucursal); ?>">
                            <input type="hidden" name="fecha_desde" value="<?php echo isset($_GET['fecha_desde']) ? htmlspecialchars($_GET['fecha_desde']) : ''; ?>">
                            <input type="hidden" name="fecha_hasta" value="<?php echo isset($_GET['fecha_hasta']) ? htmlspecialchars($_GET['fecha_hasta']) : ''; ?>">
                            <input type="hidden" name="usuario" value="<?php echo isset($_GET['usuario']) ? htmlspecialchars($_GET['usuario']) : ''; ?>">
                            <input type="hidden" name="estado" value="<?php echo isset($_GET['estado']) ? htmlspecialchars($_GET['estado']) : ''; ?>">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow transition ml-2" title="Exportar a Excel">
                                <i class="fas fa-file-excel"></i>
                            </button>
                        </form>
                        <form method="get" action="views/conteos_ciclicos/exportar_pdf.php" style="display:inline;">
                            <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($id_producto); ?>">
                            <input type="hidden" name="id_sucursal" value="<?php echo htmlspecialchars($id_sucursal); ?>">
                            <input type="hidden" name="fecha_desde" value="<?php echo isset($_GET['fecha_desde']) ? htmlspecialchars($_GET['fecha_desde']) : ''; ?>">
                            <input type="hidden" name="fecha_hasta" value="<?php echo isset($_GET['fecha_hasta']) ? htmlspecialchars($_GET['fecha_hasta']) : ''; ?>">
                            <input type="hidden" name="usuario" value="<?php echo isset($_GET['usuario']) ? htmlspecialchars($_GET['usuario']) : ''; ?>">
                            <input type="hidden" name="estado" value="<?php echo isset($_GET['estado']) ? htmlspecialchars($_GET['estado']) : ''; ?>">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow transition ml-2" title="Exportar a PDF">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
                <form method="get" class="flex gap-4 mb-4 items-center" id="formFiltros">
                    <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($id_producto); ?>">
                    <input type="hidden" name="id_sucursal" value="<?php echo htmlspecialchars($id_sucursal); ?>">
                    <label class="text-sm font-medium text-gray-700">Desde:
                        <input type="date" name="fecha_desde" value="<?php echo isset($_GET['fecha_desde']) ? htmlspecialchars($_GET['fecha_desde']) : ''; ?>" class="border rounded px-2 py-1 ml-1">
                    </label>
                    <label class="text-sm font-medium text-gray-700">Hasta:
                        <input type="date" name="fecha_hasta" value="<?php echo isset($_GET['fecha_hasta']) ? htmlspecialchars($_GET['fecha_hasta']) : ''; ?>" class="border rounded px-2 py-1 ml-1">
                    </label>
                    <button type="button" id="btnMasFiltros" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-3 rounded ml-2">Más filtros</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded ml-2">Filtrar</button>
                    <a href="conteo_ciclico.php?id_producto=<?php echo $id_producto; ?>&id_sucursal=<?php echo $id_sucursal; ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded ml-2">Limpiar</a>
                </form>
                <div id="filtrosExtra" class="<?php echo (isset($_GET['usuario']) || isset($_GET['estado'])) ? 'flex gap-4 items-center mb-4' : 'hidden flex gap-4 items-center mb-4'; ?>">
                    <label class="text-sm font-medium text-gray-700">Usuario:
                        <input type="text" name="usuario" form="formFiltros" value="<?php echo isset($_GET['usuario']) ? htmlspecialchars($_GET['usuario']) : ''; ?>" class="border rounded px-2 py-1 ml-1">
                    </label>
                    <label class="text-sm font-medium text-gray-700">Estado:
                        <select name="estado" form="formFiltros" class="border rounded px-2 py-1 ml-1">
                            <option value="">Todos</option>
                            <option value="Pendiente" <?php if(isset($_GET['estado']) && $_GET['estado']=='Pendiente') echo 'selected'; ?>>Pendiente</option>
                            <option value="Completado" <?php if(isset($_GET['estado']) && $_GET['estado']=='Completado') echo 'selected'; ?>>Completado</option>
                            <option value="Cancelado" <?php if(isset($_GET['estado']) && $_GET['estado']=='Cancelado') echo 'selected'; ?>>Cancelado</option>
                        </select>
                    </label>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var btnMasFiltros = document.getElementById('btnMasFiltros');
                    var filtrosExtra = document.getElementById('filtrosExtra');
                    if (btnMasFiltros && filtrosExtra) {
                        btnMasFiltros.addEventListener('click', function() {
                            filtrosExtra.classList.toggle('hidden');
                        });
                    }
                });
                </script>
                <?php
                $fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '';
                $fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '';
                $usuario = isset($_GET['usuario']) ? trim($_GET['usuario']) : '';
                $estado = isset($_GET['estado']) ? trim($_GET['estado']) : '';

                if ($fecha_desde || $fecha_hasta || $usuario || $estado) {
                    $sql = getConteosCiclicosFiltradosQuery($fecha_desde, $fecha_hasta, $usuario, $estado);
                    $params = [$id_producto, $id_sucursal];
                    if ($fecha_desde && $fecha_hasta) {
                        $params[] = $fecha_desde;
                        $params[] = $fecha_hasta;
                    } elseif ($fecha_desde) {
                        $params[] = $fecha_desde;
                    } elseif ($fecha_hasta) {
                        $params[] = $fecha_hasta;
                    }
                    if ($usuario) {
                        $params[] = "%$usuario%";
                    }
                    if ($estado) {
                        $params[] = $estado;
                    }
                } else {
                    $sql = getConteosCiclicosByProductoSucursalQuery();
                    $params = array($id_producto, $id_sucursal);
                }

                $result = pg_query_params($conn, $sql, $params);
                ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" id="thOdernar" onclick="ordenarPorColumna('tbody', 0, 'iconoOrden', 'buscadorProducto', 10, 'paginacionProducto')">
                                    ID <span id="iconoOrden" data-asc="true">↑</span>
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cantidad Real</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cantidad Sistema</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Diferencia</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer"
                                    onclick="ordenarPorColumna('tbody', 4, 'iconoOrdenFecha', 'buscadorConteo', 10, 'paginacionConteo')">
                                    Fecha Conteo <span id="iconoOrdenFecha" data-asc="true">↑</span>
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer"
                                    onclick="ordenarPorColumna('tbody', 7, 'iconoOrdenAjuste', 'buscadorConteo', 10, 'paginacionConteo')">
                                    Fecha Ajuste <span id="iconoOrdenAjuste" data-asc="true">↑</span>
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if ($result && pg_num_rows($result) > 0): ?>
                                <?php while ($row = pg_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap"><?php echo $row['id_conteo']; ?></td>
                                        <td class="px-4 py-2 whitespace-nowrap"><?php echo $row['cantidad_real']; ?></td>
                                        <td class="px-4 py-2 whitespace-nowrap"><?php echo $row['cantidad_sistema']; ?></td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <?php
                                            $dif = $row['diferencia'];
                                            if ($dif < 0) {
                                                $clase = 'text-red-700 font-bold bg-red-100 rounded px-2 py-1';
                                            } elseif ($dif == 0) {
                                                $clase = 'text-green-600 font-bold bg-green-100 rounded px-2 py-1';
                                            } elseif ($dif > 0) {
                                                $clase = 'text-yellow-700 font-bold bg-yellow-100 rounded px-2 py-1';
                                            }
                                            ?>
                                            <span class="<?php echo $clase; ?>">
                                                <?php echo $row['diferencia']; ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap"><?php echo date('d/m/Y', strtotime($row['fecha_conteo'])); ?></td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <?php echo htmlspecialchars($row['nombre_usuario'] ?? $row['usuario_id']); ?>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap"><?php echo ucfirst($row['estado_conteo']); ?></td>
                                        <td class="px-4 py-2 whitespace-nowrap"><?php echo $row['fecha_ajuste'] ? date('d/m/Y', strtotime($row['fecha_ajuste'])) : '-'; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button
                                                class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded btn-editar"
                                                data-id="<?php echo htmlspecialchars($row['id_conteo']); ?>"
                                                data-real="<?php echo htmlspecialchars($row['cantidad_real']); ?>"
                                                data-sistema="<?php echo htmlspecialchars($row['cantidad_sistema']); ?>"
                                                data-usuario="<?php echo htmlspecialchars($row['usuario_id']); ?>"
                                                data-diferencia="<?php echo htmlspecialchars($row['diferencia']); ?>"
                                                data-fecha="<?php echo htmlspecialchars($row['fecha_conteo']); ?>"
                                                data-estado="<?php echo htmlspecialchars($row['estado_conteo']); ?>"
                                                data-producto="<?php echo htmlspecialchars($row['producto_id']); ?>"
                                                data-sucursal="<?php echo htmlspecialchars($row['sucursal_id']); ?>"
                                                data-comentarios="<?php echo htmlspecialchars($row['comentarios']); ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="cursor-pointer bg-gray-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded" onclick="verComentario('<?php echo htmlspecialchars(addslashes($row['comentarios'])); ?>')">
                                                <i class="fa-solid fa-comment-dots mr-1"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="px-4 py-2 text-center text-gray-500">No hay conteos cíclicos registrados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="paginacionConteo" class="flex justify-center mt-4"></div>
        </div>
    </main>
    <script src="assets/js/conteo_ciclico.js"></script>
    <script src="assets/js/tabla_utils.js"></script>
    <?php include_once './includes/footer.php'; ?>
    <?php include_once 'views/conteos_ciclicos/modals/modal_nuevo_conteo.php'; ?>
    <?php include_once 'views/conteos_ciclicos/modals/modal_ver_comentario.php'; ?>
    <?php include_once 'views/conteos_ciclicos/modals/modal_editar_conteo.php'; ?>
</body>

</html>