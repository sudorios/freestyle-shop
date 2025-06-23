<?php

function verificarSesionAdmin()
{
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
        header('Location: ../../login.php');
        exit();
    }
}

function verificarMetodoPost()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../../sucursales.php?error=2');
        exit();
    }
}

function validarCamposSucursal($nombre, $direccion, $tipo, $id_supervisor)
{
    $errores = array();
    if (empty($nombre)) {
        $errores[] = "El nombre de la sucursal es obligatorio";
    }
    if (empty($direccion)) {
        $errores[] = "La dirección es obligatoria";
    }
    if (empty($tipo)) {
        $errores[] = "El tipo de sucursal es obligatorio";
    }
    if (empty($id_supervisor)) {
        $errores[] = "El supervisor es obligatorio";
    }
    return $errores;
}

function manejarResultadoConsulta($result, $conn, $success_url = '../../sucursales.php?success=2', $error_url = '../../sucursales.php?error=1')
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
?> 