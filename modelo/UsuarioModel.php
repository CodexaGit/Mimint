<?php
class UsuarioModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function actualizarEstado($documento, $rol, $estado) {
        if ($this->conexion->connect_errno) {
            throw new Exception('Error de conexión: ' . $this->conexion->connect_error);
        }

        $consulta = "UPDATE usuario SET rol = ?, estado = ? WHERE documento = ?";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }

        $stmt->bind_param("ssi", $rol, $estado, $documento);
        return $stmt->execute();
    }

    public function modificarUsuario($documento, $nombre, $apellido, $email, $rol, $estado) {
        if ($this->conexion->connect_errno) {
            throw new Exception('Error de conexión: ' . $this->conexion->connect_error);
        }

        $consulta = "UPDATE usuario SET nombre = ?, apellido = ?, email = ?, rol = ?, estado = ? WHERE documento = ?";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }

        $stmt->bind_param("ssssss", $nombre, $apellido, $email, $rol, $estado, $documento);
        return $stmt->execute();
    }

    public function eliminarUsuario($documento) {
        $query = "DELETE FROM usuario WHERE documento = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $documento);

        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Usuario eliminado correctamente.'];
        } else {
            return ['status' => 'error', 'message' => 'Error al eliminar el usuario.'];
        }
    }

    public function obtenerDocentesyAdmins() {
        if ($this->conexion->connect_errno) {
            throw new Exception('Error de conexión: ' . $this->conexion->connect_error);
        }

        $consulta = "SELECT documento, nombre, apellido, email, rol FROM usuario WHERE rol = 'Docente' OR rol = 'Admin' AND estado = 'Aprobado'";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();
        if (!$resultado) {
            throw new Exception("No se pudo realizar la consulta.");
        }
        return $resultado;
    }

    public function obtenerUsuarios($estado = null, $filtro = 'asc', $busqueda = null) {
        if ($this->conexion->connect_errno) {
            throw new Exception('Error de conexión: ' . $this->conexion->connect_error);
        }

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
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }

        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();
        if (!$resultado) {
            throw new Exception("No se pudo realizar la consulta.");
        }
        return $resultado;
    }

    public function obtenerUsuarioPorDocumentoYContrasena($documento, $contrasena) {
        if ($this->conexion->connect_errno) {
            throw new Exception('Error de conexión: ' . $this->conexion->connect_error);
        }

        $consulta = "SELECT documento, email, contrasena, nombre, apellido, rol, estado FROM usuario WHERE documento = ? AND contrasena = ?";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }

        $stmt->bind_param("ss", $documento, $contrasena);
        if (!$stmt->execute()) {
            throw new Exception("No se pudo realizar la consulta.");
        }
        return $stmt->get_result();
    }
    
    public function obtenerUsuarioPorDocumento($documento) {
        if ($this->conexion->connect_errno) {
            throw new Exception('Error de conexión: ' . $this->conexion->connect_error);
        }

        $consulta = "SELECT * FROM usuario WHERE documento = ?";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }

        $stmt->bind_param("s", $documento);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return null;
        }
    }
    
    public function obtenerUsuarioPorCedulaYEmail($cedula, $email) {
        $stmt = $this->conexion->prepare("SELECT * FROM usuario WHERE documento = ? AND email = ?");
        $stmt->bind_param("ss", $cedula, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function registrarUsuario($documento, $email, $contrasena, $nombre, $apellido) {
        if ($this->conexion->connect_errno) {
            throw new Exception('Error de conexión: ' . $this->conexion->connect_error);
        }

        $consulta = "INSERT INTO usuario (documento, email, contrasena, nombre, apellido) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }

        $stmt->bind_param("sssss", $documento, $email, $contrasena, $nombre, $apellido);
        return $stmt->execute();
    }

    public function añadirUsuario($documento, $nombre, $apellido, $email, $rol, $estado) {
        if ($this->conexion->connect_errno) {
            throw new Exception('Error de conexión: ' . $this->conexion->connect_error);
        }
        $consulta = "INSERT INTO usuario (documento, nombre, apellido, email, rol, estado) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
        }
    
        $stmt->bind_param("ssssss", $documento, $nombre, $apellido, $email, $rol, $estado);
        return $stmt->execute();
    }

    public function actualizarContrasena($cedula, $nuevaContrasena) {
        $consulta = "UPDATE usuario SET contrasena = ? WHERE documento = ?";
        $stmt = $this->conexion->prepare($consulta);
        $stmt->bind_param('ss', $nuevaContrasena, $cedula);
        return $stmt->execute();
    }
}
?>




