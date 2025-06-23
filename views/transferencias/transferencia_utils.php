<?php
// Funciones auxiliares para transferencias 

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
        header('Location: login.php');
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