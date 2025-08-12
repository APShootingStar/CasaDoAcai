<?php
session_start();

// Carrinho
if (!isset($_SESSION["carrinho"])) {
    $_SESSION["carrinho"] = [];
}

// Lista de copos para o açaí customizável
$ListaCopo = [
    "300ml" => 16.00,
    "500ml" => 20.00,
    "700ml" => 23.00
];

// Complementos opcionais para o açaí customizável
$listaComplementos = [
    "Leite Condensado" => 2.00,
    "Banana" => 2.00,
    "Morango" => 2.50,
    "Paçoca"  => 1.00,
    "Leite Ninho" => 2.90,
    "Nutella" => 5.00,
    "Stikadinho" => 2.50,
    "Chocoball" => 0.50,
    "Kiwi" => 2.95,
    "Granola" => 1.50,
    "Aveia" => 0.50,
    "Manga" => 2.95
];

// Açais fixos
$produtosFixos = [
    [
        "id" => 1,
        "nome" => "Açaí Tropical",
        "tamanho" => "300ml",
        "preco" => 18.00,
        "complementos" => [
            "Banana" => 0,
            "Abacaxi" => 0,
            "Granola" => 0,
            "Mel" => 0
        ]
    ],
    [
        "id" => 2,
        "nome" => "Açaí Doce Tentação",
        "tamanho" => "500ml",
        "preco" => 22.00,
        "complementos" => [
            "Morango" => 0,
            "Leite Condensado" => 0,
            "Leite Ninho" => 0,
            "Mel" => 0
        ]
    ],
    [
        "id" => 3,
        "nome" => "Açaí Explosão de Frutas",
        "tamanho" => "500ml",
        "preco" => 23.00,
        "complementos" => [
            "Morango" => 0,
            "Abacaxi" => 0,
            "Banana" => 0,
            "Manga" => 0
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casa do Açaí - Cardápio</title>
    
    <link rel="stylesheet" href="style.css">
    <style>
        .user-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .header-btn {
            background-color: var(--primary-purple-header, #6A1B9A);
            color: #E0BBE4; /* Cor roxa clara para os links */
            text-decoration: underline;
            font-weight: bold;
            font-size: 1.2em;
            padding: 5px 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .header-btn:hover {
            color: #FFFFFF; /* Branco ao passar o mouse */
        }
        
        .welcome-message {
            color: white;
            font-size: 1.2em;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header>
    <h1>
        <img src="imagens/acaiteria_logo.png" alt="Logo da Casa do Açaí" style="height: 100px;">
    </h1>

    <!-- Container para os botões de login, cadastro e logout -->
    <div class="user-actions">
        <?php if (!isset($_SESSION['cliente_logado']) && !isset($_SESSION['admin_logado'])): ?>
            <!-- Links de login e cadastro estilizados como botões -->
            <a href="login.php" class="header-btn">Fazer Login</a>
            <a href="cadastro_cliente.php" class="header-btn">Cadastre-se</a>
        <?php elseif (isset($_SESSION['cliente_logado'])): ?>
            <span class="welcome-message">Bem vindo <?= htmlspecialchars($_SESSION['cliente_nome']) ?>!</span>
            <a href="logout.php" class="header-btn">Sair</a>
        <?php elseif (isset($_SESSION['admin_logado'])): ?>
            <span class="welcome-message">Olá, Administrador!</span>
            <a href="admin_panel/admin.php" class="header-btn">Painel Admin</a>
            <a href="logout.php" class="header-btn">Sair</a>
        <?php endif; ?>
    </div>
</header>

<h2>🍧 Açaís Especiais</h2>

<?php foreach ($produtosFixos as $produto): ?>
    <div class="product-card">
        <div class="product-info-box">
            <div class="product-info-box-text">
                <h3><?= htmlspecialchars($produto["nome"]) ?></h3>
                <p>Tamanho: <?= htmlspecialchars($produto["tamanho"]) ?></p>
                <p>Preço: R$ <?= number_format($produto["preco"], 2, ',', '.') ?></p>

                <?php if (!empty($produto["complementos"])): ?>
                    <p class="complementos-title">Complementos:</p>
                    <ul>
                        <?php foreach ($produto["complementos"] as $nome => $valor): ?>
                            <li><?= htmlspecialchars($nome) ?> (incluído)</li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <form action="receba.php" method="get" class="order-form-special">
                    <input type="hidden" name="produtoId" value="<?= $produto['id'] ?>">
                    <div class="quantity-container">
                        <label for="quantidade_<?= $produto['id'] ?>">Quantidade:</label>
                        <input type="number" name="quantidade" id="quantidade_<?= $produto['id'] ?>" min="1" value="1" required>
                    </div>
                    <button type="submit" class="button-add-to-cart">Adicionar ao carrinho</button>
                </form>
            </div>
            <div class="product-info-box-image-container">
                <img src="imagens/acai_<?= $produto['id'] ?>.jpg" alt="<?= htmlspecialchars($produto["nome"]) ?>" class="product-info-box-image">
            </div>
        </div>
    </div>
<?php endforeach; ?>

<h2>🛠️ Monte seu Açaí Personalizado</h2>

<form action="receba.php" method="get" class="order-form">
    <input type="hidden" name="produtoId" value="custom">

    <label for="tamanhoCopo"><strong>Tamanho do Copo:</strong></label>
    <select name="tamanhoCopo" id="tamanhoCopo" required>
        <option value="">Selecione o tamanho</option>
        <?php foreach ($ListaCopo as $tamanho => $preco) : ?>
            <option value="<?= $tamanho . '*' . $preco ?>">
                <?= $tamanho ?> | R$ <?= number_format($preco, 2, ',', '.') ?>
            </option>
        <?php endforeach; ?>
    </select>

    <h3>Complementos Opcionais:</h3>
    <?php foreach ($listaComplementos as $nome => $valor): ?>
        <div class="checkbox-item">
            <input type="checkbox" name="complementos[]" id="comp_<?= str_replace(' ', '_', $nome) ?>" value="<?= $nome . '*' . $valor ?>">
            <label for="comp_<?= str_replace(' ', '_', $nome) ?>">
                <?= $nome ?> (+ R$ <?= number_format($valor, 2, ',', '.') ?>)
            </label>
        </div>
    <?php endforeach; ?>

    <br>
    <label for="qtdAcaíBase">Quantidade:</label>
    <input type="number" name="qtdAcaíBase" id="qtdAcaíBase" min="1" value="1" required>

    <br><br>
    <button type="submit" class="button-add-to-cart">Adicionar ao carrinho</button>
</form>

<br><br>

<?php if (!empty($_SESSION["carrinho"])): ?>
    <a href="receba.php" class="view-cart-link">🛒 Ver carrinho</a>
<?php endif; ?>

</body>
</html>

