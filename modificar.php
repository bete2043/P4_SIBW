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

$nombre = $_SESSION['username'];

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar y limpiar los datos del formulario
    $username =$_POST["username"];
    $password = $_POST["password"]; // Recuerda validar y hashear la contraseña antes de almacenarla
    $gmail = $_POST["gmail"];

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

    // Hashear la contraseña antes de almacenarla en la base de datos
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Preparar la consulta SQL para actualizar los datos del usuario, incluyendo el nombre de usuario
        $stmt = $conn->prepare("UPDATE usuarios SET username = ?, gmail = ?, password = ? WHERE username = ?");
        $stmt->bind_param("ssss", $username, $gmail, $hashed_password, $nombre);
    // Ejecutar la consulta y verificar si se realizó correctamente
    if ($stmt->execute()) {
        // Actualizar la sesión con el nuevo nombre de usuario si ha cambiado
        $_SESSION['username'] = $username;
        // Redirigir al usuario a una página de éxito
        $success_message = "Datos personales modificados correctamente.";
    } else {
        $error_message = "Error al modificar los datos del usuario: " . $stmt->error;
    }

    $stmt->close();
        

        
}
$conn->close();

echo $twig->render('modificar.html', ['nombre' => $nombre, 'error_message' => $error_message,
'success_message' => $success_message]);
?>
