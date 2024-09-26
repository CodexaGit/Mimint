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
        return $result;
    }

    public function crearReserva($dia, $horainicio, $horafin, $cantidadpersonas, $descripcion, $docente, $sala) {
        $sql = "INSERT INTO reserva (dia, horainicio, horafin, cantidadpersonas, descripcion, docente, sala) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssisss", $dia, $horainicio, $horafin, $cantidadpersonas, $descripcion, $docente, $sala);
        if ($stmt->execute()) {
            return $stmt->insert_id; // Devuelve el ID de la reserva creada
        } else {
            throw new Exception("Error al crear la reserva: " . $stmt->error);
        }
    }

    public function agregarEquipamiento($reserva_id, $nombre, $cantidad) {
        $sql = "INSERT INTO requiere (reserva, equipamiento, cantidad) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("isi", $reserva_id, $nombre, $cantidad);
        if (!$stmt->execute()) {
            throw new Exception("Error al agregar el equipamiento: " . $stmt->error);
        }
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
}
?>