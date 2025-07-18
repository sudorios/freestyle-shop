<?php
require_once __DIR__ . '/../models/Producto.php';

class ProductoController {
    public function listar() {
        $productos = Producto::obtenerTodos();
        $subcategorias = Producto::obtenerSubcategorias();
        require __DIR__ . '/../views/productos/listar.php';
    }

} 