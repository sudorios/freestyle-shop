<?php
require_once __DIR__ . '/../core/Database.php';

class Usuario {
    public static function obtenerTodos() {
        $conn = Database::getConexion();
        $sql = "SELECT * FROM usuario ORDER BY id_usuario ASC";
        
        $result = pg_query($conn, $sql);
        $usuarios = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $usuarios[] = $row;
            }
        }
        return $usuarios;
    }

    public static function obtenerPorId($id) {
        $conn = Database::getConexion();
        $sql = "SELECT * FROM usuario WHERE id_usuario = $1";
        $result = pg_query_params($conn, $sql, [$id]);
        if ($result && pg_num_rows($result) > 0) {
            return pg_fetch_assoc($result);
        }
        return null;
    }

    public static function registrar($nombre_usuario, $email_usuario, $ref_usuario, $password_usuario, $telefono_usuario, $direccion_usuario, $rol_usuario = 'cliente') {
        $conn = Database::getConexion();
        
        if (self::existePorEmail($email_usuario)) {
            return false;
        }
        
        if (self::existePorNickname($ref_usuario)) {
            return false;
        }
        
        $sql = "INSERT INTO usuario (nombre_usuario, email_usuario, ref_usuario, pass_usuario, telefono_usuario, direccion_usuario, rol_usuario, estado_usuario) 
                VALUES ($1, $2, $3, $4, $5, $6, $7, true)";
        
        $params = [
            $nombre_usuario,
            $email_usuario,
            $ref_usuario,
            password_hash($password_usuario, PASSWORD_DEFAULT),
            $telefono_usuario,
            $direccion_usuario,
            $rol_usuario
        ];
        
        $result = pg_query_params($conn, $sql, $params);
        return $result;
    }

    public static function actualizar($id_usuario, $nombre_usuario, $email_usuario, $ref_usuario, $telefono_usuario, $direccion_usuario) {
        $conn = Database::getConexion();
        
        if (self::existePorEmailExcluyendoId($email_usuario, $id_usuario)) {
            return false;
        }
        
        if (self::existePorNicknameExcluyendoId($ref_usuario, $id_usuario)) {
            return false;
        }
        
        $sql = "UPDATE usuario SET nombre_usuario = $1, email_usuario = $2, ref_usuario = $3, telefono_usuario = $4, direccion_usuario = $5 WHERE id_usuario = $6";
        $params = [
            $nombre_usuario,
            $email_usuario,
            $ref_usuario,
            $telefono_usuario,
            $direccion_usuario,
            $id_usuario
        ];
        
        $result = pg_query_params($conn, $sql, $params);
        return $result;
    }

    public static function cambiarPassword($id_usuario, $nueva_password) {
        $conn = Database::getConexion();
        
        $sql = "UPDATE usuario SET pass_usuario = $1 WHERE id_usuario = $2";
        $params = [
            password_hash($nueva_password, PASSWORD_DEFAULT),
            $id_usuario
        ];
        
        $result = pg_query_params($conn, $sql, $params);
        return $result;
    }

    public static function existePorEmail($email_usuario) {
        $conn = Database::getConexion();
        $sql = "SELECT id_usuario FROM usuario WHERE email_usuario = $1";
        $result = pg_query_params($conn, $sql, [$email_usuario]);
        return pg_num_rows($result) > 0;
    }

    public static function existePorEmailExcluyendoId($email_usuario, $id_usuario) {
        $conn = Database::getConexion();
        $sql = "SELECT id_usuario FROM usuario WHERE email_usuario = $1 AND id_usuario != $2";
        $result = pg_query_params($conn, $sql, [$email_usuario, $id_usuario]);
        return pg_num_rows($result) > 0;
    }

    public static function existePorNickname($ref_usuario) {
        $conn = Database::getConexion();
        $sql = "SELECT id_usuario FROM usuario WHERE ref_usuario = $1";
        $result = pg_query_params($conn, $sql, [$ref_usuario]);
        return pg_num_rows($result) > 0;
    }

    public static function existePorNicknameExcluyendoId($ref_usuario, $id_usuario) {
        $conn = Database::getConexion();
        $sql = "SELECT id_usuario FROM usuario WHERE ref_usuario = $1 AND id_usuario != $2";
        $result = pg_query_params($conn, $sql, [$ref_usuario, $id_usuario]);
        return pg_num_rows($result) > 0;
    }

    public static function validarCampos($nombre_usuario, $email_usuario, $ref_usuario, $telefono_usuario, $direccion_usuario) {
        $errores = [];
        
        if (empty($nombre_usuario)) {
            $errores[] = 'El nombre es obligatorio';
        }
        
        if (empty($email_usuario)) {
            $errores[] = 'El email es obligatorio';
        } elseif (!filter_var($email_usuario, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El formato del email no es válido';
        }
        
        if (empty($ref_usuario)) {
            $errores[] = 'El nickname es obligatorio';
        }
        
        if (empty($telefono_usuario)) {
            $errores[] = 'El teléfono es obligatorio';
        }
        
        if (empty($direccion_usuario)) {
            $errores[] = 'La dirección es obligatoria';
        }
        
        return $errores;
    }

    public static function validarPassword($password, $confirmar_password) {
        $errores = [];
        
        if (empty($password)) {
            $errores[] = 'La contraseña es obligatoria';
        } elseif (strlen($password) < 6) {
            $errores[] = 'La contraseña debe tener al menos 6 caracteres';
        }
        
        if ($password !== $confirmar_password) {
            $errores[] = 'Las contraseñas no coinciden';
        }
        
        return $errores;
    }

    public static function obtenerPorEmailONick($usuario) {
        $conn = Database::getConexion();
        $sql = "SELECT * FROM usuario WHERE ref_usuario = $1 OR email_usuario = $1 LIMIT 1";
        $result = pg_query_params($conn, $sql, [$usuario]);
        if ($result && pg_num_rows($result) > 0) {
            return pg_fetch_assoc($result);
        }
        return null;
    }
} 