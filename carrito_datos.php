<?php
session_start();
header('Content-Type: application/json');
include_once './conexion/cone.php';
$usuario_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$session_id = session_id();
if ($usuario_id) {
    $sql = "SELECT id FROM carrito WHERE usuario_id = $1";
    $params = [$usuario_id];
} else {
    $sql = "SELECT id FROM carrito WHERE session_id = $1";
    $params = [$session_id];
}
$result = pg_query_params($conn, $sql, $params);
$row = pg_fetch_assoc($result);
$carrito_id = $row ? $row['id'] : null;
$items = [];
$total = 0;
$totalOriginal = 0;
$totalDescuento = 0;
$cantidadTotal = 0;
if ($carrito_id) {
    $sql = "SELECT ci.id, ci.cantidad, ci.talla, ci.precio_unitario, p.nombre_producto, ip.url_imagen, i.precio_venta, cp.oferta
            FROM carrito_items ci
            JOIN producto p ON ci.producto_id = p.id_producto
            JOIN catalogo_productos cp ON cp.producto_id = p.id_producto
            JOIN ingreso i ON cp.ingreso_id = i.id
            LEFT JOIN imagenes_producto ip ON cp.imagen_id = ip.id
            WHERE ci.carrito_id = $1 AND ci.estado = 'activo'";
    $res = pg_query_params($conn, $sql, [$carrito_id]);
    while ($item = pg_fetch_assoc($res)) {
        $precio = $item['precio_unitario'];
        $precioOriginal = $item['precio_venta'];
        $oferta = $item['oferta'];
        $precioConDescuento = $precioOriginal * (1 - ($oferta / 100));
        $subtotal = $precioConDescuento * $item['cantidad'];
        $subtotalOriginal = $precioOriginal * $item['cantidad'];
        $descuento = $subtotalOriginal - $subtotal;
        $cantidadTotal += $item['cantidad'];
        $total += $subtotal;
        $totalOriginal += $subtotalOriginal;
        $totalDescuento += $descuento;
        $items[] = [
            'id' => $item['id'],
            'nombre_producto' => $item['nombre_producto'],
            'url_imagen' => $item['url_imagen'],
            'talla' => $item['talla'],
            'cantidad' => $item['cantidad'],
            'precio_venta' => $precioOriginal,
            'precio_con_descuento' => $precioConDescuento,
            'oferta' => $oferta,
            'subtotal' => $subtotal,
            'subtotalOriginal' => $subtotalOriginal,
            'descuento' => $descuento
        ];
    }
}
echo json_encode([
    'items' => $items,
    'total' => $total,
    'totalOriginal' => $totalOriginal,
    'totalDescuento' => $totalDescuento,
    'cantidadTotal' => $cantidadTotal
]); 