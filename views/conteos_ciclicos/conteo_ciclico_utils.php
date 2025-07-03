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
        header('Location: ../../conteo_ciclico.php?error=2');
        exit();
    }
}

function verificarIdConteo($id_conteo)
{
    if (empty($id_conteo) || !is_numeric($id_conteo)) {
        header('Location: ../../conteo_ciclico.php?error=2');
        exit();
    }
}

function verificarResultadoConsulta($result, $redirect_url = '../../conteo_ciclico.php', $error_code = 3)
{
    if (!$result || pg_num_rows($result) == 0) {
        header("Location: $redirect_url?error=$error_code");
        exit();
    }
}

function validarCamposConteoCiclico($cantidad_real, $cantidad_sistema, $fecha_conteo)
{
    $errores = array();
    if ($cantidad_real === '' || !is_numeric($cantidad_real)) {
        $errores[] = "La cantidad real es obligatoria y debe ser numérica";
    }
    if ($cantidad_sistema === '' || !is_numeric($cantidad_sistema)) {
        $errores[] = "La cantidad del sistema es obligatoria y debe ser numérica";
    }
    if (empty($fecha_conteo)) {
        $errores[] = "La fecha es obligatoria";
    }
    return $errores;
}

function manejarResultadoConsulta($result, $conn, $success_url = '../../conteo_ciclico.php?success=2', $error_url = '../../conteo_ciclico.php?error=1')
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