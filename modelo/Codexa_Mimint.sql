CREATE DATABASE CODEXA_MIMINT;
USE CODEXA_MIMINT;

CREATE TABLE `administra` (
  `administrador` int NOT NULL,
  `sala` varchar(30) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `fechaModificacion` date NOT NULL,
  PRIMARY KEY (`administrador`,`sala`),
  KEY `sala` (`sala`)
) ENGINE=InnoDB;


INSERT INTO `administra` (`administrador`, `sala`, `descripcion`, `fechaModificacion`) VALUES
(22334455, 'Biblioteca', 'Se modifica la capacidad', '2024-05-10'),
(22334455, 'Sala de Arte', 'Se modifica la localidad', '2024-06-15'),
(56380006, 'Sala de Estudio', 'Se modifica el nombre', '2024-07-20'),
(56380006, 'Sala de informatica', NULL, '2024-10-12'),
(99887766, 'Laboratorio de ciencias', 'Se modifica el nombre', '2024-11-15');


CREATE TABLE `caracteristicas` (
  `sala` varchar(30) NOT NULL,
  `caracteristica` varchar(20) NOT NULL,
  PRIMARY KEY (`sala`,`caracteristica`)
) ENGINE=InnoDB;


INSERT INTO `caracteristicas` (`sala`, `caracteristica`) VALUES
('Biblioteca', 'Cuenta con libros'),
('Sala de informatica', 'Cuenta con computado'),
('Sala de informatica', 'Cuenta con proyector'),
('Salón de Actos', 'Cuenta con instrumen'),
('Salón de Actos', 'Es espacioso');


CREATE TABLE `equipamiento` (
  `nombre` varchar(30) NOT NULL,
  `cantidad` int NOT NULL,
  PRIMARY KEY (`nombre`)
) ENGINE=InnoDB;

INSERT INTO `equipamiento` (`nombre`, `cantidad`) VALUES
('alargues ', 30),
('botellas de agua ', 100),
('Cafes', 100),
('Camaras', 10),
('Cargadores', 55),
('Hojas de escrito', 300),
('Laptops', 50),
('Marcadores', 70),
('Microfonos', 20),
('mouses ', 40);

CREATE TABLE `requiere` (
  `reserva` int NOT NULL,
  `equipamiento` varchar(50) NOT NULL,
  `cantidad` int NOT NULL,
  PRIMARY KEY (`reserva`,`equipamiento`),
  KEY `equipamiento` (`equipamiento`)
) ENGINE=InnoDB;


INSERT INTO `requiere` (`reserva`, `equipamiento`, `cantidad`) VALUES
(1, 'Marcadores', 4),
(3, 'Cargadores', 13),
(4, 'alargues ', 10),
(4, 'botellas de agua ', 10),
(6, 'Hojas de escrito', 20);


