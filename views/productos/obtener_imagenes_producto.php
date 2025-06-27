<?php
if (!isset($_GET['id_producto']) || !is_numeric($_GET['id_producto'])) {
    echo '<p class="text-gray-500">Producto no válido.</p>';
    exit;
}
include_once '../../conexion/cone.php';
$id_producto = intval($_GET['id_producto']);
$sql = "SELECT * FROM imagenes_producto WHERE producto_id = $1 ORDER BY creado_en DESC";
$result = pg_query_params($conn, $sql, array($id_producto));
if ($result && pg_num_rows($result) > 0) {
    echo '<div class="grid grid-cols-2 gap-4">';
    while ($img = pg_fetch_assoc($result)) {
        $tipo_vista = ($img['vista_producto'] == 1) ? 'Parte Frontal' : 'Parte Posterior';
        echo '<div class="flex flex-col items-center border-2 border-gray-300 rounded-lg shadow-md p-4 bg-white">';
        echo '<img src="' . htmlspecialchars($img['url_imagen']) . '" class="w-64 h-64 object-cover rounded mb-2 border border-gray-200 shadow-sm" />';
        echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full border border-indigo-400 mb-1">' . $tipo_vista . '</span>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p class="text-gray-500">No hay imágenes para este producto.</p>';
} 