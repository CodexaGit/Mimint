<?php
class ReporteModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerDatosReporte($seccionReporte) {
        $datos = [];
        if ($seccionReporte == 'todoRegistros') {
            $datos['usuarios'] = $this->obtenerDatosUsuarios();
            $datos['reservas'] = $this->obtenerDatosReservas();
            $datos['salas'] = $this->obtenerDatosSalas();
            $datos['equipamiento'] = $this->obtenerDatosEquipamiento();
            $datos['administra'] = $this->obtenerDatosAdministra();
            $datos['caracteristicas'] = $this->obtenerDatosCaracteristicas();
            $datos['requiere'] = $this->obtenerDatosRequiere();
            $datos['verifica'] = $this->obtenerDatosVerifica();
        } elseif ($seccionReporte == 'equipamientoMasUsado') {
            $consulta = "SELECT equipamiento.nombre, SUM(requiere.cantidad) as uso 
            FROM equipamiento 
            INNER JOIN requiere ON equipamiento.nombre = requiere.equipamiento 
            GROUP BY equipamiento.nombre 
            ORDER BY uso DESC";
            $datos = $this->ejecutarConsulta($consulta);
        } elseif ($seccionReporte == 'salasMasUsadas') {
            $consulta = "SELECT sala.nombre, COUNT(reserva.id) as uso 
            FROM reserva 
            INNER JOIN sala ON sala.nombre = reserva.sala 
            GROUP BY sala.nombre 
            ORDER BY uso DESC";
            $datos = $this->ejecutarConsulta($consulta);
        }

        return $datos;
    }

    private function obtenerDatosUsuarios() {
        $consulta = "SELECT documento, email, nombre, rol FROM usuario";
        return $this->ejecutarConsulta($consulta);
    }

    private function obtenerDatosReservas() {
        $consulta = "SELECT r.dia, r.horainicio, r.horafin, r.cantidadpersonas AS personas, u.nombre AS docente, s.nombre AS sala 
                     FROM reserva r 
                     JOIN usuario u ON r.docente = u.documento 
                     JOIN sala s ON r.sala = s.nombre";
        return $this->ejecutarConsulta($consulta);
    }

    private function obtenerDatosSalas() {
        $consulta = "SELECT * FROM sala";
        return $this->ejecutarConsulta($consulta);
    }

    private function obtenerDatosEquipamiento() {
        $consulta = "SELECT * FROM equipamiento";
        return $this->ejecutarConsulta($consulta);
    }

    private function obtenerDatosAdministra() {
        $consulta = "SELECT * FROM administra";
        return $this->ejecutarConsulta($consulta);
    }

    private function obtenerDatosCaracteristicas() {
        $consulta = "SELECT * FROM caracteristicas";
        return $this->ejecutarConsulta($consulta);
    }

    private function obtenerDatosRequiere() {
        $consulta = "SELECT * FROM requiere";
        return $this->ejecutarConsulta($consulta);
    }

    private function obtenerDatosVerifica() {
        $consulta = "SELECT * FROM verifica";
        return $this->ejecutarConsulta($consulta);
    }

    private function ejecutarConsulta($consulta) {
        $stmt = $this->conexion->prepare($consulta);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $datos = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $datos;
    }
}
?>