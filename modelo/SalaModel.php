<?php
class SalaModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerSalas() {
        $consulta = "SELECT nombre FROM sala";
        $resultado = $this->conexion->query($consulta);
        if (!$resultado) {
            throw new Exception("No se pudo realizar la consulta.");
        }
        return $resultado;
    }

    public function obtenerSala($nombre) {
        $consulta = "SELECT * FROM sala WHERE nombre = ?";
        $stmt = $this->conexion->prepare($consulta);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if (!$resultado) {
            throw new Exception("No se pudo realizar la consulta.");
        }
        return $resultado->fetch_assoc();
    }
}
?>