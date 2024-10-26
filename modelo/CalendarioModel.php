<?php
class CalendarioModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function obtenerReservasPorFecha($fecha, $modoYo = false) {
        // Implementar la lógica para obtener las reservas desde la base de datos
        $consulta = "SELECT * FROM reserva WHERE dia = '$fecha' AND estado = 'Aprobado' ORDER BY horainicio";
        if ($modoYo) {
            // Filtrar por el usuario actual (ajusta según tu lógica de usuario)
            if (isset($_SESSION['documento'])) {
                $docente = $_SESSION['documento']; // Asegúrate de que la sesión esté iniciada y el documento del usuario esté disponible
                $consulta .= " AND docente = '$docente'";
            } else {
                throw new Exception("Usuario no autenticado");
            }
        }
        $resultado = $this->conexion->query($consulta);
        if (!$resultado) {
            throw new Exception("Error en la consulta: " . $this->conexion->error);
        }
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
    
    public function obtenerReservas($modoYo = false) {
        // Implementar la lógica para obtener todas las reservas aprobadas desde la base de datos
        $consulta = "SELECT * FROM reserva WHERE estado = 'Aprobado' ORDER BY horainicio";
        if ($modoYo) {
            // Filtrar por el usuario actual (ajusta según tu lógica de usuario)
            if (isset($_SESSION['documento'])) {
                $docente = $_SESSION['documento']; // Asegúrate de que la sesión esté iniciada y el documento del usuario esté disponible
                $consulta .= " AND docente = '$docente'";
            } else {
                throw new Exception("Usuario no autenticado");
            }
        }
        $resultado = $this->conexion->query($consulta);
        if (!$resultado) {
            throw new Exception("Error en la consulta: " . $this->conexion->error);
        }
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerReservaPorId($id) {
        // Implementar la lógica para obtener una reserva por su ID
        $consulta = "SELECT * FROM reserva WHERE id = ?";
        $stmt = $this->conexion->prepare($consulta);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if (!$resultado) {
            throw new Exception("Error en la consulta: " . $this->conexion->error);
        }
        return $resultado->fetch_assoc();
    }

    public function eliminarReserva($id) {
        // Implementar la lógica para eliminar una reserva por su ID
        $consulta = "DELETE FROM reserva WHERE id = ?";
        $stmt = $this->conexion->prepare($consulta);
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar la reserva: " . $stmt->error);
        }
        return true;
    }

    
}
?>