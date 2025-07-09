<?php
session_start();


// Obtém o nome do arquivo atual
$arquivo = basename($_SERVER['SCRIPT_NAME']);

// Define a raiz do diretório para redirecionamento
define('DIR_ROOT', '/');

// Se o usuário já estiver logado e estiver na página de login, redireciona para a tela inicial
if (isset($_SESSION['id']) && ($arquivo == 'login.php' || $arquivo == 'verifica-login.php')) {
    header('Location:  telainicial.php');
    exit;
}

// Se o usuário não estiver logado e tentar acessar páginas protegidas, redireciona para o login
if (!isset($_SESSION['id']) && $arquivo != 'login.php' && $arquivo != 'verifica-login.php') {
    header('Location: login.php');
    exit;


}


?>
