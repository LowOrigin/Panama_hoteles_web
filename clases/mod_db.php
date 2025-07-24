<?php
class mod_db {
    private $conexion;

    public function __construct() {
        $host = "localhost";
        $db = "sistema_hoteles";
        $user = "root";
        $pass = "";

        $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
        try {
            $this->conexion = new PDO($dsn, $user, $pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function getConexion() {
        return $this->conexion;
    }

    public function insertSeguro($tabla, $datos) {
        $columnas = implode(", ", array_keys($datos));
        $marcadores = ":" . implode(", :", array_keys($datos));

        $sql = "INSERT INTO $tabla ($columnas) VALUES ($marcadores)";
        $stmt = $this->conexion->prepare($sql);

        foreach ($datos as $clave => $valor) {
            $stmt->bindValue(":$clave", $valor);
        }

        return $stmt->execute();
    }

    public function log($usuario) {
        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        return $stmt->fetchObject();
    }
}
?>