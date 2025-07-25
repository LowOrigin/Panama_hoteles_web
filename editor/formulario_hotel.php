<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'editor') {
    header('Location: ../index.php');
    exit;
}

require_once '../clases/mod_db.php';

$db = new mod_db();
$conn = $db->getConexion();

// Obtener categorías e instalaciones
$categorias = $conn->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
$instalaciones = $conn->query("SELECT * FROM instalaciones")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Hotel</title>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f2f5;
      margin: 0;
      padding: 0;
    }

    .form-container {
      max-width: 700px;
      margin: 60px auto;
      background-color: #fff;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      position: relative;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
      color: #555;
    }

    input[type="text"],
    input[type="number"],
    input[type="file"],
    select,
    textarea {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 15px;
    }

    textarea {
      resize: vertical;
    }

    .checkboxes label {
      display: inline-block;
      margin-right: 15px;
      margin-top: 6px;
      font-weight: normal;
    }

    .btn {
      background-color: #007bff;
      color: #fff;
      padding: 10px 18px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #0056b3;
    }

    .habitacion-group {
      margin-bottom: 10px;
    }

    /* Botón de retroceso */
    .back-button {
      position: absolute;
      top: 20px;
      left: 20px;
      text-decoration: none;
      background-color: #ddd;
      color: #333;
      padding: 8px 14px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: bold;
      display: inline-block;
      transition: background-color 0.3s ease;
    }

    .back-button:hover {
      background-color: #bbb;
    }
  </style>
</head>
<body>

<a href="dashboard_editor.php" class="back-button">← Volver</a>

<div class="form-container">
  <h2>Registrar Nuevo Hotel</h2>
  <form action="procesar_hotel.php" method="POST" enctype="multipart/form-data">

    <div class="form-group">
      <label>Nombre del Hotel:</label>
      <input type="text" name="nombre" required>
    </div>

    <div class="form-group">
      <label>Dirección:</label>
      <input type="text" name="direccion" required>
    </div>

    <div class="form-group">
      <label>Descripción:</label>
      <textarea name="descripcion" rows="4" required></textarea>
    </div>

    <div class="form-group">
      <label>Categoría:</label>
      <select name="categoria_id" required>
        <?php foreach ($categorias as $cat): ?>
          <option value="<?= $cat['id'] ?>"><?= $cat['nombre'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label>Instalaciones:</label>
      <div class="checkboxes">
        <?php foreach ($instalaciones as $inst): ?>
          <label>
            <input type="checkbox" name="instalaciones[]" value="<?= $inst['id'] ?>"> <?= $inst['nombre'] ?>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="form-group">
      <label>Imagen principal:</label>
      <input type="file" name="imagen" accept="image/*" required>
    </div>

    <div class="form-group">
      <label>Habitaciones (tipo y capacidad):</label>
      <div id="habitaciones">
        <div class="habitacion-group">
          <input type="text" name="tipo_habitacion[]" placeholder="Tipo" required>
          <input type="number" name="capacidad[]" placeholder="Capacidad" min="1" required>
        </div>
      </div>
      <button type="button" onclick="agregarHabitacion()" class="btn">Agregar otra habitación</button>
    </div>

    <button type="submit" class="btn">Guardar Hotel</button>
  </form>
</div>

<script>
function agregarHabitacion() {
  const div = document.createElement('div');
  div.className = 'habitacion-group';
  div.innerHTML = `
    <input type="text" name="tipo_habitacion[]" placeholder="Tipo" required>
    <input type="number" name="capacidad[]" placeholder="Capacidad" min="1" required>
  `;
  document.getElementById('habitaciones').appendChild(div);
}
</script>
</body>
</html>
