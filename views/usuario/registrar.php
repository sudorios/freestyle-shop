<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../conexion/cone.php';
include_once __DIR__ . '/usuario_queries.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["txtname"]);
    $correo = trim($_POST["txtcorreo"]);
    $nickname = trim($_POST["txtnick"]);
    $contrasenia = trim($_POST["txtpass"]);
    $telefono = trim($_POST["txttelefono"]);
    $direccion = trim($_POST["txtdireccion"]);
    $hash = password_hash($contrasenia, PASSWORD_DEFAULT);
    
    $estado = true;
    $rol = 'cliente';
    $creado_en = date('Y-m-d H:i:s');

    $query = getUsuarioByEmail();
    $result = pg_query_params($conn, $query, array($correo));

    if (pg_num_rows($result) > 0) {
        header('Location: ../../login_add.php?error=correo');
        exit;
    } else {
        $query2 = getUsuarioByNickname();
        $result2 = pg_query_params($conn, $query2, array($nickname));

        if (pg_num_rows($result2) > 0) {
            header('Location: ../../login_add.php?error=nick');
            exit;
        } else {
            $query3 = insertUsuario();
            $result3 = pg_query_params($conn, $query3, array(
                $nombre,
                $correo, 
                $nickname, 
                $hash, 
                $telefono, 
                $direccion, 
                $estado, 
                $rol, 
                $creado_en
            ));

            if ($result3) {
                header('Location: ../../login_add.php?success=1');
                exit;
            } else {
                header('Location: ../../login_add.php?error=registro');
                exit;
            }
        }
        pg_free_result($result2);
    }
    pg_free_result($result);
}