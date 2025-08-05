<?php
require_once './src/database/Database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = htmlspecialchars(trim($_POST['nome']));
    $cpf = htmlspecialchars(trim($_POST['cpf']));
    $telefone = htmlspecialchars(trim($_POST['telefone']));
    $endereco = htmlspecialchars(trim($_POST['endereco'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email']));
    $senha = $_POST['senha'];

    if (empty($nome) || empty($cpf) || empty($email) || empty($senha)) {
        $_SESSION['cadastro_erro'] = "Por favor, preencha todos os campos obrigatórios.";
        header("Location: cadastro_cliente.php"); 
        exit();
    }
    
    $db = new Database();

    try {
        $check_email_sql = "SELECT id_cliente FROM cliente WHERE email = :email";
        $email_exists = $db->select($check_email_sql, [':email' => $email]);
        if (!empty($email_exists)) {
            $_SESSION['cadastro_erro'] = "Erro ao cadastrar: o e-mail informado já está em uso.";
            header("Location: cadastro_cliente.php"); 
            exit();
        }

        $check_cpf_sql = "SELECT id_cliente FROM cliente WHERE cpf = :cpf";
        $cpf_exists = $db->select($check_cpf_sql, [':cpf' => $cpf]);
        if (!empty($cpf_exists)) {
            $_SESSION['cadastro_erro'] = "Erro ao cadastrar: o CPF informado já está em uso.";
            header("Location: cadastro_cliente.php"); 
            exit();
        }

        $sql = "INSERT INTO cliente (nome, cpf, telefone, endereco, email, senha) VALUES (:nome, :cpf, :telefone, :endereco, :email, :senha)";
        
        $params = [
            ':nome' => $nome,
            ':cpf' => $cpf,
            ':telefone' => $telefone,
            ':endereco' => $endereco,
            ':email' => $email,
            ':senha' => $senha
        ];
        
        $db->insert($sql, $params);

        $_SESSION['cadastro_sucesso'] = "Cliente cadastrado com sucesso! Você pode fazer login agora.";
        header("Location: login.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['cadastro_erro'] = "Erro ao cadastrar: " . $e->getMessage();
        header("Location: cadastro_cliente.php"); 
        exit();
    } catch (Exception $e) {
        $_SESSION['cadastro_erro'] = "Erro interno: " . $e->getMessage();
        header("Location: cadastro_cliente.php");
        exit();
    }

} else {
    header("Location: cadastro_cliente.php");
    exit();
}
