<?php
session_start();
$usuario = $_SESSION['usuario'] ?? null;

// Lógica de conexión y hoteles filtrados
require_once 'filtrar_hoteles.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio - Panama Hotels</title>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .titulo {
      text-align: center;
      font-size: 48px;
      font-weight: bold;
      margin-top: 30px;
    }

    .subtitulo {
      text-align: center;
      font-size: 20px;
      color: #555;
      margin-bottom: 20px;
    }

    .navbar {
      background-color: #2c3e50;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 24px;
    }

    .navbar .nav-left a,
    .navbar .nav-right a {
      color: white;
      text-decoration: none;
      margin: 0 12px;
      font-weight: bold;
    }

    .navbar .nav-left a:hover,
    .navbar .nav-right a:hover {
      text-decoration: underline;
    }

    .navbar .nav-right .btn {
      padding: 6px 12px;
      border-radius: 6px;
      border: none;
      text-decoration: none;
      cursor: pointer;
      font-weight: bold;
    }

    .btn-login {
      background-color: #2980b9;
      color: white;
    }

    .btn-register {
      background-color: #34495e;
      color: white;
    }

    .user-menu {
      position: relative;
      display: inline-block;
    }

    .user-button {
      background-color: #27ae60;
      color: white;
      padding: 8px 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .dropdown {
      display: none;
      position: absolute;
      right: 0;
      background-color: #111;
      min-width: 140px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.4);
      z-index: 1;
      border-radius: 6px;
      overflow: hidden;
    }

    .dropdown a {
      color: white;
      padding: 12px 16px;
      display: block;
      text-decoration: none;
      font-weight: bold;
    }

    .dropdown a:hover {
      background-color: #333;
    }

    .user-menu:hover .dropdown {
      display: block;
    }

    .card-container {
      max-width: 600px;
      margin: 40px auto 20px;
      text-align: center;
    }

    .filter-container {
      text-align: center;
      margin-bottom: 30px;
    }

    .hotel-grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin: 40px auto;
      max-width: 1200px;
    }

    .hotel-card {
      display: block;
      width: 260px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      overflow: hidden;
      text-decoration: none;
      color: #333;
      transition: transform 0.2s ease;
    }

    .hotel-card:hover {
      transform: translateY(-5px);
    }

    .hotel-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .hotel-info {
      background-color: #fff;
      padding: 12px 16px;
      box-sizing: border-box;
      text-align: center;
    }

    .hotel-info h3 {
      margin: 0 0 8px;
      font-size: 18px;
      font-weight: bold;
      color: #2c3e50;
    }

    .hotel-info p {
      margin: 0;
      font-size: 14px;
      color: #555;
    }

    select {
      padding: 6px 12px;
      font-size: 15px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    label {
      font-weight: bold;
      margin-right: 10px;
    }
  </style>
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

  <!-- Filtro por categoría -->
  <div class="filter-container">
    <form method="GET" action="index.php">
      <label for="categoria">Filtrar por estrellas:</label>
      <select name="categoria" id="categoria" onchange="this.form.submit()">
        <option value="">-- Todas --</option>
        <option value="1" <?= (isset($_GET['categoria']) && $_GET['categoria'] == 1) ? 'selected' : '' ?>>1 estrella</option>
        <option value="2" <?= (isset($_GET['categoria']) && $_GET['categoria'] == 2) ? 'selected' : '' ?>>2 estrellas</option>
        <option value="3" <?= (isset($_GET['categoria']) && $_GET['categoria'] == 3) ? 'selected' : '' ?>>3 estrellas</option>
        <option value="4" <?= (isset($_GET['categoria']) && $_GET['categoria'] == 4) ? 'selected' : '' ?>>4 estrellas</option>
        <option value="5" <?= (isset($_GET['categoria']) && $_GET['categoria'] == 5) ? 'selected' : '' ?>>5 estrellas</option>
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

