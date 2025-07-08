<?php
session_start(); 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // --- CREDENCIAIS (Antes do dia 08/07 e sem o banco de dados) ---
    $admin_username_fixo = "admin";
    $admin_password_fixa = "admin123"; 

    if ($username === $admin_username_fixo && $password === $admin_password_fixa) {
        // Login bem-sucedido: 
        $_SESSION['admin_logado'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: admin.php");
        exit();
    } else {
        // Login falhou: define mensagem de erro e redireciona de volta para o login
        $_SESSION['login_erro'] = "Usuário ou senha incorretos.";
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>