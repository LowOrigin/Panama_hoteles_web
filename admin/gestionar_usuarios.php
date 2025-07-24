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

// Cambiar estado de usuario si se recibe una acción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['accion'])) {
    $id = (int) $_POST['id'];
    $accion = $_POST['accion'] === 'desactivar' ? 0 : 1;
    $stmt = $conexion->prepare("UPDATE usuarios SET activo = :activo WHERE id = :id");
    $stmt->execute(['activo' => $accion, 'id' => $id]);
}

// Obtener usuarios (excepto clientes)
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE rol IN ('admin', 'editor')");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestionar Usuarios</title>
  <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
  <div class="card-container">
    <h1>Listado de Usuarios (Admin / Editor)</h1>
    <table border="1" cellpadding="8">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Usuario</th>
          <th>Correo</th>
          <th>Rol</th>
          <th>Estado</th>
          <th>Acciones</th>
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
              <button type="submit">
                <?= $user['activo'] ? 'Desactivar' : 'Activar' ?>
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
