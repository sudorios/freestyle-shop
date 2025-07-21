<?php
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../cloudinary_config.php';
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class ProductoController {
    public function listar() {
        $productos = Producto::obtenerTodos();
        $subcategorias = Producto::obtenerSubcategorias();
        require __DIR__ . '/../views/productos/listar.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=producto&action=listar&error=2&msg=Acceso denegado');
            exit;
        }
        $ref = trim($_POST['ref_producto'] ?? '');
        $nombre = trim($_POST['nombre_producto'] ?? '');
        $descripcion = trim($_POST['descripcion_producto'] ?? '');
        $id_subcategoria = $_POST['id_subcategoria'] ?? '';
        $talla = trim($_POST['talla_producto'] ?? '');
        $estado = true;

        $conn = Database::getConexion();
        $result = pg_query_params($conn, "SELECT 1 FROM producto WHERE ref_producto = $1", array($ref));
        if (pg_num_rows($result) > 0) {
            $ref = $this->generarReferenciaUnicaBD($conn);
            if ($ref === false) {
                $msg = urlencode('No se pudo generar una referencia única. Intente de nuevo.');
                header('Location: index.php?controller=producto&action=listar&error=2&msg=' . $msg);
                exit();
            }
        }

        $errores = $this->validarCamposProducto($ref, $nombre, $id_subcategoria, $talla);
        if (!empty($errores)) {
            $msg = urlencode(implode(', ', $errores));
            header('Location: index.php?controller=producto&action=listar&error=2&msg=' . $msg);
            exit();
        }

        $sql = "INSERT INTO producto (ref_producto, nombre_producto, descripcion_producto, id_subcategoria, talla_producto, estado) VALUES ($1, $2, $3, $4, $5, $6)";
        $params = array($ref, $nombre, $descripcion, $id_subcategoria, $talla, $estado);
        $result = pg_query_params($conn, $sql, $params);
        if ($result) {
            header('Location: index.php?controller=producto&action=listar&success=2');
        } else {
            header('Location: index.php?controller=producto&action=listar&error=1');
        }
        exit();
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=producto&action=listar&error=2&msg=Acceso denegado');
            exit;
        }
        $ref_producto = trim($_POST['ref_producto'] ?? '');
        $nombre = trim($_POST['nombre_producto'] ?? '');
        $descripcion = trim($_POST['descripcion_producto'] ?? '');
        $id_subcategoria = $_POST['id_subcategoria'] ?? '';
        $talla = trim($_POST['talla_producto'] ?? '');

        $errores = $this->validarCamposProducto($ref_producto, $nombre, $id_subcategoria, $talla);
        if (!empty($errores)) {
            $msg = urlencode(implode(', ', $errores));
            header('Location: index.php?controller=producto&action=listar&error=2&msg=' . $msg);
            exit();
        }

        $conn = Database::getConexion();
        $sql = "UPDATE producto SET nombre_producto = $1, descripcion_producto = $2, id_subcategoria = $3, talla_producto = $4, actualizado_en = CURRENT_TIMESTAMP WHERE ref_producto = $5";
        $params = [$nombre, $descripcion, $id_subcategoria, $talla, $ref_producto];
        $result = pg_query_params($conn, $sql, $params);
        if ($result) {
            header('Location: index.php?controller=producto&action=listar&success=2');
        } else {
            header('Location: index.php?controller=producto&action=listar&error=1');
        }
        exit();
    }

    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=producto&action=listar&error=2&msg=Acceso denegado');
            exit;
        }
        $id_producto = $_POST['id_producto'] ?? null;
        if (!$id_producto || !is_numeric($id_producto)) {
            header('Location: index.php?controller=producto&action=listar&error=2&msg=ID inválido');
            exit;
        }
        $result = Producto::eliminar($id_producto);
        if ($result) {
            header('Location: index.php?controller=producto&action=listar&success=2');
        } else {
            $error_msg = pg_last_error();
            header('Location: index.php?controller=producto&action=listar&error=1&msg=' . urlencode('Error al eliminar: ' . $error_msg));
        }
        exit();
    }

    public function subirImagen() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
            exit;
        }
        
        header('Content-Type: application/json');
        
        $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
        $vista_producto = isset($_POST['vista_producto']) ? intval($_POST['vista_producto']) : 1;

        if (!$id_producto || !isset($_FILES['file'])) {
            echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
            exit();
        }

        $file = $_FILES['file'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'error' => 'Error al subir el archivo']);
            exit();
        }

        $tmp_name = $file['tmp_name'];
        $nombre_archivo = basename($file['name']);

        try {
            $cloudinary = new Cloudinary(Configuration::instance());
            $upload = $cloudinary->uploadApi()->upload($tmp_name, [
                'folder' => 'productos/',
                'public_id' => pathinfo($nombre_archivo, PATHINFO_FILENAME) . '_' . uniqid(),
                'overwrite' => true,
                'resource_type' => 'image',
            ]);
            $url_imagen = $upload['secure_url'] ?? null;
            if (!$url_imagen) throw new Exception('No se obtuvo URL de Cloudinary');

            $result = Producto::subirImagen($id_producto, $url_imagen, $vista_producto);
            if ($result) {
                echo json_encode(['success' => true, 'url' => $url_imagen]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al guardar en la base de datos']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit();
    }

    public function obtenerImagenes() {
        if (!isset($_GET['id_producto']) || !is_numeric($_GET['id_producto'])) {
            echo '<p class="text-gray-500">Producto no válido.</p>';
            exit;
        }
        
        $id_producto = intval($_GET['id_producto']);
        $imagenes = Producto::obtenerImagenes($id_producto);
        
        if (!empty($imagenes)) {
            echo '<div class="grid grid-cols-2 gap-4">';
            foreach ($imagenes as $img) {
                $tipo_vista = ($img['vista_producto'] == 1) ? 'Parte Frontal' : 'Parte Posterior';
                echo '<div class="flex flex-col items-center border-2 border-gray-300 rounded-lg shadow-md p-4 bg-white">';
                echo '<img src="' . htmlspecialchars($img['url_imagen']) . '" class="w-64 h-64 object-cover rounded mb-2 border border-gray-200 shadow-sm" />';
                echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full border border-indigo-400 mb-1">' . $tipo_vista . '</span>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p class="text-gray-500">No hay imágenes para este producto.</p>';
        }
        exit();
    }

    public function obtenerPorId() {
        header('Content-Type: application/json');
        
        $id_producto = isset($_GET['id_producto']) ? intval($_GET['id_producto']) : 0;
        if (!$id_producto) {
            echo json_encode(['error' => 'ID de producto no válido']);
            exit;
        }

        $producto = Producto::obtenerPorId($id_producto);
        if ($producto) {
            echo json_encode($producto);
        } else {
            echo json_encode(['error' => 'Producto no encontrado']);
        }
        exit();
    }

    public function generarReferencia() {
        header('Content-Type: application/json');
        
        $ref = Producto::generarReferencia();
        if ($ref === false) {
            echo json_encode(['success' => false, 'error' => 'No se pudo generar una referencia única.']);
        } else {
            echo json_encode(['success' => true, 'referencia' => $ref]);
        }
        exit();
    }

    public function ver() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            die('Producto no válido.');
        }
        $producto = Producto::obtenerDetallePorCatalogoId($id);
        if (!$producto) {
            die('Producto no encontrado o no está en oferta.');
        }
        $tallas_disponibles = Producto::obtenerTallasPorCatalogoId($id);
        $imagenes = Producto::obtenerImagenesPorCatalogo($id);
        require __DIR__ . '/../views/productos/ver.php';
    }

    public function stock() {
        header('Content-Type: application/json');
        $catalogo_id = isset($_GET['catalogo_id']) ? intval($_GET['catalogo_id']) : (isset($_POST['catalogo_id']) ? intval($_POST['catalogo_id']) : 0);
        $talla = isset($_GET['talla']) ? $_GET['talla'] : (isset($_POST['talla']) ? $_POST['talla'] : '');
        if ($catalogo_id <= 0 || empty($talla)) {
            echo json_encode(['success' => false, 'error' => 'Parámetros inválidos']);
            exit;
        }
        $conn = Database::getConexion();
        $sql = "SELECT s.cantidad, i.precio_venta, (i.precio_venta * (1 - (cp.oferta / 100))) AS precio_con_descuento
                FROM catalogo_productos cp
                JOIN ingreso i ON cp.ingreso_id = i.id
                JOIN inventario_sucursal s ON cp.producto_id = s.id_producto AND cp.sucursal_id = s.id_sucursal
                JOIN producto p ON cp.producto_id = p.id_producto
                WHERE cp.id = $1 AND p.talla_producto = $2 AND cp.sucursal_id = 7 AND (cp.estado = true OR cp.estado = 't') LIMIT 1";
        $res = pg_query_params($conn, $sql, [$catalogo_id, $talla]);
        $row = pg_fetch_assoc($res);
        if ($row) {
            echo json_encode([
                'success' => true,
                'cantidad' => intval($row['cantidad']),
                'precio_venta' => floatval($row['precio_venta']),
                'precio_con_descuento' => floatval($row['precio_con_descuento'])
            ]);
        } else {
            echo json_encode(['success' => false, 'cantidad' => 0]);
        }
        exit;
    }

    private function validarCamposProducto($ref, $nombre, $id_subcategoria, $talla) {
        $errores = [];
        if (empty($ref)) $errores[] = 'Referencia requerida';
        if (empty($nombre)) $errores[] = 'Nombre requerido';
        if (empty($id_subcategoria)) $errores[] = 'Subcategoría requerida';
        if (empty($talla)) $errores[] = 'Talla requerida';
        return $errores;
    }
    private function generarReferenciaUnicaBD($conn) {
        for ($i = 0; $i < 5; $i++) {
            $ref = strval(rand(10000000, 99999999));
            $result = pg_query_params($conn, "SELECT 1 FROM producto WHERE ref_producto = $1", [$ref]);
            if (pg_num_rows($result) === 0) {
                return $ref;
            }
        }
        return false;
    }
} 