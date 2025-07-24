<?php
session_start();

class IndexController
{
    public function handleRequest()
    {
        // Mensaje de bienvenida
        if (isset($_SESSION['usuario'])) {
            echo "<h2>Bienvenido, " . htmlspecialchars($_SESSION['usuario']) . "</h2>";
        } else {
            echo "<h2>Bienvenido a nuestro sitio de hoteles</h2>";
        }

        // Menú principal con enlaces relativos saliendo de /index hacia /public
        echo "<ul>";
        echo "<li><a href='../public/ver_hotel.php'>Ver Hoteles</a></li>";
        echo "<li><a href='../public/reservar.php'>Reservar</a></li>";
        echo "<li><a href='../formularios/login_form.php'>Iniciar Sesión</a></li>";
        echo "<li><a href='../public/registro.php'>Registrarse</a></li>";
        echo "</ul>";
    }
}

// Ejecutar controlador
$index = new IndexController();
$index->handleRequest();