<?php
session_start();
$usuario = $_SESSION['usuario'] ?? null;

// Lógica de conexión y hoteles filtrados
require_once 'filtrar_hoteles.php';
// Obtener provincias
$stmtProvincias = $conn->query("SELECT id, nombre FROM provincias ORDER BY nombre ASC");
$provincias = $stmtProvincias->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio - Panama Hotels</title>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
  <link rel="stylesheet" href="../css/indexCss.css">
</head>
<body>

<div class="contenido">
  <h1 class="titulo">Panama Hotels</h1>
  <h2 class="subtitulo">El mejor sitio de hostelería en línea</h2>

  <div class="navbar">
    <div class="nav-left">
      <a href="index.php">🏠 Inicio</a>
      <a href="nosotros.php">📄 Nosotros</a>
      <a href="../public/reservas.php">📅 Reservas</a>
    </div>

    <div class="nav-right">
      <?php if (!$usuario): ?>
        <a href="../formularios/login_form.php" class="btn btn-login">Iniciar Sesión</a>
        <a href="../formularios/formulario_registro.php" class="btn btn-register">Registrarse</a>
      <?php else: ?>
        <div class="user-menu">
          <button class="user-button"><?= htmlspecialchars($usuario) ?> ⬇</button>
          <div class="dropdown">
            <a href="../public/logout.php">Cerrar Sesión</a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="card-container">
    <h2>
      <?php if ($usuario): ?>
        Bienvenido, <?= htmlspecialchars($usuario); ?>
      <?php else: ?>
        Tu mejor experiencia en hospedaje
      <?php endif; ?>
    </h2>
  </div>

  <!-- Filtros por categoría y provincia -->
<div class="filter-container">
  <form method="GET" action="index.php">
    <label for="categoria">Filtrar por estrellas:</label>
    <select name="categoria" id="categoria" onchange="this.form.submit()">
      <option value="">-- Todas --</option>
      <?php for ($i = 1; $i <= 5; $i++): ?>
        <option value="<?= $i ?>" <?= (isset($_GET['categoria']) && $_GET['categoria'] == $i) ? 'selected' : '' ?>>
          <?= $i ?> estrella<?= $i > 1 ? 's' : '' ?>
        </option>
      <?php endfor; ?>
    </select>

    <label for="provincia" style="margin-left: 20px;">Filtrar por provincia:</label>
    <select name="provincia" id="provincia" onchange="this.form.submit()">
      <option value="">-- Todas --</option>
      <?php foreach ($provincias as $prov): ?>
        <option value="<?= $prov['id'] ?>" <?= (isset($_GET['provincia']) && $_GET['provincia'] == $prov['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($prov['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>
</div>


  <!-- Tarjetas de hoteles -->
  <div class="hotel-grid">
    <?php foreach ($hoteles as $row):
      $imagenNombre = $row['imagen'] ?? '';
      $rutaImagen = (!empty($imagenNombre) && file_exists("../img_hoteles/" . $imagenNombre))
        ? "../img_hoteles/" . $imagenNombre
        : "../img/imagen_por_defecto.jpg";
    ?>
      <a class="hotel-card" href="../public/ver_hotel.php?id=<?= intval($row['id']) ?>">
        <img src="<?= $rutaImagen ?>" alt="Imagen del hotel">
        <div class="hotel-info">
          <h3><?= htmlspecialchars($row['nombre']) ?></h3>
          <p><?= htmlspecialchars($row['direccion']) ?></p>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<?php include("../index/footer.php"); ?>

</body>
</html>

