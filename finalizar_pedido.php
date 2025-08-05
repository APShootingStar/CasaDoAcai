<?php
session_start();

// Verifica se o carrinho existe e não está vazio
if (!isset($_SESSION["carrinho"]) || empty($_SESSION["carrinho"])) {
    header("Location: index.php");
    exit();
}

$total_pedido = 0;
foreach ($_SESSION["carrinho"] as $item) {
    $total_pedido += $item['subtotal'];
}

$etapa = 'pagamento';
$mensagem_sucesso = '';
$nome_cliente = '';
$forma_pagamento = '';
$pix_copia_cola = 'chave-pix-ficticia-exemplo@email.com';
$qr_code_url = 'https://i.pinimg.com/736x/05/b6/eb/05b6eb105c4cb179db87bffbdcbf6617.jpg';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Processa o formulário de pagamento
    $nome_cliente = htmlspecialchars(trim($_POST['nome_cliente']));
    $forma_pagamento = htmlspecialchars(trim($_POST['forma_pagamento']));
    
    // Mensagem de sucesso
    $mensagem_sucesso = "Seu pedido foi finalizado com sucesso, " . $nome_cliente . "! O total é R$ " . number_format($total_pedido, 2, ',', '.') . ".";
    
    // Limpa o carrinho após a finalização
    unset($_SESSION["carrinho"]);

    $etapa = 'confirmacao';
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pedido</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background-color: var(--card-background-white);
        }
        h1, h2 {
            color: var(--primary-purple-header);
        }
        .success {
            color: #4CAF50;
            font-size: 1.2em;
        }
        .error {
            color: #f44336;
            font-size: 1.2em;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: var(--primary-purple-header);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: var(--button-hover-purple);
        }
        .payment-options {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .pix-info {
            margin-top: 30px;
            text-align: center;
        }
        .pix-info img {
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }
        .pix-key-box {
            background-color: #f0f0f5;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }
        .copy-button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <header>
        <h1><img src="https://placehold.co/200x100/6A1B9A/ffffff?text=Casa+do+A%C3%A7a%C3%AD" alt="Logo da Casa do Açaí"></h1>
    </header>
    <div class="container">

        <?php if ($etapa == 'pagamento'): ?>
            <h2>Resumo do Pedido</h2>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quant</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION["carrinho"] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['produto']) ?></td>
                            <td><?= $item['quant'] ?></td>
                            <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3>Total: R$ <?= number_format($total_pedido, 2, ',', '.') ?></h3>

            <form action="finalizar_pedido.php" method="post">
                <label for="nome_cliente">Seu Nome:</label>
                <input type="text" name="nome_cliente" id="nome_cliente" required>
                <br><br>

                <label>Forma de Pagamento:</label>
                <div class="payment-options">
                    <label>
                        <input type="radio" name="forma_pagamento" value="Pix" required> Pix
                    </label>
                    <label>
                        <input type="radio" name="forma_pagamento" value="Dinheiro"> Dinheiro
                    </label>
                    <label>
                        <input type="radio" name="forma_pagamento" value="Cartão"> Cartão
                    </label>
                </div>
                <button type="submit" class="btn">Confirmar Pagamento</button>
            </form>

        <?php elseif ($etapa == 'confirmacao'): ?>
            <h2>Pedido Finalizado!</h2>
            <p class="success"><?= htmlspecialchars($mensagem_sucesso) ?></p>

            <?php if ($forma_pagamento == 'Pix'): ?>
                <div class="pix-info">
                    <h3>Pagamento via Pix</h3>
                    <p>Por favor, faça a leitura do QR Code ou utilize a chave abaixo:</p>
                    <img src="<?= $qr_code_url ?>" alt="QR Code Pix" width="200" height="200">
                    <p>Chave Copia e Cola:</p>
                    <div class="pix-key-box" onclick="copiarChave()">
                        <span id="pix-key-text"><?= htmlspecialchars($pix_copia_cola) ?></span>
                        <button type="button" class="copy-button" id="copy-btn" title="Copiar chave">📄</button>
                    </div>
                    <p id="copy-message" style="color: #4CAF50; display:none;">Chave copiada!</p>
                </div>
            <?php endif; ?>

            <a href="index.php" class="btn">Fazer novo pedido</a>
        <?php endif; ?>

    </div>
    <script>
        // Função para copiar a chave Pix
        function copiarChave() {
            const pixKeyText = document.getElementById('pix-key-text').innerText;
            const tempInput = document.createElement('input');
            tempInput.value = pixKeyText;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            
            const copyMessage = document.getElementById('copy-message');
            copyMessage.style.display = 'block';
            setTimeout(() => {
                copyMessage.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>
