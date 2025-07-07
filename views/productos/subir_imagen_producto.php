<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');
require_once '../../conexion/cone.php';
require_once '../../cloudinary_config.php';
require_once 'producto_queries.php';
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

$id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
$vista_producto = isset($_POST['vista_producto']) ? intval($_POST['vista_producto']) : 1;

if (!$id_producto || !isset($_FILES['file'])) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit();
}

$file = $_FILES['file'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Error al subir el archivo']);
    exit();
}

$tmp_name = $file['tmp_name'];
$nombre_archivo = basename($file['name']);

try {
    $cloudinary = new Cloudinary(Configuration::instance());
    $upload = $cloudinary->uploadApi()->upload($tmp_name, [
        'folder' => 'productos/',
        'public_id' => pathinfo($nombre_archivo, PATHINFO_FILENAME) . '_' . uniqid(),
        'overwrite' => true,
        'resource_type' => 'image',
    ]);
    $url_imagen = $upload['secure_url'] ?? null;
    if (!$url_imagen) throw new Exception('No se obtuvo URL de Cloudinary');

    $sql = insertImageProductQuery();
    $params = [$id_producto, $url_imagen, $vista_producto];
    $result = pg_query_params($conn, $sql, $params);
    if ($result) {
        echo json_encode(['success' => true, 'url' => $url_imagen]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al guardar en la base de datos']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

