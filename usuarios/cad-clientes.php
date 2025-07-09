<?php
require_once __DIR__.'/../verificar-login.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/CAD.CLIENTES_style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <style>
        
    </style>
</head>
<body>

    <form method="POST" action="">
    <img src="../assets/imgs/logo-horizontal.png" alt="Logo" class="logo"> <!-- Altere para o caminho correto da sua logo -->
    <h1>Cadastro de Cliente</h1>
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone" required>
        <div class="botoes">
        <button type="submit">Cadastrar</button>

        <!-- Botões dentro do formulário -->
        <a href="../telainicial.php" class="back-btn">Voltar</a>
        <a href="consulta-clientes.php" class="back-btn">Consultar Clientes Cadastrados</a>
        </div>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Conectar ao banco de dados
        include_once "./conexao.php";

        // Captura os dados do formulário
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];

        // Verifica se os campos não estão vazios
        if (empty($nome) || empty($telefone)) {
            echo "<div class='resultado erro'>Por favor, preencha todos os campos.</div>";
        } else {
            // Verifica se já existe um cliente com o mesmo nome
            $sql_check = "SELECT * FROM clientes WHERE nome = '$nome'";
            $result = mysqli_query($conexao, $sql_check);

            if (mysqli_num_rows($result) > 0) {
                echo "<div class='resultado erro'>Já existe um cliente com o nome '$nome'.</div>";
            } else {
                // Insere os dados na tabela 'clientes'
                $sql = "INSERT INTO clientes (nome, telefone) VALUES ('$nome', '$telefone')";

                if (mysqli_query($conexao, $sql)) {
                    echo "<div class='resultado'>Cliente cadastrado com sucesso!</div>";
                } else {
                    echo "<div class='resultado erro'>Erro ao cadastrar cliente: " . mysqli_error($conexao) . "</div>";
                }
            }
        }

        // Fechar a conexão
        mysqli_close($conexao);
    }
    ?>

</body>
</html>
