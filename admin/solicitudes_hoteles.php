<?php
session_start();
require_once '../clases/mod_db.php';

// Verifica si es admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo "Acceso denegado.";
    exit();
}

$db = new mod_db();
$conn = $db->getConexion();

// Cambiar estado de aprobación
if (isset($_GET['cambiar']) && isset($_GET['estado'])) {
    $id = intval($_GET['cambiar']);
    $nuevoEstado = intval($_GET['estado']) === 1 ? 1 : 0;

    $stmt = $conn->prepare("UPDATE hoteles SET aprobado = :estado WHERE id = :id");
    $stmt->execute([':estado' => $nuevoEstado, ':id' => $id]);

    header("Location: solicitudes_hoteles.php");
    exit();
}

// Obtener hoteles
$stmt = $conn->query("SELECT id, nombre, direccion, aprobado FROM hoteles ORDER BY id DESC");
$hoteles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitudes de Hoteles</title>
    <link rel="stylesheet" href="../css/estilosAdmin.css">
    <link rel="stylesheet" href="../css/hotelAdminCss.css"> <!-- nuevo enlace -->
</head>
<body>
<a href="dashboard.php" class="back-button">← Volver</a>

<h1>Solicitudes de Publicación de Hoteles</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($hoteles as $hotel): ?>
            <tr>
                <td><?= $hotel['id'] ?></td>
                <td><?= htmlspecialchars($hotel['nombre']) ?></td>
                <td><?= htmlspecialchars($hotel['direccion']) ?></td>
                <td>
                    <?php if ($hotel['aprobado']): ?>
                        <span class="estado-aprobado">Aprobado</span>
                    <?php else: ?>
                        <span class="estado-rechazado">No aprobado</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($hotel['aprobado']): ?>
                        <a href="?cambiar=<?= $hotel['id'] ?>&estado=0" class="btn-rechazar">❌ Rechazar</a>
                    <?php else: ?>
                        <a href="?cambiar=<?= $hotel['id'] ?>&estado=1" class="btn-aprobar">✅ Aprobar</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
