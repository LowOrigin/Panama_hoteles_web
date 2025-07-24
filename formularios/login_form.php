<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="../css/login.css">
</head>
<body>
  <div class="card-container">
    <h1>Login</h1>
    <form action="../public/procesar_login.php" method="POST">
      <input type="text" name="usuario" placeholder="Usuario" required />
      <input type="password" name="contrasena" placeholder="Contraseña" required />
      <input type="hidden" name="tolog" value="true" />
      <button type="submit">Ingresar</button>
    </form>
    <p>¿No tienes cuenta? <a href="formulario_registro.php">Regístrate</a></p>
   <?php if (isset($_GET['error'])) echo '<p class="error">' . htmlspecialchars($_GET['error']) . '</p>'; ?>
  </div>
</body>
</html>
