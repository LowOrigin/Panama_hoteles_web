<?php
require_once 'mod_db.php';

class Categoria {
    private $db;
    public function __construct() {
        $this->db = (new mod_db())->conn;
    }

    public function listar() {
        $stmt = $this->db->prepare("SELECT * FROM categorias ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($nombre) {
        $stmt = $this->db->prepare("INSERT INTO categorias (nombre) VALUES (?)");
        return $stmt->execute([$nombre]);
    }

    public function obtener($id) {
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function editar($id, $nombre) {
        $stmt = $this->db->prepare("UPDATE categorias SET nombre = ? WHERE id = ?");
        return $stmt->execute([$nombre, $id]);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
