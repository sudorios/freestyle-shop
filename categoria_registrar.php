<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include_once 'conexion/cone.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["txtnombre"]);
    $descripcion = trim($_POST["txtdescripcion"]);
    $estado = $_POST["txtestado"] == '1' ? true : false;
    $creado_en = date('Y-m-d H:i:s');

    // Verificar si la categoría ya existe
    $query = "SELECT id_categoria FROM categoria WHERE nombre_categoria = $1";
    $result = pg_query_params($conn, $query, array($nombre));

    if (pg_num_rows($result) > 0) {
        header("Location: categoria_add.php?error=1&msg=La categoría ya existe");
        exit();
    } else {
        $query2 = "INSERT INTO categoria(nombre_categoria, descripcion_categoria, estado_categoria, creado_en) 
                  VALUES ($1, $2, $3, $4)";
        
        $result2 = pg_query_params($conn, $query2, array(
            $nombre,
            $descripcion, 
            $estado, 
            $creado_en
        ));

        if ($result2) {
            header("Location: categoria.php?success=1");
        } else {
            header("Location: categoria_add.php?error=1&msg=Error al registrar la categoría");
        }
    }
    pg_free_result($result);
} else {
    header("Location: categoria.php");
}
?> 