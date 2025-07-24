<?php
require_once '../clases/mod_db.php';

$db = new mod_db();
$conn = $db->getConexion();

// Verifica si se ha seleccionado un hotel
if (isset($_GET['id'])) {
    $hotel_id = intval($_GET['id']);

    // Obtener información del hotel
    $stmtHotel = $conn->prepare("SELECT * FROM hoteles WHERE id = :id");
    $stmtHotel->bindParam(":id", $hotel_id, PDO::PARAM_INT);
    $stmtHotel->execute();
    $hotel = $stmtHotel->fetch(PDO::FETCH_ASSOC);

    if ($hotel) {
        echo "<h2>" . htmlspecialchars($hotel['nombre']) . "</h2>";
        echo "<p><strong>Ubicación:</strong> " . htmlspecialchars($hotel['ubicacion']) . "</p>";
        echo "<p><strong>Descripción:</strong> " . htmlspecialchars($hotel['descripcion']) . "</p>";
        
        // Obtener habitaciones del hotel
        $stmtHabitaciones = $conn->prepare("SELECT tipo, capacidad FROM habitaciones WHERE hotel_id = :hotel_id");
        $stmtHabitaciones->bindParam(":hotel_id", $hotel_id, PDO::PARAM_INT);
        $stmtHabitaciones->execute();
        $habitaciones = $stmtHabitaciones->fetchAll(PDO::FETCH_ASSOC);

        if ($habitaciones) {
            echo "<h3>Tipos de Habitaciones:</h3><ul>";
            foreach ($habitaciones as $hab) {
                echo "<li><strong>Tipo:</strong> " . htmlspecialchars($hab['tipo']) . 
                     " — <strong>Capacidad:</strong> " . intval($hab['capacidad']) . " personas</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No hay habitaciones registradas para este hotel.</p>";
        }
    } else {
        echo "<p>Hotel no encontrado.</p>";
    }

    echo "<p><a href='ver_hotel.php'>← Volver a la lista de hoteles</a></p>";

} else {
    // Mostrar lista de hoteles
    $stmt = $conn->query("SELECT id, nombre FROM hoteles");

    echo "<h2>Hoteles disponibles en Panamá</h2>";
    echo "<ul>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li><a href='ver_hotel.php?id=" . intval($row['id']) . "'>" . 
             htmlspecialchars($row['nombre']) . "</a></li>";
    }
    echo "</ul>";
}
?>
