<?php
require_once __DIR__ . '/../models/Categoria.php';

class CategoriaController {
    public function listar() {
        $categorias = Categoria::obtenerTodas();
        require __DIR__ . '/../views/categorias/listar.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=categoria&action=listar&error=2&msg=Acceso denegado');
            exit;
        }
        
        $nombre = trim($_POST['nombre_categoria'] ?? '');
        $descripcion = trim($_POST['descripcion_categoria'] ?? '');

        $errores = $this->validarCamposCategoria($nombre);
        if (!empty($errores)) {
            $msg = urlencode(implode(', ', $errores));
            header('Location: index.php?controller=categoria&action=listar&error=2&msg=' . $msg);
            exit();
        }

        if (Categoria::existePorNombre($nombre)) {
            header('Location: index.php?controller=categoria&action=listar&error=2&msg=Ya existe una categoría con ese nombre');
            exit();
        }

        $result = Categoria::registrar($nombre, $descripcion);
        if ($result) {
            header('Location: index.php?controller=categoria&action=listar&success=1');
        } else {
            header('Location: index.php?controller=categoria&action=listar&error=1');
        }
        exit();
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=categoria&action=listar&error=2&msg=Acceso denegado');
            exit;
        }
        
        $id_categoria = $_POST['id_categoria'] ?? null;
        $nombre = trim($_POST['nombre_categoria'] ?? '');
        $descripcion = trim($_POST['descripcion_categoria'] ?? '');
        $estado = true; // Siempre activo

        if (!$id_categoria || !is_numeric($id_categoria)) {
            header('Location: index.php?controller=categoria&action=listar&error=2&msg=ID inválido');
            exit;
        }

        $errores = $this->validarCamposCategoria($nombre);
        if (!empty($errores)) {
            $msg = urlencode(implode(', ', $errores));
            header('Location: index.php?controller=categoria&action=listar&error=2&msg=' . $msg);
            exit();
        }

        if (Categoria::existePorNombre($nombre, $id_categoria)) {
            header('Location: index.php?controller=categoria&action=listar&error=2&msg=Ya existe una categoría con ese nombre');
            exit();
        }

        $result = Categoria::actualizar($id_categoria, $nombre, $descripcion, $estado);
        if ($result) {
            header('Location: index.php?controller=categoria&action=listar&success=2');
        } else {
            header('Location: index.php?controller=categoria&action=listar&error=1');
        }
        exit();
    }

    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=categoria&action=listar&error=2&msg=Acceso denegado');
            exit;
        }
        
        $id_categoria = $_POST['id_categoria'] ?? null;
        if (!$id_categoria || !is_numeric($id_categoria)) {
            header('Location: index.php?controller=categoria&action=listar&error=2&msg=ID inválido');
            exit;
        }

        $result = Categoria::eliminar($id_categoria);
        if ($result) {
            header('Location: index.php?controller=categoria&action=listar&success=2');
        } else {
            header('Location: index.php?controller=categoria&action=listar&error=1');
        }
        exit();
    }

    private function validarCamposCategoria($nombre) {
        $errores = [];
        if (empty($nombre)) {
            $errores[] = 'El nombre es obligatorio';
        }
        return $errores;
    }
} 