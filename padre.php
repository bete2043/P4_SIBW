<?php

require_once "/usr/local/lib/php/vendor/autoload.php";
$loader=new \Twig\Loader\FilesystemLoader('templates');
$twig=new \Twig\Environment($loader);

session_start();

// Verifica si el usuario está en la sesión
$info = [
    'usuario_iniciado' => false,
    'username' => '',
    'rol' => ''
];

if (isset($_SESSION['username'])) {
    $info['usuario_iniciado'] = true;
    $info['username'] = htmlspecialchars($_SESSION['username']);
    $info['rol'] = htmlspecialchars($_SESSION['rol']);
}

// Renderizar la plantilla con Twig
echo $twig->render('padre.html', ['info' => $info]);
?>
