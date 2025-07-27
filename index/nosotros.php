<?php
session_start();
$usuario = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nosotros - Panama Hotels</title>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
  <link rel="stylesheet" href="../css/nosotrosCss.css"> <!-- NUEVO -->
</head>
<body>

<div class="navbar">
  <div class="nav-left">
    <a href="index.php">🏠 Inicio</a>
    <a href="nosotros.php" style="color: #4FC3F7; text-decoration:underline;">📄 Nosotros</a>
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

<div class="nosotros-contenido">
  <h1>Sobre Panama Hotels</h1>
  <p>
    <b>Panama Hotels</b> es un sistema de reservas de hoteles en línea desarrollado como proyecto académico para la asignatura de Desarrollo de Software VII.<br>
    Nuestro objetivo es ofrecer una plataforma intuitiva, segura y eficiente para la gestión de hoteles, reservas y atención al usuario.
  </p>
  <h2>Equipo de Desarrollo</h2>
  <ul class="equipo-lista">
    <li><b>Yosue Pineda</b> – Coordinador & Full Stack</li>
    <li><b>Alex Pan</b> – Backend, Autenticación y Seguridad</li>
    <li><b>Erick Ospina</b> – Frontend, Interfaz y Reportes</li>
    <li><b>Cesar Aburto</b> – Documentación y Testing</li>
  </ul>
  <div class="vision">
    <b>Visión:</b> Ser la solución universitaria referente en gestión hotelera online, destacando por su facilidad de uso, calidad técnica y valor académico.
  </div>
  <div class="mision">
    <b>Misión:</b> Brindar una plataforma amigable para que usuarios y administradores gestionen hoteles y reservas de forma eficiente, incorporando buenas prácticas de desarrollo, seguridad y diseño.
  </div>
  <div class="contacto">
    <b>Contacto:</b> <br>
    Email: <a href="mailto:soporte@panamahotels.edu.pa">soporte@panamahotels.edu.pa</a><br>
    Universidad Tecnológica de Panamá
  </div>
</div>

<?php include("../index/footer.php"); ?>

</body>
</html>
