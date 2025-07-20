<?php
require_once __DIR__ . '/../models/Sucursal.php';

class SucursalController {
    public function listar() {
        $sucursales = Sucursal::obtenerTodas();
        $supervisores = Sucursal::obtenerSupervisores();
        require __DIR__ . '/../views/sucursales/listar.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=sucursal&action=listar&error=1&msg=Acceso denegado');
            exit;
        }
        
        $nombre_sucursal = $_POST['nombre_sucursal'] ?? '';
        $direccion_sucursal = $_POST['direccion_sucursal'] ?? '';
        $tipo_sucursal = $_POST['tipo_sucursal'] ?? '';
        $id_supervisor = $_POST['id_supervisor'] ?? '';

        $errores = Sucursal::validarCampos($nombre_sucursal, $direccion_sucursal, $tipo_sucursal, $id_supervisor);
        if (!empty($errores)) {
            $msg = urlencode(implode(', ', $errores));
            header('Location: index.php?controller=sucursal&action=listar&error=1&msg=' . $msg);
            exit();
        }

        $result = Sucursal::registrar($nombre_sucursal, $direccion_sucursal, $tipo_sucursal, $id_supervisor);
        if ($result) {
            header('Location: index.php?controller=sucursal&action=listar&success=1&msg=Sucursal registrada correctamente');
        } else {
            header('Location: index.php?controller=sucursal&action=listar&error=1&msg=Error al registrar sucursal o ya existe');
        }
        exit();
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=sucursal&action=listar&error=1&msg=Acceso denegado');
            exit;
        }
        
        $id_sucursal = $_POST['id_sucursal'] ?? '';
        $nombre_sucursal = $_POST['nombre_sucursal'] ?? '';
        $direccion_sucursal = $_POST['direccion_sucursal'] ?? '';
        $tipo_sucursal = $_POST['tipo_sucursal'] ?? '';
        $id_supervisor = $_POST['id_supervisor'] ?? '';

        if (!$id_sucursal || !is_numeric($id_sucursal)) {
            header('Location: index.php?controller=sucursal&action=listar&error=1&msg=ID de sucursal inválido');
            exit;
        }

        $errores = Sucursal::validarCampos($nombre_sucursal, $direccion_sucursal, $tipo_sucursal, $id_supervisor);
        if (!empty($errores)) {
            $msg = urlencode(implode(', ', $errores));
            header('Location: index.php?controller=sucursal&action=listar&error=1&msg=' . $msg);
            exit();
        }

        $result = Sucursal::actualizar($id_sucursal, $nombre_sucursal, $direccion_sucursal, $tipo_sucursal, $id_supervisor);
        if ($result) {
            header('Location: index.php?controller=sucursal&action=listar&success=1&msg=Sucursal actualizada correctamente');
        } else {
            header('Location: index.php?controller=sucursal&action=listar&error=1&msg=Error al actualizar sucursal o nombre ya existe');
        }
        exit();
    }

    public function desactivar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=sucursal&action=listar&error=1&msg=Acceso denegado');
            exit;
        }
        
        $id_sucursal = $_POST['id_sucursal'] ?? '';
        if (!$id_sucursal || !is_numeric($id_sucursal)) {
            header('Location: index.php?controller=sucursal&action=listar&error=1&msg=ID de sucursal inválido');
            exit;
        }

        $result = Sucursal::desactivar($id_sucursal);
        if ($result) {
            header('Location: index.php?controller=sucursal&action=listar&success=1&msg=Sucursal desactivada correctamente');
        } else {
            header('Location: index.php?controller=sucursal&action=listar&error=1&msg=Error al desactivar sucursal');
        }
        exit();
    }

    public function activar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=sucursal&action=listar&error=1&msg=Acceso denegado');
            exit;
        }
        
        $id_sucursal = $_POST['id_sucursal'] ?? '';
        if (!$id_sucursal || !is_numeric($id_sucursal)) {
            header('Location: index.php?controller=sucursal&action=listar&error=1&msg=ID de sucursal inválido');
            exit;
        }

        $result = Sucursal::activar($id_sucursal);
        if ($result) {
            header('Location: index.php?controller=sucursal&action=listar&success=1&msg=Sucursal activada correctamente');
        } else {
            header('Location: index.php?controller=sucursal&action=listar&error=1&msg=Error al activar sucursal');
        }
        exit();
    }
} 