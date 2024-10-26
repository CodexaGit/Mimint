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

    public function agregarEquipamiento($nombre, $cantidad) {
        $sql = "INSERT INTO equipamiento (nombre, cantidad) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('si', $nombre, $cantidad);
        $stmt->execute();
    }

    public function modificarEquipamiento($nombre, $cantidad) {
        $sql = "UPDATE equipamiento SET cantidad = ? WHERE nombre = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('is', $cantidad, $nombre);
        $stmt->execute();
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

    public function obtenerEquipamientoPorNombre($nombre) {
        $sql = "SELECT * FROM equipamiento WHERE nombre = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
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