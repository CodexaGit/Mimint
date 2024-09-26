<?php
class UsuarioModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function actualizarEstado($documento, $rol, $estado) {
        $consulta = "UPDATE usuario SET rol = ?, estado = ? WHERE documento = ?";
        $stmt = $this->conexion->prepare($consulta);
        $stmt->bind_param("ssi", $rol, $estado, $documento);
        return $stmt->execute();
    }

    public function obtenerUsuarios($estado = null, $filtro = 'asc', $busqueda = null) {
        $consulta = "SELECT * FROM usuario";
        $params = [];
        $types = '';

        if ($estado) {
            $consulta .= " WHERE estado = ?";
            $params[] = $estado;
            $types .= 's';
        }

        if ($busqueda) {
            $consulta .= $estado ? " AND" : " WHERE";
            $consulta .= " (documento LIKE ? OR nombre LIKE ? OR email LIKE ? OR apellido LIKE ? OR estado LIKE ? OR rol LIKE ?)";
            $busqueda = '%' . $busqueda . '%';
            for ($i = 0; $i < 6; $i++) {
                $params[] = $busqueda;
                $types .= 's';
            }
        }

        $consulta .= " ORDER BY nombre " . $filtro;
        $stmt = $this->conexion->prepare($consulta);

        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();
        if (!$resultado) {
            throw new Exception("No se pudo realizar la consulta.");
        }
        return $resultado; // Devolver el objeto de resultado de MySQLi
    }

    public function obtenerUsuarioPorDocumentoYContrasena($documento, $contrasena) {
        $consulta = "SELECT documento, email, contrasena, nombre, apellido, rol, estado FROM usuario WHERE documento = ? AND contrasena = ?";
        $stmt = $this->conexion->prepare($consulta);
        $stmt->bind_param("ss", $documento, $contrasena);
        if (!$stmt->execute()) {
            throw new Exception("No se pudo realizar la consulta.");
        }
        return $stmt->get_result();
    }

    public function obtenerUsuarioPorDocumento($documento) {
        $consulta = "SELECT nombre, documento FROM usuario WHERE documento = ?";
        $stmt = $this->conexion->prepare($consulta);
        $stmt->bind_param("s", $documento);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return null;
        }
    }

    public function registrarUsuario($documento, $email, $contrasena, $nombre, $apellido) {
        $consulta = "INSERT INTO usuario (documento, email, contrasena, nombre, apellido) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($consulta);
        $stmt->bind_param("sssss", $documento, $email, $contrasena, $nombre, $apellido);
        return $stmt->execute();
    }
}
?>