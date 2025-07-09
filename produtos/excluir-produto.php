<?php
require_once '../verificar-login.php';
require_once '../usuarios/verificar-nivel.php';
verificarNivel('admin'); // Somente administradores acessam essa página

// Conectar ao banco de dados
include_once "../usuarios/conexao.php";

// Verificar se o ID do produto foi passado na URL
if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];

    // Verificar se o produto está emprestado
    $sql_verificar_emprestimo = "SELECT COUNT(*) AS total FROM emprestimos_produtos WHERE id_produto = ?";
    
    // Usar a consulta preparada para evitar SQL Injection
    if ($stmt = mysqli_prepare($conexao, $sql_verificar_emprestimo)) {
        mysqli_stmt_bind_param($stmt, "i", $id_produto);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $total);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Se o produto estiver emprestado (total > 0), não permitir a exclusão
        if ($total > 0) {
            echo "<script>alert('Erro: Este produto está emprestado e não pode ser excluído.'); window.location.href = 'consulta-estoque.php';</script>";
            exit; // Interromper a execução do código para evitar a exclusão
        }
    } else {
        echo "<script>alert('Erro ao verificar o status do empréstimo.'); window.location.href = 'consulta-estoque.php';</script>";
        exit;
    }

    // Preparar a consulta para excluir o produto
    $sql_excluir_produto = "DELETE FROM produto WHERE id_produto = ?";

    // Usar a consulta preparada para evitar SQL Injection
    if ($stmt = mysqli_prepare($conexao, $sql_excluir_produto)) {
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
    echo "<script>alert('ID de produto não encontrado!'); window.location.href = 'consulta-estoque.php';</script>";
}

// Fechar a conexão com o banco de dados
mysqli_close($conexao);
?>
