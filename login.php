// archivo: login.php
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
  <div class="card-container">
    <h1>Iniciar Sesión</h1>
    <form method="POST" action="procesar_login.php">
      <input type="text" name="usuario" placeholder="Usuario o correo" required>
      <input type="password" name="clave" placeholder="Contraseña" required>
      <button type="submit">Entrar</button>
    </form>
    <p>¿No tienes cuenta? <a href="public/formulario_registro.php">Regístrate</a></p>
    <?php if (isset($_GET['error'])) echo "<p class='error'>" . htmlspecialchars($_GET['error']) . "</p>"; ?>
  </div>
</body>
</html>