CREATE DATABASE CODEXA_MIMINT;
USE CODEXA_MIMINT;

CREATE TABLE usuario(
    documento INT(8) PRIMARY KEY,
    email VARCHAR(50) NOT NULL,
    contrasena VARCHAR(50) NOT NULL,
    nombre VARCHAR(20) NOT NULL,
    apellido VARCHAR(20) NOT NULL,
    rol VARCHAR(20) NOT NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'Pendiente'
) ENGINE=InnoDB;

CREATE TABLE sala(
    nombre VARCHAR(30) PRIMARY KEY,
    capacidad INT(4) NOT NULL,
    localidad VARCHAR(20) NOT NULL
) ENGINE=InnoDB; 

CREATE TABLE caracteristicas(
    sala VARCHAR(30),
    caracteristica VARCHAR(20),
    PRIMARY KEY(sala, caracteristica),
    FOREIGN KEY(sala) REFERENCES sala(nombre)
) ENGINE=InnoDB;

CREATE TABLE reserva(
    id INT(3) AUTO_INCREMENT PRIMARY KEY,
    dia DATE NOT NULL,
    horainicio TIME NOT NULL,
    horafin TIME NOT NULL,
    cantidadpersonas INT(4) NOT NULL,
    descripcion VARCHAR(100),
    docente INT(8) NOT NULL,
    sala VARCHAR(30) NOT NULL,
    estado VARCHAR(12) DEFAULT 'Pendiente',
    FOREIGN KEY(docente) REFERENCES usuario(documento),
    FOREIGN KEY(sala) REFERENCES sala(nombre)
) ENGINE=InnoDB;

