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

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Preparar la consulta SQL para actualizar los datos del usuario, incluyendo el nombre de usuario
        $stmt = $conn->prepare("INSERT INTO 
        actividad (precio,fecha,actividad,descripcion ,material_necesario,enlace_consejos,portada) 
        VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("dssssss", $precio, $fecha, $actividad, $descripcion,$material,$consejos,$portada);

        $stmt1 = $conn->prepare("INSERT INTO fotos (ruta,ruta2,actividad_id) VALUES (?,?,?)");
        $stmt1->bind_param("ssi",$foto1,$foto2,$id);
        $stmt1->execute();
        // Ejecutar la consulta y verificar si se realizó correctamente
        
        if ($stmt->execute()) {
            // Redirigir al usuario a una página de éxito
            header("Location: portada.php");
        } else {
            $error_message = "Error al añadir la actividad: " . $stmt->error;
        }

        $stmt1->close();

    $stmt->close();
        

        
}
$conn->close();

echo $twig->render('gestor.html', ['error_message' => $error_message ?? '']);
?>
