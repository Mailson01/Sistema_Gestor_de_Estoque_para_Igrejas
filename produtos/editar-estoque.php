<?php

require_once '../verificar-login.php';

include_once "../usuarios/conexao.php"; // Conexão com o banco de dados
require_once '../usuarios/verificar-nivel.php';
verificarNivel('admin'); // Somente administradores acessam essa página

// Verificar se o ID do produto foi passado corretamente pela URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_produto = (int)$_GET['id']; // Recupera o ID do produto
} else {
    die("ID de produto inválido ou não especificado!");
}

// Buscar o produto atual para mostrar na tela
$query = "
    SELECT p.produto, e.quantidade_total, p.foto 
    FROM produtos p
    INNER JOIN estoque e ON p.id_produto = e.id_produto
    WHERE p.id_produto = $id_produto
";

$result = mysqli_query($conexao, $query);
if (mysqli_num_rows($result) > 0) {
    $produto_data = mysqli_fetch_assoc($result);
} else {
    die("Produto não encontrado no banco de dados.");
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_nome = $_POST['produto_nome'];
    $quantidade_total = (int)$_POST['quantidade_total'];
    $foto = $produto_data['foto']; // Mantém a foto atual, caso não seja alterada

    // Verifica se foi enviada uma nova imagem
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_temp = $_FILES['foto']['tmp_name'];
        $foto_nome = $_FILES['foto']['name'];
        $foto_extensao = pathinfo($foto_nome, PATHINFO_EXTENSION);
        $foto_novo_nome = "uploads/" . uniqid() . "." . $foto_extensao;

        // Mover a nova imagem para a pasta 'uploads'
        if (move_uploaded_file($foto_temp, $foto_novo_nome)) {
            $foto = $foto_novo_nome; // Atualiza o caminho da foto no banco
        } else {
            echo "<p>Erro ao fazer upload da imagem.</p>";
        }
    }

    // Atualizar o nome do produto, quantidade e a imagem no banco de dados
    $update_query = "
        UPDATE produtos p
        JOIN estoque e ON p.id_produto = e.id_produto
        SET p.produto = '$produto_nome', e.quantidade_total = $quantidade_total, p.foto = '$foto'
        WHERE p.id_produto = $id_produto
    ";

    if (mysqli_query($conexao, $update_query)) {
        echo "<p>Produto atualizado com sucesso!</p>";
    } else {
        echo "<p>Erro ao atualizar o produto.</p>";
    }
}

mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/EDITAR.PROD_style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <style>
       
    </style>
</head>
<body>

<div class="container">
<img src="../assets/imgs/logo-horizontal.png" alt="Logo" class="logo"> <!-- Altere para o caminho correto da sua logo -->
    <h1>Editar Produto</h1>

    <form method="POST" class="form-container" enctype="multipart/form-data">
        <label for="produto_nome">Nome do Produto</label>
        <input type="text" id="produto_nome" name="produto_nome" value="<?php echo $produto_data['produto']; ?>" required>

        <label for="quantidade_total">Quantidade no Estoque</label>
        <input type="number" id="quantidade_total" name="quantidade_total" value="<?php echo $produto_data['quantidade_total']; ?>" required>

        <label for="foto">Imagem do Produto</label>
        <input type="file" id="foto" name="foto">
        <img src="<?php echo $produto_data['foto']; ?>" alt="Imagem do Produto">
        <a href="consulta-estoque.php" class="back-btn">Voltar para Consulta de Estoque</a>
        <button type="submit">Salvar Alterações</button>
    </form>
</div>

</body>
</html>
