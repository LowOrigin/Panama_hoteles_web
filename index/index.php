<?php
// Inicia la sesión PHP para poder usar variables de sesión (por ejemplo, usuario logueado)
session_start();
$usuario = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio - Sistema de Hoteles</title>
  <link rel="stylesheet" href="../css/estilosGenerales.css">
</head>
<body>
  <div class="card-container">
    <h1>Sistema de Hoteles</h1>
    <h2>
      <?php if ($usuario): ?>
        Bienvenido, <?= htmlspecialchars($usuario); ?>
      <?php else: ?>
        Bienvenido a nuestro sitio de hoteles
      <?php endif; ?>
    </h2>

<<<<<<< HEAD
// Definición de la clase IndexController que manejará la lógica principal del index
class IndexController
{
    // Método que procesa la solicitud y genera la salida HTML correspondiente
    public function handleRequest()
    {
        // Si existe una variable de sesión 'usuario' significa que el usuario está logueado
        if (isset($_SESSION['usuario'])) {
            // Mostrar mensaje de bienvenida personalizado, usando htmlspecialchars para evitar inyección XSS
            echo "<h2>Bienvenido, " . htmlspecialchars($_SESSION['usuario']) . "</h2>";
        } else {
            // Si no está logueado, mostrar mensaje de bienvenida genérico
            echo "<h2>Bienvenido a nuestro sitio de hoteles</h2>";
        }

        // Mostrar un menú principal simple con enlaces
        echo "<ul>";
        // Enlace para ver la lista de hoteles (archivo ver_hotel.php en carpeta public)
        echo "<li><a href='../public/ver_hotel.php'>Ver Hoteles</a></li>";
        // Enlace para ir al formulario de reservar (archivo reservar.php en carpeta public)
        echo "<li><a href='../public/reservar.php'>Reservar</a></li>";
        // Enlace para ir al formulario de inicio de sesión (archivo login_form.php en carpeta formularios)
        echo "<li><a href='../formularios/login_form.php'>Iniciar Sesión</a></li>";
        // Enlace para ir a la página de registro de usuarios (registro.php en carpeta public)
        echo "<li><a href='../public/registro.php'>Registrarse</a></li>";
        echo "</ul>";
    }
}

// Se crea una instancia de la clase IndexController
$index = new IndexController();

// Se llama al método handleRequest para ejecutar la lógica y mostrar el contenido
$index->handleRequest();
=======
    <ul style="margin-top: 30px; list-style: none; padding: 0;">
      <li><a href="../public/ver_hotel.php">🏨 Ver Hoteles</a></li>
      <li><a href="../public/reservar.php">📅 Reservar</a></li>
      <li><a href="../formularios/login_form.php">🔐 Iniciar Sesión</a></li>
      <li><a href="../public/registro.php">📝 Registrarse</a></li>
    </ul>
  </div>

  <footer>
    <p>&copy; <?= date("Y") ?> Sistema de Hoteles | Todos los derechos reservados</p>
  </footer>
</body>
</html>
>>>>>>> 2a2dab7aa3641be1241f41b53817ba3515ed5afd
