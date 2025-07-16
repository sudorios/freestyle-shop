<?php
function getInventarioSucursalQuery($id_sucursal = null) {
    $where = '';
    if ($id_sucursal) {
        $where = "WHERE isuc.id_sucursal = $1";
    }
    return "SELECT isuc.*, p.nombre_producto, p.talla_producto, s.nombre_sucursal
            FROM inventario_sucursal isuc
            JOIN producto p ON isuc.id_producto = p.id_producto
            JOIN sucursal s ON isuc.id_sucursal = s.id_sucursal
            $where
            ORDER BY p.nombre_producto ASC";
} 