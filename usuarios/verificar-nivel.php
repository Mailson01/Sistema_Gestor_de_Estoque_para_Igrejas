<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}


// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Função para verificar o nível
function verificarNivel($nivelPermitido) {
    if ($_SESSION['nivel'] !== $nivelPermitido) {
        echo "<script>
            alert('Acesso negado! Esta área é restrita a usuários do tipo: $nivelPermitido');
            window.location.href = '../telainicial.php';
        </script>";
        exit();
    }
}
?>

