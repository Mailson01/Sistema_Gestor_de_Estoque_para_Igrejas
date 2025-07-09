<?php
include_once "./conexao.php";

session_start();

// Se o usuário já estiver logado, redireciona para a tela inicial
if (isset($_SESSION['id'])) {
    header('Location: telainicial.php');
    exit;
}

if (isset($_POST['loginn']) && isset($_POST['senha'])) {
    $loginn = $_POST['loginn'];
    $senha = $_POST['senha'];

    // Valida as credenciais no banco de dados
    $veri = "SELECT * FROM credenciais WHERE loginn = '$loginn' AND senha = '$senha'";
    $resultado = mysqli_query($conexao, $veri);

    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    if (mysqli_num_rows($resultado) > 0) {
        $dados = mysqli_fetch_assoc($resultado); // <- Aqui pegamos os dados da linha retornada

        // Armazena dados na sessão
        $_SESSION['id'] = $dados['id'];            // ou outro identificador que você usa
        $_SESSION['loginn'] = $dados['loginn'];
        $_SESSION['nivel'] = $dados['nivel'];      // <- Aqui armazenamos o nível do usuário

        header('Location: /igrejav3/telainicial.php');
        exit;
    } else {
        echo "<script>
            alert('Login ou Senha Incorreto');
            window.location.href = '../login.php';
        </script>";
        exit();
    }
}
?>
