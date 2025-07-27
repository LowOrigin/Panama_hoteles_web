<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'editor') {
    header("Location: ../formularios/login_form.php");
    exit;
}

$nombre = htmlspecialchars($_SESSION['usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel del Editor</title>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
  <link rel="stylesheet" href="../css/dashboardEditor.css">

</head>
<body>

  <div class="editor-header">
    <h1>Panel del Editor</h1>
    <p>Bienvenido, <?= $nombre ?> (rol: editor)</p>
  </div>

  <div class="card-grid">
    <a href="formulario_hotel.php" class="card-link">
      <div class="editor-card">
        <span>➕</span>
        <h3>Registrar Hotel</h3>
      </div>
    </a>

    <a href="historial_hoteles.php" class="card-link">
      <div class="editor-card">
        <span style="font-size: 32px;">📋</span>
        <h3>Historial de mis Hoteles</h3>
       </div>
    </a>


    <a href="../public/logout.php" class="card-link">
      <div class="editor-card">
        <span>🚪</span>
        <h3>Cerrar Sesión</h3>
      </div>
    </a>
  </div>
<!-- Footer -->

</body>
</html>
