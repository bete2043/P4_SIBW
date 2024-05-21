<?php
require_once "/usr/local/lib/php/vendor/autoload.php";
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

require_once 'bd.php';

// Crear una conexión con la base de datos
$conn = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$query = isset($_GET['query']) ? '%' . $conn->real_escape_string($_GET['query']) . '%' : '';

if ($query) {
    $sql = "SELECT * FROM actividad WHERE actividad LIKE ? OR descripcion LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $query, $query);
} else {
    $sql = "SELECT * FROM actividad";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

$actividades = [];
while ($row = $result->fetch_assoc()) {
    $actividades[] = $row;
}

$stmt->close();
$conn->close();

echo $twig->render('actividades.html', ['actividades' => $actividades]);
?>
