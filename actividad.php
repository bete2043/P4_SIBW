<?php
require_once "/usr/local/lib/php/vendor/autoload.php";
require_once "bd.php";
ob_start(); 
include 'padre.php';
ob_end_clean(); 


$loader=new \Twig\Loader\FilesystemLoader('templates');
$twig=new \Twig\Environment($loader);

if (isset($_GET['ev'])) {
    $idEv = $_GET['ev'];
} else {
    $idEv = -1;
}

$evento = getEvento($idEv);
$fotos = fotos($idEv);
$comentarios = comentarios($idEv);

$palabrasProhibidas = palabras_prohibidas();

$pp_json=json_encode($palabrasProhibidas);

// Verifica si el usuario está en la sesión
$info = [
    'usuario_iniciado' => false,
    'username' => '',
    'rol' => '',
    'email' =>''
];

if (isset($_SESSION['username'])) {
    $info['usuario_iniciado'] = true;
    $info['username'] = htmlspecialchars($_SESSION['username']);
    $info['rol'] = htmlspecialchars($_SESSION['rol']);
    $user = getUser($_SESSION['username']);
    $email=$user['gmail'];
    $info['email'] = $email;
} 

$mysqli = new mysqli("lamp-mysql8", "jaime", "jaime", "SIBW");

    if($mysqli->connect_errno){
        echo ("Fallo al conectar: " . $mysqli->connect_error);
    }

    $error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $coment = $_POST['comentario'];
    $actividad_id = $idEv;

    $result = $mysqli->query("SELECT * FROM actividad WHERE id = '$actividad_id'");
    if ($result->num_rows == 0) {
        echo "Error: actividad_id no válido. '$actividad_id'";
        exit();
    }

    // Preparar y vincular
    $stmt = $mysqli->prepare("INSERT INTO comentarios (nombre, email, comentario, actividad_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nombre, $email, $coment, $actividad_id);



    // Ejecutar la declaración
    if ($stmt->execute()) {
        header("Location: actividad.php?ev=$actividad_id");
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $mysqli->close();
}

echo $twig->render('actividad.html',['evento' => $evento, 'fotos' => $fotos,
                    'comentarios' => $comentarios,'info' => $info, 'idEv' => $idEv]);
?>

<script>
    var palabrasProhibidas = <?php echo $pp_json; ?>; 
</script>