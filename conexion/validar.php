<?php
session_start();
include_once 'cone.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['txtusu']);
    $contrasenia = trim($_POST['txtpass']);

    $query = "SELECT id_usuario, ref_usuario, pass_usuario, rol_usuario FROM usuario WHERE ref_usuario = $1 OR email_usuario = $1 LIMIT 1";
    $result = pg_query_params($conn, $query, array($usuario));

    if ($result) {
        if (pg_num_rows($result) === 1) {
            $row = pg_fetch_assoc($result);
            $hash = $row['pass_usuario'];

            if (password_verify($contrasenia, $hash)) {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['id'] = $row['id_usuario'];
                $_SESSION['rol'] = $row['rol_usuario'];
                
                $usuario_id = $row['id_usuario'];
                $session_id = session_id();
                $sql = "SELECT id FROM carrito WHERE session_id = $1";
                $result_carrito_sesion = pg_query_params($conn, $sql, array($session_id));
                $row_sesion = pg_fetch_assoc($result_carrito_sesion);
                $sql = "SELECT id FROM carrito WHERE usuario_id = $1";
                $result_carrito_usuario = pg_query_params($conn, $sql, array($usuario_id));
                $row_usuario = pg_fetch_assoc($result_carrito_usuario);
                if ($row_sesion && $row_usuario) {
                    $carrito_sesion_id = $row_sesion['id'];
                    $carrito_usuario_id = $row_usuario['id'];
                    $sql = "SELECT producto_id, talla, cantidad, precio_unitario FROM carrito_items WHERE carrito_id = $1 AND estado = 'activo'";
                    $result_items = pg_query_params($conn, $sql, array($carrito_sesion_id));
                    while ($item = pg_fetch_assoc($result_items)) {
                        $sql_check = "SELECT id, cantidad FROM carrito_items WHERE carrito_id = $1 AND producto_id = $2 AND talla = $3 AND estado = 'activo'";
                        $params_check = array($carrito_usuario_id, $item['producto_id'], $item['talla']);
                        $result_check = pg_query_params($conn, $sql_check, $params_check);
                        $row_check = pg_fetch_assoc($result_check);
                        if ($row_check) {
                            $nueva_cantidad = $row_check['cantidad'] + $item['cantidad'];
                            $sql_update = "UPDATE carrito_items SET cantidad = $1 WHERE id = $2";
                            pg_query_params($conn, $sql_update, array($nueva_cantidad, $row_check['id']));
                        } else {
                            $sql_insert = "INSERT INTO carrito_items (carrito_id, producto_id, talla, cantidad, precio_unitario) VALUES ($1, $2, $3, $4, $5)";
                            pg_query_params($conn, $sql_insert, array($carrito_usuario_id, $item['producto_id'], $item['talla'], $item['cantidad'], $item['precio_unitario']));
                        }
                    }
                    $sql = "DELETE FROM carrito WHERE id = $1";
                    pg_query_params($conn, $sql, array($carrito_sesion_id));
                } elseif ($row_sesion && !$row_usuario) {
                    $carrito_sesion_id = $row_sesion['id'];
                    $sql = "UPDATE carrito SET usuario_id = $1, session_id = NULL WHERE id = $2";
                    pg_query_params($conn, $sql, array($usuario_id, $carrito_sesion_id));
                }
                
                if ($_SESSION['rol'] === 'cliente') {
                    header('Location: ../index.php');
                } else {
                    header('Location: ../dashboard.php');
                }
                exit();
            } else {
                header("Location: ../login.php?error=1");
                exit();
            }
        } else {
            header("Location: ../login.php?error=1");
        }
    } else {
        echo "Error en la consulta: " . pg_last_error($conn);
    }

    pg_free_result($result);
    pg_close($conn);
}