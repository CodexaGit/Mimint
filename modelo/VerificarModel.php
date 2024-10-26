<?php
class VerificarModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function agregarVerificacion($accion, $id, $mensaje, $cedula) {
        if ($this->conexion->connect_errno) {
            throw new Exception('Error de conexión: ' . $this->conexion->connect_error);
        }

        // Iniciar una transacción
        $this->conexion->begin_transaction();

        try {
            // Insertar en la tabla verifica
            $consultaVerifica = "INSERT INTO verifica (administrador, reserva, accion, mensaje) VALUES (?, ?, ?, ?)";
            $stmtVerifica = $this->conexion->prepare($consultaVerifica);
            if (!$stmtVerifica) {
                throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
            }
            $stmtVerifica->bind_param("siss", $cedula, $id, $accion, $mensaje);
            $stmtVerifica->execute();

            // Determinar el estado basado en la acción
            $estado = ($accion === 'Aprobado') ? 'Aprobado' : 'Rechazado';
            // Añadir registro de depuración para verificar el valor de $estado
            error_log("Estado determinado: " . $estado);

            // Actualizar el estado de la reserva
            $consultaReserva = "UPDATE reserva SET estado = ? WHERE id = ?";
            $stmtReserva = $this->conexion->prepare($consultaReserva);
            if (!$stmtReserva) {
                throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
            }
            $stmtReserva->bind_param("si", $estado, $id);
            $stmtReserva->execute();

            // Confirmar la transacción
            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $this->conexion->rollback();
            throw $e;
        }
    }
}
?>