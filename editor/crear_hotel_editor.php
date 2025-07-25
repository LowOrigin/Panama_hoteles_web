<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'editor') {
    header("Location: ../index/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proponer nuevo hotel</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h2>Proponer nuevo hotel</h2>
    <form action="../control/procesar_crear_hotel.php" method="POST" enctype="multipart/form-data">
        <label>Nombre del hotel:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion" required></textarea><br><br>

        <label>Dirección:</label><br>
        <input type="text" name="direccion" required><br><br>

        <label>Categoría:</label><br>
        <select name="categoria_id" required>
            <option value="1">1 estrella</option>
            <option value="2">2 estrellas</option>
            <option value="3">3 estrellas</option>
            <option value="4">4 estrellas</option>
            <option value="5">5 estrellas</option>
        </select><br><br>

        <label>Imagen del hotel:</label><br>
        <input type="file" name="imagen" accept="image/*" required><br><br>

        <input type="submit" value="Enviar solicitud">
    </form>
</body>
</html>
