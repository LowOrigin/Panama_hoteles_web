<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
  <div class="card-container">
    <h1>Login</h1>
    <form action="../public/index.php" method="POST">
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
