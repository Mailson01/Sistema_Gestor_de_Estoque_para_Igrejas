<?php

require_once __DIR__.'/../verificar-login.php';
require_once '../usuarios/verificar-nivel.php';
verificarNivel('admin'); // Somente administradores acessam essa página

include_once "../usuarios/conexao.php";
// Conexão com o banco de dados
$conn = mysqli_connect('localhost', 'root', '', 'igreja');
if (!$conn) {
    die('Erro de conexão: ' . mysqli_connect_error());
}

// Obter clientes e produtos para preencher as opções no formulário
$sqlClientes = "SELECT id_cliente, nome FROM clientes";
$resultClientes = mysqli_query($conn, $sqlClientes);

$sqlProdutos = "SELECT id_produto, produto FROM produtos";
$resultProdutos = mysqli_query($conn, $sqlProdutos);

// Criar um array de produtos para facilitar o uso no JavaScript
$produtos = [];
while ($produto = mysqli_fetch_assoc($resultProdutos)) {
    $produtos[] = $produto;
}

// Variáveis de mensagem
$mensagem = '';
$mensagemClass = ''; // Para definir a classe de estilo (sucesso ou erro)

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coletar dados do formulário
    $id_cliente = $_POST['id_cliente'];
    $produtosSelecionados = $_POST['id_produto'];
    $quantidades = $_POST['quantidade'];

    // Verificar se o cliente foi selecionado e produtos foram escolhidos
    if ($id_cliente && !empty($produtosSelecionados)) {
        // Iniciar a transação para garantir consistência
        mysqli_begin_transaction($conn);

        try {
            // Inserir o empréstimo principal
            $sqlEmprestimo = "INSERT INTO emprestimos (id_cliente, data_emprestimo) VALUES ('$id_cliente', NOW())";
            $resultEmprestimo = mysqli_query($conn, $sqlEmprestimo);

            if (!$resultEmprestimo) {
                throw new Exception("Erro ao cadastrar o empréstimo principal: " . mysqli_error($conn));
            }

            // Obter o ID do empréstimo inserido
            $id_emprestimo = mysqli_insert_id($conn);

            // Inserir os produtos no empréstimo e atualizar o estoque
            foreach ($produtosSelecionados as $index => $id_produto) {
                $quantidade = $quantidades[$index];

                // Inserir o produto no empréstimo
                $sqlItem = "INSERT INTO emprestimos_produtos (id_emprestimo, id_produto, quantidade) 
                            VALUES ('$id_emprestimo', '$id_produto', '$quantidade')";
                $resultItem = mysqli_query($conn, $sqlItem);

                if (!$resultItem) {
                    throw new Exception("Erro ao cadastrar produto no empréstimo: " . mysqli_error($conn));
                }

                // Atualizar o estoque, subtraindo a quantidade emprestada
                $sqlEstoque = "UPDATE estoque 
                               SET quantidade_total = quantidade_total - $quantidade 
                               WHERE id_produto = '$id_produto' AND quantidade_total >= $quantidade";
                $resultEstoque = mysqli_query($conn, $sqlEstoque);

                if (!$resultEstoque) {
                    throw new Exception("Erro ao atualizar o estoque: " . mysqli_error($conn));
                }

                // Verificar se o estoque foi suficiente
                if (mysqli_affected_rows($conn) == 0) {
                    throw new Exception("Estoque insuficiente para o produto $id_produto.");
                }
            }

            // Commit da transação
            mysqli_commit($conn);

            // Mensagem de sucesso
            $mensagem = "Empréstimo cadastrado com sucesso!";
            $mensagemClass = 'success';
        } catch (Exception $e) {
            // Rollback da transação em caso de erro
            mysqli_rollback($conn);

            // Mensagem de erro
            $mensagem = "Erro ao cadastrar o empréstimo: " . $e->getMessage();
            $mensagemClass = 'alert';
        }
    } else {
        $mensagem = "Por favor, preencha todos os campos corretamente.";
        $mensagemClass = 'alert';
    }
}

// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/CAD.EMPRES_style.css">
    <title>Cadastrar Empréstimo</title>
    <img src="../assets/imgs/logo-horizontal.png" alt="logo" class="header-img">

    <style>
      
    </style>
</head>
<body>
    <header>
    </header>

    <div class="form-container">
    
        <form action="" method="post">
        <h1>Cadastro de Empréstimo de Produto</h1>
            <!-- Exibe a mensagem de sucesso ou erro -->
            <?php if ($mensagem) { ?>
                <p class="<?php echo $mensagemClass; ?>"><?php echo $mensagem; ?></p>
            <?php } ?>
            <div id="produtos">
            <div class="produto-item">
            <label for="id_cliente">Cliente:</label>
            <select name="id_cliente" required>
                <option value="">Selecione um Cliente</option>
                <?php while ($cliente = mysqli_fetch_assoc($resultClientes)) { ?>
                    <option value="<?php echo $cliente['id_cliente']; ?>"><?php echo $cliente['nome']; ?></option>
                <?php } ?>
            </select>

            
                    <label for="id_produto">Produto:</label>
                    <select name="id_produto[]" required>
                        <option value="">Selecione um Produto</option>
                        <?php foreach ($produtos as $produto) { ?>
                            <option value="<?php echo $produto['id_produto']; ?>"><?php echo $produto['produto']; ?></option>
                        <?php } ?>
                    </select>
                    
                    <div class="quant">
                    <label for="quantidade">Quantidade:</label>
                    <input type="number" name="quantidade[]" min="1" required>
                    </div>
                    </div>
                    </div>
            <div class="botoes">
            <button type="button" id="addProduto">Adicionar Outro Produto</button><br><br>

            <button type="submit">Cadastrar Empréstimo</button>
            <button type="button" id="voltar" onclick="window.location.href='../telainicial.php';">Voltar</button>
            </div>
        </form>
    </div>
    <script>
    document.getElementById('addProduto').addEventListener('click', function () {
        const container = document.getElementById('produtos');
        const item = container.querySelector('.produto-item');

        // Clona o item de produto
        const novoItem = item.cloneNode(true);

        // Limpa os valores dos campos clonados
        novoItem.querySelector('select[name="id_produto[]"]').selectedIndex = 0;
        novoItem.querySelector('input[name="quantidade[]"]').value = '';

        // Adiciona o novo item ao container
        container.appendChild(novoItem);
    });
</script>

</body>
</html>
