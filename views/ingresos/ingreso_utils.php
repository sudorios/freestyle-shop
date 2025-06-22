<?php

function verificarSesionAdmin()
{
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
        header('Location: login.php');
        exit();
    }
}

function verificarMetodoPost()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ingreso.php?error=2');
        exit();
    }
}

function verificarIdIngreso($id_ingreso)
{
    if (empty($id_ingreso) || !is_numeric($id_ingreso)) {
        header('Location: ingreso.php?error=2');
        exit();
    }
}

function verificarResultadoConsulta($result, $redirect_url = 'ingreso.php', $error_code = 3)
{
    if (!$result || pg_num_rows($result) == 0) {
        header("Location: $redirect_url?error=$error_code");
        exit();
    }
}

function validarCamposIngreso($referencia, $id_producto, $precio_costo, $precio_venta, $cantidad, $fecha_ingreso)
{
    $errores = array();
    
    if (empty($referencia)) {
        $errores[] = "La referencia es obligatoria";
    }
    
    if (empty($id_producto) || !is_numeric($id_producto)) {
        $errores[] = "El producto es obligatorio y debe ser válido";
    }
    
    if (empty($precio_costo) || !is_numeric($precio_costo) || $precio_costo <= 0) {
        $errores[] = "El precio de costo es obligatorio y debe ser mayor a 0";
    }
    
    if (empty($precio_venta) || !is_numeric($precio_venta) || $precio_venta <= 0) {
        $errores[] = "El precio de venta es obligatorio y debe ser mayor a 0";
    }
    
    if (empty($cantidad) || !is_numeric($cantidad) || $cantidad <= 0) {
        $errores[] = "La cantidad es obligatoria y debe ser mayor a 0";
    }
    
    if (empty($fecha_ingreso)) {
        $errores[] = "La fecha de ingreso es obligatoria";
    }
    
    return $errores;
}

function manejarResultadoConsulta($result, $conn, $success_url = 'ingreso.php?success=2', $error_url = 'ingreso.php?error=1')
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
        header('Location: login.php');
        exit();
    }
    return $_SESSION['id'];
}

function calcularPrecioCostoConIgv($precio_costo, $igv = 0.18)
{
    return $precio_costo * (1 + $igv);
}

function calcularUtilidadEsperada($precio_venta, $precio_costo_unidad, $cantidad)
{
    $utilidad_unidad = $precio_venta - $precio_costo_unidad;
    return $utilidad_unidad * $cantidad;
}

function calcularUtilidadNeta($precio_venta, $precio_costo_con_igv_unidad, $cantidad)
{
    $utilidad_neta_unidad = $precio_venta - $precio_costo_con_igv_unidad;
    return $utilidad_neta_unidad * $cantidad;
}

function formatearFecha($fecha)
{
    return date('Y-m-d', strtotime($fecha));
}

function validarFecha($fecha)
{
    $fecha_actual = date('Y-m-d');
    $fecha_ingreso = date('Y-m-d', strtotime($fecha));
    
    if ($fecha_ingreso > $fecha_actual) {
        return false;
    }
    
    return true;
}
?> 