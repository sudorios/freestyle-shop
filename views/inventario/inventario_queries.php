<?php
function getInventarioSucursalQuery() {
    return "SELECT i.*, p.nombre_producto, s.nombre_sucursal
            FROM inventario_sucursal i
            JOIN producto p ON i.id_producto = p.id_producto
            JOIN sucursal s ON i.id_sucursal = s.id_sucursal
            ORDER BY s.nombre_sucursal ASC, p.nombre_producto ASC";
} 