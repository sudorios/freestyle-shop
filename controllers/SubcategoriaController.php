<?php
require_once __DIR__ . '/../models/Subcategoria.php';
require_once __DIR__ . '/../models/Categoria.php';

class SubcategoriaController {
    public function listar() {
        $subcategorias = Subcategoria::obtenerTodas();
        $categorias = Categoria::obtenerTodas(); // Para los modales
        require __DIR__ . '/../views/subcategorias/listar.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=subcategoria&action=listar&error=2&msg=Acceso denegado');
            exit;
        }
        
        $nombre = trim($_POST['nombre_subcategoria'] ?? '');
        $descripcion = trim($_POST['descripcion_subcategoria'] ?? '');
        $id_categoria = $_POST['id_categoria'] ?? null;

        $errores = $this->validarCamposSubcategoria($nombre, $id_categoria);
        if (!empty($errores)) {
            $msg = urlencode(implode(', ', $errores));
            header('Location: index.php?controller=subcategoria&action=listar&error=2&msg=' . $msg);
            exit();
        }

        if (Subcategoria::existePorNombre($nombre)) {
            header('Location: index.php?controller=subcategoria&action=listar&error=2&msg=Ya existe una subcategoría con ese nombre');
            exit();
        }

        $result = Subcategoria::registrar($nombre, $descripcion, $id_categoria);
        if ($result) {
            header('Location: index.php?controller=subcategoria&action=listar&success=1');
        } else {
            header('Location: index.php?controller=subcategoria&action=listar&error=1');
        }
        exit();
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=subcategoria&action=listar&error=2&msg=Acceso denegado');
            exit;
        }
        
        $id_subcategoria = $_POST['id_subcategoria'] ?? null;
        $nombre = trim($_POST['nombre_subcategoria'] ?? '');
        $descripcion = trim($_POST['descripcion_subcategoria'] ?? '');
        $id_categoria = $_POST['id_categoria'] ?? null;

        if (!$id_subcategoria || !is_numeric($id_subcategoria)) {
            header('Location: index.php?controller=subcategoria&action=listar&error=2&msg=ID inválido');
            exit;
        }

        $errores = $this->validarCamposSubcategoria($nombre, $id_categoria);
        if (!empty($errores)) {
            $msg = urlencode(implode(', ', $errores));
            header('Location: index.php?controller=subcategoria&action=listar&error=2&msg=' . $msg);
            exit();
        }

        if (Subcategoria::existePorNombre($nombre, $id_subcategoria)) {
            header('Location: index.php?controller=subcategoria&action=listar&error=2&msg=Ya existe una subcategoría con ese nombre');
            exit();
        }

        $result = Subcategoria::actualizar($id_subcategoria, $nombre, $descripcion, $id_categoria);
        if ($result) {
            header('Location: index.php?controller=subcategoria&action=listar&success=2');
        } else {
            header('Location: index.php?controller=subcategoria&action=listar&error=1');
        }
        exit();
    }

    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=subcategoria&action=listar&error=2&msg=Acceso denegado');
            exit;
        }
        
        $id_subcategoria = $_POST['id_subcategoria'] ?? null;
        if (!$id_subcategoria || !is_numeric($id_subcategoria)) {
            header('Location: index.php?controller=subcategoria&action=listar&error=2&msg=ID inválido');
            exit;
        }

        $result = Subcategoria::eliminar($id_subcategoria);
        if ($result) {
            header('Location: index.php?controller=subcategoria&action=listar&success=2');
        } else {
            header('Location: index.php?controller=subcategoria&action=listar&error=1');
        }
        exit();
    }

    private function validarCamposSubcategoria($nombre, $id_categoria) {
        $errores = [];
        if (empty($nombre)) {
            $errores[] = 'El nombre es obligatorio';
        }
        if (!$id_categoria || !is_numeric($id_categoria)) {
            $errores[] = 'La categoría es obligatoria';
        }
        return $errores;
    }
} 