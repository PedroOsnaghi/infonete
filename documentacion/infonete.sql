drop database if exists infonete;
create database infonete;
use infonete;

create table rol
(
    id       int PRIMARY KEY AUTO_INCREMENT,
    rol_name varchar(30) not null
);

create table usuario
(
    id        int primary key auto_increment,
    nombre    varchar(50),
    apellido  varchar(50),
    email     varchar(50)  not null,
    pass      varchar(50)  not null,
    domicilio varchar(100) not null,
    latitud   double,
    longitud  double,
    avatar    varchar(50),
    vhash     varchar(100),
    rol       int          not null,
    estado    int          not null,
    activo    int          not null,
    FOREIGN KEY (rol) REFERENCES rol (id)
);



create table tipo_producto
(
    id   int primary key auto_increment,
    tipo varchar(30)
);

create table producto
(
    id               int primary key auto_increment,
    id_tipo_producto int,
    nombre           varchar(50),
    imagen           varchar(50),
    activo           BOOLEAN default (1),
    foreign key (id_tipo_producto) references tipo_producto (id)
);

create table tipo_suscripcion
(
    id          int primary key auto_increment,
    duracion    int,/*duracion en días*/
    descripcion varchar(30)
);/*descripcion: MENSUAL ANUAL SEMESTRAL*/

create table suscripcion
(
    id                  int primary key auto_increment,
    descripcion         varchar(50),
    id_tipo_suscripcion int,
    tag                 varchar(30),
    precio              float,
    foreign key (id_tipo_suscripcion) references tipo_suscripcion (id)
);

create table usuario_suscripcion
(
    id_usuario     int,
    id_suscripcion int,
    id_producto    int,
    fecha_inicio   date,
    activa         BOOLEAN,
    primary key (id_usuario, id_suscripcion),
    foreign key (id_usuario) references usuario (id),
    foreign key (id_suscripcion) references suscripcion (id),
    foreign key (id_producto) references producto (id)
);

create table edicion
(
    id          int primary key auto_increment,
    numero      int,
    titulo      varchar(100),
    descripcion varchar(255),
    precio      float,
    fecha       date default (null),
    estado      boolean,
    id_producto int,
    portada     varchar(50),
    foreign key (id_producto) references producto (id)
);

create table compra_edicion
(
    id_usuario int,
    id_edicion int,
    fecha      date,
    primary key (id_usuario, id_edicion),
    foreign key (id_usuario) references usuario (id),
    foreign key (id_edicion) references edicion (id)
);

create table seccion
(
    id          int primary key auto_increment,
    nombre      varchar(50),
    descripcion varchar(100)
);

create table estado_articulo
(
    id     int primary key,
    estado varchar(30)
);

create table articulo
(
    id         int primary key auto_increment,
    titulo     varchar(255),
    subtitulo  varchar(255),
    contenido  longtext,
    link       varchar(255),
    link_video varchar(100),
    ubicacion  varchar(255),
    create_at  timestamp,
    id_estado  int,
    update_at  timestamp DEFAULT (null),
    id_autor   int,
    latitud    double,
    longitud   double,
    foreign key (id_estado) references estado_articulo (id),
    foreign key (id_autor) references usuario (id)
);

create table articulo_edicion
(
    id_seccion  int,
    id_articulo int,
    id_edicion  int,
    primary key (id_articulo, id_seccion),
    foreign key (id_seccion) references seccion (id),
    foreign key (id_articulo) references articulo (id),
    foreign key (id_edicion) references edicion (id)
);

create table tipo_archivo
(
    id   int primary key auto_increment,
    tipo varchar(100)
);

create table archivo
(
    id          int primary key auto_increment,
    id_articulo int,
    id_tipo     int,
    nombre      varchar(100),
    size        float,
    foreign key (id_tipo) references tipo_archivo (id),
    foreign key (id_articulo) references articulo (id)
);

