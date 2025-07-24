<?php
// Inicia la sesión PHP para poder usar variables de sesión (por ejemplo, para saber si el usuario ha iniciado sesión)
session_start();

// Se obtiene el valor de la variable de sesión 'usuario', si existe; si no, se asigna null
$usuario = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio - Sistema de Hoteles</title>
  <!-- Se enlaza la hoja de estilos general -->
  <link rel="stylesheet" href="../css/estilosGenerales.css">
</head>
<body>
  <!-- Contenedor principal con estilo de tarjeta -->
  <div class="card-container">
    <h1>Sistema de Hoteles</h1>

    <!-- Muestra un saludo personalizado si el usuario ha iniciado sesión -->
    <h2>
      <?php if ($usuario): ?>
        Bienvenido, <?= htmlspecialchars($usuario); ?> <!-- Se escapa el nombre del usuario por seguridad -->
      <?php else: ?>
        Bienvenido a nuestro sitio de hoteles <!-- Mensaje por defecto para visitantes no autenticados -->
      <?php endif; ?>
    </h2>

    <!-- Lista de navegación con enlaces a las funcionalidades principales -->
    <ul style="margin-top: 30px; list-style: none; padding: 0;">
      <li><a href="../public/ver_hotel.php">🏨 Ver Hoteles</a></li>       <!-- Página para ver hoteles disponibles -->
      <li><a href="../public/reservar.php">📅 Reservar</a></li>          <!-- Página para hacer una reserva -->
      <li><a href="../formularios/login_form.php">🔐 Iniciar Sesión</a></li> <!-- Página de login -->
      <li><a href="../formularios/formulario_registro.php">📝 Registrarse</a></li>       <!-- Página de registro -->
    </ul>
  </div>

  <!-- Pie de página con derechos de autor dinámicos usando la fecha actual -->
  <footer>
    <p>&copy; <?= date("Y") ?> Sistema de Hoteles | Todos los derechos reservados</p>
  </footer>
</body>
</html>
