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
$usuarioExiste = false;
$correoExiste = false;
$claveInvalida = false;

$datosForm = [
    'nombre' => '',
    'apellido' => '',
    'usuario' => '',
    'correo' => '',
    'clave' => '',
    'sexo' => '',
    'rol' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    foreach ($datosForm as $key => $_) {
    $valor = SanitizarEntrada::limpiarCadena($_POST[$key] ?? '');

    // Aplicar capitalización estilizada para nombre y apellido
    if (in_array($key, ['nombre', 'apellido'])) {
        $valor = SanitizarEntrada::capitalizarTituloEspañol($valor);
    }

    $datosForm[$key] = $valor;
}



    $correoValido = filter_var($datosForm['correo'], FILTER_VALIDATE_EMAIL);
    $claveInvalida = strlen($datosForm['clave']) < 6;

    $duplicados = $db->usuarioOCorreoExiste($datosForm['usuario'], $datosForm['correo']);
    $usuarioExiste = $duplicados['usuario'];
    $correoExiste = $duplicados['correo'];

    if (!$correoValido || $claveInvalida || $usuarioExiste || $correoExiste) {
        $mensaje = "";
    } else {
        $hash = password_hash($datosForm['clave'], PASSWORD_DEFAULT);
        $datos = [
            'nombre' => $datosForm['nombre'],
            'apellido' => $datosForm['apellido'],
            'usuario' => $datosForm['usuario'],
            'correo' => $datosForm['correo'],
            'clave' => $hash,
            'sexo' => $datosForm['sexo'],
            'rol' => $datosForm['rol'],
            'activo' => true
        ];

        $ok = $db->insertSeguro("usuarios", $datos);
        if ($ok) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Usuario creado correctamente',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'crear_usuario.php';
                    });
                });
            </script>";
            exit;
        } else {
            $mensaje = "Error al crear usuario.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Usuario</title>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
  <link rel="stylesheet" href="../css/crearUsuario.css">
</head>
<body>

<a href="dashboard.php" class="back-button">
  ⬅ <span>Volver</span>
</a>

<div class="card-container">
  <h1>Crear Usuario (Admin/Editor)</h1>

  <?php if ($mensaje): ?>
    <p style="color:red"><?= htmlspecialchars($mensaje) ?></p>
  <?php endif; ?>
  <?php if ($usuarioExiste): ?><p style="color:red">El nombre de usuario ya existe.</p><?php endif; ?>
  <?php if ($correoExiste): ?><p style="color:red">El correo electrónico ya está registrado.</p><?php endif; ?>
  <?php if ($claveInvalida): ?><p style="color:red">La contraseña debe tener al menos 6 caracteres.</p><?php endif; ?>

  <form method="POST">
    <input type="text" name="nombre" placeholder="Nombre" value="<?= htmlspecialchars($datosForm['nombre']) ?>" required>
    <input type="text" name="apellido" placeholder="Apellido" value="<?= htmlspecialchars($datosForm['apellido']) ?>" required>
    <input type="text" name="usuario" placeholder="Usuario" value="<?= htmlspecialchars($datosForm['usuario']) ?>" required>
    <input type="email" name="correo" placeholder="Correo electrónico" value="<?= htmlspecialchars($datosForm['correo']) ?>" required>
    <input type="password" name="clave" placeholder="Contraseña" class="<?= $claveInvalida ? 'error-borde' : '' ?>" required>

    <select name="sexo" required>
      <option value="">Sexo</option>
      <option value="M" <?= $datosForm['sexo'] === 'M' ? 'selected' : '' ?>>Masculino</option>
      <option value="F" <?= $datosForm['sexo'] === 'F' ? 'selected' : '' ?>>Femenino</option>
    </select>

    <select name="rol" required>
      <option value="">Rol</option>
      <option value="admin" <?= $datosForm['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
      <option value="editor" <?= $datosForm['rol'] === 'editor' ? 'selected' : '' ?>>Editor</option>
    </select>

    <button type="submit">Crear Usuario</button>
  </form>
</div>
</body>
</html>
