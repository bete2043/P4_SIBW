<?php

require_once "/usr/local/lib/php/vendor/autoload.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$conn = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

    if($conn->connect_errno){
        echo ("Fallo al conectar: " . $conn->connect_error);
    }

$varsParaTwig = [];
$imagenes = [];

$sql = "SELECT * FROM images";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $imagenes[] = $row;
    }
}

$varsParaTwig['imagenes'] = $imagenes;

$conn->close();

echo $twig->render('galeria.html', $varsParaTwig);
?>
