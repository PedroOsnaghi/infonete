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
    nombre    varchar(70),
    apellido  varchar(70),
    email     varchar(60)  not null,
    pass      varchar(50)  not null,
    domicilio varchar(150) not null,
    latitud   double,
    longitud  double,
    avatar    varchar(100),
    vhash     varchar(130),
    rol       int          not null,
    estado    int          not null,
    activo    int          not null,
    FOREIGN KEY (rol) REFERENCES rol (id)
);



create table tipo_producto
(
    id   int primary key auto_increment,
    tipo varchar(50)
);

create table producto
(
    id               int primary key auto_increment,
    id_tipo_producto int,
    nombre           varchar(100),
    descripcion		 varchar(255),
    imagen           varchar(100),
    activo           BOOLEAN default (1),
    foreign key (id_tipo_producto) references tipo_producto (id)
);

create table tipo_suscripcion
(
    id          int primary key auto_increment,
    duracion    int,/*duracion en días*/
    descripcion varchar(100)
);/*descripcion: MENSUAL ANUAL SEMESTRAL*/

create table suscripcion
(
    id                  int primary key auto_increment,
    descripcion         varchar(100),
    id_tipo_suscripcion int,
    tag                 varchar(50),
    precio              float,
    foreign key (id_tipo_suscripcion) references tipo_suscripcion (id)
);

create table factura (
    id double PRIMARY KEY,
    cantidad int,
    detalle varchar(255),
    precio decimal,
    id_usuario int,
    foreign key (id_usuario) references usuario (id)
);

create table usuario_suscripcion
(
    id_usuario     int,
    id_suscripcion int,
    id_producto    int,
    fecha_inicio   datetime,
    id_pago 		double,
    activa         BOOLEAN,
    primary key (id_usuario, id_suscripcion,id_producto, id_pago),
    foreign key (id_usuario) references usuario (id),
    foreign key (id_suscripcion) references suscripcion (id),
    foreign key (id_producto) references producto (id),
    foreign key (id_pago) references factura (id)
);

create table edicion
(
    id          int primary key auto_increment,
    numero      int,
    titulo      varchar(255),
    descripcion varchar(255),
    precio      float,
    fecha       date default (null),
    estado      boolean,
    id_producto int,
    portada     varchar(100),
    foreign key (id_producto) references producto (id)
);

create table compra_edicion
(
    id_usuario int,
    id_edicion int,
    fecha      date,
    id_pago    double,
    primary key (id_usuario, id_edicion,id_pago),
    foreign key (id_usuario) references usuario (id),
    foreign key (id_edicion) references edicion (id),
    foreign key (id_pago) references factura (id)
);

