// archivo: procesar_login.php
<?php
session_start();
require_once("includes/conexion.php");

$usuario = $_POST['usuario'] ?? '';
$clave = $_POST['clave'] ?? '';

if (empty($usuario) || empty($clave)) {
    header("Location: login.php?error=Campos obligatorios");
    exit();
}

try {
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE (usuario = :usuario OR correo = :usuario) AND activo = 1");
    $stmt->execute(['usuario' => $usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($clave, $user['clave'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['rol'] = $user['rol'];

        // Redirige según el rol
        if ($user['rol'] === 'admin') {
            header("Location: admin/dashboard.php");
        } elseif ($user['rol'] === 'editor') {
            header("Location: editor/mis_hoteles.php");
        } else {
            header("Location: index.php");
        }
    } else {
        header("Location: login.php?error=Credenciales incorrectas");
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>