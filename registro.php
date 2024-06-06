<?php
$error_message = "";

require_once "/usr/local/lib/php/vendor/autoload.php";
$loader=new \Twig\Loader\FilesystemLoader('templates');
$twig=new \Twig\Environment($loader);

session_start();

$error_message = '';
$success_message = '';

// Verificar si se ha enviado el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar y limpiar los datos del formulario
    $username = htmlspecialchars($_POST["username"]);
    $password = $_POST["password"]; // Recuerda validar y hashear la contraseña antes de almacenarla
    $gmail = htmlspecialchars($_POST["gmail"]); 

    // Validar los datos del formulario (puedes agregar más validaciones según tus requisitos)
    if (empty($username) || empty($password) || empty($gmail)) {
        $error_message = "Por favor, completa todos los campos.";
    } else {

        $conn = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Verificar si el nombre de usuario ya está en uso
        $sql = "SELECT * FROM usuarios WHERE username='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error_message = "El nombre de usuario ya está en uso. Por favor, elige otro.";
        } else {
            // Insertar los datos del nuevo usuario en la base de datos
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hashear la contraseña antes de almacenarla en la base de datos

            $sql = "INSERT INTO usuarios (username, password, gmail) VALUES ('$username', '$hashed_password', '$gmail')";

            if ($conn->query($sql) === TRUE) {
                // Redirigir al usuario a una página de éxito
                $success_message = "Usuario registrado correctamente: " . $conn->error;
                exit();
            } else {
                $error_message = "Error al registrar el usuario: " . $conn->error;
            }
        }

        // Cerrar la conexión a la base de datos
        $conn->close();

    }

}

echo $twig->render('registro.html',['error_message' => $error_message,
'success_message' => $success_message]);

?>