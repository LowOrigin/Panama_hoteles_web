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

// Manejar acciones (activar/desactivar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['accion'])) {
    $id = (int) $_POST['id'];
    $accion = $_POST['accion'] === 'desactivar' ? 0 : 1;
    $stmt = $conexion->prepare("UPDATE usuarios SET activo = :activo WHERE id = :id");
    $stmt->execute(['activo' => $accion, 'id' => $id]);
}

// Obtener usuarios que no sean clientes
$stmt = $conexion->prepare("SELECT id, nombre, apellido, usuario, correo, rol, activo FROM usuarios WHERE rol IN ('admin', 'editor')");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestionar Usuarios</title>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
</head>
<body>
  <div class="admin-panel">
    <h1>Gestión de Usuarios (Admin/Editor)</h1>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre completo</th>
          <th>Usuario</th>
          <th>Correo</th>
          <th>Rol</th>
          <th>Estado</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($usuarios as $user): ?>
          <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['nombre'] . ' ' . $user['apellido']) ?></td>
            <td><?= htmlspecialchars($user['usuario']) ?></td>
            <td><?= htmlspecialchars($user['correo']) ?></td>
            <td><?= $user['rol'] ?></td>
            <td><?= $user['activo'] ? 'Activo' : 'Inactivo' ?></td>
            <td>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <input type="hidden" name="accion" value="<?= $user['activo'] ? 'desactivar' : 'activar' ?>">
                <button class="btn-action" type="submit"><?= $user['activo'] ? 'Desactivar' : 'Activar' ?></button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <br>
    <a class="btn-volver" href="../admin/dashboard.php">← Volver al Panel</a>
  </div>
</body>
</html>
