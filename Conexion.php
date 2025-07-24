<?php
class Conexion {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $this->pdo = new PDO(
            'mysql:host=localhost;dbname=noticiasdb;charset=utf8',
            'root', // usuario
            ''    // contraseña
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getConexion() {
        if (!self::$instance) self::$instance = new Conexion();
        return self::$instance->pdo;
    }
}
?>
