<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="../css/login.css">
  <style>
    .back-button {
      position: absolute;
      top: 20px;
      left: 20px;
      text-decoration: none;
      font-size: 18px;
      color: #fff;
      background-color: rgba(0, 0, 0, 0.4);
      padding: 8px 12px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      transition: background-color 0.3s ease;
    }

    .back-button:hover {
      background-color: rgba(0, 0, 0, 0.6);
    }

    .back-button span {
      margin-left: 8px;
    }

    body {
      position: relative;
    }
  </style>
</head>
<body>
  <!-- Botón de regreso -->
  <a href="../index/index.php" class="back-button">
    ⬅ <span>Volver al inicio</span>
  </a>

  <div class="card-container">
    <h1>Login</h1>
    <form action="../public/procesar_login.php" method="POST">
      <input type="text" name="usuario" placeholder="Usuario" required />
      <input type="password" name="contrasena" placeholder="Contraseña" required />
      <input type="hidden" name="tolog" value="true" />
      <button type="submit">Ingresar</button>
    </form>
    <p>¿No tienes cuenta? <a href="formulario_registro.php">Regístrate</a></p>
    <?php if (isset($_GET['error'])) echo '<p style="color:red">' . htmlspecialchars($_GET['error']) . '</p>'; ?>
  </div>
</body>
</html>
