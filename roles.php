<?php
session_start();
require_once "/usr/local/lib/php/vendor/autoload.php";
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

require_once 'bd.php';

// Lista de roles disponibles
$roles_disponibles = array("registrado", "moderador", "gestor", "super");

// Variables para mensajes de error y éxito
$error_message = '';
$success_message = '';

// Verificar si se ha enviado el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["username"]);
    $new_role = htmlspecialchars($_POST["new_role"]);

    // Validar los datos del formulario
    if (empty($username) || empty($new_role)) {
        $error_message = "Por favor, completa todos los campos.";
    } elseif (!in_array($new_role, $roles_disponibles)) {
        $error_message = "Rol no válido.";
    } else {
        // Crear una conexión con la base de datos
        $conn = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Verificar si el usuario existe
        $stmt = $conn->prepare("SELECT rol FROM usuarios WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Obtener el rol actual del usuario
            $user = $result->fetch_assoc();
            $current_role = $user['rol'];

            if ($current_role == 'super' && $new_role != 'super') {
                // Verificar si es el único superusuario
                $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM usuarios WHERE rol = 'super'");
                $stmt->execute();
                $result = $stmt->get_result();
                $count = $result->fetch_assoc()['count'];

                if ($count <= 1) {
                    $error_message = "No se puede eliminar el único superusuario.";
                } else {
                    // Actualizar el rol del usuario
                    $stmt = $conn->prepare("UPDATE usuarios SET rol = ? WHERE username = ?");
                    $stmt->bind_param("ss", $new_role, $username);

                    if ($stmt->execute()) {
                        $success_message = "El rol del usuario se ha actualizado correctamente.";
                    } else {
                        $error_message = "Error al actualizar el rol del usuario: " . $stmt->error;
                    }
                }
            } else {
                // Actualizar el rol del usuario
                $stmt = $conn->prepare("UPDATE usuarios SET rol = ? WHERE username = ?");
                $stmt->bind_param("ss", $new_role, $username);

                if ($stmt->execute()) {
                    $success_message = "El rol del usuario se ha actualizado correctamente.";
                } else {
                    $error_message = "Error al actualizar el rol del usuario: " . $stmt->error;
                }
            }
        } else {
            $error_message = "El usuario no existe.";
        }

        $stmt->close();
        $conn->close();
    }
}

// Consultar usuarios para mostrar en el formulario
$conn = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$result = $conn->query("SELECT username, gmail, rol FROM usuarios");
$usuarios = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();

echo $twig->render('roles.html', [
    'usuarios' => $usuarios,
    'roles_disponibles' => $roles_disponibles,
    'error_message' => $error_message,
    'success_message' => $success_message
]);
?>
