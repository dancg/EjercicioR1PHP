create table usuarios(
id int auto_increment primary key,
email varchar(45) unique not null,
perfil enum('Administrador', 'Usuario'),
ciudad varchar(50),
pass varchar(128) not null
);
insert into usuarios(email, perfil, ciudad, pass) values ('usuadmin@gmail.com', 'Administrador', 'Almeria',
'$2y$10$qbJl.DaQq2f70naUbaTuD.Qej88cv6YW97CYcl1BESvaibHzZh3yy'); 