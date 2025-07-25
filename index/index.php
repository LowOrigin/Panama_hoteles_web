<?php
session_start();
$usuario = $_SESSION['usuario'] ?? null;
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
       background-color: #111; /* fondo negro */
       min-width: 140px;
       box-shadow: 0 8px 16px rgba(0,0,0,0.4);
       z-index: 1;
       border-radius: 6px;
       overflow: hidden;
    }

    .dropdown a {
       color: white; /* letras blancas */
       padding: 12px 16px;
       display: block;
       text-decoration: none;
       font-weight: bold;
    }

    .dropdown a:hover {
      background-color: #333; /* gris oscuro al pasar el mouse */
    }


    .user-menu:hover .dropdown {
      display: block;
    }

    .card-container {
      max-width: 600px;
      margin: 40px auto;
      text-align: center;
    }
  </style>
</head>
<body>

<!-- Título principal -->
<div class="contenido">
  <h1 style="text-align: center;">Panama Hotels</h1>
  <h2 style="text-align: center;">El mejor sitio de hostelería en línea</h2>

  <!-- Navbar debajo del título -->
  <div class="navbar">
    <div class="nav-left">
      <a href="index.php">🏠 Inicio</a>
      <a href="nosotros.php">📄 Nosotros</a>
      <a href="../public/ver_hotel.php">🏨 Ver Hoteles</a>
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

  <!-- Bienvenida -->
  <div class="card-container">
    <h2>
      <?php if ($usuario): ?>
        Bienvenido, <?= htmlspecialchars($usuario); ?>
      <?php else: ?>
        Tu mejor experiencia en hospedaje
      <?php endif; ?>
    </h2>
  </div>
</div>

<!-- Footer -->
<?php include("../index/footer.php"); ?>

</body>
</html>