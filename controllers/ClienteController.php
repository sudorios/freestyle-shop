<?php
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {
    public function listar() {
        $clientes = Cliente::obtenerTodos();
        require __DIR__ . '/../views/clientes/listar.php';
    }
    // Placeholders para editar, cambiarPassword, eliminar
} 