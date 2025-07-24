<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../formularios/login_form.php");
    exit;
}

require_once("../clases/mod_db.php");
require_once("../clases/SanitizarEntrada.php");

$db = new mod_db();
$conexion = $db->getConexion();

// Activar o desactivar usuarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['accion'])) {
    $id = (int) $_POST['id'];
    $accion = $_POST['accion'];
    $idActual = $_SESSION['id'] ?? 0;

    if ($id === $idActual && $accion === 'desactivar') {
        // No permitir desactivarse a sí mismo
        echo "<script>alert('No puedes desactivarte a ti mismo.'); window.location.href='gestionar_usuarios.php';</script>";
        exit;
    }

    $activo = $accion === 'desactivar' ? 0 : 1;
    $stmt = $conexion->prepare("UPDATE usuarios SET activo = :activo WHERE id = :id");
    $stmt->execute(['activo' => $activo, 'id' => $id]);
}

// Obtener usuarios organizados por rol
$roles = ['admin', 'editor', 'cliente'];
$usuariosPorRol = [];

foreach ($roles as $rol) {
    $stmt = $conexion->prepare("SELECT id, nombre, apellido, usuario, correo, rol, activo FROM usuarios WHERE rol = :rol");
    $stmt->execute(['rol' => $rol]);
    $usuariosPorRol[$rol] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestionar Usuarios</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
</head>
<body>
  <div class="admin-panel">
    <h1>Gestión de Usuarios</h1>
    <a class="btn-volver" href="dashboard.php">← Volver al Panel</a>

    <?php foreach ($usuariosPorRol as $rol => $usuarios): ?>
      <section class="usuario-section">
        <h2><?= ucfirst($rol) ?><?= $rol === 'cliente' ? 's' : 'es' ?></h2>
        <?php if (empty($usuarios)): ?>
          <p>No hay usuarios con rol <?= $rol ?>.</p>
        <?php else: ?>
          <div class="usuarios-grid">
            <?php foreach ($usuarios as $user): ?>
              <div class="usuario-card <?= $user['activo'] ? 'activo' : 'inactivo' ?>">
                <h3><?= htmlspecialchars($user['nombre'] . ' ' . $user['apellido']) ?></h3>
                <p><strong>Usuario:</strong> <?= htmlspecialchars($user['usuario']) ?></p>
                <p><strong>Correo:</strong> <?= htmlspecialchars($user['correo']) ?></p>
                <p><strong>Rol:</strong> <?= $user['rol'] ?></p>
                <p><strong>Estado:</strong> <?= $user['activo'] ? 'Activo' : 'Inactivo' ?></p>
                <form method="POST">
                  <input type="hidden" name="id" value="<?= $user['id'] ?>">
                  <input type="hidden" name="accion" value="<?= $user['activo'] ? 'desactivar' : 'activar' ?>">
                <?php
$isSelf = $user['id'] == ($_SESSION['id'] ?? 0) && $user['activo'];
?>
<button 
  type="<?= $isSelf ? 'button' : 'submit' ?>" 
  class="btn-estado <?= $isSelf ? 'btn-disabled' : '' ?>" 
  <?= $isSelf ? 'data-self="true"' : '' ?>>
  <?= $user['activo'] ? 'Desactivar' : 'Activar' ?>
</button>
                </form>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>
    <?php endforeach; ?>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('button[data-self="true"]').forEach(btn => {
      btn.addEventListener('click', () => {
        Swal.fire({
          icon: 'warning',
          title: 'Acción no permitida',
          text: 'No puedes desactivarte a ti mismo.',
          confirmButtonText: 'Entendido'
        });
      });
    });
  });
</script>
</body>
</html>
