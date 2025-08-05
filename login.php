<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #8c1091ff; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .login-container { 
            background-color: #fff; 
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
            width: 300px; 
            text-align: center; 
        }
        .login-container h2 { 
            margin-bottom: 20px; 
            color: #333; 
        }
        .login-container input[type="text"],
        .login-container input[type="password"] { 
            width: calc(100% - 20px); 
            padding: 10px; 
            margin-bottom: 15px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
        }
        .login-container input[type="submit"] { 
            background-color: #4e044bff; 
            color: white; 
            padding: 10px 15px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 1em; 
            width: 100%; 
        }
        .login-container input[type="submit"]:hover { 
            background-color: #7c0886ff; 
        }
        
        /* Estilos para as mensagens de feedback */
        .message { 
            padding: 10px; 
            border-radius: 4px; 
            margin-bottom: 15px; 
        }
        .error { 
            color: #842029; 
            background-color: #f8d7da; 
            border: 1px solid #f5c2c7; 
        }
        .success { 
            color: #0f5132; 
            background-color: #d1e7dd; 
            border: 1px solid #badbcc; 
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        
        <?php
        // Exibe mensagem de erro se existir e limpa a sessão
        if (isset($_SESSION['login_erro'])) {
            echo '<p class="message error">' . $_SESSION['login_erro'] . '</p>';
            unset($_SESSION['login_erro']);
        }
        
        // Exibe mensagem de sucesso se existir (após o cadastro) e limpa a sessão
        if (isset($_SESSION['cadastro_sucesso'])) {
            echo '<p class="message success">' . $_SESSION['cadastro_sucesso'] . '</p>';
            unset($_SESSION['cadastro_sucesso']);
        }
        ?>

        <form action="processa_login.php" method="POST">
            <!-- Campos com os nomes corretos: email e senha -->
            <input type="text" name="email" placeholder="E-mail" required><br>
            <input type="password" name="senha" placeholder="Senha" required><br>
            <input type="submit" value="Entrar">
        </form>
    </div>
</body>
</html>
