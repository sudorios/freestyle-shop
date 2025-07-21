<?php
require_once __DIR__ . '/Usuario.php';

class Cliente extends Usuario {
    public static function obtenerTodos() {
        $conn = Database::getConexion();
        $sql = "SELECT * FROM usuario WHERE rol_usuario = 'cliente' ORDER BY id_usuario ASC";
        $result = pg_query($conn, $sql);
        $clientes = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $clientes[] = $row;
            }
        }
        return $clientes;
    }
} 