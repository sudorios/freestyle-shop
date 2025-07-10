<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
include_once 'conexion/cone.php';
include_once 'includes/head.php';
include_once 'includes/header.php';
?>

<body>
    <h1>Hola Mundo</h1>
    <a href="login.php">Admin</a>
</body>

</html>