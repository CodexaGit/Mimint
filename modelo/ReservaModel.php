<?php
class ReservaModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerReservas() {
        $sql = "SELECT * FROM reserva";
        $result = $this->conexion->query($sql);
        if ($result === false) {
            throw new Exception("Error al obtener las reservas: " . $this->conexion->error);
        }
        
        $reservasArray = [];
        while ($reserva = $result->fetch_assoc()) {
            $reservasArray[] = $reserva;
        }
        
        return $reservasArray;
    }

    public function crearReserva($dia, $horainicio, $horafin, $cantidadpersonas, $descripcion, $docente, $sala, $equipamiento) {
        $sql = "INSERT INTO reserva (dia, horainicio, horafin, cantidadpersonas, descripcion, docente, sala) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
        }
        $stmt->bind_param("sssisis", $dia, $horainicio, $horafin, $cantidadpersonas, $descripcion, $docente, $sala);
        if (!$stmt->execute()) {
            throw new Exception("Error al crear la reserva: " . $stmt->error);
        }
        $reserva_id = $stmt->insert_id;

        // Agregar el equipamiento requerido
        foreach ($equipamiento as $equipo) {
            $this->agregarEquipamiento($reserva_id, $equipo['nombre'], $equipo['cantidad']);
        }

        return $reserva_id;
    }

    public function agregarEquipamiento($reserva_id, $nombre, $cantidad) {
        $sql = "INSERT INTO requiere (reserva, equipamiento, cantidad) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
        }
        $stmt->bind_param("iss", $reserva_id, $nombre, $cantidad);
        if (!$stmt->execute()) {
            throw new Exception("Error al agregar el equipamiento: " . $stmt->error);
        }
        return true;
    }

    public function obtenerPeticionesPendientes($busqueda = null) {
        $query = "
            SELECT reserva.*, CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_completo
            FROM reserva
            JOIN usuario ON reserva.docente = usuario.documento
            WHERE reserva.estado = 'pendiente'
        ";
        if ($busqueda) {
            $busqueda = $this->conexion->real_escape_string($busqueda);
            $query .= " AND (usuario.nombre LIKE '%$busqueda%' OR usuario.apellido LIKE '%$busqueda%' OR reserva.descripcion LIKE '%$busqueda%')";
        }
        $result = $this->conexion->query($query);

        $resultado = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $resultado[] = $row;
            }
        }
        return $resultado;
    }

    public function actualizarEstadoReserva($id, $estado) {
        $query = "UPDATE reserva SET estado = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('si', $estado, $id);
        return $stmt->execute();
    }

    public function eliminarReserva($id) {
        // Eliminar equipamiento asociado a la reserva
        $sqlEquipamiento = "DELETE FROM requiere WHERE reserva = ?";
        $stmtEquipamiento = $this->conexion->prepare($sqlEquipamiento);
        $stmtEquipamiento->bind_param('i', $id);
        if (!$stmtEquipamiento->execute()) {
            throw new Exception("Error al eliminar el equipamiento asociado: " . $stmtEquipamiento->error);
        }

        // Eliminar la reserva
        $sql = "DELETE FROM reserva WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar la reserva: " . $stmt->error);
        }
    }
    
    public function buscarReservas($query) {
        $sql = "SELECT * FROM reserva WHERE descripcion LIKE ? OR docente LIKE ? OR sala LIKE ?";
        $stmt = $this->conexion->prepare($sql);
        $likeQuery = "%$query%";
        $stmt->bind_param('sss', $likeQuery, $likeQuery, $likeQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        $reservas = [];
        while ($row = $result->fetch_assoc()) {
            $reservas[] = $row;
        }
        return $reservas;
    }
    public function modificarReserva($id, $dia, $horainicio, $horafin, $cantidadpersonas, $descripcion, $docente, $sala) {
        $sql = "UPDATE reserva SET dia = ?, horainicio = ?, horafin = ?, cantidadpersonas = ?, descripcion = ?, docente = ?, sala = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('sssisssi', $dia, $horainicio, $horafin, $cantidadpersonas, $descripcion, $docente, $sala, $id);
        $stmt->execute();
    }

    public function obtenerReserva($id) {
        $sql = 'SELECT * FROM reserva WHERE reserva.id = ?';
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            throw new Exception('Reserva no encontrada');
        }
    }
}
?>