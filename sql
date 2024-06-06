//tabla actividad 

CREATE TABLE actividad(

    id INT AUTO_INCREMENT PRIMARY KEY,
    precio DECIMAL(10, 2),
    fecha DATE,
    actividad VARCHAR(255),
    descripcion TEXT,
    material_necesario TEXT,
    enlace_consejos VARCHAR(255)
);

//las dos actividades
INSERT INTO actividad (precio, fecha, actividad, descripcion, material_necesario, enlace_consejos) 
VALUES (50.00, '2024-04-22', 'Piragüismo', 'El piragüismo es un deporte acuático que se practica en una embarcación y en la que te propulsas con una pala o dos. Consiste en bajar por un rio montado en la embarcación superando los obstáculos que nos presenta el río, tales como corrientes rápidas, piedras, ramasincluso las propias curvas del río.', 
'El material necesario es el siguiente:Piragua, pala, chaleco salvavidas y casco. Estos materiales estan incluidos con el precio de la actividad.De casa se recomienda traer ropa y calzado adecuado y una muda de ropa para cambiarse.',
'https://www.ranasella.com/blog/36-historia-piragismo-consejos-practica/');

INSERT INTO actividad (precio, fecha, actividad, descripcion, material_necesario, enlace_consejos) 
VALUES (70.00, '2024-02-16', 'Parapente', 'El parapente es una emocionante actividad aérea donde te deslizas en una aeronave ligera, impulsada por las corrientes de aire y controlada por un par de cuerdas. Consiste en elevarse en el cielo, sorteando desafíos como corrientes ascendentes, turbulencias, e incluso adaptándose a las cambiantes formas del viento y del terreno.', 
'PEl equipo necesario incluye: parapente, arnés, casco y, por supuesto, las ganas de volar. Todos estos elementos están cubiertos por el precio de la actividad. Se recomienda que traigas ropa cómoda y calzado adecuado para disfrutar al máximo de la experiencia.', 
'https://overflytenerife.com/es/consejos-de-parapente/');


//Comprobar los datos introducidos:
SELECT * FROM eventos;

//tabla fotos


CREATE TABLE fotos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ruta VARCHAR(255),
    ruta2 VARCHAR(255),
    actividad_id INT,
    FOREIGN KEY (actividad_id) REFERENCES actividad(id)
);

INSERT INTO fotos (ruta,ruta2, actividad_id) VALUES ('../imagenes/pira1.jpg','../imagenes/pira2.jpg', 1);

INSERT INTO fotos (ruta,ruta2, actividad_id) VALUES ('../imagenes/parac1.jpg','../imagenes/para2.jpg', 2);



//tabla palabras_prohibidas

CREATE TABLE palabras_prohibidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    palabra VARCHAR(255) NOT NULL
);

INSERT INTO palabras_prohibidas (palabra) VALUES
    ('puta'),
    ('tonto'),
    ('joder'),
    ('coño'),
    ('cabron'),
    ('fea'),
    ('inutil'),
    ('maricon');

//tabla comentarios

CREATE TABLE comentarios(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(255),
    comentario TEXT
);

mysql -h 127.0.0.1 -P 3306 -u jaime -p

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
