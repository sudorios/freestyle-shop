<?php
session_start();
include_once '../../conexion/cone.php';
include_once 'producto_queries.php';



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

$errores = [];
if (empty($producto_id)) {
    $errores[] = 'El producto es requerido';
}
if (empty($sucursal_id)) {
    $errores[] = 'La sucursal es requerida';
}
if (empty($ingreso_id)) {
    $errores[] = 'El ingreso es requerido';
}
if (empty($imagen_id)) {
    $errores[] = 'La imagen es requerida';
}

if ($estado_oferta === 'true') {
    if (empty($limite_oferta)) {
        $errores[] = 'La fecha límite de oferta es requerida cuando está en oferta';
    }
    if (empty($oferta)) {
        $errores[] = 'El porcentaje de descuento es requerido cuando está en oferta';
    }
    if ($oferta < 0 || $oferta > 100) {
        $errores[] = 'El porcentaje de descuento debe estar entre 0 y 100';
    }
}

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
?> 