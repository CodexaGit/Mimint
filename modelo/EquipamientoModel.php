<?php
class EquipamientoModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function buscarEquipamiento($query) {
        $stmt = $this->conexion->prepare("SELECT nombre FROM equipamiento WHERE nombre LIKE ?");
        $likeQuery = "%$query%";
        $stmt->bind_param("s", $likeQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        $equipamientos = [];
        while ($row = $result->fetch_assoc()) {
            $equipamientos[] = trim($row['nombre']); // Eliminar espacios en blanco
        }
        return $equipamientos;
    }

    public function obtenerEquipamiento() {
        $stmt = $this->conexion->prepare("SELECT nombre FROM equipamiento");
        $stmt->execute();
        $result = $stmt->get_result();
        $equipamientos = [];
        while ($row = $result->fetch_assoc()) {
            $equipamientos[] = trim($row['nombre']); // Eliminar espacios en blanco
        }
        return $equipamientos;
    }
}