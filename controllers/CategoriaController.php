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
        
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

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
        
        $categoria_id = $_POST['categoria_id'] ?? null;
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $estado = true; // Siempre activo

        if (!$categoria_id || !is_numeric($categoria_id)) {
            header('Location: index.php?controller=categoria&action=listar&error=2&msg=ID inválido');
            exit;
        }

        $errores = $this->validarCamposCategoria($nombre);
        if (!empty($errores)) {
            $msg = urlencode(implode(', ', $errores));
            header('Location: index.php?controller=categoria&action=listar&error=2&msg=' . $msg);
            exit();
        }

        if (Categoria::existePorNombre($nombre, $categoria_id)) {
            header('Location: index.php?controller=categoria&action=listar&error=2&msg=Ya existe una categoría con ese nombre');
            exit();
        }

        $result = Categoria::actualizar($categoria_id, $nombre, $descripcion, $estado);
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
        
        $categoria_id = $_POST['categoria_id'] ?? null;
        if (!$categoria_id || !is_numeric($categoria_id)) {
            header('Location: index.php?controller=categoria&action=listar&error=2&msg=ID inválido');
            exit;
        }

        $result = Categoria::eliminar($categoria_id);
        if ($result) {
            header('Location: index.php?controller=categoria&action=listar&success=2');
        } else {
            header('Location: index.php?controller=categoria&action=listar&error=1');
        }
        exit();
    }

    public function ver() {
        $categoria_id = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : 0;
        if ($categoria_id <= 0) {
            die('Categoría no válida.');
        }
        $orden = $_GET['orden'] ?? 'nombre_asc';
        $id_subcategoria = isset($_GET['id_subcategoria']) ? intval($_GET['id_subcategoria']) : 0;
        $conn = Database::getConexion();
        $sql_sub = "SELECT id_subcategoria, nombre_subcategoria FROM subcategoria WHERE categoria_id = $1 ORDER BY nombre_subcategoria ASC";
        $res_sub = pg_query_params($conn, $sql_sub, [$categoria_id]);
        $subcategorias = [];
        while ($row = pg_fetch_assoc($res_sub)) {
            $subcategorias[] = $row;
        }
        $productos = Categoria::obtenerProductosPorCategoria($categoria_id, $id_subcategoria, $orden);
        require __DIR__ . '/../views/categorias/ver.php';
    }

    private function validarCamposCategoria($nombre) {
        $errores = [];
        if (empty($nombre)) {
            $errores[] = 'El nombre es obligatorio';
        }
        return $errores;
    }
} 