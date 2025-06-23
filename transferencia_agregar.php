<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once './conexion/cone.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (!$conn) {
    die('Error de conexión: ' . pg_last_error($conn));
}

// Obtener sucursales activas
$sql_suc = "SELECT id_sucursal, nombre_sucursal, tipo_sucursal FROM sucursal WHERE estado_sucursal = true ORDER BY nombre_sucursal ASC";
$res_suc = pg_query($conn, $sql_suc);
$sucursales = [];
while ($row = pg_fetch_assoc($res_suc)) {
    $sucursales[] = $row;
}

// Endpoint AJAX para stock dinámico
if (isset($_GET['ajax_stock']) && $_GET['ajax_stock'] == '1' && isset($_GET['producto']) && isset($_GET['sucursal'])) {
    $id_producto = pg_escape_string($conn, $_GET['producto']);
    $id_sucursal = pg_escape_string($conn, $_GET['sucursal']);
    $sql_stock = "SELECT COALESCE(cantidad, 0) AS total_stock FROM inventario_sucursal WHERE id_producto = '$id_producto' AND id_sucursal = '$id_sucursal'";
    $res_stock = pg_query($conn, $sql_stock);
    $row_stock = pg_fetch_assoc($res_stock);
    $total_stock = $row_stock ? $row_stock['total_stock'] : 0;
    echo $total_stock;
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php'; ?>

<body id="main-content" class="bg-gray-100 ml-72 mt-20">
    <?php include_once './includes/header.php'; ?>
    <main>
        <div class="container mx-auto px-6 mt-6">
            <div class="flex flex-col items-start mb-6">
                <h3 class="text-2xl font-semibold text-gray-800">Nueva Transferencia entre Sucursales</h3>
                <hr class="w-full border-t-2 border-gray-300 mt-2 mb-6" />
            </div>
            <?php
            $paso1 = true;
            $paso2 = isset($_GET['origen']) && $_GET['origen'] !== '';
            $paso3 = $paso2 && isset($_GET['destino']) && $_GET['destino'] !== '';
            $tipo_origen = null;
            $tipo_destino = null;
            foreach ($sucursales as $suc) {
                if ($paso2 && $suc['id_sucursal'] == $_GET['origen']) {
                    $tipo_origen = $suc['tipo_sucursal'];
                }
                if ($paso3 && $suc['id_sucursal'] == $_GET['destino']) {
                    $tipo_destino = $suc['tipo_sucursal'];
                }
            }
            ?>
            <div class="flex items-center justify-center mb-10">
                <!-- Paso 1: Origen -->
                <div class="flex flex-col items-center">
                    <div class="rounded-full p-4 shadow-lg <?php echo $paso1 ? 'bg-purple-500 text-white' : 'bg-gray-300 text-gray-500'; ?>">
                        <i class="fas fa-warehouse fa-2x"></i>
                    </div>
                    <span class="mt-2 text-sm font-semibold <?php echo $paso1 ? 'text-purple-700' : 'text-gray-500'; ?>">Origen</span>
                </div>
                <!-- Línea -->
                <div class="flex-1 h-1 mx-2 <?php echo $paso2 ? 'bg-purple-400' : 'bg-gray-300'; ?>"></div>
                <!-- Paso 2: Destino -->
                <div class="flex flex-col items-center">
                    <div class="rounded-full p-4 shadow-lg <?php echo $paso2 ? 'bg-purple-500 text-white' : 'bg-gray-300 text-gray-500'; ?>">
                        <i class="fas fa-store fa-2x"></i>
                    </div>
                    <span class="mt-2 text-sm font-semibold <?php echo $paso2 ? 'text-purple-700' : 'text-gray-500'; ?>">Destino</span>
                </div>
                <!-- Línea -->
                <div class="flex-1 h-1 mx-2 <?php echo $paso3 ? 'bg-purple-400' : 'bg-gray-300'; ?>"></div>
                <!-- Paso 3: Detalles -->
                <div class="flex flex-col items-center">
                    <div class="rounded-full p-4 shadow-lg <?php echo $paso3 ? 'bg-purple-500 text-white' : 'bg-gray-300 text-gray-500'; ?>">
                        <i class="fas fa-truck fa-2x"></i>
                    </div>
                    <span class="mt-2 text-sm font-semibold <?php echo $paso3 ? 'text-purple-700' : 'text-gray-500'; ?>">Detalles</span>
                </div>
            </div>

            <!-- Formulario de catálogo SOLO si falta origen o destino -->
            <?php if (!isset($_GET['origen']) || $_GET['origen'] === '' || !isset($_GET['destino']) || $_GET['destino'] === ''): ?>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <div class="flex flex-col items-start mb-8">
                        <!-- Paso Origen: Selección y confirmación -->
                        <?php if(!isset($_GET['origen']) || $_GET['origen'] === ''): ?>
                            <form method="GET" action="transferencia_agregar.php">
                                <span class="text-gray-700 font-medium mb-2 block">Selecciona la sucursal origen</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                                    <?php foreach ($sucursales as $suc) { ?>
                                        <label class="w-full h-full cursor-pointer">
                                            <input type="radio" name="origen" value="<?php echo $suc['id_sucursal']; ?>" class="hidden">
                                            <div class="border rounded-lg p-4 flex flex-col items-center transition border-gray-300 bg-white hover:border-purple-400 hover:bg-purple-100">
                                                <i class="fas <?php echo ($suc['tipo_sucursal'] === 'almacen') ? 'fa-warehouse' : (($suc['tipo_sucursal'] === 'fisica') ? 'fa-store' : 'fa-globe'); ?> fa-2x mb-2"></i>
                                                <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($suc['nombre_sucursal']); ?></span>
                                                <span class="text-xs text-gray-500 capitalize"><?php echo $suc['tipo_sucursal']; ?></span>
                                            </div>
                                        </label>
                                    <?php } ?>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" id="btn-siguiente-origen" class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-300 ease-in-out" disabled>Siguiente</button>
                                </div>
                            </form>
                            <script>
                            const radiosOrigen = document.querySelectorAll('input[name="origen"]');
                            const btnSiguienteOrigen = document.getElementById('btn-siguiente-origen');
                            radiosOrigen.forEach(radio => {
                                radio.addEventListener('change', function() {
                                    radiosOrigen.forEach(r => r.parentElement.querySelector('div').classList.remove('border-purple-500', 'bg-purple-50', 'shadow-lg'));
                                    if (this.checked) {
                                        this.parentElement.querySelector('div').classList.add('border-purple-500', 'bg-purple-50', 'shadow-lg');
                                        btnSiguienteOrigen.disabled = false;
                                    }
                                });
                            });
                            // Estado inicial: si hay uno seleccionado por el navegador
                            radiosOrigen.forEach(radio => {
                                if (radio.checked) {
                                    radio.parentElement.querySelector('div').classList.add('border-purple-500', 'bg-purple-50', 'shadow-lg');
                                    btnSiguienteOrigen.disabled = false;
                                }
                            });
                            </script>
                        <?php endif; ?>

                        <!-- Paso Destino: Selección y confirmación -->
                        <?php if(isset($_GET['origen']) && $_GET['origen'] !== '' && (!isset($_GET['destino']) || $_GET['destino'] === '')): ?>
                            <form method="GET" action="transferencia_agregar.php">
                                <input type="hidden" name="origen" value="<?php echo htmlspecialchars($_GET['origen']); ?>">
                                <span class="text-gray-700 font-medium mb-2 block">Selecciona la sucursal destino</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                                    <?php foreach ($sucursales as $suc) { 
                                        if ($_GET['origen'] != $suc['id_sucursal']) { ?>
                                        <label class="w-full h-full cursor-pointer">
                                            <input type="radio" name="destino" value="<?php echo $suc['id_sucursal']; ?>" class="hidden">
                                            <div class="border rounded-lg p-4 flex flex-col items-center transition border-gray-300 bg-white hover:border-purple-400 hover:bg-purple-100">
                                                <i class="fas <?php echo ($suc['tipo_sucursal'] === 'almacen') ? 'fa-warehouse' : (($suc['tipo_sucursal'] === 'fisica') ? 'fa-store' : 'fa-globe'); ?> fa-2x mb-2"></i>
                                                <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($suc['nombre_sucursal']); ?></span>
                                                <span class="text-xs text-gray-500 capitalize"><?php echo $suc['tipo_sucursal']; ?></span>
                                            </div>
                                        </label>
                                    <?php }} ?>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <a href="transferencia_agregar.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition duration-300 ease-in-out flex items-center">Atrás</a>
                                    <button type="submit" id="btn-siguiente-destino" class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-300 ease-in-out" disabled>Siguiente</button>
                                </div>
                            </form>
                            <script>
                            // Resaltar card seleccionada en destino
                            const radiosDestino = document.querySelectorAll('input[name="destino"]');
                            const btnSiguienteDestino = document.getElementById('btn-siguiente-destino');
                            radiosDestino.forEach(radio => {
                                radio.addEventListener('change', function() {
                                    radiosDestino.forEach(r => r.parentElement.querySelector('div').classList.remove('border-purple-500', 'bg-purple-50', 'shadow-lg'));
                                    if (this.checked) {
                                        this.parentElement.querySelector('div').classList.add('border-purple-500', 'bg-purple-50', 'shadow-lg');
                                        btnSiguienteDestino.disabled = false;
                                    }
                                });
                            });
                            radiosDestino.forEach(radio => {
                                if (radio.checked) {
                                    radio.parentElement.querySelector('div').classList.add('border-purple-500', 'bg-purple-50', 'shadow-lg');
                                    btnSiguienteDestino.disabled = false;
                                }
                            });
                            </script>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['origen']) && $_GET['origen'] !== '' && isset($_GET['destino']) && $_GET['destino'] !== ''): ?>
                <div class="relative w-full max-w-xl mx-auto mt-10 bg-white rounded-lg shadow-lg p-8">
                    <div class="absolute top-4 right-6 flex items-center space-x-2 text-sm text-gray-600">
                        <i class="fas fa-user-circle fa-lg"></i>
                        <span><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
                    </div>
                    <h4 class="text-xl font-semibold mb-6 text-gray-800">Detalles de la Transferencia</h4>
                    <form method="POST" action="views/transferencias/transferencia_registrar.php">
                        <input type="hidden" name="origen" value="<?php echo htmlspecialchars($_GET['origen']); ?>">
                        <input type="hidden" name="destino" value="<?php echo htmlspecialchars($_GET['destino']); ?>">
                        <div class="mb-4">
                            <label for="producto" class="block text-gray-700 font-medium mb-1">Producto</label>
                            <select id="producto" name="producto" class="w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-3 py-2 bg-gray-50" required>
                                <option value="">Seleccione un producto...</option>
                                <?php
                                $sql_prod = "SELECT id_producto, nombre_producto FROM producto WHERE estado = true ORDER BY nombre_producto ASC";
                                $res_prod = pg_query($conn, $sql_prod);
                                $num_prod = pg_num_rows($res_prod);
                                if ($num_prod == 0) {
                                    echo '<option disabled>No hay productos activos</option>';
                                }
                                while ($prod = pg_fetch_assoc($res_prod)) {
                                    $selected = (isset($_POST['producto']) && $_POST['producto'] == $prod['id_producto']) ? 'selected' : '';
                                    echo '<option value="' . $prod['id_producto'] . '" ' . $selected . '>' . htmlspecialchars($prod['nombre_producto']) . '</option>';
                                }
                                ?>
                            </select>
                            <div id="stock-info" class="mt-2 text-sm text-gray-600"></div>
                        </div>
                        <!-- Cantidad -->
                        <div class="mb-4">
                            <label for="cantidad" class="block text-gray-700 font-medium mb-1">Cantidad</label>
                            <input type="number" id="cantidad" name="cantidad" min="1" class="w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-3 py-2 bg-gray-50" required>
                        </div>
                        <!-- Fecha -->
                        <div class="mb-6">
                            <label for="fecha" class="block text-gray-700 font-medium mb-1">Fecha</label>
                            <input type="date" id="fecha" name="fecha" class="w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-3 py-2 bg-gray-50" required value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <!-- Botón Registrar Transferencia dentro del form POST -->
                        <div class="flex justify-end mt-8 gap-2">
                            <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">Registrar Transferencia</button>
                        </div>
                    </form>
                    <!-- Botón Atrás fuera del form POST, pero alineado -->
                    <div class="flex justify-end mt-2 gap-2">
                        <form method="GET" action="transferencia_agregar.php" class="m-0">
                            <input type="hidden" name="origen" value="<?php echo htmlspecialchars($_GET['origen']); ?>">
                            <button type="submit" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition duration-300 ease-in-out">Atrás</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <?php include_once './includes/footer.php'; ?>
    <script>
    // Mostrar stock dinámicamente al cambiar producto
    const selectProd = document.getElementById('producto');
    const stockInfo = document.getElementById('stock-info');
    const idSucursalOrigen = '<?php echo isset($_GET['origen']) ? htmlspecialchars($_GET['origen']) : ''; ?>';
    selectProd.addEventListener('change', function() {
        const id = this.value;
        if (!id) {
            stockInfo.textContent = '';
            return;
        }
        stockInfo.textContent = 'Cargando stock...';
        fetch('transferencia_agregar.php?ajax_stock=1&producto=' + encodeURIComponent(id) + '&sucursal=' + encodeURIComponent(idSucursalOrigen))
            .then(r => r.text())
            .then(stock => {
                stockInfo.innerHTML = 'Stock en sucursal origen: <span class="font-bold text-gray-900">' + stock + '</span>';
            });
    });
    // Si ya hay producto seleccionado al cargar, mostrar stock
    window.addEventListener('DOMContentLoaded', function() {
        if (selectProd.value) {
            selectProd.dispatchEvent(new Event('change'));
        }
    });
    </script>
</body>

</html>