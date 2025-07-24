<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../public/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administrador</title>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
</head>
<body>
  <div class="card-container">
    <h1>👑 Panel del Administrador</h1>
    <p>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?></p>

    <ul style="margin-top: 30px;">
      <li><a href="gestionar_usuarios.php">👥 Gestionar Usuarios</a></li>
      <li><a href="crear_usuario.php">➕ Crear Usuario (Admin/Editor)</a></li>
      <li><a href="solicitudes_hoteles.php">🏨 Solicitudes de Hoteles</a></li>
      <li><a href="ver_reservas.php">📖 Ver Reservas</a></li>
      <li><a href="../public/logout.php">🔒 Cerrar Sesión</a></li>
    </ul>
  </div>
</body>
</html>
