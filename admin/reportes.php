<?php
require_once '../clases/mod_db.php';
$db = (new mod_db())->conn;

// Generar reporte de reservas por hotel
// Este reporte muestra el total de reservas por cada hotel
$sql = "SELECT h.nombre, COUNT(r.id) as total_reservas
        FROM hoteles h
        LEFT JOIN reservas r ON h.id = r.hotel_id
        GROUP BY h.id
        ORDER BY total_reservas DESC";
$stmt = $db->prepare($sql);
$stmt->execute();
$reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Reservas por Hotel</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h2>Reporte: Total de Reservas por Hotel</h2>
    <table>
        <thead>
            <tr>
                <th>Hotel</th>
                <th>Reservas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($reporte as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= $row['total_reservas'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
