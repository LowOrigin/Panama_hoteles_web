<?php
session_start();
require_once '../clases/mod_db.php';

// Verifica si es admin (ajusta según tu sistema)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo "Acceso denegado.";
    exit();
}

$db = new mod_db();
$conn = $db->getConexion();

// Si se solicitó cambiar el estado de aprobación
if (isset($_GET['cambiar']) && isset($_GET['estado'])) {
    $id = intval($_GET['cambiar']);
    $nuevoEstado = intval($_GET['estado']) === 1 ? 1 : 0;

    $stmt = $conn->prepare("UPDATE hoteles SET aprobado = :estado WHERE id = :id");
    $stmt->execute([':estado' => $nuevoEstado, ':id' => $id]);

    header("Location: solicitudes_hoteles.php");
    exit();
}

// Obtener todos los hoteles
$stmt = $conn->query("SELECT id, nombre, direccion, aprobado FROM hoteles ORDER BY id DESC");
$hoteles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitudes de Hoteles</title>
    <link rel="stylesheet" href="../css/estilosAdmin.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #2980b9;
            color: white;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .estado-aprobado {
            color: green;
            font-weight: bold;
        }

        .estado-rechazado {
            color: #c0392b;
            font-weight: bold;
        }

        .btn-aprobar, .btn-rechazar {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-aprobar {
            background-color: #27ae60;
        }

        .btn-rechazar {
            background-color: #e74c3c;
        }

        .btn-aprobar:hover {
            background-color: #219150;
        }

        .btn-rechazar:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

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
