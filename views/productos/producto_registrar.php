<?php
session_start();
include_once '../../conexion/cone.php';
include_once 'producto_queries.php';
include_once 'producto_utils.php';

verificarSesionAdmin();
verificarMetodoPost();

$ref = trim($_POST['ref_producto'] ?? '');
$nombre = trim($_POST['nombre_producto'] ?? '');
$descripcion = trim($_POST['descripcion_producto'] ?? '');
$id_subcategoria = $_POST['id_subcategoria'] ?? '';
$talla = trim($_POST['talla_producto'] ?? '');

// Verificar unicidad de la referencia
$result = pg_query_params($conn, "SELECT 1 FROM producto WHERE ref_producto = $1", array($ref));
if (pg_num_rows($result) > 0) {
    $ref = generarReferenciaUnicaBD($conn);
    if ($ref === false) {
        $msg = urlencode('No se pudo generar una referencia única. Intente de nuevo.');
        header('Location: ../../producto.php?error=2&msg=' . $msg);
        exit();
    }
}

$errores = validarCamposProducto($ref, $nombre, $id_subcategoria, $talla);
if (!empty($errores)) {
    $msg = urlencode(implode(', ', $errores));
    header('Location: ../../producto.php?error=2&msg=' . $msg);
    exit();
}

$sql = insertProductQuery();
$params = array($ref, $nombre, $descripcion, $id_subcategoria, $talla);
$result = pg_query_params($conn, $sql, $params);
manejarResultadoConsulta($result, $conn, '../../producto.php?success=2', '../../producto.php?error=1');
?> 