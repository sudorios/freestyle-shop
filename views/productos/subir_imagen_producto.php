<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../../conexion/cone.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../../login.php');
    exit();
}

