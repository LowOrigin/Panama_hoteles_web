<?php
session_start();
require_once '../clases/mod_db.php';
require_once '../control/ReservasController.php';

$db = new mod_db();
$conn = $db->getConexion();

$control = new ControlReserva();

$errores = [];
$reservaExitosa = false;

$usuario_id = $_SESSION['usuario_id'] ?? null;

// Procesar formulario de reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $habitacion_id = $_POST['habitacion_id'] ?? null;
    $fecha_entrada = $_POST['fecha_entrada'] ?? null;
    $fecha_salida = $_POST['fecha_salida'] ?? null;
    $personas = $_POST['personas'] ?? 1;
    $hotel_id = intval($_GET['id']);

    if (!$usuario_id) {
        $errores[] = "Debes iniciar sesión para reservar.";
    }
    if (!$habitacion_id || !$fecha_entrada || !$fecha_salida) {
        $errores[] = "Todos los campos son obligatorios.";
    }

    if (empty($errores)) {
        $datos = [
            'habitacion_id' => $habitacion_id,
            'fecha_entrada' => $fecha_entrada,
            'fecha_salida' => $fecha_salida,
            'personas' => $personas,
        ];

        $errores = $control->validarDatos($datos);

        if (empty($errores)) {
            if ($control->excedeCapacidad($habitacion_id, $personas)) {
                $errores[] = "La cantidad de personas excede la capacidad de la habitación.";
            } elseif ($control->habitacionOcupada($habitacion_id, $fecha_entrada, $fecha_salida)) {
                $errores[] = "La habitación ya está reservada en esas fechas.";
            } else {
                $reservaExitosa = $control->hacerReserva($usuario_id, $habitacion_id, $fecha_entrada, $fecha_salida, $personas);
                if ($reservaExitosa) {
                    header("Location: reservas.php");
                    exit();
                } else {
                    $errores[] = "Error al guardar la reserva.";
                }
            }
        }
    }
}

// Mostrar hotel y habitaciones si se pasó id por GET
if (isset($_GET['id'])) {
    $hotel_id = intval($_GET['id']);
    $stmtHotel = $conn->prepare("SELECT * FROM hoteles WHERE id = :id");
    $stmtHotel->execute([':id' => $hotel_id]);
    $hotel = $stmtHotel->fetch(PDO::FETCH_ASSOC);

    if (!$hotel) {
        echo "<p>Hotel no encontrado.</p>";
        echo "<p><a href='ver_hotel.php'>← Volver a la lista de hoteles</a></p>";
        exit;
    }

    // Obtener habitaciones del hotel
    $stmtHabitaciones = $conn->prepare("SELECT * FROM habitaciones WHERE hotel_id = :hotel_id");
    $stmtHabitaciones->execute([':hotel_id' => $hotel_id]);
    $habitaciones = $stmtHabitaciones->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title><?= isset($hotel) ? htmlspecialchars($hotel['nombre']) : 'Hoteles' ?></title>
    <link rel="stylesheet" href="../css/estilosGenerales.css" />
    <style>
        .habitacion-card {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .reserva-form {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 6px;
            background-color: #f9f9f9;
            max-width: 400px;
        }
        .reserva-form label {
            display: block;
            margin-top: 10px;
        }
    </style>
    <script>
        function mostrarFormularioReserva() {
            document.getElementById('formularioReserva').style.display = 'block';
            document.getElementById('btnMostrarReserva').style.display = 'none';
        }
    </script>
</head>
<body>
    <?php if (!isset($hotel)): ?>
        <h2>Hoteles disponibles en Panamá</h2>
        <ul>
        <?php
            $stmt = $conn->query("SELECT id, nombre FROM hoteles");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<li><a href='ver_hotel.php?id=" . intval($row['id']) . "'>" .
                     htmlspecialchars($row['nombre']) . "</a></li>";
            }
        ?>
        </ul>
    <?php else: ?>
        <h1><?= htmlspecialchars($hotel['nombre']) ?></h1>
        <p><strong>Ubicación:</strong> <?= htmlspecialchars($hotel['direccion']) ?></p>
        <p><?= htmlspecialchars($hotel['descripcion']) ?></p>

        <h3>Habitaciones disponibles:</h3>
        <?php if ($habitaciones): ?>
            <?php foreach ($habitaciones as $hab): ?>
                <div class="habitacion-card">
                    <p>
                        <strong>Tipo:</strong> <?= htmlspecialchars($hab['tipo']) ?><br>
                        <strong>Capacidad:</strong> <?= intval($hab['capacidad']) ?> personas
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay habitaciones registradas para este hotel.</p>
        <?php endif; ?>

        <?php if ($usuario_id): ?>
            <button id="btnMostrarReserva" onclick="mostrarFormularioReserva()">Reservar</button>

            <div id="formularioReserva" class="reserva-form" style="display:none;">
                <h3>Formulario de Reserva</h3>

                <?php if (!empty($errores)): ?>
                    <ul style="color:red;">
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <form method="POST" action="ver_hotel.php?id=<?= $hotel_id ?>">
                    <label for="habitacion_id">Habitación:</label>
                    <select name="habitacion_id" required>
                        <option value="">-- Seleccione una habitación --</option>
                        <?php foreach ($habitaciones as $hab): ?>
                            <option value="<?= $hab['id'] ?>">
                                <?= htmlspecialchars($hab['tipo']) ?> (Capacidad: <?= intval($hab['capacidad']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="fecha_entrada">Fecha de Entrada:</label>
                    <input type="date" name="fecha_entrada" required />

                    <label for="fecha_salida">Fecha de Salida:</label>
                    <input type="date" name="fecha_salida" required />

                    <label for="personas">Personas:</label>
                    <input type="number" name="personas" min="1" value="1" required />

                    <button type="submit">Confirmar Reserva</button>
                </form>
            </div>
        <?php else: ?>
            <p><em>Debes iniciar sesión para poder reservar.</em></p>
        <?php endif; ?>

        <p><a href="ver_hotel.php">← Volver a la lista de hoteles</a></p>
    <?php endif; ?>
</body>
</html>
