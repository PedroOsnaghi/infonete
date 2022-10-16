drop database if exists infonete;
create database infonete;
use infonete;

create table usuario (
	id int primary key auto_increment,
    nombreUsuario varchar(50) not null,
    pass varchar(20) not null,
    rol int not null
);

insert into usuario (nombreUsuario, pass, rol) values ('test', '123', 1);