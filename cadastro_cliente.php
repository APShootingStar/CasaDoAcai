<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #a01e99ff; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 400px; text-align: center; }
        .container h2 { margin-bottom: 20px; color: #333; }
        .container input[type="text"],
        .container input[type="email"],
        .container input[type="password"] {
            width: calc(100% - 20px); padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;
        }
        .container input[type="submit"] {
            background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; width: 100%;
        }
        .container input[type="submit"]:hover { background-color: #0056b3; }
        .message { margin-top: 10px; padding: 10px; border-radius: 4px; }
        .error { color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; }
        .success { color: #0f5132; background-color: #d1e7dd; border: 1px solid #badbcc; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastro</h2>
        <form action="processa_cadastro.php" method="POST">
            <input type="text" name="nome" placeholder="Nome Completo" required><br>
            <input type="text" name="cpf" placeholder="CPF (ex: 12345678901)" required><br>
            <input type="text" name="telefone" placeholder="Telefone (ex: 5511988887777)" required><br>
            <input type="text" name="endereco" placeholder="Endereço (opcional)"><br>
            <input type="email" name="email" placeholder="E-mail (para login)" required><br>
            <input type="password" name="senha" placeholder="Senha" required><br>
            <input type="submit" value="Cadastrar">
        </form>
        <?php
        // Verifica e exibe mensagem de erro
        if (isset($_SESSION['cadastro_erro'])) {
            echo '<p class="message error">' . $_SESSION['cadastro_erro'] . '</p>';
            unset($_SESSION['cadastro_erro']);
        }

        // Verifica e exibe mensagem de sucesso
        if (isset($_SESSION['cadastro_sucesso'])) {
            echo '<p class="message success">' . $_SESSION['cadastro_sucesso'] . '</p>';
            unset($_SESSION['cadastro_sucesso']);
        }
        ?>
    </div>
</body>
</html>
