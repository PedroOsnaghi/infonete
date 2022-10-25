drop database if exists infonete;
create database infonete;
use infonete;

create table usuario (
	id int primary key auto_increment,
    nombre varchar(50),
    apellido varchar(50),
    email varchar(50) not null,
    pass varchar(50) not null,
    domicilio varchar(100) not null,
    latitud double,
    longitud double,
    avatar varchar(50),
    vhash varchar(100),
    rol int not null,
    estado int not null,
    activo int not null
);

insert into usuario (nombre, apellido, email, pass, domicilio, latitud, longitud, avatar, vhash, rol, estado, activo) 
			 values ('nombre_test', 'apellido_test', 1, 'test@test.com', 'domicilio test', 0, 0, 'default.png', '', 1, 1, 1);