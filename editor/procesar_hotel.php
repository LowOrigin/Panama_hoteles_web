<?php
session_start();
require_once '../clases/mod_db.php';

// Verificar rol editor
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'editor') {
    header("Location: ../index.php");
    exit();
}

$db = new mod_db();
$conn = $db->getConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria_id = $_POST['categoria_id'] ?? null;
    $instalaciones = $_POST['instalaciones'] ?? [];
    $tipos = $_POST['tipo_habitacion'] ?? [];
    $capacidades = $_POST['capacidad'] ?? [];

    // Procesar imagen
    $nombreImagen = null;
    $directorioImagenes = '../img_hoteles/';

    if (!empty($_FILES['imagen']['name'])) {
        // Crear el directorio si no existe
        if (!file_exists($directorioImagenes)) {
            mkdir($directorioImagenes, 0777, true);
        }

        $archivoTmp = $_FILES['imagen']['tmp_name'];
        $nombreOriginal = basename($_FILES['imagen']['name']);
        $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
        $extensionesValidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        // Verifica que sea una imagen válida
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
        // Insertar hotel
        $stmt = $conn->prepare("INSERT INTO hoteles (nombre, descripcion, direccion, categoria_id, imagen) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $direccion, $categoria_id, $nombreImagen]);
        $hotel_id = $conn->lastInsertId();

        // Insertar instalaciones
        if (!empty($instalaciones)) {
            $stmtInst = $conn->prepare("INSERT INTO hotel_instalacion (hotel_id, instalacion_id) VALUES (?, ?)");
            foreach ($instalaciones as $instalacion_id) {
                $stmtInst->execute([$hotel_id, $instalacion_id]);
            }
        }

        // Insertar habitaciones
        if (!empty($tipos)) {
            $stmtHab = $conn->prepare("INSERT INTO habitaciones (hotel_id, tipo, capacidad) VALUES (?, ?, ?)");
            foreach ($tipos as $index => $tipo) {
                $capacidad = $capacidades[$index] ?? 1;
                $stmtHab->execute([$hotel_id, $tipo, $capacidad]);
            }
        }

        $_SESSION['mensaje'] = "Hotel registrado correctamente.";
        header("Location: ../editor/formulario_hotel.php");
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
