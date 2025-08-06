<?php
session_start(); // Inicia a sessão

// VERIFICAÇÃO DE SEGURANÇA
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: login.php");
    exit();
}

// Inicializa o array se ele não existir
if (!isset($_SESSION['produtos'])) {
    $_SESSION['produtos'] = [];
}

if (isset($_GET['id'])) {
    $id_para_deletar = $_GET['id'];
    $deletado = false;


    foreach ($_SESSION['produtos'] as $key => $produto) {
        if ($produto['id'] == $id_para_deletar) {
            array_splice($_SESSION['produtos'], $key, 1); // Remove o item
            
            $_SESSION['produtos'] = array_values($_SESSION['produtos']);
            $deletado = true;
            break;
        }
    }

    if ($deletado) {
        $_SESSION['mensagem_sucesso'] = "Produto deletado com sucesso!";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao deletar produto: ID não encontrado ou já deletado.";
    }
} else {
    $_SESSION['mensagem_erro'] = "Nenhum ID de produto fornecido para deletar.";
}

header("Location: admin.php"); // Redireciona de volta para a lista de produtos
exit();
?>