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
    $stmtHotel = $conn->prepare("
        SELECT h.*, u.nombre AS nombre_editor 
        FROM hoteles h 
        LEFT JOIN usuarios u ON h.creado_por = u.id 
        WHERE h.id = :id
    ");
    $stmtHotel->execute([':id' => $hotel_id]);
    $hotel = $stmtHotel->fetch(PDO::FETCH_ASSOC);

    if (!$hotel) {
        echo "<p>Hotel no encontrado.</p><p><a href='ver_hotel.php'>← Volver</a></p>";
        exit;
    }

    $nombreImagen = $hotel['imagen'] ?? 'default.jpg';
    $imagenHotel = "../img_hoteles/" . $nombreImagen;

    // Habitaciones con precio
    $stmtHabitaciones = $conn->prepare("
        SELECT h.*, c.precio_por_noche
        FROM habitaciones h
        LEFT JOIN costes_habitaciones c ON h.id = c.habitacion_id
        WHERE h.hotel_id = :hotel_id
    ");
    $stmtHabitaciones->execute([':hotel_id' => $hotel_id]);
    $habitaciones = $stmtHabitaciones->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= isset($hotel) ? htmlspecialchars($hotel['nombre']) : 'Hoteles en Panamá' ?></title>
    <link rel="stylesheet" href="../css/estilosHoteles.css">
    <link rel="stylesheet" href="../css/estilosDetalleHotel.css">
    <script>
        function mostrarFormularioReserva() {
            document.getElementById('formularioReserva').style.display = 'block';
            document.getElementById('btnMostrarReserva').style.display = 'none';
        }

        function calcularTotal() {
            const habitacionSelect = document.querySelector('select[name="habitacion_id"]');
            const entrada = new Date(document.querySelector('input[name="fecha_entrada"]').value);
            const salida = new Date(document.querySelector('input[name="fecha_salida"]').value);
            const precio = parseFloat(habitacionSelect.selectedOptions[0].dataset.precio);

            if (entrada && salida && !isNaN(precio) && salida > entrada) {
                const diff = Math.ceil((salida - entrada) / (1000 * 60 * 60 * 24));
                const total = diff * precio;
                document.getElementById('precioPorNoche').innerText = precio.toFixed(2);
                document.getElementById('totalEstimado').innerText = total.toFixed(2);
            } else {
                document.getElementById('precioPorNoche').innerText = "--";
                document.getElementById('totalEstimado').innerText = "--";
            }
        }
    </script>
</head>
<body>
    <div style="margin: 20px;">
        <a href="../index/index.php" class="btn-volver">← Volver a la lista de hoteles</a>
    </div>

<?php if (!isset($hotel)): ?>
    <h2>Hoteles disponibles en Panamá</h2>
<?php else: ?>
    <h1><?= htmlspecialchars($hotel['nombre']) ?></h1>
    <img src="<?= htmlspecialchars($imagenHotel) ?>" alt="Imagen del hotel" style="max-width:600px; width:100%; border-radius: 10px; margin-bottom: 20px;">
    <p><strong>Ubicación:</strong> <?= htmlspecialchars($hotel['direccion']) ?></p>
    <p><?= htmlspecialchars($hotel['descripcion']) ?></p>

    <?php if (!empty($hotel['nombre_editor'])): ?>
        <p><strong>Publicado por:</strong> <?= htmlspecialchars($hotel['nombre_editor']) ?></p>
    <?php endif; ?>

    <h3>Habitaciones disponibles:</h3>
    <?php if ($habitaciones): ?>
        <?php foreach ($habitaciones as $hab): ?>
            <div class="habitacion-card">
                <strong>Tipo:</strong> <?= htmlspecialchars($hab['tipo']) ?><br>
                <strong>Capacidad:</strong> <?= intval($hab['capacidad']) ?> personas<br>
                <strong>Precio por noche:</strong> $<?= number_format($hab['precio_por_noche'], 2) ?>
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
                <select name="habitacion_id" required onchange="calcularTotal()">
                    <option value="">-- Seleccione una habitación --</option>
                    <?php foreach ($habitaciones as $hab): ?>
                        <option value="<?= $hab['id'] ?>" data-precio="<?= $hab['precio_por_noche'] ?>">
                            <?= htmlspecialchars($hab['tipo']) ?> (Cap: <?= intval($hab['capacidad']) ?>) - $<?= number_format($hab['precio_por_noche'], 2) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="fecha_entrada">Fecha de Entrada:</label>
                <input type="date" name="fecha_entrada" required onchange="calcularTotal()">

                <label for="fecha_salida">Fecha de Salida:</label>
                <input type="date" name="fecha_salida" required onchange="calcularTotal()">

                <label for="personas">Personas:</label>
                <input type="number" name="personas" min="1" value="1" required>

                <p><strong>Precio por noche:</strong> $<span id="precioPorNoche">--</span></p>
                <p><strong>Total estimado:</strong> $<span id="totalEstimado">--</span></p>

                <button type="submit">Confirmar Reserva</button>
            </form>
        </div>
    <?php else: ?>
        <p><em>Debes iniciar sesión para poder reservar.</em></p>
    <?php endif; ?>
<?php endif; ?>

<?php include("../index/footer.php"); ?>
</body>
</html>
