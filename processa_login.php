<?php

session_start();

require_once 'src/database/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['senha'];

    // --- CREDENCIAIS DO ADMINISTRADOR (FIXAS) ---
    $admin_email_fixo = "admin@gmail.com";
    $admin_password_fixa = "adm123";

    // A senha do admin não está no banco de dados, então é uma verificação simples.
    if ($email === $admin_email_fixo && $password === $admin_password_fixa) {
        $_SESSION['admin_logado'] = true;
        $_SESSION['admin_nome'] = "Administrador";
        header("Location: ./admin_panel/admin.php");
        exit(); // CRÍTICO: Encerra a execução para não continuar.
    }

    // 2. TENTA O LOGIN DO CLIENTE 
    try {
        $db = new Database();

        $sql = "SELECT id_cliente, nome, email, senha FROM cliente WHERE email = :email";
        $pesquisa = $db->select($sql, [':email' => $email]);

        // Verifica se o cliente existe
        if (count($pesquisa) > 0) {
            $cliente = $pesquisa[0];
            
            if ($password == $cliente['senha']) { 
                $_SESSION['cliente_logado'] = true;
                $_SESSION['cliente_id'] = $cliente['id_cliente'];
                $_SESSION['cliente_nome'] = $cliente['nome'];
                
                header("Location: ./index.php");
                exit();
            }
        }
    } catch (Exception $e) {
        error_log("Erro no banco de dados durante o login: " . $e->getMessage());
    }

    // 3. SE NENHUM LOGIN FUNCIONOU, EXIBE A MENSAGEM DE ERRO
    // A mensagem de erro é a mesma para senha incorreta ou usuário não encontrado.
    $_SESSION['login_erro'] = "E-mail ou senha incorretos.";
    header("Location: login.php");
    exit();

} else {
    header("Location: login.php");
    exit();
}
?>