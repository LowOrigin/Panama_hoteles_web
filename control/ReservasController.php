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
        $conn = $this->db->getConexion();

        // Obtener hotel_id
        $stmtHotel = $conn->prepare("SELECT hotel_id FROM habitaciones WHERE id = :habitacion_id");
        $stmtHotel->execute([':habitacion_id' => $habitacion_id]);
        $hotel_id = $stmtHotel->fetchColumn();

        if (!$hotel_id) {
            throw new Exception("Habitación no encontrada o no asociada a un hotel válido.");
        }

        // Obtener precio por noche
        $stmtPrecio = $conn->prepare("SELECT precio_por_noche FROM costes_habitaciones WHERE habitacion_id = :habitacion_id");
        $stmtPrecio->execute([':habitacion_id' => $habitacion_id]);
        $precio = $stmtPrecio->fetchColumn();

        if ($precio === false) {
            throw new Exception("No se encontró precio asignado para la habitación.");
        }

        // Calcular total por cantidad de noches
        $entrada = new DateTime($fecha_entrada);
        $salida = new DateTime($fecha_salida);
        $dias = $entrada->diff($salida)->days;

        if ($dias <= 0) {
            throw new Exception("El rango de fechas no es válido.");
        }

        $total = $dias * $precio;

        // Insertar reserva con total
        return $this->db->insertSeguro('reservas', [
            'usuario_id'     => $usuario_id,
            'hotel_id'       => $hotel_id,
            'habitacion_id'  => $habitacion_id,
            'fecha_entrada'  => $fecha_entrada,
            'fecha_salida'   => $fecha_salida,
            'personas'       => $personas,
            'total'          => $total
        ]);
    }
}
