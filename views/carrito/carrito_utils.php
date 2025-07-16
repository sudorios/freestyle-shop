<?php
include_once __DIR__ . '/../../conexion/cone.php';
include_once 'carrito_queries.php';

function obtener_carrito_id($conn) {
    $usuario_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
    $session_id = session_id();
    if ($usuario_id) {
        $sql = query_obtener_carrito_por_usuario();
        $params = [$usuario_id];
    } else {
        $sql = query_obtener_carrito_por_sesion();
        $params = [$session_id];
    }
    $result = pg_query_params($conn, $sql, $params);
    $row = pg_fetch_assoc($result);
    return $row ? $row['id'] : null;
}

function obtener_items_carrito($conn, $carrito_id) {
    $items = [];
    if ($carrito_id) {
        $sql = query_obtener_items_carrito();
        $res = pg_query_params($conn, $sql, [$carrito_id]);
        while ($item = pg_fetch_assoc($res)) {
            $items[] = $item;
        }
    }
    return $items;
}

function calcular_totales($items) {
    $total = 0;
    $totalOriginal = 0;
    $totalDescuento = 0;
    $cantidadTotal = 0;
    foreach ($items as $item) {
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
    }
    return [
        'total' => $total,
        'totalOriginal' => $totalOriginal,
        'totalDescuento' => $totalDescuento,
        'cantidadTotal' => $cantidadTotal
    ];
} 