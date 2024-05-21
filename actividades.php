<?php
session_start();
require_once "/usr/local/lib/php/vendor/autoload.php";
require_once 'bd.php';
$loader=new \Twig\Loader\FilesystemLoader('templates');
$twig=new \Twig\Environment($loader);

$conn = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT * FROM actividad";
$resultado = $conn->query($sql);

$actividades = [];
if ($resultado->num_rows > 0) {
    // Recorrer los resultados y almacenarlos en un arreglo
    while ($fila = $resultado->fetch_assoc()) {
        $actividades[] = $fila;
    }
}

$conn->close();

// Pasar los comentarios a la plantilla Twig
echo $twig->render('actividades.html', ['actividades' => $actividades]);
?>
