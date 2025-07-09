<?php

require_once '../verificar-login.php';
require_once '../usuarios/verificar-nivel.php';
verificarNivel('admin'); // Somente administradores acessam essa página
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/CADUSUARIOS_style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <style>
    
    </style>
</head>
<body>
    <div class="form-container">
    <img src="../assets/imgs/logo-horizontal.png" alt="Logo" class="logo"> <!-- Altere para o caminho correto da sua logo -->
       <form action="verifca-cad-user.php" method="post">
    <h2>Cadastro de Usuário</h2>

    <!-- Exibe mensagem de erro ou sucesso -->
    <div id="message" class="message">Mensagem de erro ou sucesso aqui</div>

    <label for="loginn">Novo Login</label>
    <input type="text" name="loginn" id="loginn" required>

    <label for="password">Senha</label>
    <input type="password" name="senha" id="password" required>

    <label class="nivel-ass2" for="nivel"><h3>Nivel de acesso</h3></label>
    <select class="nivel-ass" name="nivel" id="nivel" required>
        <option value="">Selecione...</option>
        <option value="admin">Administrador</option>
        <option value="usuario">Usuário Comum</option>

    </select>

    <button type="submit">Cadastrar</button>
</form>

        <!-- Botão de voltar -->
        <a href="../telainicial.php" class="back-button">Voltar</a>
    </div>
</body>
</html>
