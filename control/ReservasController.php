<?php
// Se incluye el archivo de conexión a base de datos usando una ruta relativa
require_once __DIR__ . '/../clases/mod_db.php';

// Clase encargada de controlar la lógica de validación y gestión de reservas
class ControlReserva {
    // Atributo privado para manejar la conexión a la base de datos
    private $db;

    // Constructor de la clase: se crea una instancia del manejador de base de datos
    public function __construct() {
        $this->db = new mod_db();  // Se inicializa la conexión usando mod_db
    }

    // Verifica si una habitación ya está ocupada en el rango de fechas dado
    public function habitacionOcupada($habitacion_id, $fecha_entrada, $fecha_salida) {
        // Consulta SQL para detectar conflictos en fechas para la misma habitación
        $sql = "SELECT COUNT(*) FROM reservas 
                WHERE habitacion_id = :habitacion_id 
                AND (
                    (:fecha_entrada BETWEEN fecha_entrada AND fecha_salida) OR
                    (:fecha_salida BETWEEN fecha_entrada AND fecha_salida) OR
                    (fecha_entrada BETWEEN :fecha_entrada AND :fecha_salida)
                )";

        // Se prepara la consulta y se ejecuta con los parámetros proporcionados
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->execute([
            ':habitacion_id' => $habitacion_id,
            ':fecha_entrada' => $fecha_entrada,
            ':fecha_salida' => $fecha_salida
        ]);

        // Devuelve true si hay al menos una reserva que entra en conflicto (habitacion ocupada)
        return $stmt->fetchColumn() > 0;
    }

    // Valida los datos enviados desde el formulario de reserva
    public function validarDatos($datos) {
        $errores = []; // Arreglo que almacenará los mensajes de error

        // Validar que todos los campos requeridos estén presentes y no vacíos
        if (empty($datos['habitacion_id']) || empty($datos['fecha_entrada']) || empty($datos['fecha_salida']) || empty($datos['personas'])) {
            $errores[] = "Todos los campos son obligatorios.";
        }

        // Verifica que la fecha de entrada sea anterior a la fecha de salida
        if (strtotime($datos['fecha_entrada']) >= strtotime($datos['fecha_salida'])) {
            $errores[] = "La fecha de entrada debe ser anterior a la de salida.";
        }

        // Verifica que el número de personas sea mayor a cero
        if ($datos['personas'] <= 0) {
            $errores[] = "La cantidad de personas debe ser mayor que 0.";
        }

        return $errores; // Devuelve el arreglo con errores encontrados (si hay)
    }

    // Comprueba si el número de personas excede la capacidad de la habitación
    public function excedeCapacidad($habitacion_id, $personas) {
        // Consulta la capacidad de la habitación desde la base de datos
        $sql = "SELECT capacidad FROM habitaciones WHERE id = :id";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->execute([':id' => $habitacion_id]);
        $capacidad = $stmt->fetchColumn(); // Se obtiene la capacidad

        // Retorna true si el número de personas supera la capacidad
        return $personas > $capacidad;
    }

    // Registra la reserva en la base de datos si todo está correcto
    public function hacerReserva($usuario_id, $habitacion_id, $fecha_entrada, $fecha_salida, $personas) {
        // Se usa el método insertSeguro del manejador de base de datos (mod_db)
        return $this->db->insertSeguro('reservas', [
            'usuario_id' => $usuario_id,
            'habitacion_id' => $habitacion_id,
            'fecha_entrada' => $fecha_entrada,
            'fecha_salida' => $fecha_salida,
            'personas' => $personas
        ]);
    }
}
?>
