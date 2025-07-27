<?php
session_start();
require_once '../clases/mod_db.php';
require_once '../clases/SanitizarEntrada.php'; // Asegúrate de tener esta clase en esa ruta

// Verificar rol editor
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'editor') {
    header("Location: ../index.php");
    exit();
}

$db = new mod_db();
$conn = $db->getConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y sanitizar datos del formulario
    $nombre = SanitizarEntrada::capitalizarTituloEspañol($_POST['nombre'] ?? '');
    $direccion = SanitizarEntrada::limpiarCadena($_POST['direccion'] ?? '');
    $provincia_id = SanitizarEntrada::limpiarCadena($_POST['provincia_id'] ?? '');
    $descripcion = SanitizarEntrada::limpiarCadena($_POST['descripcion'] ?? '');
    $categoria_id = SanitizarEntrada::limpiarCadena($_POST['categoria_id'] ?? '');
    $instalaciones = $_POST['instalaciones'] ?? [];
    $tipos = $_POST['tipo_habitacion'] ?? [];
    $capacidades = $_POST['capacidad'] ?? [];
    $precios = $_POST['precio'] ?? [];
    $temporadas = $_POST['temporada'] ?? [];

    // Validar provincia
    if (!$provincia_id || !is_numeric($provincia_id)) {
        $_SESSION['error'] = "Debe seleccionar una provincia válida.";
        header("Location: formulario_hotel.php");
        exit();
    }

    // Obtener ID del editor que está creando el hotel
    $creado_por = $_SESSION['usuario_id'] ?? null;

    // Procesar imagen
    $nombreImagen = null;
    $directorioImagenes = '../img_hoteles/';

    if (!empty($_FILES['imagen']['name'])) {
        if (!file_exists($directorioImagenes)) {
            mkdir($directorioImagenes, 0777, true);
        }

        $archivoTmp = $_FILES['imagen']['tmp_name'];
        $nombreOriginal = basename($_FILES['imagen']['name']);
        $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
        $extensionesValidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($extension, $extensionesValidas)) {
            $nombreImagen = uniqid() . '.' . $extension;
            $rutaDestino = $directorioImagenes . $nombreImagen;

            if (!move_uploaded_file($archivoTmp, $rutaDestino)) {
                $_SESSION['error'] = "Error al subir la imagen al servidor.";
                header("Location: formulario_hotel.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Formato de imagen no válido. Solo se permiten: jpg, jpeg, png, gif, webp.";
            header("Location: formulario_hotel.php");
            exit();
        }
    }

    try {
        // Insertar hotel con provincia
        $stmt = $conn->prepare("INSERT INTO hoteles (nombre, descripcion, direccion, categoria_id, imagen, creado_por, provincia_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $direccion, $categoria_id, $nombreImagen, $creado_por, $provincia_id]);
        $hotel_id = $conn->lastInsertId();

        // Insertar instalaciones del hotel
        if (!empty($instalaciones)) {
            $stmtInst = $conn->prepare("INSERT INTO hotel_instalacion (hotel_id, instalacion_id) VALUES (?, ?)");
            foreach ($instalaciones as $instalacion_id) {
                $stmtInst->execute([$hotel_id, intval($instalacion_id)]);
            }
        }

        // Insertar habitaciones y sus costes
        if (!empty($tipos)) {
            $stmtHab = $conn->prepare("INSERT INTO habitaciones (hotel_id, tipo, capacidad) VALUES (?, ?, ?)");
            $stmtCosto = $conn->prepare("INSERT INTO costes_habitaciones (habitacion_id, precio_por_noche, temporada) VALUES (?, ?, ?)");

            foreach ($tipos as $index => $tipo) {
                $tipoSanitizado = SanitizarEntrada::limpiarCadena($tipo);
                $capacidad = intval($capacidades[$index] ?? 1);
                $stmtHab->execute([$hotel_id, $tipoSanitizado, $capacidad]);
                $habitacion_id = $conn->lastInsertId();

                $precio = floatval($precios[$index] ?? 0);
                $temporada = SanitizarEntrada::limpiarCadena($temporadas[$index] ?? 'alta');

                $stmtCosto->execute([$habitacion_id, $precio, $temporada]);
            }
        }

        // Mostrar alerta de éxito
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Hotel creado</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Hotel creado correctamente',
                    confirmButtonText: 'Volver'
                }).then(() => {
                    window.location.href = 'formulario_hotel.php';
                });
            });
        </script>
        </body>
        </html>
        <?php
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = "Error al registrar hotel: " . $e->getMessage();
        header("Location: formulario_hotel.php");
        exit();
    }
} else {
    header("Location: formulario_hotel.php");
    exit();
}
