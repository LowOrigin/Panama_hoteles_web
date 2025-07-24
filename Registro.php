<?php
require_once("conexion.php");

class Registro {
    private $db;
    private $nombre, $apellido, $usuario, $correo, $clave, $sexo;

    public function __construct($nombre, $apellido, $usuario, $correo, $clave, $sexo) {
        $this->db = new Conexion();
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->usuario = $usuario;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->sexo = $sexo;
    }

    public function registrarCliente() {
        if (strlen($this->usuario) < 4) {
            return ['success' => false, 'message' => 'Usuario muy corto.'];
        }

        $pdo = $this->db->getConexion();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = ? OR correo = ?");
        $stmt->execute([$this->usuario, $this->correo]);
        if ($stmt->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Usuario o correo ya existe.'];
        }

        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, usuario, correo, clave, sexo, rol) VALUES (?, ?, ?, ?, ?, ?, 'cliente')");
        $ok = $stmt->execute([$this->nombre, $this->apellido, $this->usuario, $this->correo, $hash, $this->sexo]);

        return $ok ? ['success' => true, 'message' => 'Cliente registrado.'] : ['success' => false, 'message' => 'Error al registrar.'];
    }
}
