<?php
session_start();
require_once 'bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$id = $_POST['comentario_id'];
$id1 = $_POST['act_id'];

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

    // Borrar el comentario
    $stmt = $conn->prepare("DELETE FROM comentarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: actividad.php?ev=$id1");
        exit();
    } else {
        echo  "Error al borrar el comentario: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

?>
