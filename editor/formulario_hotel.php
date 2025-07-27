<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'editor') {
    header('Location: ../index.php');
    exit;
}

require_once '../clases/mod_db.php';

$db = new mod_db();
$conn = $db->getConexion();

// Obtener categorías, instalaciones y provincias
$categorias = $conn->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
$instalaciones = $conn->query("SELECT * FROM instalaciones")->fetchAll(PDO::FETCH_ASSOC);
$provincias = $conn->query("SELECT * FROM provincias ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Hotel</title>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
  <link rel="stylesheet" href="../css/formulario_hotel.css">
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
      <label>Provincia:</label>
      <select name="provincia_id" required>
        <option value="">-- Seleccione una provincia --</option>
        <?php foreach ($provincias as $prov): ?>
          <option value="<?= $prov['id'] ?>"><?= htmlspecialchars($prov['nombre']) ?></option>
        <?php endforeach; ?>
      </select>
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
      <label>Habitaciones:</label>
      <div id="habitaciones">
        <div class="habitacion-group">
          <input type="text" name="tipo_habitacion[]" placeholder="Tipo" required>
          <input type="number" name="capacidad[]" placeholder="Capacidad" min="1" required>
          <input type="number" name="precio[]" placeholder="Precio por noche" min="0" step="0.01" required>
          <select name="temporada[]" required>
            <option value="alta">Temporada Alta</option>
            <option value="baja">Temporada Baja</option>
          </select>
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
    <input type="number" name="precio[]" placeholder="Precio por noche" min="0" step="0.01" required>
    <select name="temporada[]" required>
      <option value="alta">Temporada Alta</option>
      <option value="baja">Temporada Baja</option>
    </select>
  `;
  document.getElementById('habitaciones').appendChild(div);
}
</script>

<!-- Footer -->
<?php include("../index/footer.php"); ?>
</body>
</html>
