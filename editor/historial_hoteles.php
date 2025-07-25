<?php
session_start();
require_once '../clases/mod_db.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'editor') {
    header("Location: ../formularios/login_form.php");
    exit;
}

$usuario = $_SESSION['usuario'];

// Obtener ID del editor
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();
$editor = $result->fetch_assoc();

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
$stmt->bind_param("i", $editor_id);
$stmt->execute();
$hoteles = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Hoteles</title>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
  <style>
    body {
      background-color: #0f2027;
      color: white;
      font-family: 'Segoe UI', sans-serif;
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      background-color: #1f2b37;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.3);
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 28px;
      color: #fff;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #263445;
    }

    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #444;
    }

    th {
      background-color: #34495e;
      color: #fff;
    }

    .estado-aprobado {
      color: #2ecc71;
      font-weight: bold;
    }

    .estado-rechazado {
      color: #e74c3c;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="container">
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
      <?php if ($hoteles->num_rows > 0): ?>
        <?php while ($hotel = $hoteles->fetch_assoc()): ?>
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
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5" style="text-align:center;">No has registrado hoteles aún.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>
