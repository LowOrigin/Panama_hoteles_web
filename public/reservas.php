<?php
session_start();
require_once '../clases/mod_db.php';
require_once '../control/ReservasController.php';

$db = new mod_db();
$reservaCtrl = new ControlReserva($db);

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    echo "<p>Por favor inicia sesión para ver tus reservas.</p>";
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Cancelar reserva si se solicita
if (isset($_GET['cancelar'])) {
    $idReserva = $_GET['cancelar'];
    $db->getConexion()->prepare("DELETE FROM reservas WHERE id = :id AND usuario_id = :usuario_id")->execute([
        ':id' => $idReserva,
        ':usuario_id' => $usuario_id
    ]);
    header("Location: reservas.php");
    exit();
}

// Consultar reservas del usuario
$sql = "SELECT r.id, h.nombre AS hotel, hab.tipo AS habitacion, r.fecha_entrada, r.fecha_salida, r.personas
        FROM reservas r
        JOIN hoteles h ON r.hotel_id = h.id
        JOIN habitaciones hab ON r.habitacion_id = hab.id
        WHERE r.usuario_id = :usuario_id";

$stmt = $db->getConexion()->prepare($sql);
$stmt->execute([':usuario_id' => $usuario_id]);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Reservas</title>
    <link rel="stylesheet" href="../css/estilosGenerales.css">
    <style>
        .reserva-contenedor {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .reserva-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .reserva-card h3 {
            margin: 0 0 10px;
            color: #2c3e50;
        }

        .reserva-card p {
            margin: 4px 0;
            color: #555;
        }

        .btn-cancelar {
            display: inline-block;
            margin-top: 10px;
            background-color: #c0392b;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.2s ease;
        }

        .btn-cancelar:hover {
            background-color: #e74c3c;
        }

        .titulo-reserva {
            text-align: center;
            font-size: 36px;
            margin-top: 40px;
            color: #2c3e50;
        }
    </style>
</head>
<body>

    <div class="reserva-contenedor">
        <h1 class="titulo-reserva">Mis Reservas</h1>

        <?php if (empty($reservas)): ?>
            <p style="text-align:center; color: #777;">No tienes reservas registradas.</p>
        <?php else: ?>
            <?php foreach ($reservas as $res): ?>
                <div class="reserva-card">
                    <h3><?= htmlspecialchars($res['hotel']) ?></h3>
                    <p><strong>Habitación:</strong> <?= htmlspecialchars($res['habitacion']) ?></p>
                    <p><strong>Entrada:</strong> <?= $res['fecha_entrada'] ?></p>
                    <p><strong>Salida:</strong> <?= $res['fecha_salida'] ?></p>
                    <p><strong>Personas:</strong> <?= $res['personas'] ?></p>
                    <a class="btn-cancelar" href="reservas.php?cancelar=<?= $res['id'] ?>" onclick="return confirm('¿Cancelar esta reserva?')">Cancelar</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>
</html>
