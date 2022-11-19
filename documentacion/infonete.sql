drop database if exists infonete;
create database infonete;
use infonete;

create table rol (
	id int PRIMARY KEY AUTO_INCREMENT,
    rol_name varchar(30) not null
);

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
    activo int not null,
    FOREIGN KEY (rol) REFERENCES rol(id)
);



create table tipo_producto(
id int primary key auto_increment,
tipo varchar(30));

create table producto(
id int primary key auto_increment,
id_tipo_producto int, 
nombre varchar(50),
imagen varchar(50),
activo BOOLEAN default(1),
foreign key(id_tipo_producto) references tipo_producto(id));

create table tipo_suscripcion(
id int primary key auto_increment,
duracion int,/*duracion en días*/
descripcion varchar(30));/*descripcion: MENSUAL ANUAL SEMESTRAL*/

create table suscripcion(
id int primary key auto_increment,
descripcion varchar(50),
id_tipo_suscripcion int,
tag varchar(30),
precio float,
foreign key(id_tipo_suscripcion) references tipo_suscripcion(id));              

create table usuario_suscripcion(
id_usuario int,
id_suscripcion int,
id_producto int,
fecha_inicio date,
activa BOOLEAN,
primary key(id_usuario, id_suscripcion),
foreign key(id_usuario) references usuario(id),
foreign key(id_suscripcion) references suscripcion(id),
foreign key(id_producto) references producto(id));

create table edicion(
id int primary key auto_increment,
numero int,
titulo varchar(100),
descripcion varchar(255),
precio float,
fecha date default(null),
estado boolean,
id_producto int,
portada varchar(50),
foreign key(id_producto) references producto(id));

create table compra_edicion(
id_usuario int,
id_edicion int,
fecha date,
primary key(id_usuario,id_edicion),
foreign key(id_usuario) references usuario(id),
foreign key (id_edicion) references edicion(id));

create table seccion(
id int primary key auto_increment,
nombre varchar(50),
descripcion varchar(100));

create table estado_articulo(
id int primary key,
estado varchar(30));

create table articulo(
id int primary key auto_increment,
titulo varchar(80),
subtitulo varchar(100),
contenido longtext,
link varchar(100),
link_video varchar(100),
ubicacion varchar(255),
latitud double,
longitud double,
create_at timestamp,
id_estado int,
id_autor int,
update_at timestamp DEFAULT(null),
foreign key(id_estado) references estado_articulo(id),
foreign key(id_autor) references usuario(id));

create table articulo_edicion(
id_seccion int,
id_articulo int,
id_edicion int,
primary key(id_articulo,id_seccion),
foreign key(id_seccion) references seccion(id),
foreign key(id_articulo) references articulo(id),
foreign key(id_edicion) references edicion(id));

create table tipo_archivo(
id int primary key auto_increment,
tipo varchar(100));

create table archivo(
id int primary key auto_increment,
id_articulo int,
id_tipo int,
nombre varchar(100),
size float,
foreign key(id_tipo) references tipo_archivo(id),
foreign key(id_articulo) references articulo(id));

insert into rol (rol_name) values ("Lector"),("Redactor"),("Editor"),("Administrador");

insert into usuario (nombre, apellido, email, pass, domicilio, latitud, longitud, avatar, vhash, rol, estado, activo) 
			 values ('Facundo', 'Herrera', 'admin@infonete.com', '827ccb0eea8a706c4c34a16891f84e7b', 'domicilio', 0, 0, 'face16.jpg', '', 4, 1, 1),
					('Carolina', 'Montenegro', 'editor@infonete.com', '827ccb0eea8a706c4c34a16891f84e7b', 'domicilio', 0, 0, 'face23.jpg', '', 3, 1, 1),
                    ('Nicolas', 'Arana', 'redactor@infonete.com', '827ccb0eea8a706c4c34a16891f84e7b', 'domicilio', 0, 0, 'face23.jpg', '', 2, 1, 1),
                    ('Romina', 'Godoy', 'lector@infonete.com', '827ccb0eea8a706c4c34a16891f84e7b', 'domicilio', 0, 0, 'face26.jpg', '', 1, 1, 1);
             
insert into tipo_archivo(tipo) values('IMAGEN'),('VIDEO'),('AUDIO');

insert into estado_articulo(id, estado) values(0, 'Draft'),(1, 'En Revision'),(2, 'Aprobada'),(3, 'Publicada'),(-1, 'Baja');

insert into tipo_suscripcion(duracion,descripcion) values(30,'mensual'),(180,'semestral'),(365,'anual');

insert into tipo_producto(tipo) values('DIARIO'),('REVISTA');