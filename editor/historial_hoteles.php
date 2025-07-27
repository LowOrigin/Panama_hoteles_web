<?php
session_start();
require_once '../clases/mod_db.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'editor') {
    header("Location: ../formularios/login_form.php");
    exit;
}

$db = new mod_db();
$conn = $db->getConexion();

$usuario = $_SESSION['usuario'];

// Obtener ID del editor
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$stmt->execute([$usuario]);
$editor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$editor) {
    echo "Editor no encontrado.";
    exit;
}

$editor_id = $editor['id'];

// Obtener hoteles creados por el editor
$query = "SELECT h.nombre, h.descripcion, h.direccion, c.nombre AS categoria, h.aprobado
          FROM hoteles h
          LEFT JOIN categorias c ON h.categoria_id = c.id
          WHERE h.creado_por = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$editor_id]);
$hoteles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Hoteles</title>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
</head>
<body>

<div class="container">
  <a class="btn-volver" href="dashboard_editor.php">← Volver al Panel</a>
  <h2>Mis Hoteles Registrados</h2>

  <table>
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Dirección</th>
        <th>Categoría</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($hoteles) > 0): ?>
        <?php foreach ($hoteles as $hotel): ?>
          <tr>
            <td><?= htmlspecialchars($hotel['nombre']) ?></td>
            <td><?= htmlspecialchars($hotel['descripcion']) ?></td>
            <td><?= htmlspecialchars($hotel['direccion']) ?></td>
            <td><?= htmlspecialchars($hotel['categoria']) ?></td>
            <td>
              <?php if ($hotel['aprobado']): ?>
                <span class="estado-aprobado">✅ Aprobado</span>
              <?php else: ?>
                <span class="estado-rechazado">❌ No aprobado</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5" style="text-align:center;">No has registrado hoteles aún.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include("../index/footer.php"); ?>
</body>
</html>
