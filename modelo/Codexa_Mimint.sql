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

