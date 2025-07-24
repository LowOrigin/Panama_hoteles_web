<?php
// Inicia la sesión PHP para poder usar variables de sesión (por ejemplo, usuario logueado)
session_start();

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
