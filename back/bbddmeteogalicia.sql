-- Creamos la base de datos
CREATE DATABASE `meteogalicia` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ;


-- Creamos las tablas usuarios y temperaturas
CREATE TABLE `meteogalicia`.`usuarios` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`nick` VARCHAR (50) NOT NULL,
`pass` VARCHAR (32) NOT NULL,
-- Ponemos 32 porque es en número de caracteres al pasar la contraseña a md5
`rol` ENUM ('user','admin','guest'),
`idEstacion` INT NOT NULL,
UNIQUE INDEX (idEstacion)
) ENGINE = INNODB;


CREATE TABLE `meteogalicia`.`temperaturas` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`id_usuario` INT NOT NULL,
`id_estacion` INT NOT NULL,
`valTemperatura` FLOAT NOT NULL,
-- Ponemos float porque los datos que obtenemos pueden tener decimales
CONSTRAINT fk_id_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
CONSTRAINT fk_id_estacion FOREIGN KEY (id_estacion) REFERENCES usuarios(idEstacion)
) ENGINE = INNODB;


-- Creamos el usuario dwes con la contraseña abc123.
CREATE USER `dwes`
    IDENTIFIED BY `abc123.`;
    
-- Le damos acceso a todas las tablas (meteogalicia.*) al usuario dwes    
GRANT ALL ON `meteogalicia`.*
    TO `dwes`;

