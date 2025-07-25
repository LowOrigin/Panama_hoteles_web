<?php
session_start();

// Verifica que sea un editor
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'editor') {
    header("Location: ../index/index.php");
    exit();
}

// Verifica que se haya enviado el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "../clases/mod_db.php"; // ajusta la ruta si tu conexión está en otro archivo

    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $direccion = $_POST['direccion'];
    $categoria_id = $_POST['categoria_id'];

    // Procesar imagen
    $nombreImagen = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['imagen']['tmp_name'];
        $nombreOriginal = basename($_FILES['imagen']['name']);
        $directorioDestino = "../public/imagenes/";
        $nombreImagen = uniqid() . "_" . $nombreOriginal;

        if (!move_uploaded_file($tmp, $directorioDestino . $nombreImagen)) {
            die("Error al guardar la imagen.");
        }
    }

    // Insertar en base de datos
    $sql = "INSERT INTO hoteles (nombre, descripcion, direccion, categoria_id, imagen, aprobado) 
            VALUES (?, ?, ?, ?, ?, 0)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssis", $nombre, $descripcion, $direccion, $categoria_id, $nombreImagen);

    if ($stmt->execute()) {
        echo "Hotel propuesto con éxito. Esperando aprobación del administrador.";
    } else {
        echo "Error al guardar: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "Acceso inválido.";
}
?>