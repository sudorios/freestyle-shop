<?php
// Archivo: views/productos/producto_utils.php

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
        header('Location: producto.php?error=2');
        exit();
    }
}

function verificarIdProducto($id_producto)
{
    if (empty($id_producto) || !is_numeric($id_producto)) {
        header('Location: producto.php?error=2');
        exit();
    }
}

function verificarResultadoConsulta($result, $redirect_url = 'producto.php', $error_code = 3)
{
    if (!$result || pg_num_rows($result) == 0) {
        header("Location: $redirect_url?error=$error_code");
        exit();
    }
}

function validarCamposProducto($ref, $nombre, $id_subcategoria, $talla)
{
    $errores = array();
    if (empty($ref)) {
        $errores[] = "La referencia es obligatoria";
    }
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio";
    }
    if (empty($id_subcategoria) || !is_numeric($id_subcategoria)) {
        $errores[] = "La subcategoría es obligatoria y debe ser válida";
    }
    if (empty($talla)) {
        $errores[] = "La talla es obligatoria";
    }
    return $errores;
}

function manejarResultadoConsulta($result, $conn, $success_url = 'producto.php?success=2', $error_url = 'producto.php?error=1')
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

function generarReferenciaUnicaBD($conn) {
    $intentos = 0;
    do {
        $ref = strval(rand(10000000, 99999999)); 
        $result = pg_query_params($conn, "SELECT 1 FROM producto WHERE ref_producto = $1", array($ref));
        $existe = pg_num_rows($result) > 0;
        $intentos++;
    } while ($existe && $intentos < 5);
    if ($existe) {
        return false;
    }
    return $ref;
} 