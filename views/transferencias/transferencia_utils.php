<?php

function verificarMetodoPost()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../../transferencia.php?error=2');
        exit();
    }
}

function verificarIdTransferencia($id_transferencia)
{
    if (empty($id_transferencia) || !is_numeric($id_transferencia)) {
        header('Location: ../../transferencia.php?error=2');
        exit();
    }
}

function verificarResultadoConsulta($result, $redirect_url = '../../transferencia.php', $error_code = 3)
{
    if (!$result || pg_num_rows($result) == 0) {
        header("Location: $redirect_url?error=$error_code");
        exit();
    }
}

function validarCamposTransferencia($origen, $destino, $producto, $cantidad, $fecha)
{
    $errores = array();
    if (empty($origen) || !is_numeric($origen)) {
        $errores[] = "La sucursal de origen es obligatoria y debe ser válida";
    }
    if (empty($destino) || !is_numeric($destino)) {
        $errores[] = "La sucursal de destino es obligatoria y debe ser válida";
    }
    if ($origen == $destino) {
        $errores[] = "La sucursal de origen y destino no pueden ser la misma";
    }
    if (empty($producto) || !is_numeric($producto)) {
        $errores[] = "El producto es obligatorio y debe ser válido";
    }
    if (empty($cantidad) || !is_numeric($cantidad) || $cantidad <= 0) {
        $errores[] = "La cantidad es obligatoria y debe ser mayor a 0";
    }
    if (empty($fecha)) {
        $errores[] = "La fecha es obligatoria";
    }
    return $errores;
}

function manejarResultadoConsulta($result, $conn, $success_url = '../../transferencia.php?success=2', $error_url = '../../transferencia.php?error=1')
{
    if ($result) {
        header("Location: $success_url");
        exit();
    } else {
        $error_msg = pg_last_error($conn);
        header("Location: $error_url&msg=" . urlencode('Error al procesar: ' . $error_msg));
        exit();
    }
}

function obtenerIdUsuarioSesion()
{
    if (!isset($_SESSION['id'])) {
        header('Location: ../../index.php?controller=usuario&action=login');
        exit();
    }
    return $_SESSION['id'];
}

function formatearFecha($fecha)
{
    return date('Y-m-d', strtotime($fecha));
}

function validarFecha($fecha)
{
    $fecha_actual = date('Y-m-d');
    $fecha_transferencia = date('Y-m-d', strtotime($fecha));
    if ($fecha_transferencia > $fecha_actual) {
        return false;
    }
    return true;
} 

function obtenerSucursalesActivasConTipo($conn) {
    $sucursales = [];
    $res = pg_query($conn, getSucursalesActivasConTipoQuery());
    while ($row = pg_fetch_assoc($res)) {
        $sucursales[] = $row;
    }
    return $sucursales;
}

function obtenerFiltrosTransferencia() {
    $fecha_inicio = isset($_GET['fecha_inicio']) && $_GET['fecha_inicio'] !== '' ? $_GET['fecha_inicio'] : '';
    $fecha_fin = isset($_GET['fecha_fin']) && $_GET['fecha_fin'] !== '' ? $_GET['fecha_fin'] : '';
    $filtro_origen = isset($_GET['origen']) && $_GET['origen'] !== '' ? $_GET['origen'] : '';
    $filtro_destino = isset($_GET['destino']) && $_GET['destino'] !== '' ? $_GET['destino'] : '';
    return [
        'fecha_inicio' => $fecha_inicio,
        'fecha_fin' => $fecha_fin,
        'origen' => $filtro_origen,
        'destino' => $filtro_destino
    ];
} 

function obtenerTipoSucursal($sucursales, $id_sucursal) {
    foreach ($sucursales as $suc) {
        if ($suc['id_sucursal'] == $id_sucursal) {
            return $suc['tipo_sucursal'] ?? '';
        }
    }
    return '';
}

function renderOpcionesSucursales($sucursales, $selected = null, $excluir = null) {
    foreach ($sucursales as $suc) {
        if ($excluir !== null && $suc['id_sucursal'] == $excluir) continue;
        $isSelected = ($selected == $suc['id_sucursal']) ? 'selected' : '';
        echo '<option value="' . $suc['id_sucursal'] . '" ' . $isSelected . '>' . htmlspecialchars($suc['nombre_sucursal']) . '</option>';
    }
}

function renderOpcionesProductos($conn, $selected = null) {
    $sql_prod = getProductosActivosQuery();
    $res_prod = pg_query($conn, $sql_prod);
    $num_prod = pg_num_rows($res_prod);
    if ($num_prod == 0) {
        echo '<option disabled>No hay productos activos</option>';
    }
    while ($prod = pg_fetch_assoc($res_prod)) {
        $isSelected = ($selected == $prod['id_producto']) ? 'selected' : '';
        $nombre = htmlspecialchars($prod['nombre_producto']);
        $talla = htmlspecialchars($prod['talla_producto']);
        $texto = $nombre . ($talla ? '(' . $talla . ')' : '');
        echo '<option value="' . $prod['id_producto'] . '" ' . $isSelected . '>' . $texto . '</option>';
    }
} 

function renderRadiosSucursales($sucursales, $name, $selected = null, $excluir = null) {
    foreach ($sucursales as $suc) {
        if ($excluir !== null && $suc['id_sucursal'] == $excluir) continue;
        $isChecked = ($selected == $suc['id_sucursal']) ? 'checked' : '';
        $icon = ($suc['tipo_sucursal'] === 'almacen') ? 'fa-warehouse' : (($suc['tipo_sucursal'] === 'fisica') ? 'fa-store' : 'fa-globe');
        echo '<label class="w-full h-full cursor-pointer">';
        echo '<input type="radio" name="' . $name . '" value="' . $suc['id_sucursal'] . '" class="hidden" ' . $isChecked . '>';
        echo '<div class="border rounded-lg p-4 flex flex-col items-center transition border-gray-300 bg-white hover:border-purple-400 hover:bg-purple-100">';
        echo '<i class="fas ' . $icon . ' fa-2x mb-2"></i>';
        echo '<span class="font-semibold text-gray-800">' . htmlspecialchars($suc['nombre_sucursal']) . '</span>';
        echo '<span class="text-xs text-gray-500 capitalize">' . htmlspecialchars($suc['tipo_sucursal']) . '</span>';
        echo '</div>';
        echo '</label>';
    }
} 

function obtenerStockProductoSucursal($conn, $producto, $sucursal) {
    $id_producto = pg_escape_string($conn, $producto);
    $id_sucursal = pg_escape_string($conn, $sucursal);
    $sql_stock = "SELECT COALESCE(cantidad, 0) AS total_stock FROM inventario_sucursal WHERE id_producto = '$id_producto' AND id_sucursal = '$id_sucursal'";
    $res_stock = pg_query($conn, $sql_stock);
    $row_stock = pg_fetch_assoc($res_stock);
    return $row_stock ? $row_stock['total_stock'] : 0;
}