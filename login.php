<?php
require_once "/usr/local/lib/php/vendor/autoload.php";
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

require_once 'bd.php';

session_start();
$error_message ='';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nick = $_POST['username'];
    $pass = $_POST['password'];

    $usuario = checkLogin($nick, $pass);
    if ($usuario) {
        $user = getUser($nick);
        $_SESSION['username'] = $user['username'];
        $_SESSION['rol'] = $user['rol'];

        header("Location: portada.php");
        exit();
    } else {
        $error_message = "Nombre de usuario o contraseña incorrectos";
    }
}

echo $twig->render('login.html', ['error_message' =>$error_message]);
?>
