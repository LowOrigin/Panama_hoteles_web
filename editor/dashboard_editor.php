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
  <style>
    body {
      background: #0f2027;
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
    }

    .editor-header {
      text-align: center;
      padding: 30px 0 10px;
    }

    .editor-header h1 {
      font-size: 36px;
      margin-bottom: 10px;
    }

    .editor-header p {
      font-size: 18px;
      color: #ccc;
    }

    .card-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
      padding: 40px 20px;
      max-width: 1100px;
      margin: 0 auto;
    }

    .editor-card {
      background-color: #1f2b37;
      border: 2px solid #333;
      border-radius: 10px;
      padding: 30px 20px;
      width: 220px;
      height: 160px;
      text-align: center;
      transition: transform 0.2s ease;
      cursor: pointer;
      box-shadow: 0 0 12px rgba(0,0,0,0.4);
    }

    .editor-card:hover {
      transform: translateY(-5px);
      background-color: #263445;
    }

    .editor-card span {
      font-size: 50px;
      display: block;
      margin-bottom: 10px;
    }

    .editor-card h3 {
      font-size: 18px;
      color: #fff;
    }

    a.card-link {
      text-decoration: none;
      color: inherit;
    }
  </style>
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
        <span>📋</span>
        <h3>Ver Historial de Hoteles</h3>
      </div>
    </a>

    <a href="../public/logout.php" class="card-link">
      <div class="editor-card">
        <span>🚪</span>
        <h3>Cerrar Sesión</h3>
      </div>
    </a>
  </div>

</body>
</html>