CREATE TABLE `reserva` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dia` date NOT NULL,
  `horainicio` time NOT NULL,
  `horafin` time NOT NULL,
  `cantidadpersonas` int NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `docente` int NOT NULL,
  `sala` varchar(30) NOT NULL,
  `estado` varchar(12) DEFAULT 'Pendiente',
  PRIMARY KEY (`id`),
  KEY `docente` (`docente`),
  KEY `sala` (`sala`)
) ENGINE=InnoDB;

INSERT INTO `reserva` (`id`, `dia`, `horainicio`, `horafin`, `cantidadpersonas`, `descripcion`, `docente`, `sala`, `estado`) VALUES
(1, '2024-08-15', '16:00:00', '18:00:00', 10, NULL, 56380006, 'Sala de Estudio', 'Rechazado'),
(2, '2024-08-19', '10:00:00', '13:00:00', 15, 'Este es una reunión de padres ', 55667788, 'Laboratorio de ciencias', 'Rechazado'),
(3, '2024-09-13', '12:00:00', '13:00:00', 21, 'Se busca realizar una clase extra para los estudiantes que tengan bajas', 66778899, 'Sala de Música', 'Aprobado'),
(4, '2024-10-09', '13:30:00', '15:00:00', 12, 'Esta es una reunión de todos los profesores de Bachillerato tecnológico', 33445566, 'Sala de Profesores', 'Pendiente'),
(5, '2024-09-19', '10:00:00', '12:00:00', 5, 'Se busca hacer una reunión con todos los delegados de 6to', 99887766, 'Sala de Estudio', 'Aprobado'),
(6, '2024-10-01', '12:00:00', '15:00:00', 10, 'Se busca reforzar conocimientos de php a alumnos de 6to tecnológico', 56380006, 'Sala de informatica', 'Aprobado');

CREATE TABLE `sala` (
  `nombre` varchar(30) NOT NULL,
  `capacidad` int NOT NULL,
  `localidad` varchar(20) NOT NULL,
  PRIMARY KEY (`nombre`)
) ENGINE=InnoDB;

INSERT INTO `sala` (`nombre`, `capacidad`, `localidad`) VALUES
('Biblioteca', 40, 'tercer piso'),
('Laboratorio de ciencias', 25, 'segundo piso'),
('Sala de Arte', 30, 'tercer piso'),
('Sala de Estudio', 35, 'edificio central'),
('Sala de informatica', 30, 'primer piso'),
('Sala de Lectura', 35, 'primer piso'),
('Sala de Música', 25, 'edificio central'),
('Sala de Profesores', 15, 'segundo piso'),
('Sala de Proyectos', 30, 'primer piso'),
('Salón de Actos', 15, 'Edificio central');


CREATE TABLE `usuario` (
  `documento` int NOT NULL,
  `email` varchar(50) NOT NULL,
  `contrasena` varchar(50) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'Pendiente',
  PRIMARY KEY (`documento`)
) ENGINE=InnoDB;

INSERT INTO `usuario` (`documento`, `email`, `contrasena`, `nombre`, `apellido`, `rol`, `estado`) VALUES
(22334455, 'pedro.lopez@gmail.com', '4321', 'Pedro', 'Lopez', 'Admin', 'Aprobado'),
(33445566, 'sofia.martinez@gmail.com', 'SofiaM123', 'Sofia', 'Martinez', 'Estudiante', 'Aprobado'),
(44556677, 'cfernandez@impulso.edu.uy', 'Carlos1111', 'Carlos', 'Fernandez', 'Docente', 'Aprobado'),
(55667788, 'laura.fernandez@gmail.com', 'FernandezL21031', 'Laura', 'Fernandez', 'Estudiante', 'Rechazado'),
(56380006, 'sneves@impulso.edu.uy', 'seba', 'Sebastian', 'Neves', 'Admin', 'Aprobado'),
(66778899, 'pablo.sanchez@gmail.com', 'PabloPablo11', 'Pablo', 'Sanchez', 'Docente', 'Aprobado'),
(87654321, 'maria.garcia@gmail.com', 'Contraseñaña1111', 'Maria', 'Garcia', 'Docente', 'Aprobado'),
(99887766, 'javier.mendez@gmail.com', 'Javi123012302', 'Javier', 'Mendez', 'Admin', 'Rechazado');


CREATE TABLE `verifica` (
  `administrador` int NOT NULL,
  `reserva` int NOT NULL,
  `accion` varchar(10) DEFAULT NULL,
  `mensaje` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`administrador`,`reserva`),
  KEY `reserva` (`reserva`)
) ENGINE=InnoDB;


INSERT INTO `verifica` (`administrador`, `reserva`, `accion`, `mensaje`) VALUES
(22334455, 2, 'Cancela', 'Ese dia no esta disponible esa sala'),
(56380006, 3, 'Aprueba', 'Se acepta la reserva'),
(56380006, 5, 'Aprueba', 'Se acepta la reserva'),
(99887766, 1, 'Cancela', 'Los equipamientos no estaran disponibles ese dia'),
(99887766, 6, 'Aprueba', 'Se acepta la reserva');
