<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once './conexion/cone.php';

if (!$conn) {
    die('Error de conexión: ' . pg_last_error($conn));
}

$sql = "SELECT 
    cp.id,
    p.nombre_producto,
    ip.url_imagen,
    i.precio_venta,
    cp.estado,
    cp.estado_oferta,
    cp.limite_oferta,
    cp.oferta,
    (i.precio_venta * (1 - (cp.oferta / 100))) AS precio_con_descuento
FROM 
    catalogo_productos cp
JOIN 
    producto p ON cp.producto_id = p.id_producto
JOIN 
    ingreso i ON cp.ingreso_id = i.id
JOIN 
    imagenes_producto ip ON cp.imagen_id = ip.id
WHERE
    cp.sucursal_id = 7
ORDER BY 
    cp.id ASC;";
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
            <?php
            // Mostrar mensajes de éxito o error
            if (isset($_GET['success'])) {
                $msg = $_GET['msg'] ?? 'Operación exitosa';
                echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">' . htmlspecialchars($msg) . '</div>';
            }
            if (isset($_GET['error'])) {
                $msg = $_GET['msg'] ?? 'Error en la operación';
                echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">' . htmlspecialchars($msg) . '</div>';
            }
            ?>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Catálogo de Productos</h3>
                <button onclick="abrirModalAgregarCatalogo()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    Agregar Producto
                </button>
            </div>
            <hr class="my-4 border-t-2 border-gray-200 rounded-full opacity-80">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Venta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Oferta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio con Descuento</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = pg_fetch_assoc($result)) { ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($row['url_imagen']) { ?>
                                            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded" onclick="mostrarImagenModal('<?php echo htmlspecialchars($row['url_imagen']); ?>')">Ver imagen</button>
                                        <?php } else { ?>
                                            <span class="text-gray-400">Sin imagen</span>
                                        <?php } ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo ($row['precio_venta'] !== null) ? 'S/ ' . number_format($row['precio_venta'], 2) : '<span class="text-gray-400">-</span>'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo ($row['estado'] == 1 || $row['estado'] === 't') ? '<span class="text-green-600">Activo</span>' : '<span class="text-red-600">Inactivo</span>'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($row['estado_oferta'] == 1 || $row['estado_oferta'] === 't') { ?>
                                            <div>
                                                <span class="text-orange-600 font-semibold">En Oferta</span>
                                                <br>
                                                <small class="text-gray-500">Límite: <?php echo date('d/m/Y', strtotime($row['limite_oferta'])); ?></small>
                                                <?php if ($row['oferta'] !== null) { ?>
                                                    <br>
                                                    <small class="text-red-600">Descuento: <?php echo htmlspecialchars($row['oferta']); ?>%</small>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <span class="text-gray-400">Sin oferta</span>
                                        <?php } ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo ($row['precio_con_descuento'] !== null) ? 'S/ ' . number_format($row['precio_con_descuento'], 2) : '<span class="text-gray-400">-</span>'; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Modal Agregar al Catálogo -->
    <?php include_once './views/productos/modals/modal_agregar_catalogo.php'; ?>
    
    <!-- Modal para mostrar imagen grande -->
    <div id="modalImagen" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
        <div class="relative bg-white rounded-lg shadow-lg p-4 max-w-2xl w-full flex flex-col items-center">
            <button onclick="cerrarModalImagen()" class="absolute top-2 right-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full w-8 h-8 flex items-center justify-center">&times;</button>
            <img id="imagenModalGrande" src="" alt="Imagen producto" class="max-h-[70vh] w-auto rounded" />
        </div>
    </div>
    
    <script>
        function abrirModalAgregarCatalogo() {
            document.getElementById('modalBackgroundAgregarCatalogo').classList.remove('hidden');
            document.getElementById('modalAgregarCatalogo').classList.remove('hidden');
        }

        function cerrarModalAgregarCatalogo() {
            document.getElementById('modalBackgroundAgregarCatalogo').classList.add('hidden');
            document.getElementById('modalAgregarCatalogo').classList.add('hidden');
        }

        // Cerrar modal al hacer clic en el fondo
        document.getElementById('modalBackgroundAgregarCatalogo').addEventListener('click', function() {
            cerrarModalAgregarCatalogo();
        });

        // Validación del formulario
        document.getElementById('formAgregarCatalogo').addEventListener('submit', function(e) {
            const estadoOferta = document.getElementById('estado_oferta').value;
            const limiteOferta = document.getElementById('limite_oferta').value;
            const oferta = document.getElementById('oferta').value;

            if (estadoOferta === 'true') {
                if (!limiteOferta) {
                    e.preventDefault();
                    alert('La fecha límite de oferta es requerida cuando está en oferta');
                    return false;
                }
                if (!oferta) {
                    e.preventDefault();
                    alert('El porcentaje de descuento es requerido cuando está en oferta');
                    return false;
                }
                if (oferta < 0 || oferta > 100) {
                    e.preventDefault();
                    alert('El porcentaje de descuento debe estar entre 0 y 100');
                    return false;
                }
            }
        });

        // Mostrar/ocultar campos de oferta según el estado
        document.getElementById('estado_oferta').addEventListener('change', function() {
            const limiteOfertaDiv = document.getElementById('limite_oferta').parentElement;
            const ofertaDiv = document.getElementById('oferta').parentElement;
            
            if (this.value === 'true') {
                limiteOfertaDiv.style.display = 'block';
                ofertaDiv.style.display = 'block';
                document.getElementById('limite_oferta').required = true;
                document.getElementById('oferta').required = true;
            } else {
                limiteOfertaDiv.style.display = 'none';
                ofertaDiv.style.display = 'none';
                document.getElementById('limite_oferta').required = false;
                document.getElementById('oferta').required = false;
            }
        });

        function mostrarImagenModal(url) {
            document.getElementById('imagenModalGrande').src = url;
            document.getElementById('modalImagen').classList.remove('hidden');
        }
        function cerrarModalImagen() {
            document.getElementById('modalImagen').classList.add('hidden');
            document.getElementById('imagenModalGrande').src = '';
        }
        // Cerrar modal al hacer clic fuera de la imagen
        const modalImagen = document.getElementById('modalImagen');
        modalImagen.addEventListener('click', function(e) {
            if (e.target === modalImagen) {
                cerrarModalImagen();
            }
        });
        // Cerrar modal con Escape
        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                cerrarModalImagen();
            }
        });
       
    </script>
    
    <?php include_once './includes/footer.php'; ?>
</body>
</html> 