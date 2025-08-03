<?php
// Area de codigo PHP
session_start();

// Verificando se há uma sessão com o nome carrinho já criado
if (!isset($_SESSION["carrinho"])) {
    $_SESSION["carrinho"] = array();
}

// Lista de copos e complementos
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

$ListaCopo = [
    "300ml" => 16.00,
    "500ml" => 20.00,
    "700ml" => 23.00
];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casa do Açaí</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
        
        .complemento-item {
            margin-bottom: 10px;
        }
        .complemento-item label {
          
            margin-left: 10px; 
        }
    </style>
</head>
 
<body>
    <header>
        <h1>
            <img src="imagens/acaiteria_logo.png" alt="Logo da Casa do Açai" style="height: 100px;">
        </h1>
    </header>

    <form action="receba.php" method="get" class="order-form">
        <h2>Escolha o Tamanho do seu Copo de Açaí</h2>
        <label for="tamanhoCopo">Tamanho do Copo:</label>
        <br>
        <select name="tamanhoCopo" id="tamanhoCopo" required>
            <option value="">Selecione o tamanho</option>
            <?php foreach ($ListaCopo as $tamanho => $preco) : ?>
                <option value="<?= $tamanho."*".$preco ?>">
                    <?= $tamanho." | R$ ".number_format($preco, 2, ',', '.') ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <h2> Adicione seus Complementos Favoritos (Opcional)</h2>
        <div id="complementosList">
            <?php foreach ($listaComplementos as $c => $v) : ?>
                <div class="checkbox-item"> 
                    <input type="checkbox" name="complementos[]" id="complemento_<?= str_replace(' ', '_', $c) ?>" value="<?= $c."*".$v ?>">
                    <label for="complemento_<?= str_replace(' ', '_', $c) ?>">
                        <span><?= $c ?></span> <span class="price">R$ <?= number_format($v, 2, ',', '.') ?></span> </label>
                </div>
            <?php endforeach; ?>
        </div>
        <br>
        
        <div class="quantity-section">
            <label for="qtdAcaíBase">Quantos açaís completos com essas opções?</label>
            <input type="number" name="qtdAcaíBase" id="qtdAcaíBase" step="1" min="1" max="999" value="1" required>
        </div>

        <br><br>
        
        <button type="submit" class="button-add-to-cart">Adicionar ao carrinho</button>

        <?php
        
        if (isset($_SESSION["carrinho"]) && count($_SESSION["carrinho"]) > 0) :
        ?>
            <a href="receba.php" class="view-cart-link"> Ver itens do carrinho </a>
        <?php endif; ?>
    </form>
</body>
</html>

