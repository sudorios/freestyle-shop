<?php

function get_pedido_filters_from_get() {
    $fecha_desde = $_GET['fecha_desde'] ?? '';
    $fecha_hasta = $_GET['fecha_hasta'] ?? '';
    $estado = $_GET['estado'] ?? '';
    $orden_precio = $_GET['orden_precio'] ?? '';
    $busqueda = $_GET['busqueda'] ?? '';
    return compact('fecha_desde', 'fecha_hasta', 'estado', 'orden_precio', 'busqueda');
}

function build_pedido_where_sql_and_params($filters) {
    $where = [];
    $params = [];
    $paramIdx = 1;
    if ($filters['fecha_desde']) {
        $where[] = "p.fecha >= $" . $paramIdx;
        $params[] = $filters['fecha_desde'];
        $paramIdx++;
    }
    if ($filters['fecha_hasta']) {
        $where[] = "p.fecha <= $" . $paramIdx;
        $params[] = $filters['fecha_hasta'];
        $paramIdx++;
    }
    if ($filters['estado']) {
        $where[] = "p.estado = $" . $paramIdx;
        $params[] = $filters['estado'];
        $paramIdx++;
    }
    if ($filters['busqueda']) {
        $where[] = "(CAST(p.id_pedido AS TEXT) ILIKE $" . $paramIdx . " OR u.nombre_usuario ILIKE $" . $paramIdx . ")";
        $params[] = '%' . $filters['busqueda'] . '%';
        $paramIdx++;
    }
    $where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
    return [$where_sql, $params];
}

function get_pedido_order_sql($orden_precio) {
    if ($orden_precio === 'mayor') {
        return 'ORDER BY p.total DESC';
    } elseif ($orden_precio === 'menor') {
        return 'ORDER BY p.total ASC';
    }
    return 'ORDER BY p.fecha DESC';
}

function formatear_estado_pedido($estado) {
    switch ($estado) {
        case 'PENDIENTE': return 'Pendiente';
        case 'RECIBIDO': return 'Recibido';
        case 'CANCELADO': return 'Cancelado';
        default: return $estado;
    }
}

function formatear_fecha_pedido($fecha) {
    // Puedes personalizar el formato según tus necesidades
    return date('d/m/Y H:i', strtotime($fecha));
} 