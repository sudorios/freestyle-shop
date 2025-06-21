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
        header('Location: usuario.php?error=2');
        exit();
    }
}

function verificarIdUsuario($id_usuario)
{
    if (empty($id_usuario) || !is_numeric($id_usuario)) {
        header('Location: usuario.php?error=2');
        exit();
    }
}

function verificarResultadoConsulta($result, $redirect_url = 'usuario.php', $error_code = 3)
{
    if (!$result || pg_num_rows($result) == 0) {
        header("Location: $redirect_url?error=$error_code");
        exit();
    }
}

function validarCamposUsuario($nombre_usuario, $email_usuario, $ref_usuario, $telefono_usuario, $direccion_usuario, $rol_usuario, $estado_usuario)
{
    $errores = array();

    if (empty($nombre_usuario)) {
        $errores[] = "El nombre es obligatorio";
    }

    if (empty($email_usuario)) {
        $errores[] = "El email es obligatorio";
    } elseif (!filter_var($email_usuario, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato del email no es válido";
    }

    if (empty($ref_usuario)) {
        $errores[] = "El nickname es obligatorio";
    }

    if (empty($telefono_usuario)) {
        $errores[] = "El teléfono es obligatorio";
    }

    if (empty($direccion_usuario)) {
        $errores[] = "La dirección es obligatoria";
    }

    if (!in_array($rol_usuario, ['cliente', 'admin'])) {
        $errores[] = "El rol no es válido";
    }

    if (!in_array($estado_usuario, ['true', 'false'])) {
        $errores[] = "El estado no es válido";
    }

    return $errores;
}

function validarCamposPassword($password_nueva, $password_confirmar)
{
    $errores = array();
    if (empty($password_nueva)) {
        $errores[] = "La nueva contraseña es obligatoria";
    }
    
    if (strlen($password_nueva) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres";
    }
    
    if ($password_nueva !== $password_confirmar) {
        $errores[] = "Las contraseñas no coinciden";
    }
    
    if (!empty($errores)) {
        $error_msg = implode(', ', $errores);
        header('Location: usuario.php?error=1&msg=' . urlencode($error_msg));
        exit();
    }    
}

function manejarResultadoConsulta($result, $conn, $success_url = 'usuario.php?success=2', $error_url = 'usuario.php?error=1')
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
