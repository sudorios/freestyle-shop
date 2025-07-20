<?php
include_once __DIR__ . '/../../includes/head.php';
include_once __DIR__ . '/../../includes/header.php';
$paso1 = true;
$paso2 = isset($_GET['origen']) && $_GET['origen'] !== '';
$paso3 = $paso2 && isset($_GET['destino']) && $_GET['destino'] !== '';
?>

<body id="main-content" class="bg-gray-100 ml-72 mt-20">
    <main>
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg px-10 py-12 mt-10 mb-12">
            <h3 class="text-2xl font-semibold text-gray-800 mb-2">Nueva Transferencia entre Sucursales</h3>
            <hr class="w-full border-t-2 border-gray-300 mt-2 mb-8" />
            <?php if (isset($_GET['error'])): ?>
                <div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 mx-auto max-w-2xl'>
                    <span class="block sm:inline">Ocurrió un error: <?php echo htmlspecialchars($_GET['msg'] ?? ''); ?></span>
                </div>
            <?php endif; ?>
            <div class="flex items-center justify-center mb-12">
                <div class="flex flex-col items-center">
                    <div class="rounded-full p-5 shadow-lg <?php echo $paso1 ? 'bg-purple-500 text-white' : 'bg-gray-300 text-gray-500'; ?>">
                        <i class="fas fa-warehouse fa-2x"></i>
                    </div>
                    <span class="mt-2 text-sm font-semibold <?php echo $paso1 ? 'text-purple-700' : 'text-gray-500'; ?>">Origen</span>
                </div>
                <div class="flex-1 h-1 mx-2 <?php echo $paso2 ? 'bg-purple-400' : 'bg-gray-300'; ?>"></div>
                <div class="flex flex-col items-center">
                    <div class="rounded-full p-5 shadow-lg <?php echo $paso2 ? 'bg-purple-500 text-white' : 'bg-gray-300 text-gray-500'; ?>">
                        <i class="fas fa-store fa-2x"></i>
                    </div>
                    <span class="mt-2 text-sm font-semibold <?php echo $paso2 ? 'text-purple-700' : 'text-gray-500'; ?>">Destino</span>
                </div>
                <div class="flex-1 h-1 mx-2 <?php echo $paso3 ? 'bg-purple-400' : 'bg-gray-300'; ?>"></div>
                <div class="flex flex-col items-center">
                    <div class="rounded-full p-5 shadow-lg <?php echo $paso3 ? 'bg-purple-500 text-white' : 'bg-gray-300 text-gray-500'; ?>">
                        <i class="fas fa-truck fa-2x"></i>
                    </div>
                    <span class="mt-2 text-sm font-semibold <?php echo $paso3 ? 'text-purple-700' : 'text-gray-500'; ?>">Detalles</span>
                </div>
            </div>
            <?php if (!isset($_GET['origen']) || $_GET['origen'] === '' || !isset($_GET['destino']) || $_GET['destino'] === ''): ?>
                <div class="mb-10">
                    <div class="flex flex-col items-start mb-10">
                        <?php if (!isset($_GET['origen']) || $_GET['origen'] === ''): ?>
                            <form method="GET" action="index.php">
                                <input type="hidden" name="controller" value="transferencia">
                                <input type="hidden" name="action" value="registrar">
                                <span class="text-gray-700 font-medium mb-2 block">Selecciona la sucursal origen</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">
                                    <?php foreach ($sucursales as $suc): ?>
                                        <label class="w-full h-full cursor-pointer">
                                            <input type="radio" name="origen" value="<?= $suc['id_sucursal'] ?>" class="hidden"
                                                <?= (($_GET['origen'] ?? null) == $suc['id_sucursal']) ? 'checked' : '' ?>>
                                            <div class="border rounded-lg p-6 flex flex-col items-center transition border-gray-300 bg-white hover:border-purple-400 hover:bg-purple-100">
                                                <i class="fas <?= $suc['tipo_sucursal'] === 'almacen' ? 'fa-warehouse' : ($suc['tipo_sucursal'] === 'fisica' ? 'fa-store' : 'fa-globe') ?> fa-2x mb-2"></i>
                                                <span class="font-semibold text-gray-800"><?= htmlspecialchars($suc['nombre_sucursal']) ?></span>
                                                <span class="text-xs text-gray-500 capitalize"><?= htmlspecialchars($suc['tipo_sucursal']) ?></span>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" id="btn-siguiente-origen"
                                        class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-300 ease-in-out"
                                        disabled>Siguiente</button>
                                </div>
                            </form>
                            <script>
                                const radiosOrigen = document.querySelectorAll('input[name="origen"]');
                                const btnSiguienteOrigen = document.getElementById('btn-siguiente-origen');
                                radiosOrigen.forEach(radio => {
                                    radio.addEventListener('change', function () {
                                        radiosOrigen.forEach(r => r.parentElement.querySelector('div').classList.remove('border-purple-500', 'bg-purple-50', 'shadow-lg'));
                                        if (this.checked) {
                                            this.parentElement.querySelector('div').classList.add('border-purple-500', 'bg-purple-50', 'shadow-lg');
                                            btnSiguienteOrigen.disabled = false;
                                        }
                                    });
                                });
                                radiosOrigen.forEach(radio => {
                                    if (radio.checked) {
                                        radio.parentElement.querySelector('div').classList.add('border-purple-500', 'bg-purple-50', 'shadow-lg');
                                        btnSiguienteOrigen.disabled = false;
                                    }
                                });
                            </script>
                        <?php endif; ?>
                        <?php if (isset($_GET['origen']) && $_GET['origen'] !== '' && (!isset($_GET['destino']) || $_GET['destino'] === '')): ?>
                            <form method="GET" action="index.php">
                                <input type="hidden" name="controller" value="transferencia">
                                <input type="hidden" name="action" value="registrar">
                                <input type="hidden" name="origen" value="<?php echo htmlspecialchars($_GET['origen']); ?>">
                                <span class="text-gray-700 font-medium mb-2 block">Selecciona la sucursal destino</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">
                                    <?php foreach ($sucursales as $suc): ?>
                                        <?php if ($_GET['origen'] == $suc['id_sucursal']) continue; ?>
                                        <label class="w-full h-full cursor-pointer">
                                            <input type="radio" name="destino" value="<?= $suc['id_sucursal'] ?>" class="hidden"
                                                <?= (($_GET['destino'] ?? null) == $suc['id_sucursal']) ? 'checked' : '' ?>>
                                            <div class="border rounded-lg p-6 flex flex-col items-center transition border-gray-300 bg-white hover:border-purple-400 hover:bg-purple-100">
                                                <i class="fas <?= $suc['tipo_sucursal'] === 'almacen' ? 'fa-warehouse' : ($suc['tipo_sucursal'] === 'fisica' ? 'fa-store' : 'fa-globe') ?> fa-2x mb-2"></i>
                                                <span class="font-semibold text-gray-800"><?= htmlspecialchars($suc['nombre_sucursal']) ?></span>
                                                <span class="text-xs text-gray-500 capitalize"><?= htmlspecialchars($suc['tipo_sucursal']) ?></span>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <a href="index.php?controller=transferencia&action=registrar"
                                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition duration-300 ease-in-out flex items-center">Atrás</a>
                                    <button type="submit" id="btn-siguiente-destino"
                                        class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-300 ease-in-out"
                                        disabled>Siguiente</button>
                                </div>
                            </form>
                            <script>
                                const radiosDestino = document.querySelectorAll('input[name="destino"]');
                                const btnSiguienteDestino = document.getElementById('btn-siguiente-destino');
                                radiosDestino.forEach(radio => {
                                    radio.addEventListener('change', function () {
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
                <div class="relative w-full mt-12">
                    <div class="absolute top-4 right-6 flex items-center space-x-2 text-sm text-gray-600">
                        <i class="fas fa-user-circle fa-lg"></i>
                        <span><?php echo htmlspecialchars($_SESSION['usuario'] ?? ''); ?></span>
                    </div>
                    <h4 class="text-xl font-semibold mb-6 text-gray-800">Detalles de la Transferencia</h4>
                    <form method="POST" action="index.php?controller=transferencia&action=registrar">
                        <input type="hidden" name="origen" value="<?php echo htmlspecialchars($_GET['origen']); ?>">
                        <input type="hidden" name="destino" value="<?php echo htmlspecialchars($_GET['destino']); ?>">
                        <div class="mb-4">
                            <label for="producto" class="block text-gray-700 font-medium mb-1">Producto</label>
                            <select id="producto" name="producto"
                                class="w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-3 py-2 bg-gray-50"
                                required>
                                <option value="">Seleccione un producto...</option>
                                <?php foreach ($productos as $prod): ?>
                                    <option value="<?= htmlspecialchars($prod['id_producto']) ?>">
                                        <?= htmlspecialchars($prod['nombre_producto']) ?><?= $prod['talla_producto'] ? ' (' . htmlspecialchars($prod['talla_producto']) . ')' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div id="stock-info" class="mt-2 text-sm text-gray-600"></div>
                        </div>
                        <div class="mb-4">
                            <label for="cantidad" class="block text-gray-700 font-medium mb-1">Cantidad</label>
                            <input type="number" id="cantidad" name="cantidad" min="1"
                                class="w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-3 py-2 bg-gray-50"
                                required>
                        </div>
                        <div class="mb-6">
                            <label for="fecha" class="block text-gray-700 font-medium mb-1">Fecha</label>
                            <input type="date" id="fecha" name="fecha"
                                class="w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-3 py-2 bg-gray-50"
                                required value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="flex justify-end mt-8 gap-2">
                            <button type="submit"
                                class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">Registrar
                                Transferencia</button>
                        </div>
                    </form>
                    <div class="flex justify-end mt-2 gap-2">
                        <form method="GET" action="index.php" class="m-0">
                            <input type="hidden" name="controller" value="transferencia">
                            <input type="hidden" name="action" value="registrar">
                            <input type="hidden" name="origen" value="<?php echo htmlspecialchars($_GET['origen']); ?>">
                            <button type="submit"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition duration-300 ease-in-out">Atrás</button>
                        </form>
                    </div>
                </div>
                <script>
                    const selectProd = document.getElementById('producto');
                    const stockInfo = document.getElementById('stock-info');
                    const idSucursalOrigen = '<?php echo isset($_GET['origen']) ? htmlspecialchars($_GET['origen']) : ''; ?>';
                    if (selectProd) {
                        selectProd.addEventListener('change', function () {
                            const id = this.value;
                            if (!id) {
                                stockInfo.textContent = '';
                                return;
                            }
                            stockInfo.textContent = 'Cargando stock...';
                            fetch('index.php?controller=transferencia&action=registrar&ajax_stock=1&producto=' + encodeURIComponent(id) + '&sucursal=' + encodeURIComponent(idSucursalOrigen))
                                .then(r => r.text())
                                .then(stock => {
                                    stockInfo.innerHTML = 'Stock en sucursal origen: <span class="font-bold text-gray-900">' + stock + '</span>';
                                });
                        });
                        window.addEventListener('DOMContentLoaded', function () {
                            if (selectProd.value) {
                                selectProd.dispatchEvent(new Event('change'));
                            }
                        });
                    }
                </script>
            <?php endif; ?>
        </div>
    </main>
    <?php include_once __DIR__ . '/../../includes/footer.php'; ?>