<?php
require_once __DIR__ . '/../models/Catalogo.php';

class CatalogoController {
    public function listar() {
        $catalogo = Catalogo::obtenerTodos();
        $productosDisponibles = Catalogo::obtenerProductosDisponibles();
        require __DIR__ . '/../views/catalogo/listar.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=catalogo&action=listar&error=1&msg=Acceso denegado');
            exit;
        }
        
        $producto_id = $_POST['producto_id'] ?? null;
        $sucursal_id = $_POST['sucursal_id'] ?? 7; 
        $ingreso_id = $_POST['ingreso_id'] ?? null;
        $imagen_id = $_POST['imagen_id'] ?? null;
        $estado_oferta = ($_POST['estado_oferta'] ?? 'false') === 'true' ? true : false;
        $limite_oferta = $_POST['limite_oferta'] ?? null;
        $oferta = $_POST['oferta'] ?? null;

        $errores = $this->validarCamposCatalogo($producto_id, $ingreso_id, $imagen_id);
        if (!empty($errores)) {
            $msg = urlencode(implode(', ', $errores));
            header('Location: index.php?controller=catalogo&action=listar&error=1&msg=' . $msg);
            exit();
        }

        if ($estado_oferta) {
            if (empty($limite_oferta)) {
                header('Location: index.php?controller=catalogo&action=listar&error=1&msg=El límite de oferta es obligatorio cuando la oferta está activa');
                exit();
            }
            if (empty($oferta) || !is_numeric($oferta) || $oferta <= 0 || $oferta > 100) {
                header('Location: index.php?controller=catalogo&action=listar&error=1&msg=El descuento debe ser un número entre 1 y 100');
                exit();
            }
        } else {
            $limite_oferta = null;
            $oferta = null;
        }

        $result = Catalogo::registrar($producto_id, $sucursal_id, $ingreso_id, $imagen_id, $estado_oferta, $limite_oferta, $oferta);
        if ($result) {
            header('Location: index.php?controller=catalogo&action=listar&success=1&msg=Producto agregado al catálogo correctamente');
        } else {
            header('Location: index.php?controller=catalogo&action=listar&error=1&msg=Error al agregar producto al catálogo o ya existe');
        }
        exit();
    }

    public function activar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=catalogo&action=listar&error=1&msg=Acceso denegado');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            header('Location: index.php?controller=catalogo&action=listar&error=1&msg=ID inválido');
            exit;
        }

        $result = Catalogo::activar($id);
        if ($result) {
            header('Location: index.php?controller=catalogo&action=listar&success=1&msg=Producto activado correctamente');
        } else {
            header('Location: index.php?controller=catalogo&action=listar&error=1&msg=Error al activar producto');
        }
        exit();
    }

    public function desactivar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=catalogo&action=listar&error=1&msg=Acceso denegado');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            header('Location: index.php?controller=catalogo&action=listar&error=1&msg=ID inválido');
            exit;
        }

        $result = Catalogo::desactivar($id);
        if ($result) {
            header('Location: index.php?controller=catalogo&action=listar&success=1&msg=Producto desactivado correctamente');
        } else {
            header('Location: index.php?controller=catalogo&action=listar&error=1&msg=Error al desactivar producto');
        }
        exit();
    }

    private function validarCamposCatalogo($producto_id, $ingreso_id, $imagen_id) {
        $errores = [];
        if (!$producto_id || !is_numeric($producto_id)) {
            $errores[] = 'El producto es obligatorio';
        }
        if (!$ingreso_id || !is_numeric($ingreso_id)) {
            $errores[] = 'El ingreso es obligatorio';
        }
        if (!$imagen_id || !is_numeric($imagen_id)) {
            $errores[] = 'La imagen es obligatoria';
        }
        return $errores;
    }
} 