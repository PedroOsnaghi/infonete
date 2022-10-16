drop database if exists infonete;
create database infonete;
use infonete;

create table usuario (
	id int primary key auto_increment,
    nombreUsuario varchar(50) not null,
    pass varchar(20) not null,
    rol int not null
);

create table lector(
    idUsuario int,
    nombre varchar(50),
    apellido varchar(50),
    email varchar(50),
    dni int,
    ubicacion int,
    constraint pk_usuario primary key (idUsuario),
    constraint fk_usuario foreign key (idUsuario) references usuario (id)
);

insert into usuario (nombreUsuario, pass, rol) values ('test', '123', 1);

