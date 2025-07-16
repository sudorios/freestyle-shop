<?php
// Utilidades para Kardex
function getWhereFechasKardex($conn, $fecha_inicio, $fecha_fin) {
    $w = [];
    if ($fecha_inicio) $w[] = "k.fecha_movimiento >= '" . pg_escape_string($conn, $fecha_inicio) . "'";
    if ($fecha_fin) $w[] = "k.fecha_movimiento <= '" . pg_escape_string($conn, $fecha_fin) . "'";
    return $w ? 'WHERE ' . implode(' AND ', $w) : '';
} 