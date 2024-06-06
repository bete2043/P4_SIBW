<?php
require_once "/usr/local/lib/php/vendor/autoload.php";
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

require_once 'bd.php';

// Crear una conexión con la base de datos
$conn = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar y limpiar los datos del formulario
    $nombre =$_POST["nombre"];

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT id FROM actividad WHERE actividad = ?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id = $row['id'];

            // Eliminar las fotos relacionadas
            $stmt1 = $conn->prepare("DELETE FROM fotos WHERE actividad_id = ?");
            $stmt1->bind_param("i", $id);
            $stmt1->execute();
            $stmt1->close();

            // Eliminar la actividad
            $stmt2 = $conn->prepare("DELETE FROM actividad WHERE actividad = ?");
            $stmt2->bind_param("s", $nombre);
            $stmt2->execute();
            $stmt2->close();

            // Redirigir al usuario a una página de éxito
            $success_message = "Se elimino la actividad con exito.";
        } else {
            $error_message = "No se encontró la actividad.";
        }

    $stmt->close();
        
}
$conn->close();

echo $twig->render('eliminar_actividad.html', ['error_message' => $error_message,
'success_message' => $success_message]);


?>