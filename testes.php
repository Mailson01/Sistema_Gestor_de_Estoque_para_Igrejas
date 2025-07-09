<?php

require_once __DIR__.'/../verificar-login.php';

// Conectar ao banco de dados
include_once "../usuarios/conexao.php";

// Verificar se o ID do produto foi passado na URL
if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];

    // Preparar a consulta para excluir o produto
    $sql = "DELETE FROM produtos WHERE id_produtos = ?";
    
    // Usar a consulta preparada para evitar SQL Injection
    if ($stmt = mysqli_prepare($conexao, $sql)) {
        // Associar o parâmetro (id_produto) ao marcador de parâmetro
        mysqli_stmt_bind_param($stmt, "i", $id_produto);

        // Executar a consulta
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Produto excluído com sucesso!'); window.location.href = 'consulta-estoque.php';</script>";
        } else {
            echo "<script>alert('Erro ao excluir produto. Tente novamente.'); window.location.href = 'consulta-estoque.php';</script>";
        }

        // Fechar a declaração
        mysqli_stmt_close($stmt);
    }
} else {
    echo "<script>alert('ID de produto não encontrado!'); window.location.href = 'consultaestoque.php';</script>";
}

// Fechar a conexão com o banco de dados
mysqli_close($conexao);
?>