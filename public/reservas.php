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
<html>
<head>
    <meta charset="UTF-8">
    <title>Mis Reservas</title>
    <!-- Enlace al archivo CSS general -->
    <link rel="stylesheet" href="../css/estilosGenerales.css">
</head>
  <!-- Navbar debajo del título -->
  <div class="navbar">
    <div class="nav-left">
      <a href="../index/index.php">🏠 Inicio</a>
      <a href="nosotros.php">📄 Nosotros</a>
      <a href="../public/ver_hotel.php">🏨 Ver Hoteles</a>
      <a href="../public/reservas.php">📅 Reservas</a>
    </div>
<body>
    <h1>Mis Reservas</h1>

    <?php if (empty($reservas)): ?>
        <p>No tienes reservas registradas.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($reservas as $res): ?>
                <li>
                    <strong>Hotel:</strong> <?= htmlspecialchars($res['hotel']) ?><br>
                    <strong>Habitación:</strong> <?= htmlspecialchars($res['habitacion']) ?><br>
                    <strong>Entrada:</strong> <?= $res['fecha_entrada'] ?><br>
                    <strong>Salida:</strong> <?= $res['fecha_salida'] ?><br>
                    <strong>Personas:</strong> <?= $res['personas'] ?><br>
                    <a href="reservas.php?cancelar=<?= $res['id'] ?>" onclick="return confirm('¿Cancelar esta reserva?')">Cancelar</a>
                    <hr>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <!-- Footer -->
<?php include("../index/footer.php"); ?>
</body>
</html>
