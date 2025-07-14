<?php
session_start();
include_once '../../conexion/cone.php';
include_once 'catalogo_queries.php';
include_once 'catalogo_utils.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../catalogo_producto.php?error=1&msg=' . urlencode('Método no permitido'));
    exit();
}

$producto_id = $_POST['producto_id'] ?? '';
$sucursal_id = 7;
$ingreso_id = $_POST['ingreso_id'] ?? '';
$imagen_id = $_POST['imagen_id'] ?? '';
$estado = $_POST['estado'] === 'true' ? true : false;
$estado_oferta = (isset($_POST['estado_oferta']) && $_POST['estado_oferta'] === 'true') ? true : false;
$estado_oferta = $estado_oferta ? 'true' : 'false';
$limite_oferta = isset($_POST['limite_oferta']) && $_POST['limite_oferta'] !== '' ? $_POST['limite_oferta'] : null;
$oferta = isset($_POST['oferta']) && $_POST['oferta'] !== '' ? $_POST['oferta'] : null;

$data = [
    'producto_id' => $producto_id,
    'sucursal_id' => $sucursal_id,
    'ingreso_id' => $ingreso_id,
    'imagen_id' => $imagen_id,
    'estado_oferta' => $estado_oferta,
    'limite_oferta' => $limite_oferta,
    'oferta' => $oferta
];

$errores = validarDatosCatalogo($data);
if (!empty($errores)) {
    $msg = urlencode(implode(', ', $errores));
    header('Location: ../../catalogo_producto.php?error=2&msg=' . $msg);
    exit();
}

$sql_check = checkCatalogoProductoExistsQuery();
$result_check = pg_query_params($conn, $sql_check, array($producto_id, $sucursal_id));
if (pg_num_rows($result_check) > 0) {
    $msg = urlencode('Este producto ya existe en el catálogo para la sucursal seleccionada');
    header('Location: ../../catalogo_producto.php?error=2&msg=' . $msg);
    exit();
}

$sql = insertCatalogoProductoQuery();
$params = array(
    $producto_id,
    $sucursal_id,
    $ingreso_id,
    $imagen_id,
    $estado,
    $estado_oferta,
    $limite_oferta,
    $oferta
);

$result = pg_query_params($conn, $sql, $params);

if ($result) {
    $msg = urlencode('Producto agregado al catálogo exitosamente');
    header('Location: ../../catalogo_producto.php?success=1&msg=' . $msg);
} else {
    $error_msg = pg_last_error($conn);
    $msg = urlencode('Error al agregar producto al catálogo: ' . $error_msg);
    header('Location: ../../catalogo_producto.php?error=1&msg=' . $msg);
}
exit(); 