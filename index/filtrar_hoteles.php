<?php
require_once '../clases/mod_db.php';
$db = new mod_db();
$conn = $db->getConexion();

$categoria = $_GET['categoria'] ?? null;
$provincia = $_GET['provincia'] ?? null;

$sql = "SELECT id, nombre, direccion, imagen FROM hoteles WHERE aprobado = 1";
$params = [];

// Filtro por categoría si se proporciona
if ($categoria && is_numeric($categoria)) {
    $sql .= " AND categoria_id = ?";
    $params[] = $categoria;
}

// Filtro por provincia si se proporciona
if ($provincia && is_numeric($provincia)) {
    $sql .= " AND provincia_id = ?";
    $params[] = $provincia;
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$hoteles = $stmt->fetchAll(PDO::FETCH_ASSOC);
