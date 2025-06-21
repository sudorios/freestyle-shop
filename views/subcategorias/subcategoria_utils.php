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
        header('Location: subcategoria.php?error=2');
        exit();
    }
}

function verificarIdSubcategoria($id_subcategoria)
{
    if (empty($id_subcategoria) || !is_numeric($id_subcategoria)) {
        header('Location: subcategoria.php?error=2');
        exit();
    }
}

function verificarResultadoConsulta($result, $redirect_url = 'subcategoria.php', $error_code = 3)
{
    if (!$result || pg_num_rows($result) == 0) {
        header("Location: $redirect_url?error=$error_code");
        exit();
    }
}

function validarCamposSubcategoria($nombre, $id_categoria)
{
    $errores = array();
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio";
    }
    if (empty($id_categoria) || !is_numeric($id_categoria)) {
        $errores[] = "La categoría es obligatoria y debe ser válida";
    }
    return $errores;
}

function manejarResultadoConsulta($result, $conn, $success_url = 'subcategoria.php?success=2', $error_url = 'subcategoria.php?error=1')
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