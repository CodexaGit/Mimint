<?php
require_once('../modelo/UsuarioModel.php');

class RegistroController {
    private $usuarioModel;

    public function __construct($conexion) {
        $this->usuarioModel = new UsuarioModel($conexion);
    }

    public function registrarUsuario($documento, $contrasena, $email, $nombreCompleto) {
        try {
            $resultado = $this->usuarioModel->obtenerUsuarioPorDocumentoYContrasena($documento, $contrasena);
            if ($resultado->num_rows > 0) {
                return ["error" => 3]; // Usuario ya existente
            } else {
                $nombreCompletoArray = explode(" ", $nombreCompleto);
                $nombre = $nombreCompletoArray[0];
                $apellido = isset($nombreCompletoArray[1]) ? $nombreCompletoArray[1] : '';

                $this->usuarioModel->registrarUsuario($documento, $email, $contrasena, $nombre, $apellido);
                return ["registrado" => true];
            }
        } catch (Exception $e) {
            return ["error" => 0]; // Error en la consulta
        }
    }
}
?>