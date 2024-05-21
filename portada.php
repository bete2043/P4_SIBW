<?php

require_once "/usr/local/lib/php/vendor/autoload.php";
$loader=new \Twig\Loader\FilesystemLoader('templates');
$twig=new \Twig\Environment($loader);

ob_start(); 
include 'padre.php';
ob_end_clean(); 

$mysqli = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

    if($mysqli->connect_errno){
        echo ("Fallo al conectar: " . $mysqli->connect_error);
    }

    $sql = "SELECT * FROM actividad";
    $resultado = $mysqli->query($sql);

    $id = [];
    if ($resultado->num_rows > 0) {
        // Recorrer los resultados y almacenarlos en un arreglo
        while ($fila = $resultado->fetch_assoc()) {
            $id[] = $fila; 
        }
    }

// Verifica si el usuario está en la sesión
$info = [
    'usuario_iniciado' => false,
    'username' => '',
    'rol' => ''
];

if (isset($_SESSION['username'])) {
    $info['usuario_iniciado'] = true;
    $info['username'] = htmlspecialchars($_SESSION['username']);
    $info['rol'] = htmlspecialchars($_SESSION['rol']);
}


echo $twig->render('portada.html',['info' => $info, 'id' => $id]);
?>
