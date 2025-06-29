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
        header('Location: categoria.php?error=2');
        exit();
    }
}

function verificarIdCategoria($id_categoria)
{
    if (empty($id_categoria) || !is_numeric($id_categoria)) {
        header('Location: categoria.php?error=2');
        exit();
    }
}

function verificarResultadoConsulta($result, $redirect_url = 'categoria.php', $error_code = 3)
{
    if (!$result || pg_num_rows($result) == 0) {
        header("Location: $redirect_url?error=$error_code");
        exit();
    }
}

function validarCamposCategoria($nombre_categoria, $descripcion_categoria, $estado_categoria)
{
    $errores = array();

    if (empty($nombre_categoria)) {
        $errores[] = "El nombre de la categoría es obligatorio";
    }

    if (empty($descripcion_categoria)) {
        $errores[] = "La descripción es obligatoria";
    }

    if (!in_array($estado_categoria, ['true', 'false'])) {
        $errores[] = "El estado no es válido";
    }

    return $errores;
}

function manejarResultadoConsulta($result, $conn, $success_url = 'categoria.php?success=2', $error_url = 'categoria.php?error=1')
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

function manejarErrores($errores, $redirect_url = 'categoria.php?error=1')
{
    if (!empty($errores)) {
        $error_msg = implode(', ', $errores);
        header("Location: $redirect_url&msg=" . urlencode($error_msg));
        exit();
    }
}

function verificarExistenciaCategoria($conn, $nombre_categoria, $id_excluir = null)
{
    include_once __DIR__ . '/categoria_queries.php';
    
    if ($id_excluir) {
        $query = getCategoriaByNombreExcludeIdQuery();
        $result = pg_query_params($conn, $query, array($nombre_categoria, $id_excluir));
    } else {
        $query = getCategoriaByNombreQuery();
        $result = pg_query_params($conn, $query, array($nombre_categoria));
    }
    
    return pg_num_rows($result) > 0;
}
?> 