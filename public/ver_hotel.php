<?php
// Se incluye la clase mod_db que maneja la conexión a la base de datos
require_once '../clases/mod_db.php';

// Se crea una instancia de la clase mod_db para establecer la conexión
$db = new mod_db();
// Se obtiene el objeto PDO de conexión
$conn = $db->getConexion();

// Se verifica si se ha recibido el parámetro 'id' vía GET, es decir, si se ha seleccionado un hotel
if (isset($_GET['id'])) {
    // Se convierte el valor recibido en entero para mayor seguridad y evitar inyección
    $hotel_id = intval($_GET['id']);

    // Preparar la consulta para obtener toda la información del hotel seleccionado
    $stmtHotel = $conn->prepare("SELECT * FROM hoteles WHERE id = :id");
    // Se enlaza el parámetro :id con el valor del hotel
    $stmtHotel->bindParam(":id", $hotel_id, PDO::PARAM_INT);
    // Ejecutar la consulta
    $stmtHotel->execute();
    // Obtener el resultado como un arreglo asociativo
    $hotel = $stmtHotel->fetch(PDO::FETCH_ASSOC);

    // Si el hotel existe
    if ($hotel) {
        // Mostrar el nombre del hotel con protección contra código malicioso (XSS)
        echo "<h2>" . htmlspecialchars($hotel['nombre']) . "</h2>";

        // Mostrar la ubicación (se asume que existe la columna 'ubicacion' en la tabla, aunque en la base de datos que mostraste está 'direccion')
        echo "<p><strong>Ubicación:</strong> " . htmlspecialchars($hotel['ubicacion']) . "</p>";

        // Mostrar la descripción del hotel
        echo "<p><strong>Descripción:</strong> " . htmlspecialchars($hotel['descripcion']) . "</p>";
        
        // Preparar consulta para obtener los tipos de habitaciones y su capacidad para ese hotel
        $stmtHabitaciones = $conn->prepare("SELECT tipo, capacidad FROM habitaciones WHERE hotel_id = :hotel_id");
        // Enlazar parámetro con el id del hotel
        $stmtHabitaciones->bindParam(":hotel_id", $hotel_id, PDO::PARAM_INT);
        // Ejecutar consulta
        $stmtHabitaciones->execute();
        // Obtener todas las habitaciones como arreglo asociativo
        $habitaciones = $stmtHabitaciones->fetchAll(PDO::FETCH_ASSOC);

        // Si existen habitaciones registradas para el hotel
        if ($habitaciones) {
            echo "<h3>Tipos de Habitaciones:</h3><ul>";
            // Recorrer cada habitación y mostrar tipo y capacidad
            foreach ($habitaciones as $hab) {
                echo "<li><strong>Tipo:</strong> " . htmlspecialchars($hab['tipo']) . 
                     " — <strong>Capacidad:</strong> " . intval($hab['capacidad']) . " personas</li>";
            }
            echo "</ul>";
        } else {
            // Si no hay habitaciones para el hotel
            echo "<p>No hay habitaciones registradas para este hotel.</p>";
        }
    } else {
        // Si no se encuentra el hotel por el id dado
        echo "<p>Hotel no encontrado.</p>";
    }

    // Enlace para volver a la lista de hoteles
    echo "<p><a href='ver_hotel.php'>← Volver a la lista de hoteles</a></p>";

} else {
    // Si no se ha seleccionado ningún hotel, mostrar la lista completa de hoteles disponibles

    // Ejecutar una consulta simple para obtener id y nombre de todos los hoteles
    $stmt = $conn->query("SELECT id, nombre FROM hoteles");

    // Título de la sección
    echo "<h2>Hoteles disponibles en Panamá</h2>";
    echo "<ul>";
    // Recorrer cada hotel y mostrar un enlace para ver detalles (pasando id por GET)
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li><a href='ver_hotel.php?id=" . intval($row['id']) . "'>" . 
             htmlspecialchars($row['nombre']) . "</a></li>";
    }
    echo "</ul>";
}
?>
