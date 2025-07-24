<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Cliente</title>
  <link rel="stylesheet" href="../css/login.css">
  <link rel="stylesheet" href="../css/estilosGenerales.css">
</head>
<body>
  <div class="card-container">
    <h1>Registro de Cliente</h1>
    <form id="formRegistro">
      <input type="text" name="nombre" placeholder="Nombre" required>
      <input type="text" name="apellido" placeholder="Apellido" required>
      <input type="text" name="usuario" placeholder="Usuario" required>
      <input type="email" name="correo" placeholder="Correo electrónico" required>
      <input type="password" name="password" placeholder="Contraseña" required>
      <select name="sexo" required>
        <option value="">Selecciona sexo</option>
        <option value="M">Masculino</option>
        <option value="F">Femenino</option>
      </select>
      <button type="submit">Registrarse</button>
    </form>
    <div id="mensaje"></div>
    <p>¿Ya tienes cuenta? <a href="login_form.php">Inicia sesión</a></p>
  </div>

  <script>
    const form = document.getElementById('formRegistro');
    const mensaje = document.getElementById('mensaje');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(form);
      const resp = await fetch('../clases/registrar.php', {
        method: 'POST',
        body: formData
      });
      const data = await resp.json();
      mensaje.textContent = data.message;
      mensaje.style.color = data.success ? 'green' : 'red';
      if (data.success) form.reset();
    });
  </script>
</body>
</html>
