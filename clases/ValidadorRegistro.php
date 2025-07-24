<?php
require_once("mod_db.php");

class ValidadorRegistro {
    public static function usuarioOCorreoExiste($usuario, $correo) {
        $db = new mod_db();
        $conexion = $db->getConexion();

        $sql = "SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario OR correo = :correo";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":correo", $correo);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }
}
?>