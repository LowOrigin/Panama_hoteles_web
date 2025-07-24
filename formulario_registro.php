<!-- formulario_registro.php -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Registro de Cliente</title>
  <link rel="stylesheet" href="../css/estilos.css" />
</head>
<body>
  <div class="card-container">
    <h1>Registro de Cliente</h1>

    <form id="formRegistro">
      <input type="text" name="nombre" placeholder="Nombre" required />
      <input type="text" name="apellido" placeholder="Apellido" required />
      <input type="text" name="usuario" placeholder="Usuario" required />
      <input type="email" name="correo" placeholder="Correo" required />
      <input type="password" name="clave" placeholder="Contraseña" required />
      <select name="sexo" required>
        <option value="">Selecciona sexo</option>
        <option value="M">Masculino</option>
        <option value="F">Femenino</option>
      </select>
      <button type="submit">Registrarse</button>
    </form>
    <div id="mensaje"></div>
  </div>

  <script>
    const form = document.getElementById('formRegistro');
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(form);
      const resp = await fetch('../clases/registrar.php', {
        method: 'POST',
        body: formData
      });
      const data = await resp.json();
      document.getElementById('mensaje').textContent = data.message;
      if (data.success) form.reset();
    });
  </script>
</body>
</html>
