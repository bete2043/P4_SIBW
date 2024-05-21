<?php
require_once "/usr/local/lib/php/vendor/autoload.php";
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

require_once 'bd.php';

ob_start(); 
include 'login.php';
ob_end_clean(); 

// Crear una conexión con la base de datos
$conn = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar y limpiar los datos del formulario
    $precio =$_POST["precio"];
    $fecha =$_POST["fecha"];
    $actividad =$_POST["actividad"];
    $descripcion =$_POST["descripcion"];
    $material =$_POST["material_necesario"];
    $consejos =$_POST["enlace_consejos"];
    $portada =$_POST["portada"];

    $foto1 =$_POST["foto1"];
    $foto2 =$_POST["foto2"];
    $id =$_POST["id"];

    $stmt = $conn->prepare("SELECT id FROM actividad WHERE actividad = ?");
    $stmt->bind_param("s", $actividad);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $actividad_id = $row['id'];

        // Actualizar la tabla actividad
        $stmt = $conn->prepare("UPDATE actividad SET precio = ?, fecha = ?, descripcion = ?, material_necesario = ?, enlace_consejos = ?, portada = ? WHERE actividad = ?");
        $stmt->bind_param("dssssss", $precio, $fecha, $descripcion, $material, $consejos, $portada, $actividad);
        $stmt->execute();

        // Actualizar la tabla fotos
        $stmt1 = $conn->prepare("UPDATE fotos SET ruta = ?, ruta2 = ? WHERE actividad_id = ?");
        $stmt1->bind_param("ssi", $foto1, $foto2, $actividad_id);
        $stmt1->execute();

        // Redirigir al usuario a una página de éxito
        header("Location: portada.php");
        exit;
    } else {
        $error_message = "No se encontró la actividad.";
    }

    $stmt->close();
    $stmt1->close();
}
        
$conn->close();

echo $twig->render('editar_actividad.html', ['error_message' => $error_message ?? '']);
?>
