<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function listar() {
        $usuarios = Usuario::obtenerTodos();
        require __DIR__ . '/../views/usuarios/listar.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $error = 1;
            $msg = 'Acceso denegado';
            require __DIR__ . '/../views/usuarios/registro.php';
            return;
        }
        
        $nombre_usuario = $_POST['txtname'] ?? '';
        $email_usuario = $_POST['txtcorreo'] ?? '';
        $ref_usuario = $_POST['txtnick'] ?? '';
        $password_usuario = $_POST['txtpass'] ?? '';
        $telefono_usuario = $_POST['txttelefono'] ?? '';
        $direccion_usuario = $_POST['txtdireccion'] ?? '';

        $errores = Usuario::validarCampos($nombre_usuario, $email_usuario, $ref_usuario, $telefono_usuario, $direccion_usuario);
        if (!empty($errores)) {
            $error = 'registro';
            $msg = implode(', ', $errores);
            require __DIR__ . '/../views/usuarios/registro.php';
            return;
        }

        $errores_password = Usuario::validarPassword($password_usuario, $password_usuario);
        if (!empty($errores_password)) {
            $error = 'registro';
            $msg = implode(', ', $errores_password);
            require __DIR__ . '/../views/usuarios/registro.php';
            return;
        }

        $result = Usuario::registrar($nombre_usuario, $email_usuario, $ref_usuario, $password_usuario, $telefono_usuario, $direccion_usuario);
        if ($result) {
            $success = 1;
            require __DIR__ . '/../views/usuarios/registro.php';
            return;
        } else {
            if (Usuario::existePorEmail($email_usuario)) {
                $error = 'correo';
                require __DIR__ . '/../views/usuarios/registro.php';
            } elseif (Usuario::existePorNickname($ref_usuario)) {
                $error = 'nick';
                require __DIR__ . '/../views/usuarios/registro.php';
            } else {
                $error = 'registro';
                require __DIR__ . '/../views/usuarios/registro.php';
            }
        }
        exit();
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=usuario&action=listar&error=1&msg=Acceso denegado');
            exit;
        }
        
        $id_usuario = $_POST['id_usuario'] ?? '';
        $nombre_usuario = $_POST['nombre_usuario'] ?? '';
        $email_usuario = $_POST['email_usuario'] ?? '';
        $ref_usuario = $_POST['ref_usuario'] ?? '';
        $telefono_usuario = $_POST['telefono_usuario'] ?? '';
        $direccion_usuario = $_POST['direccion_usuario'] ?? '';

        if (!$id_usuario || !is_numeric($id_usuario)) {
            header('Location: index.php?controller=usuario&action=listar&error=1&msg=ID de usuario inválido');
            exit;
        }

        $errores = Usuario::validarCampos($nombre_usuario, $email_usuario, $ref_usuario, $telefono_usuario, $direccion_usuario);
        if (!empty($errores)) {
            $msg = urlencode(implode(', ', $errores));
            header('Location: index.php?controller=usuario&action=listar&error=1&msg=' . $msg);
            exit();
        }

        $result = Usuario::actualizar($id_usuario, $nombre_usuario, $email_usuario, $ref_usuario, $telefono_usuario, $direccion_usuario);
        if ($result) {
            header('Location: index.php?controller=usuario&action=listar&success=1&msg=Usuario actualizado correctamente');
        } else {
            if (Usuario::existePorEmailExcluyendoId($email_usuario, $id_usuario)) {
                header('Location: index.php?controller=usuario&action=listar&error=1&msg=El email ya está registrado');
            } elseif (Usuario::existePorNicknameExcluyendoId($ref_usuario, $id_usuario)) {
                header('Location: index.php?controller=usuario&action=listar&error=1&msg=El nickname ya está en uso');
            } else {
                header('Location: index.php?controller=usuario&action=listar&error=1&msg=Error al actualizar usuario');
            }
        }
        exit();
    }

    public function cambiarPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=usuario&action=listar&error=1&msg=Acceso denegado');
            exit;
        }
        
        $id_usuario = $_POST['id_usuario'] ?? '';
        $password_nueva = $_POST['password_nueva'] ?? '';
        $password_confirmar = $_POST['password_confirmar'] ?? '';

        if (!$id_usuario || !is_numeric($id_usuario)) {
            header('Location: index.php?controller=usuario&action=listar&error=1&msg=ID de usuario inválido');
            exit;
        }

        $errores = Usuario::validarPassword($password_nueva, $password_confirmar);
        if (!empty($errores)) {
            $msg = urlencode(implode(', ', $errores));
            header('Location: index.php?controller=usuario&action=listar&error=1&msg=' . $msg);
            exit();
        }

        $result = Usuario::cambiarPassword($id_usuario, $password_nueva);
        if ($result) {
            header('Location: index.php?controller=usuario&action=listar&success=1&msg=Contraseña cambiada correctamente');
        } else {
            header('Location: index.php?controller=usuario&action=listar&error=1&msg=Error al cambiar contraseña');
        }
        exit();
    }

    public function perfil() {
        if (!isset($_SESSION['id'])) {
            header('Location: login.php');
            exit();
        }
        $row = Usuario::obtenerPorId($_SESSION['id']);
        if (!$row) {
            die('No se encontró el usuario con ID: ' . $_SESSION['id']);
        }
        require __DIR__ . '/../views/usuarios/perfil.php';
    }

    public function registro() {
        if (isset($_SESSION['usuario'])) {
            header('Location: index.php');
            exit();
        }
        $success = isset($_GET['success']) ? $_GET['success'] : null;
        $error = isset($_GET['error']) ? $_GET['error'] : null;
        $msg = isset($_GET['msg']) ? $_GET['msg'] : null;
        require __DIR__ . '/../views/usuarios/registro.php';
    }

    public function login() {
        if (isset($_SESSION['usuario'])) {
            header('Location: index.php');
            exit();
        }
        $error = isset($_GET['error']) && $_GET['error'] == 1;
        require __DIR__ . '/../views/usuarios/login.php';
    }

    public function validarLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=usuario&action=login');
            exit();
        }
        $usuario = trim($_POST['txtusu'] ?? '');
        $contrasenia = trim($_POST['txtpass'] ?? '');
        $conn = Database::getConexion();
        $query = "SELECT id_usuario, ref_usuario, pass_usuario, rol_usuario FROM usuario WHERE ref_usuario = $1 OR email_usuario = $1 LIMIT 1";
        $result = pg_query_params($conn, $query, [$usuario]);
        if ($result && pg_num_rows($result) === 1) {
            $row = pg_fetch_assoc($result);
            $hash = $row['pass_usuario'];
            if (password_verify($contrasenia, $hash)) {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['id'] = $row['id_usuario'];
                $_SESSION['rol'] = $row['rol_usuario'];
                if ($_SESSION['rol'] === 'cliente') {
                    header('Location: index.php');
                } else {
                    header('Location: dashboard.php');
                }
                exit();
            }
        }
        header('Location: index.php?controller=usuario&action=login&error=1');
        exit();
    }

    public function cerrarSesion() {
        session_unset();
        session_destroy();
        header('Location: index.php?controller=usuario&action=login');
        exit();
    }
} 