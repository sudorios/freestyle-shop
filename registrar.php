<?php

include_once 'conexion/cone.php';

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

    $query = "SELECT id_usuario FROM usuario WHERE email_usuario = $1";
    $result = pg_query_params($conn, $query, array($correo));

    if (pg_num_rows($result) > 0) {
        echo "El correo electrónico ya está registrado...";
    } else {
        $query2 = "SELECT id_usuario FROM usuario WHERE ref_usuario = $1";
        $result2 = pg_query_params($conn, $query2, array($nickname));

        if (pg_num_rows($result2) > 0) {
            echo "El nickname ya está en uso...";
        } else {
            $query3 = "INSERT INTO usuario(nombre_usuario, email_usuario, ref_usuario, pass_usuario, telefono_usuario, direccion_usuario, estado_usuario, rol_usuario, creado_en) 
                      VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)";
            
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
                echo "Usuario registrado correctamente.";
                header("refresh:2;url=login.php");
            } else {
                echo "Error al registrar al usuario.";
            }
        }
        pg_free_result($result2);
    }
    pg_free_result($result);
}
?>