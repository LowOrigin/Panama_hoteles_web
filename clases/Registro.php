<?php
require_once("mod_db.php");
require_once("SanitizarEntrada.php");
require_once("ValidadorRegistro.php");

class Registro {
    private $db;
    private $nombre, $apellido, $usuario, $correo, $clave, $sexo;

    public function __construct($nombre, $apellido, $usuario, $correo, $clave, $sexo) {
        $this->db = new mod_db();
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->usuario = $usuario;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->sexo = $sexo;
    }

    public function registrarUsuario() {
        if (strlen($this->usuario) < 4) {
            return ["success" => false, "message" => "El usuario debe tener al menos 4 caracteres."];
        }

        if (ValidadorRegistro::usuarioOCorreoExiste($this->usuario, $this->correo)) {
            return ["success" => false, "message" => "Usuario o correo ya registrado."];
        }

        $hash = password_hash($this->clave, PASSWORD_DEFAULT);

        $datos = [
            "nombre" => $this->nombre,
            "apellido" => $this->apellido,
            "usuario" => $this->usuario,
            "correo" => $this->correo,
            "clave" => $hash,
            "sexo" => $this->sexo,
            "rol" => "cliente"
        ];

        $ok = $this->db->insertSeguro("usuarios", $datos);

        return $ok ? ["success" => true, "message" => "Registro exitoso"]
                   : ["success" => false, "message" => "Error en el registro"];
    }
}
?>
