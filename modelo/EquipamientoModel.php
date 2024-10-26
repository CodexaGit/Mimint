<?php
class EquipamientoModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function buscarEquipamiento($query) {
        $sql = "SELECT * FROM equipamiento WHERE nombre LIKE ?";
        $stmt = $this->conexion->prepare($sql);
        $likeQuery = "%$query%";
        $stmt->bind_param('s', $likeQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        $equipamientos = [];
        while ($row = $result->fetch_assoc()) {
            $equipamientos[] = $row;
        }
        return $equipamientos;
    }

    public function buscarEquipamientoTodos($query) {
        $sql = "SELECT * FROM equipamiento WHERE nombre LIKE ? OR cantidad LIKE ?";
        $stmt = $this->conexion->prepare($sql);
        $likeQuery = "%$query%";
        $stmt->bind_param('ss', $likeQuery, $likeQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        $equipamientos = [];
        while ($row = $result->fetch_assoc()) {
            $equipamientos[] = $row;
        }
        return $equipamientos;
    }

    public function agregarEquipamiento($nombre, $cantidad) {
        $sql = "INSERT INTO equipamiento (nombre, cantidad) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('si', $nombre, $cantidad);
        try {
            $stmt->execute();
            return ['status' => 'success', 'message' => 'Equipamiento agregado exitosamente'];
        } catch (mysqli_sql_exception $e) {
            return ['status' => 'error', 'message' => 'Error al agregar el equipamiento: ' . $e->getMessage()];
        }
    }

    public function modificarEquipamiento($nombre, $cantidad) {
        $sql = "UPDATE equipamiento SET cantidad = ? WHERE nombre = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('is', $cantidad, $nombre);
        $stmt->execute();
        return ['status' => 'success', 'message' => 'Equipamiento modificado exitosamente'];
    }

    public function eliminarEquipamiento($nombre) {
        $sql = "DELETE FROM equipamiento WHERE nombre = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('s', $nombre);

        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Equipamiento eliminado correctamente.'];
        } else {
            return ['status' => 'error', 'message' => 'Error al eliminar el equipamiento.'];
        }
    }

    public function obtenerEquipamientoPorId($id) {
        $sql = "SELECT * FROM requiere WHERE requiere.reserva = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $equipamientos = [];
        while ($row = $result->fetch_assoc()) {
            $equipamientos[] = $row;
        }
        return $equipamientos;
    }

    public function obtenerEquipamientoPorNombre($nombre) {
        $sql = "SELECT * FROM equipamiento WHERE nombre = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenerEquipamiento() {
        $sql = "SELECT * FROM equipamiento";
        $result = $this->conexion->query($sql);
        $equipamientos = [];
        while ($row = $result->fetch_assoc()) {
            $equipamientos[] = $row;
        }
        return $equipamientos;
    }
}
?>