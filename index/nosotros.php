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
  <style>
    .nosotros-contenido {
      max-width: 720px;
      margin: 50px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 24px rgba(30,60,120,0.07);
      padding: 36px 40px 28px 40px;
      text-align: left;
      color: #222;
    }
    .nosotros-contenido h1 {
      text-align: center;
      font-size: 2.7rem;
      margin-bottom: 12px;
      color: #1a73e8;
    }
    .nosotros-contenido h2 {
      font-size: 1.5rem;
      margin-top: 34px;
      color: #1976d2;
    }
    .equipo-lista {
      margin: 0 0 12px 0;
      padding-left: 20px;
    }
    .equipo-lista li {
      margin-bottom: 7px;
      font-size: 1.1rem;
    }
    .vision, .mision {
      margin: 22px 0;
      padding: 18px;
      background: #f4f8fc;
      border-left: 4px solid #1a73e8;
      border-radius: 7px;
      color: #214;
    }
    .contacto {
      margin-top: 26px;
      font-size: 1.03rem;
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
    .btn-login { background-color: #2980b9; color: white; }
    .btn-register { background-color: #34495e; color: white; }
    .user-menu { position: relative; display: inline-block; }
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
    .dropdown a:hover { background-color: #333; }
    .user-menu:hover .dropdown { display: block; }
    @media (max-width: 820px) {
      .nosotros-contenido { padding: 20px 10px; }
    }
  </style>
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
    <!-- Puedes -->
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