CREATE TABLE equipamiento(
    nombre VARCHAR(30) PRIMARY KEY,
    cantidad INT(3) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE administra(
    administrador INT(8),
    sala VARCHAR(30),
    descripcion VARCHAR(100),
    fechaModificacion DATE NOT NULL,
    PRIMARY KEY(administrador, sala),
    FOREIGN KEY(administrador) REFERENCES usuario(documento),
    FOREIGN KEY(sala) REFERENCES sala(nombre)
) ENGINE=InnoDB;

CREATE TABLE requiere(
    reserva INT,
    equipamiento VARCHAR(50),
    cantidad INT(3) NOT NULL,
    PRIMARY KEY(reserva, equipamiento),
    FOREIGN KEY(reserva) REFERENCES reserva(id),
    FOREIGN KEY(equipamiento) REFERENCES equipamiento(nombre)
) ENGINE=InnoDB;

CREATE TABLE verifica(
    administrador INT,
    reserva INT,
    accion VARCHAR(10),
    mensaje VARCHAR(100),
    PRIMARY KEY(administrador, reserva),
    FOREIGN KEY(administrador) REFERENCES usuario(documento),
    FOREIGN KEY(reserva) REFERENCES reserva(id)
) ENGINE=InnoDB;

INSERT INTO `administra` (`administrador`, `sala`, `descripcion`, `fechaModificacion`) VALUES
(22334455, 'Salón de Actos', 'Se modifica la capacidad', '2024-08-19'),
(56380006, 'Salón de Actos', 'Se modifica la localidad', '2024-08-23'),
(99887766, 'Sala de Informática', 'Se modifica el nombre', '2024-08-12'),
(56380006, 'Sala de Informática', NULL, '2024-08-27'),
(99887766, 'Biblioteca', 'Se modifica la localidad', '2024-08-28');

INSERT INTO `caracteristicas` (`sala`, `caracteristica`) VALUES
('Biblioteca', 'Cuenta con libros'),
('Sala de Informática', 'Cuenta con computado'),
('Sala de Informática', 'Tiene un proyector'),
('Salón de Actos', 'Cuenta con instrumen'),
('Salón de Actos', 'Es espacioso ');

INSERT INTO `equipamiento` (`nombre`, `cantidad`) VALUES
('Laptops', 50),
('Cargadores', 55),
('Cafes', 100),
('mouses ', 40),
('alargues ', 30),
('botellas de agua ', 100),
('Microfonos', 20),
('Camaras', 10),
('Hojas de escrito', 300),
('Marcadores', 70);

INSERT INTO `requiere` (`reserva`, `equipamiento`, `cantidad`) VALUES
(1, 'Laptops', 0),
(2, 'Cargadores', 0),
(3, 'Cafes', 0),
(4, 'Botellas de agua', 0),
(5, 'hojas de escrito', 0),
(7, 'Cafes', 0),
(8, 'Camaras', 20),
(9, 'botellas de agua', 15),
(10, 'Camaras', 12),
(11, 'Camaras', 12);

INSERT INTO `reserva` (`id`, `dia`, `horainicio`, `horafin`, `cantidadpersonas`, `descripcion`, `docente`, `sala`, `estado`) VALUES
(1, '2024-08-19', '06:20:49', '14:20:49', 20, NULL, 12345678, 'Salón de Actos', 'Rechazado'),
(2, '2024-08-15', '10:00:00', '12:00:00', 10, 'Se busca realizar una clase extra para 10 personas', 22334455, 'Salón de Actos', 'Rechazado'),
(3, '2024-08-22', '08:00:00', '09:30:00', 20, 'Se busca reforzar conocimientos de php a alumnos de 6to tecnológico', 44556677, 'Sala de Informática', 'Aprobado'),
(4, '2024-08-29', '12:00:00', '14:30:00', 10, NULL, 66778899, 'Sala de Informática', 'Rechazado'),
(5, '2024-09-30', '15:00:00', '17:00:00', 5, 'Se busca hacer una reunión con todos los delegados de 6to', 11223344, 'Biblioteca', 'Aprobado'),
(6, '2024-02-12', '12:12:00', '13:12:00', 23, '1231321easdad asd as das asd a', 56380006, 'Biblioteca', 'Aprobado'),
(9, '0000-00-00', '12:00:00', '15:00:00', 30, 'Este es una prueba del programa', 56380006, 'Biblioteca', 'Aprobado'),
(10, '2024-02-17', '10:30:00', '13:30:00', 17, 'Esto es para comprobar si funciona la fecha ingresada', 56380006, 'Salon de informatica', 'Rechazado'),
(11, '2024-02-17', '10:30:00', '13:30:00', 17, 'Esto es para comprobar si funciona la fecha ingresada', 56380006, 'Salon de informatica', 'Rechazado');

INSERT INTO `sala` (`nombre`, `capacidad`, `localidad`) VALUES
('Salon de informatica', 30, 'primer piso'),
('Laboratorio de ciencias', 25, 'segundo piso'),
('Salón de Actos', 15, 'Edificio central'),
('Sala de Lectura', 35, 'primer piso'),
('Sala de Arte', 30, 'tercer piso'),
('Sala de Música', 25, 'edificio central'),
('Sala de Profesores', 15, 'segundo piso'),
('Biblioteca', 40, 'tercer piso'),
('Sala de Estudio', 35, 'edificio central'),
('Sala de Proyectos', 30, 'primer piso');

INSERT INTO `usuario` (`documento`, `email`, `contrasena`, `nombre`, `apellido`, `rol`, `estado`) VALUES
(56380006, 'sneves@impulso.edu.uy', 'seba', 'Sebastian', 'Neves', 'docente', 'Aprobado'),
(87654321, 'maria.garcia@gmail.com', 'Contraseñaña1111', 'Maria', 'Garcia', 'Docente', 'Aprobado'),
(22334455, 'pedro.lopez@gmail.com', '4321', 'Pedro', 'Lopez', 'Admin', 'Aprobado'),
(33445566, 'sofia.martinez@gmail.com', 'SofiaM123', 'Sofia', 'Martinez', 'Estudiante', 'Aprobado'),
(44556677, 'cfernandez@impulso.edu.uy', 'Carlos1111', 'Carlos', 'Fernandez', 'docente', 'Aprobado'),
(55667788, 'laura.fernandez@gmail.com', 'FernandezL21031', 'Laura', 'Fernandez', 'Estudiante', 'Rechazado'),
(66778899, 'pablo.sanchez@gmail.com', 'PabloPablo11', 'Pablo', 'Sanchez', 'Docente', 'Aprobado'),
(99887766, 'javier.mendez@gmail.com', 'Javi123012302', 'Javier', 'Mendez', 'Admin', 'Rechazado');

INSERT INTO `verifica` (`administrador`, `reserva`, `accion`, `mensaje`) VALUES
(22334455, 1, 'Acepta', 'Se acepta la reserva'),
(56380006, 2, 'Cancela', 'Ese dia la sala estara ocupada'),
(99887766, 3, 'Acepta', 'Se acepta la reunion'),
(56380006, 4, 'Cancela', 'No se pueden usar ese equipamiento ese dia'),
(99887766, 5, 'Acepta', '');
