<?php
session_start();
include_once 'cone.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['valido' => false, 'error' => 'No autenticado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contrasenia = trim($_POST['password'] ?? '');
    $usuario = $_SESSION['usuario'];

    $query = "SELECT pass_usuario FROM usuario WHERE ref_usuario = $1 OR email_usuario = $1 LIMIT 1";
    $result = pg_query_params($conn, $query, array($usuario));

    if ($result && pg_num_rows($result) === 1) {
        $row = pg_fetch_assoc($result);
        $hash = $row['pass_usuario'];
        if (password_verify($contrasenia, $hash)) {
            echo json_encode(['valido' => true]);
            exit();
        }
    }
    echo json_encode(['valido' => false]);
    exit();
}
echo json_encode(['valido' => false, 'error' => 'Método no permitido']); 