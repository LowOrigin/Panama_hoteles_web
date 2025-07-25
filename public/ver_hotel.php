<?php
session_start();
require_once '../clases/mod_db.php';
require_once '../control/ReservasController.php';

$db = new mod_db();
$conn = $db->getConexion();
$control = new ControlReserva($db);

$errores = [];
$reservaExitosa = false;
$usuario_id = $_SESSION['usuario_id'] ?? null;

// Procesar reserva
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

// Mostrar hotel y habitaciones
if (isset($_GET['id'])) {
    $hotel_id = intval($_GET['id']);
    $stmtHotel = $conn->prepare("SELECT * FROM hoteles WHERE id = :id");
    $stmtHotel->execute([':id' => $hotel_id]);
    $hotel = $stmtHotel->fetch(PDO::FETCH_ASSOC);

    if (!$hotel) {
        echo "<p>Hotel no encontrado.</p><p><a href='ver_hotel.php'>← Volver</a></p>";
        exit;
    }

    $stmtHabitaciones = $conn->prepare("SELECT * FROM habitaciones WHERE hotel_id = :hotel_id");
    $stmtHabitaciones->execute([':hotel_id' => $hotel_id]);
    $habitaciones = $stmtHabitaciones->fetchAll(PDO::FETCH_ASSOC);

    // Ruta manual de imagen (nombre del archivo debe ser definido aquí según el ID del hotel)
    $imagenHotel = match($hotel_id) {
        1 => "../img/hotel1.jpg",
        2 => "../img/hotel2.jpg",
        3 => "../img/hotel3.jpg",
        default => "../img/default.jpg"
    };
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= isset($hotel) ? htmlspecialchars($hotel['nombre']) : 'Hoteles en Panamá' ?></title>
    <link rel="stylesheet" href="../css/estilosHoteles.css">
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
    <div class="hotel-grid">
        <?php
        $stmt = $conn->query("SELECT id, nombre, direccion FROM hoteles");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            // Definir imagen manualmente por ID
            $imagen = match($row['id']) {
                1 => "../img/paraiso_del_mar.jpg",
                2 => "../img/sierra verde.jpg",
                3 => "../img/ciudad_central.jpg",
                4 => "../img/colonial.jpeg",
                5 => "../img/cafe.jpg",
                7 => "../img/esencia.jpg",
                default => "../img/7palabras.jpg"
            };
        ?>
            <a class="hotel-card" href="ver_hotel.php?id=<?= intval($row['id']) ?>">
                <img src="<?= $imagen ?>" alt="Imagen del hotel" style="width:100%; height:200px; object-fit:cover; border-radius: 10px;">
                <h3><?= htmlspecialchars($row['nombre']) ?></h3>
                <p><?= htmlspecialchars($row['direccion']) ?></p>
            </a>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <h1><?= htmlspecialchars($hotel['nombre']) ?></h1>

    <!-- Imagen del hotel -->
    <img src="<?= $imagenHotel ?>" alt="Imagen del hotel" style="max-width:600px; width:100%; border-radius: 10px; margin-bottom: 20px;">

    <p><strong>Ubicación:</strong> <?= htmlspecialchars($hotel['direccion']) ?></p>
    <p><?= htmlspecialchars($hotel['descripcion']) ?></p>

    <h3>Habitaciones disponibles:</h3>
    <?php if ($habitaciones): ?>
        <?php foreach ($habitaciones as $hab): ?>
            <div class="habitacion-card">
                <strong>Tipo:</strong> <?= htmlspecialchars($hab['tipo']) ?><br>
                <strong>Capacidad:</strong> <?= intval($hab['capacidad']) ?> personas
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay habitaciones disponibles para este hotel.</p>
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
                <input type="date" name="fecha_entrada" required>

                <label for="fecha_salida">Fecha de Salida:</label>
                <input type="date" name="fecha_salida" required>

                <label for="personas">Personas:</label>
                <input type="number" name="personas" min="1" value="1" required>

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
