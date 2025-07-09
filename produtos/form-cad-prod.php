<?php
// Inclui o arquivo de conexão com o banco de dados
include_once "../usuarios/conexao.php";

require_once __DIR__.'/../verificar-login.php';


// Inicializa a mensagem de resposta
$msg = '';
$msg_type = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados enviados pelo formulário
    $produto = $_POST['produto'] ?? '';
    $quantidade = $_POST['quantidade'] ?? '';
    $foto = $_FILES['foto'] ?? null;

    // Verifica se todos os campos foram preenchidos
    if (empty($produto) || empty($quantidade) || empty($foto['name'])) {
        $msg = "Por favor, preencha todos os campos e envie uma foto.";
        $msg_type = 'error';
    } else {
        // Verifica se o produto já existe
        $sql_verifica = "SELECT * FROM produtos WHERE produto = '$produto'";
        $resultado = mysqli_query($conexao, $sql_verifica);

        if (mysqli_num_rows($resultado) > 0) {
            $msg = "Erro: O produto já está cadastrado.";
            $msg_type = 'error';
        } else {
            // Processa o upload da foto
            $foto_nome = $foto['name'];
            $foto_tmp = $foto['tmp_name'];
            $foto_destino = "uploads/" . $foto_nome;

            if (move_uploaded_file($foto_tmp, $foto_destino)) {
                // Insere o produto na tabela 'produtos'
                $sql_produto = "INSERT INTO produtos (produto, quantidade, foto) VALUES ('$produto', '$quantidade', '$foto_destino')";
                if (mysqli_query($conexao, $sql_produto)) {
                    // Obtém o ID do produto inserido
                    $produto_id = mysqli_insert_id($conexao);

                    // Insere a quantidade inicial na tabela 'estoque'
                    $sql_estoque = "INSERT INTO estoque (id_produto, quantidade_total) VALUES ('$produto_id', '$quantidade')";
                    if (mysqli_query($conexao, $sql_estoque)) {
                        $msg = "Produto cadastrados com sucesso!";
                        $msg_type = 'success';
                    } else {
                        $msg = "Erro ao cadastrar o estoque: " . mysqli_error($conexao);
                        $msg_type = 'error';
                    }
                } else {
                    $msg = "Erro ao cadastrar o produto: " . mysqli_error($conexao);
                    $msg_type = 'error';
                }
            } else {
                $msg = "Erro ao fazer upload da foto.";
                $msg_type = 'error';
            }
        }
    }
}

// Fecha a conexão com o banco de dados
mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/CADPROD_style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
    <style>
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo da página -->
        <div class="logo">
            <img src="../assets/imgs/logo-horizontal.png" alt="Logo da Empresa">
        </div>
        
        <h1>Cadastrar Produto</h1>
        
        <?php if (!empty($msg)): ?>
            <div class="msg <?= $msg_type; ?>">
                <?= $msg; ?>
            </div>
        <?php endif; ?>
        
        <div class="form-content">
            <!-- Área do formulário -->
            <div class="form-area">
                <form method="POST" enctype="multipart/form-data">
                    <label for="produto">Nome do Produto:</label>
                    <input type="text" id="produto" name="produto" required>

                    <label for="quantidade">Quantidade :</label>
                    <input type="number" id="quantidade" name="quantidade" min="1" required>

                    <label for="foto">Foto do Produto:</label>
                    <input type="file" id="foto" name="foto" accept="image/*" required>

                    <button type="submit" class="btn-cadastrar">Cadastrar</button>
                    <a href="../telainicial.php"><button type="button" class="btn-voltar">Voltar para Tela Inicial</button></a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

