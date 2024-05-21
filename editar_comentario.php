<?php
session_start();
require_once "/usr/local/lib/php/vendor/autoload.php";
require_once 'bd.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);
$error_message = '';
$success_message = '';
$act_id='';
$comentario_previo='';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['act_id']) && isset($_POST['comentario_id']) ) {
        // Recoger la información del formulario de actividad.html
        $act_id = $_POST['act_id'];
        $comentario_id = $_POST['comentario_id'];

        $conn = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Recuperar el comentario previo
        $stmt_previo = $conn->prepare("SELECT comentario FROM comentarios WHERE id = ?");
        $stmt_previo->bind_param("i", $comentario_id);
        $stmt_previo->execute();
        $stmt_previo->bind_result($comentario_previo);
        $stmt_previo->fetch();
        $stmt_previo->close();

        // Escapar caracteres HTML en el comentario previo
        $comentario_previo = htmlspecialchars($comentario_previo);

        $conn->close();
    }
    if (isset($_POST['texto'])) {
        // Recoger la información del formulario de editar_comentario.html
        $texto = htmlspecialchars($_POST['texto']);
        $comentario_id = $_POST['comentario_id'];
        $act_id = $_POST['act_id'];

        // Verificar si el usuario está logueado y es un moderador, gestor o super
        if (!isset($_SESSION['username']) || ($_SESSION['rol'] != 'moderador' && $_SESSION['rol'] != 'gestor' && $_SESSION['rol'] != 'super')) {
            header("Location: portada.php");
            exit();
        }
        
        // Crear una conexión con la base de datos
        $conn = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }


        // Actualizar el comentario
        $stmt = $conn->prepare("UPDATE comentarios SET comentario = ?,  editado = 1 WHERE id = ?");
        $stmt->bind_param("si", $texto , $comentario_id);

        if ($stmt->execute()) {
            header("Location: actividad.php?ev=$act_id");
        } else {
            $error_message = "Error al actualizar el comentario: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}

echo $twig->render('editar_comentario.html', [
    'error_message' => $error_message,
    'success_message' => $success_message,
    'act_id' => $act_id,'comentario_id' => $comentario_id,
    'comentario_previo' => $comentario_previo
]);
?>
