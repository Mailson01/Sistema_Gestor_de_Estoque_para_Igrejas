<?php
include_once "../usuarios/conexao.php";

if (isset($_POST['loginn'], $_POST['senha'], $_POST['nivel'])) {
    $loginn = $_POST['loginn'];
    $senha = $_POST['senha'];
    $nivel = $_POST['nivel'];

    // Verifica se login já existe
    $verifica = "SELECT * FROM credenciais WHERE loginn = '$loginn'";
    $res = mysqli_query($conexao, $verifica);

    if (mysqli_num_rows($res) > 0) {
        echo "<script>
            alert('Esse login já está cadastrado!');
            window.location.href = '../usuarios/cad-user.php';
        </script>";
        exit;
    }

    // Insere novo usuário
    $sql = "INSERT INTO credenciais (loginn, senha, nivel) 
            VALUES ('$loginn', '$senha', '$nivel')";

    if (mysqli_query($conexao, $sql)) {
        echo "<script>
            alert('Usuário cadastrado com sucesso!');
            window.location.href = '../telainicial.php';
        </script>";
    } else {
        echo "<script>
            alert('Erro ao cadastrar usuário: " . mysqli_error($conexao) . "');
            window.location.href = 'cadastro-usuario.php';
        </script>";
    }
} else {
    echo "<script>
        alert('Preencha todos os campos!');
        window.location.href = 'cadastro-usuario.php';
    </script>";
}
?>
