<?php
// Modal para asignar imágenes a un producto
?>
<div id="modalBackgroundImagenesProducto" class="fixed inset-0 hidden bg-black opacity-50 z-40"></div>
<div id="modalImagenesProducto" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-[500px] shadow-lg rounded-md bg-white">
        <button onclick="cerrarModalImagenesProducto()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        <h3 class="text-xl font-bold mb-4">Imágenes del Producto</h3>
        <form id="formSubirImagenProducto" action="views/productos/subir_imagen_producto.php" method="POST" enctype="multipart/form-data" class="mb-4">
            <input type="hidden" name="id_producto" id="idProductoImagen">
            <div class="mb-2">
                <label class="block text-sm font-semibold mb-1">Seleccionar imagen</label>
                <input type="file" name="imagen_producto" accept="image/*" required class="border rounded px-3 py-2 w-full">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded">Subir Imagen</button>
        </form>
        <div id="listaImagenesProducto" class="mt-4">
            <?php
            if (isset($_GET['id_producto'])) {
                include_once '../../conexion/cone.php';
                $id_producto = intval($_GET['id_producto']);
                $sql = "SELECT * FROM imagenes_producto WHERE producto_id = $1 ORDER BY creado_en DESC";
                $result = pg_query_params($conn, $sql, array($id_producto));
                if ($result && pg_num_rows($result) > 0) {
                    echo '<div class="grid grid-cols-2 gap-4">';
                    while ($img = pg_fetch_assoc($result)) {
                        echo '<div class="flex flex-col items-center">';
                        echo '<img src="../../' . htmlspecialchars($img['url_imagen']) . '" class="w-32 h-32 object-cover rounded mb-2" />';
                        echo '<span class="text-xs">' . htmlspecialchars($img['creado_en']) . '</span>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<p class="text-gray-500">No hay imágenes para este producto.</p>';
                }
            } else {
                echo '<p class="text-gray-500">Seleccione un producto para ver sus imágenes.</p>';
            }
            ?>
        </div>
    </div>
</div> 