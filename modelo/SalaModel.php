<?php
class SalaModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerSalas() {
        $consulta = "SELECT * FROM sala";
        $resultado = $this->conexion->query($consulta);
        $salas = [];
        while ($row = $resultado->fetch_assoc()) {
            $salas[] = $row;
        }
        return $salas;
        
    }

    public function obtenerSala($nombre) {
        $consulta = "SELECT nombre, capacidad, localidad FROM sala WHERE nombre = ?";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return null;
        }
    }

    public function buscarSalas($nombre) {
        $consulta = "SELECT * FROM sala WHERE nombre LIKE ? OR localidad LIKE ? OR capacidad LIKE ?";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }
        $nombre = '%' . $nombre . '%';
        $stmt->bind_param("sss", $nombre, $nombre, $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $salas = [];
        while ($row = $resultado->fetch_assoc()) {
            $salas[] = $row;
        }
        return $salas;
    }

    public function obtenerCaracteristicas($nombre) {
        $consulta = "SELECT caracteristica FROM caracteristicas WHERE sala = ?";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $caracteristicas = [];
        while ($row = $resultado->fetch_assoc()) {
            $caracteristicas[] = $row['caracteristica'];
        }
        return $caracteristicas;
    }

    public function agregarSala($nombre, $capacidad, $localidad) {
        $consulta = "INSERT INTO sala (nombre, capacidad, localidad) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }
        $stmt->bind_param("sis", $nombre, $capacidad, $localidad);
        return $stmt->execute();
    }

    public function agregarCaracteristicas($nombre, $caracteristicas) {
        $consulta = "INSERT INTO caracteristicas (sala, caracteristica) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }
        foreach ($caracteristicas as $caracteristica) {
            $stmt->bind_param("ss", $nombre, $caracteristica);
            $stmt->execute();
        }
    }

    public function actualizarSala($nombre, $capacidad, $localidad) {
        $consulta = "UPDATE sala SET capacidad = ?, localidad = ? WHERE nombre = ?";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }
        $stmt->bind_param("iss", $capacidad, $localidad, $nombre);
        return $stmt->execute();
    }

    public function actualizarCaracteristicas($nombre, $caracteristicas) {
        $consulta = "DELETE FROM caracteristicas WHERE sala = ?";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }
        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        $this->agregarCaracteristicas($nombre, $caracteristicas);
    }

    public function eliminarSala($nombre) {
        // Primera consulta
        $consulta = "DELETE FROM sala WHERE nombre = ?";
        $stmt = $this->conexion->prepare($consulta);

        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }

        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $stmt->close();

        // Segunda consulta
        $consulta2 = "DELETE FROM caracteristicas WHERE sala = ?";
        $stmt2 = $this->conexion->prepare($consulta2);

        if (!$stmt2) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }

        $stmt2->bind_param("s", $nombre);
        $stmt2->execute();
        $stmt2->close();

        // Confirmar la transacción
        $this->conexion->commit();

        return true;
    }
}
?>