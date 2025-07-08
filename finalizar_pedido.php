<?php
session_start();

// Verifica se há itens no carrinho
if (!isset($_SESSION["carrinho"]) || empty($_SESSION["carrinho"])) {
    // Se o carrinho estiver vazio, redireciona de volta para a página principal
    header("Location: index.php");
    exit();
}

// Limpa o carrinho após a "finalização"
unset($_SESSION["carrinho"]);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Finalizado!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #4CAF50;
            border-radius: 8px;
            background-color: #e6ffe6;
        }
        h1 {
            color: #4CAF50;
        }
        p {
            font-size: 1.1em;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 Pedido Finalizado com Sucesso! 🎉</h1>
        <p>Agradecemos a sua compra. Seu açaí está sendo preparado!</p>
        <p>Você será contatado em breve para os detalhes da entrega.</p>
        <a href="index.php">Fazer um novo pedido</a>
    </div>
</body>
</html>