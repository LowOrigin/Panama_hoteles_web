<?php
session_start();
$usuario = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio - Sistema de Hoteles</title>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
</head>
<body>
  <div class="card-container">
    <h1>Sistema de Hoteles</h1>
    <h2>
      <?php if ($usuario): ?>
        Bienvenido, <?= htmlspecialchars($usuario); ?>
      <?php else: ?>
        Bienvenido a nuestro sitio de hoteles
      <?php endif; ?>
    </h2>

    <ul style="margin-top: 30px; list-style: none; padding: 0;">
      <li><a href="../public/ver_hotel.php">🏨 Ver Hoteles</a></li>
      <li><a href="../public/reservar.php">📅 Reservar</a></li>
      <li><a href="../formularios/login_form.php">🔐 Iniciar Sesión</a></li>
      <li><a href="../public/registro.php">📝 Registrarse</a></li>
    </ul>
  </div>

  <footer>
    <p>&copy; <?= date("Y") ?> Sistema de Hoteles | Todos los derechos reservados</p>
  </footer>
</body>
</html>
