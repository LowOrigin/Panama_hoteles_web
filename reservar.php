<?php
// Se incluye el archivo que contiene la lógica de control de reservas
require_once '../control/ReservasController.php';

// Se instancia la clase de control de reservas
$control = new ControlReserva();

// Se inicializa un arreglo para guardar los posibles errores
$errores = [];

// Se inicia la sesión para poder acceder al usuario que intenta hacer la reserva
session_start();

// Verifica si el usuario ha iniciado sesión. Si no lo ha hecho, se detiene la ejecución y se muestra un mensaje
if (!isset($_SESSION['usuario_id'])) {
    die("Debe iniciar sesión para hacer una reserva.");
}

// Verifica si el formulario fue enviado mediante el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Se obtienen los datos enviados desde el formulario
    $habitacion_id = $_POST['habitacion_id'];
    $fecha_entrada = $_POST['fecha_entrada'];
    $fecha_salida = $_POST['fecha_salida'];
    $personas = $_POST['personas'];

    // Se agrupan los datos en un array asociativo usando la función compact
    $datos = compact('habitacion_id', 'fecha_entrada', 'fecha_salida', 'personas');

    // Se realiza la validación general de los datos (campos vacíos, formato de fechas, etc.)
    $errores = $control->validarDatos($datos);

    // Verifica si el número de personas excede la capacidad permitida por la habitación
    if ($control->excedeCapacidad($habitacion_id, $personas)) {
        $errores[] = "La cantidad de personas excede la capacidad de la habitación.";
    }

    // Verifica si la habitación ya está reservada en el rango de fechas proporcionado
    if ($control->habitacionOcupada($habitacion_id, $fecha_entrada, $fecha_salida)) {
        $errores[] = "La habitación ya está reservada en esas fechas.";
    }

    // Si no hay errores, se procede a guardar la reserva
    if (empty($errores)) {
        // Llama al método para realizar la reserva, pasándole el ID del usuario y los datos de la reserva
        $reserva = $control->hacerReserva(
            $_SESSION['usuario_id'],
            $habitacion_id,
            $fecha_entrada,
            $fecha_salida,
            $personas
        );

        // Se notifica si la reserva fue exitosa o si ocurrió un error
        echo $reserva ? "Reserva realizada con éxito." : "Error al guardar la reserva.";
    } else {
        // Si hay errores, se muestran en pantalla con estilo rojo
        foreach ($errores as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
