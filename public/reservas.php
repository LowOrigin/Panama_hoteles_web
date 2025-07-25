<?php
session_start();
require_once '../clases/mod_db.php';
require_once '../control/ReservasController.php';

$db = new mod_db();
$reservaCtrl = new ControlReserva($db);

$usuario = $_SESSION['usuario'] ?? null;

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
        .navbar {
          background-color: #2c3e50;
          color: white;
          display: flex;
          justify-content: space-between;
          align-items: center;
          padding: 12px 24px;
          margin-bottom: 32px;
        }
        .navbar .nav-left a,
        .navbar .nav-right a {
          color: white;
          text-decoration: none;
          margin: 0 12px;
          font-weight: bold;
        }
        .navbar .nav-left a:hover,
        .navbar .nav-right a:hover {
          text-decoration: underline;
        }
        .navbar .nav-right .btn {
          padding: 6px 12px;
          border-radius: 6px;
          border: none;
          text-decoration: none;
          cursor: pointer;
          font-weight: bold;
        }
        .btn-login {
          background-color: #2980b9;
          color: white;
        }
        .btn-register {
          background-color: #34495e;
          color: white;
        }
        .user-menu {
          position: relative;
          display: inline-block;
        }
        .user-button {
          background-color: #27ae60;
          color: white;
          padding: 8px 12px;
          border: none;
          border-radius: 6px;
          cursor: pointer;
        }
        .dropdown {
           display: none;
           position: absolute;
           right: 0;
           background-color: #111;
           min-width: 140px;
           box-shadow: 0 8px 16px rgba(0,0,0,0.4);
           z-index: 1;
           border-radius: 6px;
           overflow: hidden;
        }
        .dropdown a {
           color: white;
           padding: 12px 16px;
           display: block;
           text-decoration: none;
           font-weight: bold;
        }
        .dropdown a:hover {
          background-color: #333;
        }
        .user-menu:hover .dropdown {
          display: block;
        }
        @media (max-width: 700px) {
          .reserva-contenedor { padding: 0 3px; }
          .navbar { flex-direction: column; gap: 9px; }
        }
    </style>
</head>
<body>

    <div class="navbar">
      <div class="nav-left">
        <a href="../index/index.php">🏠 Inicio</a>
        <a href="../index/nosotros.php">📄 Nosotros</a>
        <a href="reservas.php" style="color: #4FC3F7; text-decoration:underline;">📅 Reservas</a>
      </div>
      <div class="nav-right">
        <?php if (!$usuario): ?>
          <a href="../formularios/login_form.php" class="btn btn-login">Iniciar Sesión</a>
          <a href="../formularios/formulario_registro.php" class="btn btn-register">Registrarse</a>
        <?php else: ?>
          <div class="user-menu">
            <button class="user-button"><?= htmlspecialchars($usuario) ?> ⬇</button>
            <div class="dropdown">
              <a href="logout.php">Cerrar Sesión</a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>

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
                    <a href="reservas.php?cancelar=<?= $res['id'] ?>" class="btn-cancelar" onclick="return confirm('¿Cancelar esta reserva?')">Cancelar</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php include("../index/footer.php"); ?>
</body>
</html>
