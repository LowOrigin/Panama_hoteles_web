<?php
session_start();
require_once '../clases/mod_db.php';
require_once '../control/ReservasController.php';

$db = new mod_db();
$reservaCtrl = new ControlReserva($db);

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Mostrar página estilizada si no hay sesión iniciada
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Mis Reservas</title>
        <link rel="stylesheet" href="../css/estilosGenerales.css">
    </head>
    <body>
        <div class="card-container">
            <h1>Acceso restringido</h1>
            <p>Por favor inicia sesión para ver tus reservas.</p>
            <a class="btn-volver" href="../formularios/login_form.php">Iniciar sesión</a><br><br>
            <a class="btn-volver" href="../index/index.php">← Volver al inicio</a>
        </div>
        <?php include("../index/footer.php"); ?>
    </body>
    </html>
    <?php
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

// Consultar reservas del usuario con TOTAL
$sql = "SELECT r.id, h.nombre AS hotel, hab.tipo AS habitacion, r.fecha_entrada, r.fecha_salida, r.personas, r.total
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
</head>
<body>

<a href="../index/index.php" class="btn-volver">← Volver al inicio</a>

<div class="reserva-contenedor">
    <h1 class="titulo-reserva">Mis Reservas</h1>

    <?php if (empty($reservas)): ?>
        <p style="text-align:center; color: #bbb;">No tienes reservas registradas.</p>
    <?php else: ?>
        <div class="reservas-grid">
        <?php foreach ($reservas as $res): ?>
            <div class="reserva-card">
                <h3><?= htmlspecialchars($res['hotel']) ?></h3>
                <p><strong>Habitación:</strong> <?= htmlspecialchars($res['habitacion']) ?></p>
                <p><strong>Entrada:</strong> <?= $res['fecha_entrada'] ?></p>
                <p><strong>Salida:</strong> <?= $res['fecha_salida'] ?></p>
                <p><strong>Personas:</strong> <?= $res['personas'] ?></p>
                <p><strong>Total a pagar:</strong>
                    <?php if (!is_null($res['total'])): ?>
                        $<?= number_format((float)$res['total'], 2) ?>
                    <?php else: ?>
                        <span style="color:#ff6b6b;">No disponible</span>
                    <?php endif; ?>
                </p>
                <a href="reservas.php?cancelar=<?= $res['id'] ?>" class="btn-cancelar" onclick="return confirm('¿Cancelar esta reserva?')">Cancelar</a>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include("../index/footer.php"); ?>
</body>
</html>