insert into rol (rol_name)
values ("Lector"),
       ("Redactor"),
       ("Editor"),
       ("Administrador");

insert into usuario (nombre, apellido, email, pass, domicilio, latitud, longitud, avatar, vhash, rol, estado, activo)
values ('Facundo', 'Herrera', 'admin@infonete.com', '827ccb0eea8a706c4c34a16891f84e7b', 'domicilio', 0, 0, 'face16.jpg',
        '', 4, 1, 1),
       ('Carolina', 'Montenegro', 'editor@infonete.com', '827ccb0eea8a706c4c34a16891f84e7b', 'domicilio', 0, 0,
        'face23.jpg', '', 3, 1, 1),
       ('Nicolas', 'Arana', 'redactor@infonete.com', '827ccb0eea8a706c4c34a16891f84e7b', 'domicilio', 0, 0,
        'face23.jpg', '', 2, 1, 1),
       ('Romina', 'Godoy', 'lector@infonete.com', '827ccb0eea8a706c4c34a16891f84e7b', 'domicilio', 0, 0, 'face26.jpg',
        '', 1, 1, 1);

insert into tipo_archivo(tipo)
values ('IMAGEN'),
       ('VIDEO'),
       ('AUDIO');

insert into estado_articulo(id, estado)
values (0, 'Draft'),
       (1, 'En Revision'),
       (2, 'Aprobada'),
       (3, 'Publicada'),
       (-1, 'Baja');

insert into tipo_suscripcion(duracion, descripcion)
values (30, 'mensual'),
       (180, 'semestral'),
       (365, 'anual');

insert into tipo_producto(tipo)
values ('DIARIO'),
       ('REVISTA');

