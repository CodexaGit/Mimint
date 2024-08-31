<?php
class CalendarioModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerReuniones() {
        // Implementar la lógica para obtener las reuniones desde la base de datos
        $consulta = "SELECT * FROM reuniones";
        $resultado = $this->conexion->query($consulta);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}
?>