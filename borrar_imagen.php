<?php



// Obtener el ID de la imagen a borrar
if(isset($_GET['id'])) {
    $imagen_id = $_GET['id'];
    
    $conn = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

    if($conn->connect_errno){
        echo ("Fallo al conectar: " . $conn->connect_error);
    }
    $sql_select = "SELECT image_path FROM images WHERE id=$imagen_id";
    $result = $conn->query($sql_select);

    if ($result->num_rows > 0) {
        // Borrar la imagen de la base de datos y del sistema de archivos
        $row = $result->fetch_assoc();
        $image_path = $row['image_path'];
        unlink($image_path); // Borrar del sistema de archivos
        $sql_delete = "DELETE FROM images WHERE id=$imagen_id";
        $conn->query($sql_delete);
    }

    $conn->close();
}

// Redireccionar a la galería
header("Location: galeria.php");
exit();
?>
