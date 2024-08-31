<?php
class ReporteModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerDatosReporte($seccionReporte) {
        $consulta = "";
        if ($seccionReporte == 'usuario') {
            $consulta = "SELECT documento, email, nombre, apellido, rol, estado FROM usuario";
        } elseif ($seccionReporte == 'reserva') {
            $consulta = "SELECT r.id, r.dia, r.horainicio, r.horafin, r.cantidadpersonas, r.descripcion, u.nombre AS docente_nombre, s.nombre AS sala_nombre 
                         FROM reserva r 
                         JOIN usuario u ON r.docente = u.documento 
                         JOIN sala s ON r.sala = s.nombre";
        } elseif ($seccionReporte == 'sala') {
            $consulta = "SELECT nombre, capacidad, localidad FROM sala";
        }

        $stmt = $this->conexion->prepare($consulta);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $datos = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $datos;
    }
}
?>