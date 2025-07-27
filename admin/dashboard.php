<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../formularios/login_form.php");
    exit;
}

$nombre = htmlspecialchars($_SESSION['usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración</title>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
  <link rel="stylesheet" href="../css/panelAdmin.css">
</head>
<body>

  <div class="admin-header">
    <h1>Panel de Administración</h1>
    <p>Bienvenido, <?= $nombre ?> (rol: admin)</p>
  </div>

  <div class="card-grid">
    <a href="gestionar_usuarios.php" class="card-link">
      <div class="admin-card">
        <span>🧑‍💼</span>
        <h3>Gestionar Usuarios</h3>
      </div>
    </a>

    <a href="crear_usuario.php" class="card-link">
      <div class="admin-card">
        <span>➕</span>
        <h3>Crear Usuario</h3>
      </div>
    </a>

    <a href="solicitudes_hoteles.php" class="card-link">
      <div class="admin-card">
        <span>🏨</span>
        <h3>Solicitudes de Hoteles</h3>
      </div>
    </a>

    <a href="../admin/reportes.php?exportar=excel" class="card-link">
      <div class="admin-card">
        <span>📊</span>
        <h3>Exportar Reporte Excel</h3>
      </div>
    </a>

    <a href="../public/logout.php" class="card-link">
      <div class="admin-card">
        <span>🚪</span>
        <h3>Cerrar Sesión</h3>
      </div>
    </a>
  </div>

</body>
</html>
