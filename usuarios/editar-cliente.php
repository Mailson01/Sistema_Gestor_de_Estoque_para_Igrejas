<?php

require_once __DIR__.'/../verificar-login.php';

// Conexão com o banco de dados
include_once "conexao.php";
require_once '../usuarios/verificar-nivel.php';
verificarNivel('admin'); // Somente administradores acessam essa página


// Verificar se o id_cliente foi passado via URL
if (isset($_GET['id_cliente'])) {
    $id_cliente = $_GET['id_cliente'];

    // Consultar o cliente pelo ID
    $sql = "SELECT * FROM clientes WHERE id_cliente = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o cliente existe
    if ($result->num_rows > 0) {
        $cliente = $result->fetch_assoc();
    } else {
        die("Cliente não encontrado.");
    }
} else {
    die("ID do cliente não informado.");
}

// Verificar se o formulário foi submetido para atualizar os dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
   
    // Atualizar o cliente no banco de dados
    $update_sql = "UPDATE clientes SET nome = ?, telefone = ?, endereco = ? WHERE id_cliente = ?";
    $update_stmt = $conexao->prepare($update_sql);
    $update_stmt->bind_param("sssi", $nome, $telefone, $endereco, $id_cliente);

    if ($update_stmt->execute()) {
        echo "<div class='resultado'><script>alert('Dados atualizados com sucesso!')</script></div>"; 
    } else {
        echo "<div class='resultado erro'>Erro ao atualizar os dados: " . $conexao->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/EDIT_CLIENT_style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <style>
    </style>
</head>
<body>
<div class="form">
    <h1>Editar Cliente</h1>

    <!-- Formulário de Edição -->
    <form method="POST" action="">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone" value="<?php echo htmlspecialchars($cliente['telefone']); ?>" required>

        <button type="submit">Atualizar</button>
    </form>
   
    <!-- Botão de Voltar -->
    <a href="consulta-clientes.php" class="back-btn">Voltar para Consultar Clientes</a>
    </div>
</body>
</html>