INSERT INTO `articulo`
VALUES (78,
        'Cristina Kirchner hablará en La Plata ante un estadio colmado en medio de una fuerte expectativa por su candidatura',
        'Con La Cámpora como protagonista y gran presencia de intendentes, la Vicepresidenta será la única oradora en el acto por el Día de la Militancia. Sin Alberto Fernández, habrá representantes del ala moderada del Gobierno',
        '<p style=\"text-align: justify;\"><span style=\"font-size: 14pt;\">La C&aacute;mpora espera mucho de los&nbsp;<strong>intendentes</strong>&nbsp;para el rutilante acto que encabezar&aacute;&nbsp;<strong>Cristina</strong>&nbsp;<strong>Kirchner&nbsp;</strong>en&nbsp;<strong>La Plata.</strong>&nbsp;A cargo de la organizaci&oacute;n, la fuerza de&nbsp;<strong>M&aacute;ximo Kirchner</strong>&nbsp;les asign&oacute; a los jefes municipales del PJ el<strong>&nbsp;espacio m&aacute;s abarcativo del Estadio &Uacute;nico&nbsp;</strong>-el campo, donde caben&nbsp;<strong>20 mil</strong>&nbsp;personas- confiada en que lo ver&aacute;n repleto de seguidores dispuestos a<strong>&nbsp;vitorear a la jefa.</strong>&nbsp;El&nbsp;<strong>caudal de militantes</strong>&nbsp;ser&aacute; clave en la&nbsp;<strong>extensa jornada,</strong>&nbsp;pensada para generar un espacio de&nbsp;<strong>&ldquo;encuentro&rdquo;, &ldquo;alegr&iacute;a&rdquo; y &ldquo;esperanza&rdquo;,</strong>&nbsp;como describen los referentes camporistas. Pero, sobre todo, organizado para recordarle al resto de la dirigencia, con tiempo antes del cierre de listas de mayo, que la Vicepresidenta mantiene la&nbsp;<strong>centralidad</strong>&nbsp;en el&nbsp;<strong>agrietado Frente de Todos.</strong></span></p>\r <p style=\"text-align: justify;\"><span style=\"font-size: 14pt;\">El operativo de seguridad, m&aacute;s estricto que de costumbre tras el atentado, estar&aacute; a cargo de La C&aacute;mpora y de la polic&iacute;a bonaerense que comanda el ministro de Seguridad, Sergio Berni, hasta hace muy poco fiel dirigente kirchnerista, pero &uacute;ltimamente alejado, aunque contin&uacute;a en el cargo. Empezar&aacute; temprano, por la ma&ntilde;ana, dentro y fuera del estadio platense, y estar&aacute; pendiente de cada movimiento hasta la desconcentraci&oacute;n, que se espera para despu&eacute;s de las 21, tras el discurso de Cristina Kirchner, que est&aacute; previsto para las<strong>&nbsp;19.30.</strong></span></p>\r <p style=\"text-align: justify;\"><span style=\"font-size: 14pt;\">El p&uacute;blico, compuesto por unas 40 mil personas, se dividir&aacute; en partes,&nbsp;<strong>seg&uacute;n la pertenencia.</strong>&nbsp;Los sindicatos cercanos, como la<strong>&nbsp;CTA, el moyanismo y la Corriente Federal,</strong>&nbsp;tendr&aacute;n la platea izquierda; los partidos afines, como&nbsp;<strong>Kolina, Nuevo Encuentro y Patria Grande,</strong>&nbsp;compartir&aacute;n la izquierda; mientras que la militancia de La C&aacute;mpora tendr&aacute; exclusividad en la cabecera, el espacio m&aacute;s alejado, pero con mejor vista panor&aacute;mica. Inmediatamente junto al escenario estar&aacute;n, en primera plana, los 2000 invitados.</span></p>\r <p style=\"text-align: justify;\"><span style=\"font-size: 14pt;\">All&iacute;, la mayor parte de los asientos estar&aacute;n ocupados por los intendentes del PJ, hoy bajo la conducci&oacute;n del presidente partidario, M&aacute;ximo Kirchner. Aunque una ausencia probablemente llame la atenci&oacute;n: la del jefe municipal de La Matanza,&nbsp;<strong>Fernando Espinoza</strong>, &uacute;ltimamente bajo la mira del kirchnerismo por -lo que describen como- un d&eacute;ficit en la gesti&oacute;n, que le permiti&oacute; crecer en las encuestas a la probable candidata a intendenta del&nbsp;<strong>Movimiento Evita,&nbsp;</strong>Patricia &ldquo;La Colo&rdquo; Cubr&iacute;a. Seg&uacute;n informaron en su entorno, se encuentra en Barcelona para participar de la cumbre Smart Cities que culmina hoy, y lo m&aacute;s seguro es que no llegue a tiempo.</span></p>\r <p>&nbsp;</p>',
        'https://www.infobae.com/politica/2022/11/15/causa-vialidad-casacion-rechazo-el-pedido-de-cristina-kirchner-para-apartar-al-fiscal-luciani-y-a-los-jueces/',
        'Rl8ovQdlBN8', 'Congreso de la Nación Argentina, Avenida Entre Ríos, Buenos Aires, Argentina',
        '2022-11-17 20:15:55', 2, NULL, 3, -34.60982079999999, -58.39260609999999);

INSERT INTO `producto` VALUES (1,1,'Clarin','clarin.png',1),(2,2,'Gente','gente.png',1);

INSERT INTO `edicion` VALUES (1,1,'Titulo 123','Descripcion del tituloddd',100.5,NULL,0,2,'gente.jpg');

INSERT INTO `seccion` VALUES (1,'ECONOMIA','Todo sobre la economia del pais y el mundo..'),(2,'DEPORTES','Futbol, tenis, basquet y mas..'),(3,'POLITICA','politica nacional'),(4,'INTERNACIONALES','Informacion del resto del mundo..'),(5,'SALUD','Todo sobre la salud.. y mas!');

INSERT INTO `articulo_edicion`
VALUES (3, 78, 1);