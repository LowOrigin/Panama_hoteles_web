<?php
session_start();
require_once("../clases/mod_db.php");
require_once("../clases/ValidacionLogin.php");
require_once("../clases/SanitizarEntrada.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['tolog'])) {
    header("Location: ../formularios/login_form.php");
    exit;
}

$usuario = $_POST['usuario'] ?? '';
$clave = $_POST['contrasena'] ?? '';

$db = new mod_db();
$login = new ValidacionLogin($usuario, $clave, $db);

if ($login->logger()) {
    $login->autenticar();
    if ($login->loginExitoso()) {
        $usuarioBD = $db->log($login->getUsuario());

        // Verificamos si está activo
        if ((int) $usuarioBD->activo !== 1) {
            header("Location: ../formularios/login_form.php?error=Este usuario ha sido desactivado por el administrador.");
            exit;
        }

        // Guardar datos en sesión
        $_SESSION['usuario_id'] = $usuarioBD->id;
        $_SESSION['usuario'] = $usuarioBD->usuario;
        $_SESSION['rol'] = $usuarioBD->rol;
        $_SESSION['id'] = $usuarioBD->id; // importante para evitar desactivarse a sí mismo

        // Redirige según el rol
        if ($usuarioBD->rol === 'admin') {
            header("Location: ../admin/dashboard.php");
        } elseif ($usuarioBD->rol === 'editor') {
            header("Location: ../editor/dashboard_editor.php");
        } else {
            header("Location: ../index/index.php");
        }
        exit;
    }
}

header("Location: ../formularios/login_form.php?error=Credenciales inválidas");
exit;
?>
