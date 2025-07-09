<?php
require_once __DIR__.'/../verificar-login.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/CONS_CLIENTES_style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Clientes</title>
    <style> 
    </style>
</head>
<body>
<img src="../assets/imgs/logo-horizontal.png" alt="Logo" class="logo"> <!-- Altere para o caminho correto da sua logo -->
    <h1>Consultar Clientes</h1>
    <?php
        // Conectar ao banco de dados
        include_once "./conexao.php";
        require_once '../usuarios/verificar-nivel.php';
        // Verificar se foi passado um ID para exclusão e se é um número válido
        if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
            $delete_id = (int) $_GET['delete_id']; // Converte para inteiro
           
            // Excluir o cliente do banco de dados
            $sql_delete = "DELETE FROM clientes WHERE id_cliente = $delete_id";
            if (mysqli_query($conexao, $sql_delete)) {
                echo "<div class='resultado sucesso'>Cliente excluído com sucesso!</div>";
            } else {
                echo "<div class='resultado erro'>Erro ao excluir cliente: " . mysqli_error($conexao) . "</div>";
            }
        }

        // Consulta para obter todos os clientes
        $sql = "SELECT * FROM clientes";
        $result = mysqli_query($conexao, $sql);

        // Verificar se a consulta foi bem-sucedida
     if (!$result) {
    echo "<div class='resultado erro'>Erro na consulta: " . mysqli_error($conexao) . "</div>";
} else {
    if (mysqli_num_rows($result) > 0) {
        echo "<table>
                <tr>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            if (isset($row['id_cliente'])) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                echo "<td>" . htmlspecialchars($row['telefone']) . "</td>";
                echo "<td>";

                if (isset($_SESSION['nivel']) && $_SESSION['nivel'] == 'admin') {
                    echo "<a href='editar-cliente.php?id_cliente=" . $row['id_cliente'] . "' class='edit-btn'>Editar</a> ";
                    echo "<a href='consulta-clientes.php?delete_id=" . $row['id_cliente'] . "' class='delete-btn' onclick='return confirm(\"Apenas admins podem excluir Clientes!\")'>Excluir</a>";
                }

                echo "</td>";
                echo "</tr>";
            }
        }

        echo "</table>"; 
    } else {
        echo "<div class='resultado erro'>Nenhum cliente encontrado.</div>";
    }
}

                  // Fechar a conexão
        mysqli_close($conexao);
    ?>

    <a href="cad-clientes.php" class="back-btn">Voltar</a>
    
</body>
</html>