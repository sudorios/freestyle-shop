<?php

function verificarSesionAdminInventario()
{
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
        header('Location: ../../login.php');
        exit();
    }
}

function verificarMetodoPostInventario()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../../inventario.php?error=2');
        exit();
    }
}

function verificarIdInventario($id_inventario)
{
    if (empty($id_inventario) || !is_numeric($id_inventario)) {
        header('Location: ../../inventario.php?error=2');
        exit();
    }
}

function verificarResultadoConsultaInventario($result, $redirect_url = '../../inventario.php', $error_code = 3)
{
    if (!$result || pg_num_rows($result) == 0) {
        header("Location: $redirect_url?error=$error_code");
        exit();
    }
}

function validarCamposInventario($cantidad, $fecha_actualizacion)
{
    $errores = array();
    if ($cantidad === '' || !is_numeric($cantidad)) {
        $errores[] = "La cantidad es obligatoria y debe ser numérica";
    }
    if (empty($fecha_actualizacion)) {
        $errores[] = "La fecha de actualización es obligatoria";
    }
    return $errores;
}

function manejarResultadoConsultaInventario($result, $conn, $success_url = '../../inventario.php?success=2', $error_url = '../../inventario.php?error=1')
{
    if ($result) {
        header("Location: $success_url");
        exit();
    } else {
        $error_msg = pg_last_error($conn);
        header("Location: $error_url&msg=" . urlencode('Error al actualizar: ' . $error_msg));
        exit();
    }
} 