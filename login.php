<?php
require_once __DIR__. './verificar-login.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/login_style.css">
    <title>Paróquia São Francisco de Assis</title>
    <style>
    </style>
</head>
<body>
    <div class="form-container">
        <form action="./usuarios/verifica-login.php" method="post">
            <img src="./assets/imgs/logo-text.png" alt="Logo" class="login-logo">
            <h2>Login</h2>
            <label for="login">Login</label>
            <input type="text" name="loginn" id="loginn" required>
            
            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha" required>
            
            <button class="button" type="submit">Entrar</button>
            
       
    </div>
</body>
</html>
