<?php
require_once __DIR__ . '/../models/Reporte.php';

class ReporteController {
    public function listar() {
        $total_productos = Reporte::getTotalProductos();
        $total_pedidos = Reporte::getTotalPedidos();
        $total_ingresos = Reporte::getTotalIngresos();
        $total_usuarios = Reporte::getTotalUsuarios();
        list($estados, $cantidades) = Reporte::getProductosPorEstado();
        list($meses, $cant_pedidos) = Reporte::getPedidosPorMes();
        list($meses_ing, $cant_ingresos) = Reporte::getIngresosPorMes();
        list($top_productos, $top_cantidades) = Reporte::getTopProductosVendidos(5);
        require __DIR__ . '/../views/reportes/listar.php';
    }
} 