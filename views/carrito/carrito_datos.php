<?php
session_start();
header('Content-Type: application/json');
include_once 'carrito_utils.php';
$carrito_id = obtener_carrito_id($conn);
$items = obtener_items_carrito($conn, $carrito_id);
$totales = calcular_totales($items);

$items_front = [];
foreach ($items as $item) {
    $precioOriginal = $item['precio_venta'];
    $oferta = $item['oferta'];
    $precioConDescuento = $precioOriginal * (1 - ($oferta / 100));
    $items_front[] = [
        'id' => $item['id'],
        'nombre_producto' => $item['nombre_producto'],
        'url_imagen' => $item['url_imagen'],
        'talla' => $item['talla'],
        'cantidad' => $item['cantidad'],
        'precio_venta' => $precioOriginal,
        'precio_con_descuento' => $precioConDescuento,
        'oferta' => $oferta
    ];
}
echo json_encode([
    'items' => $items_front,
    'total' => $totales['total'],
    'totalOriginal' => $totales['totalOriginal'],
    'totalDescuento' => $totales['totalDescuento'],
    'cantidadTotal' => $totales['cantidadTotal']
]); 