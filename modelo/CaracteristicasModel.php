<?php
class CaracteristicasModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerCaracteristicas() {
        $consulta = "SELECT * FROM CARACTERISTICAS";
        $resultado = $this->conexion->query($consulta);
 
        $Caracteristicas = [];
        while ($row = $resultado->fetch_assoc()) {
            $Caracteristicas[] = $row;
        }
        return $Caracteristicas;
        
    }

    public function obtenerCaracteristicasPorSala($id) {
        $consulta = "SELECT * FROM CARACTERISTICAS WHERE sala = ?";
        $stmt = $this->conexion->prepare($consulta);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $Caracteristicas = [];
        while ($row = $resultado->fetch_assoc()) {
            $Caracteristicas[] = $row;
        }
        return $Caracteristicas;
    }

    public function agregarCaracteristicas($sala, $caracteristica) {
        $consulta = "INSERT INTO CARACTERISTICAS VALUES (?, ?)";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }
        $stmt->bind_param("ss", $sala, $caracteristica);
        return $stmt->execute();
    }

    public function buscarCaracteristicas($busqueda = ""){
        $sql = "SELECT * FROM caracteristicas";
        if ($busqueda) {
            $sql .= " WHERE caracteristica LIKE ? OR sala LIKE ?";
        }
        $stmt = $this->conexion->prepare($sql);
        if ($busqueda) {
            $busqueda = "%$busqueda%";
            $stmt->bind_param("ss", $busqueda, $busqueda);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function actualizarCaracteristicas($sala, $caracteristicaNueva, $caracteristicaVieja) {
        $consulta = "UPDATE CARACTERISTICAS SET caracteristica = ? WHERE sala = ? AND caracteristica = ?";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }
        $stmt->bind_param('sss', $caracteristicaNueva, $sala, $caracteristicaVieja);
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar la característica: " . $stmt->error);
        }
        return true;
    }

    public function eliminarCaracteristicas($sala, $caracteristica) {
        $consulta = "DELETE FROM CARACTERISTICAS WHERE sala = ? AND caracteristica = ?";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }
        $stmt->bind_param("ss", $sala, $caracteristica);
        if (!$stmt->execute()) {
            throw new Exception('Error en la ejecución de la consulta: ' . $stmt->error);
        }
        return true;
    }
}
?>