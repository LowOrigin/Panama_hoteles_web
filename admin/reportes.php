<?php
require_once '../clases/mod_db.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$db = (new mod_db())->getConexion();


if (isset($_GET['exportar']) && $_GET['exportar'] === 'excel') {
    $sql = "SELECT h.nombre AS hotel, h.direccion, c.nombre AS categoria, 
                   u.usuario AS creador, COUNT(r.id) AS total_reservas,
                   ch.precio_por_noche, ch.temporada
            FROM hoteles h
            LEFT JOIN reservas r ON h.id = r.hotel_id
            LEFT JOIN categorias c ON h.categoria_id = c.id
            LEFT JOIN usuarios u ON h.creado_por = u.id
            LEFT JOIN habitaciones hab ON h.id = hab.hotel_id
            LEFT JOIN costes_habitaciones ch ON hab.id = ch.habitacion_id
            GROUP BY h.id";

    $stmt = $db->prepare($sql);
    $stmt->execute();
    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Encabezados
    $sheet->fromArray([
        'Hotel', 'Dirección', 'Categoría', 'Creador', 
        'Reservas', 'Precio por noche', 'Temporada'
    ], NULL, 'A1');

    // Datos
    $fila = 2;
    foreach ($datos as $dato) {
        $sheet->setCellValue("A$fila", $dato['hotel']);
        $sheet->setCellValue("B$fila", $dato['direccion']);
        $sheet->setCellValue("C$fila", $dato['categoria']);
        $sheet->setCellValue("D$fila", $dato['creador']);
        $sheet->setCellValue("E$fila", $dato['total_reservas']);
        $sheet->setCellValue("F$fila", $dato['precio_por_noche']);
        $sheet->setCellValue("G$fila", $dato['temporada']);
        $fila++;
    }

    // Filtros y autosize
    $sheet->setAutoFilter("A1:G1");
    foreach (range('A', 'G') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Descargar Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="reporte_hoteles.xlsx"');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Hoteles y Reservas</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h2>Reporte General de Hoteles</h2>
    <p><a href="reportes.php?exportar=excel">📥 Descargar como Excel</a></p>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Hotel</th>
                <th>Dirección</th>
                <th>Categoría</th>
                <th>Creador</th>
                <th>Reservas</th>
                <th>Precio por noche</th>
                <th>Temporada</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT h.nombre AS hotel, h.direccion, c.nombre AS categoria, 
                       u.usuario AS creador, COUNT(r.id) AS total_reservas,
                       ch.precio_por_noche, ch.temporada
                FROM hoteles h
                LEFT JOIN reservas r ON h.id = r.hotel_id
                LEFT JOIN categorias c ON h.categoria_id = c.id
                LEFT JOIN usuarios u ON h.creado_por = u.id
                LEFT JOIN habitaciones hab ON h.id = hab.hotel_id
                LEFT JOIN costes_habitaciones ch ON hab.id = ch.habitacion_id
                GROUP BY h.id";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($reporte as $row):
        ?>
            <tr>
                <td><?= htmlspecialchars($row['hotel']) ?></td>
                <td><?= htmlspecialchars($row['direccion']) ?></td>
                <td><?= htmlspecialchars($row['categoria']) ?></td>
                <td><?= htmlspecialchars($row['creador']) ?></td>
                <td><?= $row['total_reservas'] ?></td>
                <td><?= number_format($row['precio_por_noche'], 2) ?> USD</td>
                <td><?= ucfirst($row['temporada']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
