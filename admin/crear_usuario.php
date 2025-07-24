<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../formularios/login_form.php");
    exit;
}

require_once("../clases/mod_db.php");
require_once("../clases/SanitizarEntrada.php");

$db = new mod_db();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = SanitizarEntrada::limpiarCadena($_POST['nombre'] ?? '');
    $apellido = SanitizarEntrada::limpiarCadena($_POST['apellido'] ?? '');
    $usuario  = SanitizarEntrada::limpiarCadena($_POST['usuario'] ?? '');
    $correo   = filter_var($_POST['correo'] ?? '', FILTER_VALIDATE_EMAIL);
    $clave    = SanitizarEntrada::limpiarCadena($_POST['clave'] ?? '');
    $sexo     = SanitizarEntrada::limpiarCadena($_POST['sexo'] ?? '');
    $rol      = SanitizarEntrada::limpiarCadena($_POST['rol'] ?? 'cliente');

    if ($correo && strlen($usuario) >= 4 && strlen($clave) >= 6) {
        $hash = password_hash($clave, PASSWORD_DEFAULT);

        $datos = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'usuario' => $usuario,
            'correo' => $correo,
            'clave' => $hash,
            'sexo' => $sexo,
            'rol' => $rol,
            'activo' => true
        ];

        $ok = $db->insertSeguro("usuarios", $datos);
        $mensaje = $ok ? "Usuario creado correctamente" : "Error al crear usuario";
    } else {
        $mensaje = "Datos inválidos o incompletos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Usuario</title>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
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

  <!-- Botón de regreso arriba a la izquierda -->
  <a href="dashboard.php" class="back-button">
    ⬅ <span>Volver</span>
  </a>

  <div class="card-container">
    <h1>Crear Usuario (Admin/Editor)</h1>
    <?php if ($mensaje) echo '<p>' . htmlspecialchars($mensaje) . '</p>'; ?>

    <form method="POST">
      <input type="text" name="nombre" placeholder="Nombre" required>
      <input type="text" name="apellido" placeholder="Apellido" required>
      <input type="text" name="usuario" placeholder="Usuario" required>
      <input type="email" name="correo" placeholder="Correo electrónico" required>
      <input type="password" name="clave" placeholder="Contraseña" required>
      
      <select name="sexo" required>
        <option value="">Sexo</option>
        <option value="M">Masculino</option>
        <option value="F">Femenino</option>
      </select>

      <select name="rol" required>
        <option value="">Rol</option>
        <option value="admin">Administrador</option>
        <option value="editor">Editor</option>
      </select>

      <button type="submit">Crear Usuario</button>
    </form>
  </div>
</body>
</html>