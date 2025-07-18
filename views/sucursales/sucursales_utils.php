<?php

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

function verificarIdSucursal($id_sucursal)
{
    if (empty($id_sucursal) || !is_numeric($id_sucursal)) {
        header('Location: ../../sucursales.php?error=2');
        exit();
    }
}

function verificarExistenciaSucursal($conn, $nombre_sucursal, $id_excluir = null)
{
    include_once __DIR__ . '/sucursales_queries.php';
    if ($id_excluir) {
        $query = getSucursalByNombreExcludeIdQuery();
        $result = pg_query_params($conn, $query, array($nombre_sucursal, $id_excluir));
    } else {
        $query = getSucursalByNombreQuery();
        $result = pg_query_params($conn, $query, array($nombre_sucursal));
    }
    return pg_num_rows($result) > 0;
}

function verificarResultadoConsulta($result, $redirect_url = '../../sucursales.php', $error_code = 3)
{
    if (!$result || pg_num_rows($result) == 0) {
        header("Location: $redirect_url?error=$error_code");
        exit();
    }
}
?> 