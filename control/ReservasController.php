<?php
require_once __DIR__ . '/../clases/mod_db.php';

class ControlReserva {
    private $db;

    public function __construct() {
        $this->db = new mod_db();
    }

    public function habitacionOcupada($habitacion_id, $fecha_entrada, $fecha_salida) {
        $sql = "SELECT COUNT(*) FROM reservas 
                WHERE habitacion_id = :habitacion_id 
                AND (
                    (:fecha_entrada BETWEEN fecha_entrada AND fecha_salida) OR
                    (:fecha_salida BETWEEN fecha_entrada AND fecha_salida) OR
                    (fecha_entrada BETWEEN :fecha_entrada AND :fecha_salida)
                )";

        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->execute([
            ':habitacion_id' => $habitacion_id,
            ':fecha_entrada' => $fecha_entrada,
            ':fecha_salida' => $fecha_salida
        ]);

        return $stmt->fetchColumn() > 0;
    }

    public function validarDatos($datos) {
        $errores = [];

        if (empty($datos['habitacion_id']) || empty($datos['fecha_entrada']) || empty($datos['fecha_salida']) || empty($datos['personas'])) {
            $errores[] = "Todos los campos son obligatorios.";
        }

        if (strtotime($datos['fecha_entrada']) >= strtotime($datos['fecha_salida'])) {
            $errores[] = "La fecha de entrada debe ser anterior a la de salida.";
        }

        if ($datos['personas'] <= 0) {
            $errores[] = "La cantidad de personas debe ser mayor que 0.";
        }

        return $errores;
    }

    public function excedeCapacidad($habitacion_id, $personas) {
        $sql = "SELECT capacidad FROM habitaciones WHERE id = :id";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->execute([':id' => $habitacion_id]);
        $capacidad = $stmt->fetchColumn();

        return $personas > $capacidad;
    }

public function hacerReserva($usuario_id, $habitacion_id, $fecha_entrada, $fecha_salida, $personas) {
    // Obtener el hotel_id correspondiente a la habitación
    $sql = "SELECT hotel_id FROM habitaciones WHERE id = :habitacion_id";
    $stmt = $this->db->getConexion()->prepare($sql);
    $stmt->execute([':habitacion_id' => $habitacion_id]);
    $hotel_id = $stmt->fetchColumn();

    if (!$hotel_id) {
        throw new Exception("Habitación no encontrada o no asociada a un hotel válido.");
    }

    // Insertar la reserva
    return $this->db->insertSeguro('reservas', [
        'usuario_id'     => $usuario_id,
        'hotel_id'       => $hotel_id, // AHORA SE INCLUYE
        'habitacion_id'  => $habitacion_id,
        'fecha_entrada'  => $fecha_entrada,
        'fecha_salida'   => $fecha_salida,
        'personas'       => $personas
    ]);
}

}
