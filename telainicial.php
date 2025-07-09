<?php
require_once './verificar-login.php';
require_once './usuarios/verificar-nivel.php';



// Simulando usuário logado
$usuarioLogado = $_SESSION['loginn'] ?? 'Visitante';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gestão da Igreja</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    
    <style>
       
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
       <div class="logo">Sistema da Igreja</div>
        <div class="user-info">
                     <?php
    if ($_SESSION['nivel'] === 'usuario'){
        echo '<h5> Algumas opções  de ADM estão desativadas para o seu perfil!</h5>';
    }else{
            echo '<h3>Acesso ADM, todas as funções liberadas!!</h3>';
    }
    ?>
            <span>Olá, <strong><?= htmlspecialchars($usuarioLogado) ?></strong></span>
            <form action="logout.php" method="post">
                <button type="submit"><i data-feather="log-out"></i> Sair</button>
            </form>
        </div>
    </div>

    <!-- Conteúdo principal -->
    <div class="container">
        <div class="content">
            <img src="./assets/imgs/logo-horizontal.png" alt="Logo da Igreja" class="logo-img">
            <h1 class="title">Sistema de Gestão Paroquial - Paróquia São Francisco de Assis. Seja bem-vindo(a)!</h1>
            <div class="grid-buttons">
                <form action="./produtos/form-cad-prod.php">
                    <button type="submit" class="action"><i data-feather="plus-square"></i> Cadastrar Produtos</button>
                </form>
                <form action="./produtos/consulta-estoque.php" method="post">
                    <button type="submit" class="action"><i data-feather="package"></i> Consultar Estoque</button>
                </form>
                <form action="./usuarios/cad-clientes.php">
                    <button type="submit" class="action"><i data-feather="users"></i> Cadastrar Clientes</button>
                </form>
                <form action="./produtos/cad-emprestimos.php">
                    <button type="submit" class="action"><i data-feather="arrow-up-circle"></i> Cad. Empréstimos</button>
                </form>
                <form action="./produtos/consulta-emprestimos.php">
                    <button type="submit" class="action"><i data-feather="repeat"></i> Movimentações</button>
                </form>
                <form action="./usuarios/cad-user.php" method="get">
                    <button type="submit" class="action"><i data-feather="user-plus"></i> Cadastrar Usuário</button>
                </form>
            </div>
        </div>
        
    </div>
    <script>
        feather.replace(); // Ativa os ícones Feather
    </script>
</body>
</html>
