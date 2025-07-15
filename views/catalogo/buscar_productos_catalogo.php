<?php
header('Content-Type: application/json');
include_once '../../conexion/cone.php'; // Asegura la conexión
include_once '../../catalogo_queries.php';
global $conn;

if (!$conn) {
    echo json_encode([]);
    exit;
}

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if ($q === '') {
    echo json_encode([]);
    exit;
}

$sql = "SELECT 
            p.id_producto, 
            p.nombre_producto, 
            p.talla_producto, 
            p.descripcion_producto, 
            i.precio_venta, 
            i.id AS ingreso_id, 
            ip.url_imagen, 
            ip.id AS imagen_id
        FROM 
            inventario_sucursal isuc
        JOIN 
            producto p ON isuc.id_producto = p.id_producto
        JOIN 
            ingreso i ON p.id_producto = i.id_producto
        JOIN 
            imagenes_producto ip ON p.id_producto = ip.producto_id
        WHERE 
            isuc.id_sucursal = 7
            AND isuc.cantidad > 0
            AND LOWER(p.nombre_producto) ILIKE $1
        ORDER BY 
            p.nombre_producto
        LIMIT 10;";

$result = pg_query_params($conn, $sql, array('%' . strtolower($q) . '%'));
$productos = [];
while ($row = pg_fetch_assoc($result)) {
    $productos[] = [
        'id_producto' => $row['id_producto'],
        'nombre_producto' => $row['nombre_producto'],
        'talla_producto' => $row['talla_producto'],
        'descripcion_producto' => $row['descripcion_producto'],
        'precio_venta' => $row['precio_venta'],
        'ingreso_id' => $row['ingreso_id'],
        'url_imagen' => $row['url_imagen'],
        'imagen_id' => $row['imagen_id'],
    ];
}
echo json_encode($productos); 