create table seccion
(
    id          int primary key auto_increment,
    nombre      varchar(100),
    descripcion varchar(255)
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


INSERT INTO `articulo` VALUES (78,'Cristina Kirchner hablará en La Plata ante un estadio colmado en medio de una fuerte expectativa por su candidatura','Con La Cámpora como protagonista y gran presencia de intendentes, la Vicepresidenta será la única oradora en el acto por el Día de la Militancia. Sin Alberto Fernández, habrá representantes del ala moderada del Gobierno','<p style=\"text-align: justify;\"><span style=\"font-size: 14pt;\">Las C&aacute;mpora espera mucho de los <strong>intendentes</strong>&nbsp;para el rutilante acto que encabezar&aacute;&nbsp;<strong>Cristina</strong>&nbsp;<strong>Kirchner&nbsp;</strong>en&nbsp;<strong>La Plata.</strong>&nbsp;A cargo de la organizaci&oacute;n, la fuerza de&nbsp;<strong>M&aacute;ximo Kirchner</strong>&nbsp;les asign&oacute; a los jefes municipales del PJ el<strong>&nbsp;espacio m&aacute;s abarcativo del Estadio &Uacute;nico&nbsp;</strong>-el campo, donde caben&nbsp;<strong>20 mil</strong>&nbsp;personas- confiada en que lo ver&aacute;n repleto de seguidores dispuestos a<strong>&nbsp;vitorear a la jefa.</strong>&nbsp;El&nbsp;<strong>caudal de militantes</strong>&nbsp;ser&aacute; clave en la&nbsp;<strong>extensa jornada,</strong>&nbsp;pensada para generar un espacio de&nbsp;<strong>&ldquo;encuentro&rdquo;, &ldquo;alegr&iacute;a&rdquo; y &ldquo;esperanza&rdquo;,</strong>&nbsp;como describen los referentes camporistas. Pero, sobre todo, organizado para recordarle al resto de la dirigencia, con tiempo antes del cierre de listas de mayo, que la Vicepresidenta mantiene la&nbsp;<strong>centralidad</strong>&nbsp;en el&nbsp;<strong>agrietado Frente de Todos.</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 14pt;\">El operativo de seguridad, m&aacute;s estricto que de costumbre tras el atentado, estar&aacute; a cargo de La C&aacute;mpora y de la polic&iacute;a bonaerense que comanda el ministro de Seguridad, Sergio Berni, hasta hace muy poco fiel dirigente kirchnerista, pero &uacute;ltimamente alejado, aunque contin&uacute;a en el cargo. Empezar&aacute; temprano, por la ma&ntilde;ana, dentro y fuera del estadio platense, y estar&aacute; pendiente de cada movimiento hasta la desconcentraci&oacute;n, que se espera para despu&eacute;s de las 21, tras el discurso de Cristina Kirchner, que est&aacute; previsto para las<strong>&nbsp;19.30.</strong></span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 14pt;\">El p&uacute;blico, compuesto por unas 40 mil personas, se dividir&aacute; en partes,&nbsp;<strong>seg&uacute;n la pertenencia.</strong>&nbsp;Los sindicatos cercanos, como la<strong>&nbsp;CTA, el moyanismo y la Corriente Federal,</strong>&nbsp;tendr&aacute;n la platea izquierda; los partidos afines, como&nbsp;<strong>Kolina, Nuevo Encuentro y Patria Grande,</strong>&nbsp;compartir&aacute;n la izquierda; mientras que la militancia de La C&aacute;mpora tendr&aacute; exclusividad en la cabecera, el espacio m&aacute;s alejado, pero con mejor vista panor&aacute;mica. Inmediatamente junto al escenario estar&aacute;n, en primera plana, los 2000 invitados.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 14pt;\">All&iacute;, la mayor parte de los asientos estar&aacute;n ocupados por los intendentes del PJ, hoy bajo la conducci&oacute;n del presidente partidario, M&aacute;ximo Kirchner. Aunque una ausencia probablemente llame la atenci&oacute;n: la del jefe municipal de La Matanza,&nbsp;<strong>Fernando Espinoza</strong>, &uacute;ltimamente bajo la mira del kirchnerismo por -lo que describen como- un d&eacute;ficit en la gesti&oacute;n, que le permiti&oacute; crecer en las encuestas a la probable candidata a intendenta del&nbsp;<strong>Movimiento Evita,&nbsp;</strong>Patricia &ldquo;La Colo&rdquo; Cubr&iacute;a. Seg&uacute;n informaron en su entorno, se encuentra en Barcelona para participar de la cumbre Smart Cities que culmina hoy, y lo m&aacute;s seguro es que no llegue a tiempo.</span></p>\r\n<p>&nbsp;</p>','https://www.infobae.com/politica/2022/11/15/causa-vialidad-casacion-rechazo-el-pedido-de-cristina-kirchner-para-apartar-al-fiscal-luciani-y-a-los-jueces/','Rl8ovQdlBN8','Congreso de la Nación Argentina, Hipólito Yrigoyen, Buenos Aires, Argentina','2022-11-17 20:15:55',0,NULL,3,-34.6100346,-58.3925879),(101,'La emotiva arenga de Lionel Messi antes de la final de la Copa América contra Brasil','En el marco de una miniserie documental que se estrenará en Netflix, se conoció parte de lo que fue aquella previa en el Maracaná','<p style=\"text-align: justify;\">El 10 de julio de 2021 la&nbsp;<strong>selecci&oacute;n argentina&nbsp;</strong>salt&oacute; al campo de juego del&nbsp;<strong>Maracan&aacute;&nbsp;</strong>para hacer historia. El elenco comandado por&nbsp;<strong>Lionel Messi&nbsp;</strong>venci&oacute; a Brasil 1 a 0 y conquist&oacute; la&nbsp;<strong>Copa Am&eacute;rica&nbsp;</strong>por primera vez en 28 a&ntilde;os para romper as&iacute; lo que parec&iacute;a ser un maleficio en el que estaba inmerso el combinado nacional. Ahora, la plataforma&nbsp;<strong>Netflix&nbsp;</strong><a href=\"https://www.infobae.com/que-puedo-ver/2022/11/02/exclusivo-la-seleccion-argentina-tendra-su-propia-serie-en-netflix-sean-eternos-campeones-de-america-se-estrena-el-3-de-noviembre/\" target=\"_blank\" rel=\"noopener\">anunci&oacute; el estreno de un documental</a>&nbsp;que repasa el camino hacia la gloria.</p>\r\n<p style=\"text-align: justify;\"><em><strong>Sean eternos</strong></em><em>&nbsp;</em>fue bautizada la mini serie en la que se puede observar el detr&aacute;s de escena de la Scaloneta y en su primer adelanto se vio a un&nbsp;<strong>Messi&nbsp;</strong>como pocas veces.<strong>&nbsp;&ldquo;Siempre ten&iacute;a en la cabeza el porqu&eacute;. &iquest;Por qu&eacute; no se me dio?. Una de todas las que tuvimos aunque sea&rdquo;</strong>, comienza relatando el capit&aacute;n de la&nbsp;<em>Albiceleste&nbsp;</em>en el tr&aacute;iler publicado este martes, a un d&iacute;a del estreno.</p>\r\n<p style=\"text-align: justify;\">&ldquo;Me hab&iacute;a golpeado y vuelto a levantar. Me hab&iacute;a vuelto a caer y as&iacute; y todo ten&iacute;a en la cabeza que se me iba a dar&rdquo;, declara emocionado el Diez que asegura:&nbsp;<strong>&ldquo;Siempre tuve una cuenta pendiente conmigo y con mi pa&iacute;s</strong>&rdquo;. Si eso era cierto, esa cuenta qued&oacute; saldada para siempre con ese triunfo ante Brasil.</p>\r\n<p style=\"text-align: justify;\">Entre los que participaron del documental que estar&aacute; disponible a partir del 3 de noviembre est&aacute; todo el plantel de la selecci&oacute;n argentina de aquel certamen, el entrenador,&nbsp;<strong>Lionel Scaloni</strong>, e incluso ex compa&ntilde;eros de&nbsp;<strong>Messi&nbsp;</strong>en el Barcelona como&nbsp;<strong>Xavi Hern&aacute;ndez&nbsp;</strong>y&nbsp;<strong>Neymar</strong>, quien sigue compartiendo vestuario con&nbsp;<em>La Pulga</em>&nbsp;en el&nbsp;<strong>Par&iacute;s Saint-Germain (PSG).</strong></p>','','U78tIU04xiM','Brasil','2022-11-21 20:55:54',3,'2022-11-21 21:15:48',3,-14.235004,-51.92528),(102,'Con Messi aparte por precaución, Scaloni empieza a despejar las dudas para el debut','Terminó un nuevo entrenamiento en Qatar y el entrenador de la Selección puso el foco en el equipo que pondrá ante Arabia Saudita','<div>\r\n<p style=\"text-align: justify;\">A tres d&iacute;as del debut en la Copa del Mundo ante Arabia Saudita por el Grupo C, Lionel Scaloni evalu&oacute; a los posibles titulares para el choque del martes en Lusail. Como dato principal, Lionel Messi se entren&oacute; diferenciado con el preparador f&iacute;sico pero solamente por precauci&oacute;n.</p>\r\n</div>\r\n<div style=\"text-align: justify;\">\r\n<p>Exequiel Palacios y Lisandro Mart&iacute;nez fueron los &uacute;nicos jugadores que no salieron al campo y permanecieron en el gimnasio.</p>\r\n</div>\r\n<div>\r\n<p style=\"text-align: justify;\">El DT reparti&oacute; pecheras a los posibles titulares y el equipo para arrancar el sue&ntilde;o mundialista ser&iacute;a con:&nbsp;<em>Dibu</em>&nbsp;Mart&iacute;nez; Nahuel Molina, Nicol&aacute;s Otamendi,&nbsp;<em>Cuti&nbsp;</em>Romero y Marcos Acu&ntilde;a; Rodrigo De Paul, Leandro Paredes, Alexis Mac Allister o&nbsp;<em>Papu&nbsp;</em>G&oacute;mez; &Aacute;ngel Di Mar&iacute;a, Lionel Messi y Lautaro Mart&iacute;nez.</p>\r\n<div style=\"text-align: justify;\">\r\n<p><strong>&iquest;Los probables 11 para el debut en el Mundial?</strong></p>\r\n</div>\r\n<div style=\"text-align: justify;\">\r\n<p>Scaloni reparti&oacute; las pecheras y dio un adelanto de qui&eacute;nes podr&iacute;an ser los titulares para el martes 22 de noviembre ante Arabia Saudita en Lusail.</p>\r\n</div>\r\n<div>\r\n<p style=\"text-align: justify;\">Los 11 que recibieron la prenda distintiva son:&nbsp;<em>Dibu</em>&nbsp;Mart&iacute;nez, Nahuel Molina,&nbsp;<em>Cuti&nbsp;</em>Romero, Nicol&aacute;s Otamendi, Marcos Acu&ntilde;a, Rodrigo De Paul, Leandro Paredes,&nbsp;<em>Papu</em>&nbsp;G&oacute;mez, Alexis Mac Allister, &Aacute;ngel Di Mar&iacute;a y Lautaro Mart&iacute;nez.</p>\r\n</div>\r\n</div>','https://www.infobae.com/deportes/2022/11/19/con-messi-el-entrenamiento-de-la-seleccion-argentina-en-vivo-scaloni-ultima-detalles-para-el-debut/','','A.F.A. Predio Ezeiza, A Au Ricchieri, Ezeiza, Provincia de Buenos Aires, Argentina','2022-11-21 21:12:20',3,'2022-11-21 21:15:48',3,-34.7762359,-58.5275294),(103,'El otro lado del búnker de la selección argentina: la vida en Qatar University, una ciudad con embotellamientos y sus propias reglas','La periodista argentina Jessica Costa vive en la Universidad desde hace seis meses, cuando arribó para a estudiar tras ser seleccionada para una beca','<p style=\"text-align: justify;\">El b&uacute;nker que eligi&oacute; la&nbsp;<strong>selecci&oacute;n argentina</strong>&nbsp;para permanecer con su delegaci&oacute;n durante el Mundial de Qatar es una Universidad. Aunque en realidad es una peque&ntilde;a ciudad dentro de la ciudad.&nbsp;<strong>&ldquo;Es como si fuese una ciudad dentro de otra ciudad&rdquo;</strong>, lo define la periodista&nbsp;<strong>Jessica Costa</strong>, quien recibi&oacute; una exclusiva beca y se instal&oacute; en Qatar University meses atr&aacute;s.</p>\r\n<p style=\"text-align: justify;\">Ubicada en las inmediaciones del selecto barrio de&nbsp;<em>La Perla</em>&nbsp;y m&aacute;s cerca de la sede de Lusail que del coraz&oacute;n de Doha que aglomera la mayor&iacute;a de los estadios, la casa&nbsp;<em>albiceleste</em>&nbsp;en Qatar es en realidad un enorme campus que aglutina a todas las universidades m&aacute;s importantes del pa&iacute;s y durante el a&ntilde;o es un hormiguero con&nbsp;<strong>25 mil estudiantes</strong>&nbsp;que incluso tienen transporte interno propio para poder recorrer las instalaciones.</p>\r\n<p style=\"text-align: justify;\">&ldquo;El bus a veces me lo tomo desde un punto en el medio del campus hasta la estaci&oacute;n de metro, que est&aacute; dentro de la universidad tambi&eacute;n, y estoy quiz&aacute;s arriba del bus unos 15 o 20 minutos.&nbsp;<strong>Hay mucho tr&aacute;fico a la ma&ntilde;ana, es tremendo. Muchos estudiantes vienen en autos y se hacen embotellamientos terribles</strong>. Dentro del campus les echamos la culpa a las personas que tienen choferes privados, porque los llaman para que los vayan a buscar y se quedan frenados ah&iacute; esperando&rdquo;, comenta Costa sobre su experiencia de seis meses viviendo en el hostel de la Universidad, a donde lleg&oacute; para realizar un curso intensivo de &aacute;rabe.</p>','','','Qatar','2022-11-21 21:14:34',3,'2022-11-21 21:15:48',3,25.354826,51.183884),(113,'La embajada de Ucrania le pidió a la Cancillería bloquear el canal de televisión ruso que se emite en la Argentina','Se trata de Russia Today (RT), una señal que financia el Kremlin y a la que acusan de difundir noticias falsas sobre la guerra. ','<p style=\"text-align: justify;\">La representaci&oacute;n de la embajada de Ucrania en Buenos Aires le pidi&oacute; a la Canciller&iacute;a argentina, a trav&eacute;s de una nota verbal hace unos 10 d&iacute;as,&nbsp;<strong>que se bloquee el canal de noticias ruso Russia Today (RT)&nbsp;</strong>por la propagaci&oacute;n de informaci&oacute;n falsa sobre los hechos que ocurren durante la contienda b&eacute;lica entre ambos pa&iacute;ses que comenz&oacute; el 24 de febrero de este a&ntilde;o.</p>\r\n<p style=\"text-align: justify;\">La nota verbal es el medio de comunicaci&oacute;n diplom&aacute;tico entre el Ministerio de Relaciones Exteriores de un pa&iacute;s y una misi&oacute;n diplom&aacute;tica o viceversa. A pesar de su nombre se hace por escrito porque el objetivo es que los conceptos queden m&aacute;s claramente expresados y entendidos. En Argentina la embajada de Ucrania est&aacute; a cargo del encargado de Negocios,&nbsp;<strong>Sergiy Nebrat.</strong></p>','','','Ucrania','2022-11-21 21:43:59',3,'2022-11-21 21:54:21',3,48.379433,31.16558),(114,'La TV Pública tuvo que retirar el crespón negro por Hebe de Bonafini durante la transmisión de los partidos','La periodista Ángela Lerena explicó los motivos de esta disposición durante el encuentro entre Senegal y Países Bajos por el Mundial de Qatar 2022','<p>La pelota ya comenz&oacute; a rodar en el Mundial de Qatar 2022 y, aunque todav&iacute;a la selecci&oacute;n argentina no hizo su debut, ya comenzaron a disputarse los primeros partidos. En este sentido, algunos canales ya consiguieron los derechos de transmisi&oacute;n, tal es el caso de la&nbsp;<strong>Televisi&oacute;n P&uacute;blica</strong>&nbsp;que este domingo emiti&oacute; la inauguraci&oacute;n y luego los primeros encuentros, como el que disput&oacute; el equipo local contra Ecuador y este lunes el que tuvo lugar entre Senegal y Pa&iacute;ses Bajos.</p>','','','Argentinos Juniors, Bauness, Buenos Aires, Argentina','2022-11-21 21:48:50',3,'2022-11-21 21:54:21',3,-34.5910988,-58.47305160000001),(115,'En medio del distanciamiento por el BID, Alberto Fernández no viajará a México para encontrarse con López Obrador','Iban a reunirse en la capital mexicana en una cumbre de líderes latinoamericanos prevista para el jueves de esta semana','<p style=\"text-align: justify;\">El presidente Alberto Fern&aacute;ndez finalmente&nbsp;<strong>no viajar&aacute; este jueves 24 a M&eacute;xico</strong>, donde hab&iacute;a sido invitado por su par mexicano&nbsp;<strong>Andr&eacute;s Manuel L&oacute;pez Obrador</strong>&nbsp;para participar una cumbre de l&iacute;deres de Am&eacute;rica Latina de la que tambi&eacute;n iba a formar parte el mandatario electo de Brasil, Luis Inacio &ldquo;Lula&rdquo; da Silva.</p>\r\n<p style=\"text-align: justify;\">La&nbsp;<strong>confirmaci&oacute;n&nbsp;</strong>lleg&oacute; de boca del propio<strong>&nbsp;L&oacute;pez Obrador</strong>, quien hizo el anuncio luego de confirmar que tambi&eacute;n&nbsp;<strong>qued&oacute; postergada la reuni&oacute;n de la XVII Cumbre de la Alianza del Pac&iacute;fico&nbsp;</strong>que se iba a realizar entre el 24 y el 26 de noviembre. La Alianza del Pac&iacute;fico es un bloque creado hace 10 a&ntilde;os que est&aacute; conformado por M&eacute;xico junto a Chile, Colombia y Per&uacute;. Ese c&oacute;nclave se frustr&oacute; porque el jefe de Estado peruano, Pedro Castillo, no recibi&oacute; la autorizaci&oacute;n del Congreso para viajar porque afronta una denuncia constitucional por organizaci&oacute;n criminal, tr&aacute;fico de influencias y colusi&oacute;n.</p>','https://www.infobae.com/politica/2022/11/22/alberto-fernandez-finalmente-no-viajara-a-mexico-para-encontrarse-con-lopez-obrador/','udLzP6QBKls','CABA, Buenos Aires, Argentina','2022-11-22 17:52:04',3,'2022-11-22 18:11:57',3,-34.59878909999999,-58.454596),(116,'La OCDE afirmó que en 2023 la Argentina crecerá 0,5% y registrará una inflación del 83%','La Organización difundió un panorama global y las perspectivas sobre el país; advirtió por los riesgos elevados debido a la fuerte suba de precios y las escasas reservas en el BCRA, entre otros elementos','<p style=\"text-align: justify;\">La Organizaci&oacute;n para la Cooperaci&oacute;n y el Desarrollo Econ&oacute;micos (OCDE) pronostic&oacute; que el crecimiento econ&oacute;mico de la Argentina ser&aacute; del 4,4% este a&ntilde;o, 0,5% en 2023 y del 1,8% en 2024. Adem&aacute;s, indic&oacute; que la inflaci&oacute;n ser&aacute; del 83% el a&ntilde;o pr&oacute;ximo y del 60% el siguiente.</p>\r\n<p style=\"text-align: justify;\"><a id=\"recommended-card-0\" href=\"https://www.infobae.com/economia/2022/11/16/la-inflacion-de-la-argentina-supero-a-la-de-venezuela-en-octubre-y-se-ubico-en-el-quinto-lugar-del-mundo/\" target=\"_blank\" rel=\"noopener noreferrer\" aria-label=\"La inflaci&oacute;n de la Argentina super&oacute; a la de Venezuela en octubre y se ubic&oacute; en el quinto lugar del mundo\"><img style=\"height: 155px; width: auto;\" src=\"https://cloudfront-us-east-1.images.arcpublishing.com/infobae/GDDWRSQE5BCLRGA7APAB5V3L6M.jpg\" sizes=\"(min-width: 800px) 50vw, 90vw\" srcset=\"https://www.infobae.com/new-resizer/1CmE8YgNi_neTn33YTMV9EhyZNE=/265x149/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/GDDWRSQE5BCLRGA7APAB5V3L6M.jpg 265w,https://www.infobae.com/new-resizer/3ROtTPXlSJGt7UsBHNVhdp4pTZA=/420x236/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/GDDWRSQE5BCLRGA7APAB5V3L6M.jpg 420w,https://www.infobae.com/new-resizer/v7J1HciNTd4gKDlufC6dA1rxPkk=/768x432/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/GDDWRSQE5BCLRGA7APAB5V3L6M.jpg 768w,https://www.infobae.com/new-resizer/deGHs4fMoi7ZKbCNgyR_DCTYdJU=/992x558/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/GDDWRSQE5BCLRGA7APAB5V3L6M.jpg 992w,https://www.infobae.com/new-resizer/Xb7xlxvZXwc__sSCg5XWEdDm4DI=/1200x675/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/GDDWRSQE5BCLRGA7APAB5V3L6M.jpg 1200w,https://www.infobae.com/new-resizer/Xp9TWElPXeNZ6cNSD0Nrup-yvls=/1440x810/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/GDDWRSQE5BCLRGA7APAB5V3L6M.jpg 1440w\" alt=\"\" width=\"16\" height=\"9\" loading=\"lazy\"></a></p>\r\n<p style=\"text-align: justify;\">En un informe difundido hoy, la OCDE inform&oacute; que &ldquo;tras un fuerte repunte en 2021 y un deterioro previsto en el segundo semestre de 2022, se prev&eacute; que el PIB aumente un 0,5% en 2023 y un 1,8% en 2024&Prime;.</p>','','','Venecia, Bogotá, Colombia','2022-11-22 17:54:18',3,'2022-11-22 18:11:57',3,4.5927121,-74.1379427),(117,'Elon Musk perdió una inmensa fortuna por Tesla: ¿sigue siendo el hombre más rico del mundo?','El Índice de Multimillonarios de Bloomberg marcó un desplome del patrimonio del dueño de Twitter en lo que va del año; cómo quedó ese ranking','<p style=\"text-align: justify;\">El patrimonio de<strong>&nbsp;Elon Musk</strong>&nbsp;se ha derrumbado en USD 100.000 millones desde enero, aunque sigue ubicado como la persona m&aacute;s rica del mundo.</p>\r\n<p style=\"text-align: justify;\"><a id=\"recommended-card-0\" href=\"https://www.infobae.com/america/tecno/2022/11/20/las-mejores-fotos-de-la-juventud-de-zuckerberg-musk-bill-gates-y-mas-lideres-de-la-tecnologia/\" target=\"_blank\" rel=\"noopener noreferrer\" aria-label=\"Las mejores fotos de la juventud de Zuckerberg, Musk, Bill Gates y m&aacute;s l&iacute;deres de la tecnolog&iacute;a\"><img style=\"height: 155px; width: auto;\" src=\"https://cloudfront-us-east-1.images.arcpublishing.com/infobae/R5AWB36YD5DBDH2KVQNSZNYIGA.webp\" sizes=\"(min-width: 800px) 50vw, 90vw\" srcset=\"https://www.infobae.com/new-resizer/R3KBYpzxy03_NvgE62lcTy915L8=/265x149/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/R5AWB36YD5DBDH2KVQNSZNYIGA.webp 265w,https://www.infobae.com/new-resizer/HyAiCCPhZBoAfYQayq7a2vVdTco=/420x236/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/R5AWB36YD5DBDH2KVQNSZNYIGA.webp 420w,https://www.infobae.com/new-resizer/ooIMBDlR0qiQHAYnXEe3HtkefoI=/768x432/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/R5AWB36YD5DBDH2KVQNSZNYIGA.webp 768w,https://www.infobae.com/new-resizer/Sg4GsME4zADTIxb5bRydx2KQEa4=/992x558/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/R5AWB36YD5DBDH2KVQNSZNYIGA.webp 992w,https://www.infobae.com/new-resizer/Tt4VRWq0XScKBfdDihCdwJ0uxZc=/1200x675/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/R5AWB36YD5DBDH2KVQNSZNYIGA.webp 1200w,https://www.infobae.com/new-resizer/vzhpxU9eyX6RFzj4hi4xwiEv5lg=/1440x810/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/R5AWB36YD5DBDH2KVQNSZNYIGA.webp 1440w\" alt=\"Elon Musk, Jeff Bezos, Mark Zuckerberg y Bill Gates. (foto: Business Insider)\" width=\"16\" height=\"9\" loading=\"lazy\"></a></p>\r\n<p style=\"text-align: justify;\">La riqueza de Elon Musk ha ca&iacute;do 100.500 millones de d&oacute;lares en lo que va de 2022, seg&uacute;n el &Iacute;ndice de Multimillonarios de Bloomberg.</p>\r\n<p style=\"text-align: justify;\">De todos modos, Musk se mantiene como la persona m&aacute;s rica del mundo con una fortuna de 169.800 millones de d&oacute;lares.</p>\r\n<p style=\"text-align: justify;\">Seg&uacute;n Business Insider &ldquo;Musk tiene que responsabilizar a la ca&iacute;da del precio de las acciones de Tesla el descenso de su riqueza&rdquo;.</p>\r\n<div>\r\n<div>\r\n<div id=\"infobae/economia/nota/inline\" data-google-query-id=\"CJOfr--twvsCFV-llQIdT64Bug\"></div>\r\n</div>\r\n</div>','','','Estados Unidos, Buenos Aires, Argentina','2022-11-22 17:55:58',3,'2022-11-22 18:11:57',3,-34.619588,-58.3974523),(118,'Lionel Messi habló tras la derrota de la Argentina: “Que la gente confíe”','El capitán de la Selección se mostró dolido por la caída contra Arabia Saudita pero aseguró que este grupo de jugadores revertirá la historia','<p style=\"text-align: justify;\"><strong>Lionel Messi&nbsp;</strong>habl&oacute; despu&eacute;s de la sorpresiva ca&iacute;da 2 a 1 de la&nbsp;<strong>Argentina&nbsp;</strong>contra&nbsp;<strong>Arabia Saudita&nbsp;</strong>en el&nbsp;<strong>Mundial de Qatar</strong>&nbsp;y si bien reconoci&oacute; que el equipo perdi&oacute; por errores propios, se mostr&oacute; confiado en que podr&aacute; superar a M&eacute;xico y a Polonia en los pr&oacute;ximos dos compromisos del Grupo C.</p>\r\n<p style=\"text-align: justify;\"><a id=\"recommended-card-0\" href=\"https://www.infobae.com/deportes/2022/11/20/alla-vamos-qatar-la-imagen-de-los-tres-hijos-de-messi-y-antonela-roccuzzo-antes-de-viajar-al-mundial-y-un-detalle-que-genero-asombro/\" target=\"_blank\" rel=\"noopener noreferrer\" aria-label=\"&ldquo;All&aacute; vamos, Qatar&rdquo;: la imagen de los tres hijos de Messi y Antonela Roccuzzo antes de viajar al Mundial y un detalle que gener&oacute; asombro\"><img style=\"height: 155px; width: auto;\" src=\"https://cloudfront-us-east-1.images.arcpublishing.com/infobae/25ROXIERNJH27INKR6JX6R2NVM.jpg\" sizes=\"(min-width: 800px) 50vw, 90vw\" srcset=\"https://www.infobae.com/new-resizer/YjiMFlVJp0-o0DYQMIyOLUvMNzs=/265x149/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/25ROXIERNJH27INKR6JX6R2NVM.jpg 265w,https://www.infobae.com/new-resizer/e5sE1FcPB6Q7ZIc7YXLn2bQJunw=/420x236/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/25ROXIERNJH27INKR6JX6R2NVM.jpg 420w,https://www.infobae.com/new-resizer/MEilqAKK95Dnm_Ox4-WP9CgDZnA=/768x432/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/25ROXIERNJH27INKR6JX6R2NVM.jpg 768w,https://www.infobae.com/new-resizer/iITK9eZK5yZ_eEZXwBCFrO1X-MA=/992x558/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/25ROXIERNJH27INKR6JX6R2NVM.jpg 992w,https://www.infobae.com/new-resizer/srp6rxClGq78rcf7Pjdfsil4jFk=/1200x675/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/25ROXIERNJH27INKR6JX6R2NVM.jpg 1200w,https://www.infobae.com/new-resizer/dMDqyQ2FBIJyFn1jGHprv2YgVM8=/1440x810/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/25ROXIERNJH27INKR6JX6R2NVM.jpg 1440w\" alt=\"\" width=\"16\" height=\"9\" loading=\"lazy\"></a></p>\r\n<p style=\"text-align: justify;\">&ldquo;Hace mucho que no pasamos por una situaci&oacute;n as&iacute;, as&iacute; que tenemos que estar m&aacute;s unidos que nunca, quedan 2 partidos hay que preparar lo que viene y pensar en nosotros&rdquo;, sostuvo el capit&aacute;n y autor del &uacute;nico tanto de la Albiceleste. Cabe se&ntilde;alar que<em>&nbsp;La Pulga&nbsp;</em>hab&iacute;a hecho &eacute;nfasis en la previa sobre lo importante del primer partido y que el cuadro asi&aacute;tico podr&iacute;a presentar dificultades.</p>','https://www.infobae.com/deportes/2022/11/22/lionel-messi-hablo-tras-la-derrota-de-la-argentina-que-la-gente-confie/','','Qatar','2022-11-22 18:00:35',3,'2022-11-22 18:11:58',3,25.354826,51.183884),(119,'Un conductor ebrio atropelló a dos amigas a la salida de un boliche y huyó: mató a una y dejó con muerte cerebral a la otra','Ocurrió el fin de semana la localidad bonaerense de El Talar. El asesino al volante se entregó horas después del hecho y descubrieron que tenía casi el triple de lo permitido de alcohol en sangre. Qué declaró el acusado','<p style=\"text-align: justify;\">El domingo a la ma&ntilde;ana,&nbsp;<strong>Mat&iacute;as Leonardo Mart&iacute;nez</strong>, de 31 a&ntilde;os, se entreg&oacute; en la Comisar&iacute;a N&ordm;3 de Pablo Nogu&eacute;s despu&eacute;s de causar un desastre. Unas tres horas antes, mientras manejaba alcoholizado en plena madrugada, atropell&oacute; a dos j&oacute;venes amigas que hab&iacute;an ido a festejar un cumplea&ntilde;os a una bailanta en la localidad bonaerense de El Talar y luego escap&oacute;:&nbsp;<strong>a una la mat&oacute; y a la otra la dej&oacute; internada con muerte cerebral</strong>.</p>\r\n<p style=\"text-align: justify;\">Seg&uacute;n confirmaron fuentes judiciales a&nbsp;<strong>Infobae</strong>, a pesar de entregarse algunas horas despu&eacute;s del hecho, el test de alcoholemia ratific&oacute; que estaba ebrio y revel&oacute; que&nbsp;<strong>ten&iacute;a 1,38 gramos de alcohol en sangre</strong>. Es decir, casi el triple de lo permitido. En su declaraci&oacute;n, el ahora imputado dijo que &ldquo;perdi&oacute; el control y se le fue el auto&rdquo;. Por ahora, sigue detenido.</p>','','','Argerich, Buenos Aires, Argentina','2022-11-22 18:07:13',3,'2022-11-22 18:11:58',3,-34.6040914,-58.48923839999999),(120,'Procrastinación: por qué el cerebro elige dilatar algunas acciones y cómo evitarlo','Aunque algunos estiman que es pereza, dos especialistas lo relacionaron con una incomodidad emocional. Consejos para identificar estas actitudes y cómo enfrentarlas','<p style=\"text-align: justify;\">&ldquo;En un rato&rdquo;. &ldquo;Mejor, ma&ntilde;ana&rdquo;. &ldquo;Dame 5 minutos y arranco&rdquo;. Estas tres frases son solo algunas de las que se pueden aplicar cuando se trata de procrastinar. Es que esta actitud no es otra cosa que el h&aacute;bito de aplazar una obligaci&oacute;n o un trabajo, el cual est&aacute; relacionado, generalmente, con una p&eacute;rdida de tiempo. Sin embargo, pese a que se la asocia con pereza, dos especialistas advirtieron que esta situaci&oacute;n no se debe vivir con culpa ya que, en verdad, se trata de un s&iacute;ntoma de incomodidad emocional.</p>\r\n<p style=\"text-align: justify;\"><a id=\"recommended-card-0\" href=\"https://www.infobae.com/tendencias/2022/11/18/como-aprovechar-el-tiempo-libre-de-forma-inteligente-6-consejos-de-la-ciencia-para-poner-en-practica/\" target=\"_blank\" rel=\"noopener noreferrer\" aria-label=\"&iquest;C&oacute;mo aprovechar el tiempo libre de forma inteligente?: 6 consejos de la ciencia para poner en pr&aacute;ctica\"><img style=\"height: 155px; width: auto;\" src=\"https://cloudfront-us-east-1.images.arcpublishing.com/infobae/I3NYAUIDUFDEHGZVGJHS5THTOQ.jpg\" sizes=\"(min-width: 800px) 50vw, 90vw\" srcset=\"https://www.infobae.com/new-resizer/IraKtM8a2We-OxyrcwHj9puPRkA=/265x149/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/I3NYAUIDUFDEHGZVGJHS5THTOQ.jpg 265w,https://www.infobae.com/new-resizer/i5gGAVbdcBFuCSGPD0Hz5YAWNgw=/420x236/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/I3NYAUIDUFDEHGZVGJHS5THTOQ.jpg 420w,https://www.infobae.com/new-resizer/Jp3MDO6T2-BXyioaeo4He0ymD8U=/768x432/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/I3NYAUIDUFDEHGZVGJHS5THTOQ.jpg 768w,https://www.infobae.com/new-resizer/6m9tvLgBrc5RH_RYpwh3sDC31hM=/992x558/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/I3NYAUIDUFDEHGZVGJHS5THTOQ.jpg 992w,https://www.infobae.com/new-resizer/6RyxnNuUxd9EYXb5nnG6ceWbW-s=/1200x675/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/I3NYAUIDUFDEHGZVGJHS5THTOQ.jpg 1200w,https://www.infobae.com/new-resizer/II2cYazlUr-mSmGHZHjBBmthbOE=/1440x810/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/I3NYAUIDUFDEHGZVGJHS5THTOQ.jpg 1440w\" alt=\"Cr&eacute;dito: Getty\" width=\"16\" height=\"9\" loading=\"lazy\"></a></p>\r\n<p style=\"text-align: justify;\">Es m&aacute;s, lejos de convocar a ejercerla sin l&iacute;mites, las expertas impulsan a observar algunos aspectos del trabajo cotidiano para encontrar qu&eacute; nos est&aacute; queriendo decir nuestro cerebro cuando procrastinamos. Liz Fosslien y Mollie West Duffy se dedican a entrenar equipos y l&iacute;deres para desarrollar habilidades y h&aacute;bitos para desbloquear el potencial personal y, adem&aacute;s, son coautoras de Big Feelings: How to Be Okay When Things Are Not Okay (Grandes sentimientos: c&oacute;mo estar bien cuando las cosas no est&aacute;n bien).</p>','','','San Telmo, CABA, Argentina','2022-11-22 18:09:07',3,'2022-11-22 18:12:03',3,-34.6218351,-58.3713942),(121,'Liderazgos dinámicos y escucha activa, las claves de los ganadores para atraer a los talentos jóvenes','Las empresas que lideraron el ranking de Los Mejores Lugares para Trabajar para Millennials 2022 le contaron a Infobae cuáles son sus estrategias para crear un ambiente de bienestar','<p style=\"text-align: justify;\">&iquest;Qu&eacute; esperan los&nbsp;<strong>millennials&nbsp;</strong>de un gran lugar para trabajar? Quienes hoy tiene menos de 35 a&ntilde;os forman parte de una generaci&oacute;n nativa digital e hiperconectada que, tras atravesar los avatares de la pandemia, hoy m&aacute;s que nunca prioriza la b&uacute;squeda de desarrollo laboral en equilibrio con el bienestar integral. En ese camino, el esp&iacute;ritu de equipo, los liderazgos din&aacute;micos y cercanos, junto a v&iacute;nculos de respeto y confianza, son pilares fundamentales para esta generaci&oacute;n.</p>\r\n<p style=\"text-align: justify;\">El ranking&nbsp;<a href=\"https://www.greatplacetowork.com.ar/los-mejores-lugares-para-trabajar-para-millennials/2022\" target=\"_blank\" rel=\"noopener\"><strong>Los Mejores Lugares para Trabajar para Millenials 2022&nbsp;</strong></a>de&nbsp;<strong>Great Place To Work</strong>&nbsp;revel&oacute; cu&aacute;les son las 50 compa&ntilde;&iacute;as argentinas que se distinguen por atraer y retener a los talentos j&oacute;venes.</p>','','','Argentina Av., Buenos Aires, Argentina','2022-11-22 18:10:20',3,'2022-11-22 18:12:03',3,-34.6727325,-58.4760795);

INSERT INTO `archivo` VALUES (2,101,1,'C3ATM7ASE3SGKMRIQOUESRTKTY.webp',NULL),(3,102,1,'3ANL4H2ZKSIHNDD2W6SHUOC2IQ.webp',NULL),(4,103,1,'BE6P7VAIKJH7BC7PUCD5WHRACI.webp',NULL),(5,114,1,'HWI77OYQYBG3BM3PJ2MWYXJ4CI.webp',NULL),(6,114,1,'UEJHEB3WXJDFHHWTRKJ5AES77Q.webp',NULL),(7,113,1,'EEPUMUSTDZGKBDBFW6JSJO7IOY.webp',NULL),(13,78,1,'DLVLVICZPRCW3GDJT5IYLBFBCE.webp',NULL),(14,78,1,'EYZRAM6PFFBVXHASWCY4ESWTTI.webp',NULL),(16,78,1,'KAZIZMPP4BFPBCPIIDE7WUINIE.webp',NULL),(17,115,1,'5CPXHMXXLZHMNN6LMZEWB5HECY.webp',NULL),(18,115,1,'C3ATM7ASE3SGKMRIQOUESRTKTY.webp',NULL),(19,116,1,'7D3TF4P64BFC3G2ASHWNKPQ4BM.webp',NULL),(20,116,1,'K2VJ346NOFEHXDQNJKB6C3FMOU.webp',NULL),(21,117,1,'AV6OJDS2CBQE3YRNSSYKR4UZIU.webp',NULL),(22,117,1,'CTXZH464Y5DG7AFIMBF5AJIFNQ.webp',NULL),(23,118,1,'4XENCSYBLIFHP3SERC6APHCJDY.webp',NULL),(24,118,1,'65BIS3KPUIIIMSODU5W54DXUIY.webp',NULL),(25,119,1,'FRYVLIBUD5DUTHLD54WJOHYXKM.webp',NULL),(26,119,1,'WTG34JJAFJGNXM55M7UT3JWUAU.webp',NULL),(27,120,1,'BRLSPV4Y7ZBYPAHWZLVK33QYIA.webp',NULL),(28,120,1,'ESP2HARAYVF2VLBBDO45AXKAG4.webp',NULL),(29,120,1,'H62UYHNR7RCE3FYNBUGCDJ5PUU.webp',NULL),(30,121,1,'W3ZHNRUIDZEJJJZT2L563PUVTQ.webp',NULL),(31,121,1,'XFNQEVNL4ZDGBE4OFC7AFUAZDM.webp',NULL);

INSERT INTO `producto` VALUES (1,1,'Clarin','Ultimas noticias de Argentina y el mundo','clarin.png',1),(2,2,'Gente','Últimas noticias del espectáculo e interés general en Argentina y el mundo','gente.png',1),(3,1,'OLÉ','Olé, diario deportivo líder en Argentina. Noticias deportivas de: Fútbol local e internacional, Selección Nacional, tenis, rugby, autos y más','Olé_1996.svg',1);

INSERT INTO `edicion` VALUES (1,1,'Luisana al rojo vivo','Luisana revela todos los secretos de su vida en familia. Todo y mucho mas en esta edicíon',100.5,'2022-11-22 07:11:00',1,2,'gente.jpg'),(2,1256,'Héroes para siempre','Somos Campeones de America!',450.75,'2022-11-21 10:11:00',1,3,'IcAx8HZtv_720x0.webp'),(3,3232,'La nueva Magistratura','Vea las ultimas novedades de la politica argentina',370.8,'2022-11-22 07:11:00',1,1,'HWI77OYQYBG3BM3PJ2MWYXJ4CI.webp'),(4,3256,'Boca Campeón','Detalles de la final de la copa',375.9,'2022-11-22 07:11:00',1,1,'m2UHgMsjx_720x0__1.webp'),(5,56,'Pampita al desnudo','Pampita revela todas sus intimidades',650.99,'2022-11-22 07:11:00',1,2,'Dv62Q08XgAEkpbo.jpg');

INSERT INTO `seccion` VALUES (1,'ECONOMIA','Todo sobre la economia del pais y el mundo..'),(2,'DEPORTES','Futbol, tenis, basquet y mas..'),(3,'POLITICA','politica nacional'),(4,'INTERNACIONALES','Informacion del resto del mundo..'),(5,'SALUD','Todo sobre la salud.. y mas!'),(6,'SOCIEDAD','Toda la informacion de nuestra Sociedad y del mundo..'),(7,'FARANDULA','Todas las noticias del espectaculo'),(8,'MODA','Las ultimas tendencias');

INSERT INTO `suscripcion` VALUES (1,'Basico',1,'basic',100),(2,'Premium',2,'premium',150),(3,'Pro',3,'pro',300);

INSERT INTO `articulo_edicion` VALUES (3,78,1),(2,101,2),(4,102,2),(2,103,2),(3,113,2),(6,114,2),(3,115,3),(1,116,3),(1,117,3),(2,118,4),(6,119,4),(8,120,5),(6,121,5);


