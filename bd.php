<?php
function checkLogin($nick,$pass){
    // Conexión a la base de datos
    $conn = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta para obtener el usuario por nombre de usuario
    $sql = "SELECT * FROM usuarios WHERE username = '$nick'";
    $result = $conn->query($sql);

    // Verificar si se encontró algún usuario
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verificar si la contraseña coincide
        if (password_verify($pass, $row['password'])) {
            // Cierre de la conexión
            $conn->close();
            return true;
        }
    }
    
    // Cierre de la conexión
    $conn->close();
    return false;

}

function getUser($username){
    
    $conn = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta para obtener el usuario por nombre de usuario
    $sql = "SELECT * FROM usuarios WHERE username = '$username'";
    $result = $conn->query($sql);

    // Verificar si se encontró algún usuario
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Cierre de la conexión
        $conn->close();
        return $row;
    }
    
    // Cierre de la conexión
    $conn->close();
    return 0;

}

function getEvento($idEv){
    $mysqli = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

    if($mysqli->connect_errno){
        echo ("Fallo al conectar: " . $mysqli->connect_error);
    }

    $res = $mysqli->query("SELECT fecha, actividad, precio, descripcion, material_necesario, enlace_consejos FROM actividad WHERE id=" . $idEv);


    $evento = array('fecha' => '0000/00/00', 'actividad' => 'nula', 'precio' => '0',
    'descripcion' => 'ASD','material_necesario' => 'DS','enlace_consejos' => 'AS');


    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        
        $evento = array(
            'fecha' => $row['fecha'],
            'actividad' => $row['actividad'],
            'precio' => $row['precio'],
            'descripcion' => $row['descripcion'],
            'material_necesario' => $row['material_necesario'],
            'enlace_consejos' => $row['enlace_consejos']
        );
    }

    $mysqli->close();

    return $evento;
}

function fotos($idEv){
    $mysqli = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

    if($mysqli->connect_errno){
        echo ("Fallo al conectar: " . $mysqli->connect_error);
    }

    $res = $mysqli->query("SELECT ruta,ruta2 FROM fotos WHERE actividad_id=" . $idEv);

    $fotos = array();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        
        $fotos = array(
            'ruta'=> $row['ruta'],
            'ruta2' => $row['ruta2']
        );
    }

    $mysqli->close();

    return $fotos;
}

function palabras_prohibidas(){
    $mysqli = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

    if($mysqli->connect_errno){
        echo ("Fallo al conectar: " . $mysqli->connect_error);
    }

    $result = $mysqli->query("SELECT palabra FROM palabras_prohibidas");

    $palabra_prohibida = array();
    while ($row = $result->fetch_assoc()) {
        $palabra_prohibida[] = $row['palabra'];
    }

    $mysqli->close();

    return $palabra_prohibida;
}

function comentarios($idEv) {
    $mysqli = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

    if ($mysqli->connect_errno) {
        echo ("Fallo al conectar: " . $mysqli->connect_error);
    }

    $query = "SELECT nombre, fecha, email, comentario , id , editado FROM comentarios WHERE actividad_id = " . $idEv;
    $result = $mysqli->query($query);

    $comentarios = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $comentarios[] = array(
                'nombre' => $row['nombre'],
                'fecha' => $row['fecha'],
                'email' => $row['email'],
                'comentario' => $row['comentario'],
                'id' => $row['id'],
                'editado' => $row['editado']
            );
        }
    } else {
        // If there are no comments, return a default comment
        $comentarios[] = array(
            'nombre' => 'xxx',
            'fecha' => 'xxx',
            'email' => 'xxx',
            'comentario' => 'No hay comentarios'
        );
    }

    $mysqli->close();

    return $comentarios;
}





?>
