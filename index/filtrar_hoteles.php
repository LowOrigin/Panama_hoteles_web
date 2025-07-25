<?php
require_once '../clases/mod_db.php';
$db = new mod_db();
$conn = $db->getConexion();

$categoria = $_GET['categoria'] ?? null;

if ($categoria && is_numeric($categoria)) {
    $stmt = $conn->prepare("SELECT id, nombre, direccion, imagen FROM hoteles WHERE aprobado = 1 AND categoria_id = ?");
    $stmt->execute([$categoria]);
} else {
    $stmt = $conn->query("SELECT id, nombre, direccion, imagen FROM hoteles WHERE aprobado = 1");
}

$hoteles = $stmt->fetchAll(PDO::FETCH_ASSOC);
