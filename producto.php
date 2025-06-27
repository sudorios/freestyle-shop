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

$sql = "SELECT p.*, s.nombre_subcategoria 
        FROM producto p 
        LEFT JOIN subcategoria s ON p.id_subcategoria = s.id_subcategoria 
        WHERE p.estado = true 
        ORDER BY p.id_producto ASC";

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
                <meta http-equiv="refresh" content="3;url=producto.php">
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>
                    <span class="block sm:inline">Ocurrió un error. Código: <?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
            <?php endif; ?>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Listado de Productos</h3>
                <a href="#" onclick="abrirModalAgregarProducto()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    Agregar Producto
                </a>
            </div>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Codigo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referencia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subcategoría</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Talla</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = pg_fetch_assoc($result)) { ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['id_producto']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['ref_producto']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_subcategoria']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['talla_producto']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-2" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="cursor-pointer bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded" onclick="return confirm('¿Seguro que deseas eliminar este producto?');" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <button type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded ml-2" onclick="mostrarCodigoBarrasProducto('<?php echo htmlspecialchars($row['ref_producto']); ?>')" title="Código de Barras">
                                            <i class="fas fa-barcode"></i>
                                        </button>
                                        <button type="button" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded ml-2" onclick="abrirModalImagenesProducto(<?php echo $row['id_producto']; ?>)" title="Imágenes">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php include 'views/productos/modals/modal_agregar_producto.php'; ?>
    <div id="modalBackground" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
    <div id="modalCodigoBarras" class="fixed inset-0 hidden z-40 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-8 flex flex-col items-center relative">
            <button onclick="cerrarModalCodigoBarras()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
            <h3 class="text-lg font-bold mb-4">Código de Barras</h3>
            <svg id="barcode"></svg>
            <button id="descargarBarcode" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Descargar</button>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
    function mostrarCodigoBarrasProducto(ref) {
        document.getElementById('modalCodigoBarras').classList.remove('hidden');
        document.getElementById('modalBackground').classList.remove('hidden');
        JsBarcode("#barcode", ref, {
            format: "CODE128",
            lineColor: "#000",
            width: 2,
            height: 80,
            displayValue: true
        });
        // Descargar como imagen
        document.getElementById('descargarBarcode').onclick = function() {
            var svg = document.getElementById('barcode');
            var serializer = new XMLSerializer();
            var source = serializer.serializeToString(svg);
            var image = new Image();
            image.src = 'data:image/svg+xml;base64,' + window.btoa(source);
            var link = document.createElement('a');
            link.href = image.src;
            link.download = ref + '_barcode.svg';
            link.click();
        };
    }
    function cerrarModalCodigoBarras() {
        document.getElementById('modalCodigoBarras').classList.add('hidden');
        document.getElementById('modalBackground').classList.add('hidden');
        document.getElementById('barcode').innerHTML = '';
    }
    function abrirModalImagenesProducto(idProducto) {
        document.getElementById('modalImagenesProducto').classList.remove('hidden');
        document.getElementById('modalBackgroundImagenesProducto').classList.remove('hidden');
        document.getElementById('listaImagenesProducto').innerHTML = '<p class="text-gray-500">Cargando imágenes...</p>';
        document.getElementById('formNuevaImagenProducto').classList.add('hidden');
        document.getElementById('btnMostrarFormImagen').classList.add('hidden');
        fetch('views/productos/obtener_imagenes_producto.php?id_producto=' + idProducto)
            .then(response => response.text())
            .then(html => {
                document.getElementById('listaImagenesProducto').innerHTML = html;
                document.getElementById('idProductoImagenForm').value = idProducto;
                if (html.includes('No hay imágenes para este producto')) {
                    document.getElementById('formNuevaImagenProducto').classList.remove('hidden');
                    document.getElementById('btnMostrarFormImagen').classList.add('hidden');
                } else {
                    document.getElementById('formNuevaImagenProducto').classList.add('hidden');
                    document.getElementById('btnMostrarFormImagen').classList.remove('hidden');
                }
            })
            .catch(() => {
                document.getElementById('listaImagenesProducto').innerHTML = '<p class="text-red-500">Error al cargar las imágenes.</p>';
                document.getElementById('formNuevaImagenProducto').classList.add('hidden');
                document.getElementById('btnMostrarFormImagen').classList.add('hidden');
            });
    }
    function cerrarModalImagenesProducto() {
        document.getElementById('modalImagenesProducto').classList.add('hidden');
        document.getElementById('modalBackgroundImagenesProducto').classList.add('hidden');
    }
    </script>
    
    <?php include 'views/productos/modals/modal_imagenes_producto.php'; ?>
    <?php include_once './includes/footer.php'; ?>
</body>
</html> 