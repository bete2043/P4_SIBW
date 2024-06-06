<?php

require_once "/usr/local/lib/php/vendor/autoload.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$conn = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

    if($conn->connect_errno){
        echo ("Fallo al conectar: " . $conn->connect_error);
    }

$varsParaTwig = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_FILES['imagen'])  && isset($_POST['hashtag']) ){
        $errors= array();
        $file_name = $_FILES['imagen']['name'];
        $file_size = $_FILES['imagen']['size'];
        $file_tmp = $_FILES['imagen']['tmp_name'];
        $file_type = $_FILES['imagen']['type'];
        $file_name_parts = explode('.', $file_name);
        $file_ext = strtolower(end($file_name_parts));
        $hashtag = mysqli_real_escape_string($conn, $_POST['hashtag']);
        
        $extensions= array("jpeg","jpg","png");
        
        if (in_array($file_ext,$extensions) === false){
        $errors[] = "Extensión no permitida, elige una imagen JPEG o PNG.";
        }
        
        if ($file_size > 2097152){
        $errors[] = 'Tamaño del fichero demasiado grande';
        }
        
        if (empty($errors) == true) {
            $upload_path = "imagenesSubidas/" . $file_name;
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $sql = "INSERT INTO images (image_path, hashtag) VALUES ('$upload_path', '$hashtag')";
                if ($conn->query($sql) === TRUE) {
                    $varsParaTwig['imagen'] = $upload_path;
                } else {
                    $errors[] = "Error al guardar en la base de datos: " . $conn->error;
                }
            } else {
                $errors[] = "Hubo un error al subir tu archivo.";
            }
        }
        
        $varsParaTwig['imagen'] = "imagenesSubidas/" . $file_name;
        }
        
        if (sizeof($errors) > 0) {
        $varsParaTwig['errores'] = $errors;
        }
    }

echo $twig->render('fotos.html', $varsParaTwig);
?>