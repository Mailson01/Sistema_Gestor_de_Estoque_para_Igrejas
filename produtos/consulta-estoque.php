<?php
require_once '../verificar-login.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/CONULTA_ESTOQUE_style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Produto no Estoque</title>
    <style>
    
</style>

    </style>
</head>
<body>
    <div class="container">
    <img src="../assets/imgs/logo-horizontal.png" alt="Logo" class="logo"> <!-- Altere para o caminho correto da sua logo -->
        <h1>Consulta de Produtos no Estoque</h1>
        <form method="POST" class="search-form">
            <input type="text" name="produto" id="produto" placeholder="Consultar Produto" value="">
            <button type="submit">🔍</button>
        </form>

        <?php
        include_once "../usuarios/conexao.php";

        $filtro = "";
        if (isset($_POST['produto']) && !empty($_POST['produto'])) {
            $produto = $_POST['produto'];
            $filtro = "WHERE p.produto LIKE '%$produto%'";
        }

        // Consulta ajustada para somar as quantidades no estoque
        $consulta = "
        SELECT p.produto, p.foto, IFNULL(SUM(e.quantidade_total), 0) AS quantidade_total, p.id_produto
        FROM produtos p
        LEFT JOIN estoque e ON p.id_produto = e.id_produto
        $filtro
        GROUP BY p.produto, p.foto, p.id_produto
    ";

    $result = mysqli_query($conexao, $consulta);

    if (!$result) {
        die("Erro na consulta SQL: " . mysqli_error($conexao));
    }
    
    if (mysqli_num_rows($result) > 0) {
        echo "<table class='product-table'>";
        echo "<thead><tr><th>Foto</th><th>Produto</th><th>Quantidade no Estoque</th><th>Ações</th></tr></thead><tbody>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td><img src='" . htmlspecialchars($row['foto']) . "' alt='Foto do Produto'></td>";
            echo "<td>" . htmlspecialchars($row['produto']) . "</td>";
            echo "<td>" . htmlspecialchars($row['quantidade_total']) . "</td>";
            echo "<td>";
            echo "<a href='editar-estoque.php?id=" . $row['id_produto'] . "' class='btn-action edit'>Editar</a>";
            echo "<a href='excluir-produto.php?id=" . $row['id_produto'] . "' class='btn-action delete'>Excluir</a>";
            
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p class='erro'>Nenhum produto encontrado no estoque.</p>";
    }
    
    mysqli_close($conexao);
    
        ?>

        <!-- Botões de navegação -->
        <div class="container">
    <!-- Conteúdo principal do container -->

    <!-- Botões -->
    <div class="button-container">
        <a href="javascript:history.back()" class="btn">Voltar</a>
        <a href="../telainicial.php" class="btn">Tela Inicial</a>
        </div>
        </div>
    </div>
</body>
</html>
