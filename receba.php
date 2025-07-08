
<?php

session_start();

// Garante que o carrinho exista na sessão
if( !isset( $_SESSION["carrinho"] ) ){
    $_SESSION["carrinho"] = array();
}

// Adiciona itens ao carrinho 

if (isset($_GET['tamanhoCopo']) && !empty($_GET['tamanhoCopo'])) {
    list($tamanhoCopo, $precoCopo) = explode('*', $_GET['tamanhoCopo']);
    $precoCopo = floatval($precoCopo);

    $quantidadeGeral = isset($_GET['qtdAcaíBase']) ? intval($_GET['qtdAcaíBase']) : 1;
    if ($quantidadeGeral < 1) {
        $quantidadeGeral = 1;
    }

    $subtotalCopo = $precoCopo * $quantidadeGeral;

    $_SESSION["carrinho"][] = [
        "tipo" => "copo",
        "produto" => "Açaí " . $tamanhoCopo,
        "valor" => $precoCopo,
        "quant" => $quantidadeGeral,
        "subtotal" => $subtotalCopo
    ];

    if (isset($_GET['complementos']) && is_array($_GET['complementos']) && !empty($_GET['complementos'])) {
        foreach ($_GET['complementos'] as $complementoSelecionado) {
            list($nomeComplemento, $precoComplemento) = explode('*', $complementoSelecionado);
            $precoComplemento = floatval($precoComplemento);

            $subtotalComplemento = $precoComplemento * $quantidadeGeral;

            $_SESSION["carrinho"][] = [
                "tipo" => "complemento",
                "produto" => $nomeComplemento,
                "valor" => $precoComplemento,
                "quant" => $quantidadeGeral,
                "subtotal" => $subtotalComplemento
            ];
        }
    }
    
    header("Location: receba.php");
    exit(); 
}
// --- Fim da lógica de adição ---


//total carrinho 
$total = 0;
if (isset($_SESSION["carrinho"]) && is_array($_SESSION["carrinho"])) { 
    foreach( $_SESSION["carrinho"] as $item) {
        $total += $item["subtotal"];
    }
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    

    <style>
        .btn-action {
            cursor: pointer;
            border: solid 1px grey;
            border-radius: 5px;
            padding: 5px 10px;
            transition: 0.3s;
            display: inline-block;
            margin: 2px;
        }

        .btn-action:hover{
            background-color: rgb(135, 167, 214);
            transition: 0.3s;
        }

        #form-edita {
            display: none;
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .buttons-container {
            margin-top: 20px;
        }

        .buttons-container button, .buttons-container a {
            padding: 10px 15px;
            margin-right: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        button {
            background-color:rgb(197, 162, 7);
            color: white;
        }

        button:hover {
            background-color:rgb(131, 0, 192);
        }

        .botao-finalizar-compra {
            background-color:rgb(112, 8, 153);
        }

        .botao-finalizar-compra:hover {
            background-color: #218838;
        }

        button[onclick*="cancela.php"] {
            background-color: #dc3545;
        }
        button[onclick*="cancela.php"]:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h3>Carrinho de Compras</h3>

    <table>
        <thead>
            <tr>
                <th>NUM.</th>
                <th>PRODUTO</th>
                <th>VALOR UN./kg</th>
                <th>QUANT</th>
                <th>SUBTOTAL</th>
                <th colspan="2">AÇÕES</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $num = 1;
            //exibição do carrinho
            if (!empty($_SESSION["carrinho"])) {
                foreach( $_SESSION["carrinho"] as $index => $item ) :
                ?>
                    <tr>
                        <td> <?= $num ?> </td>
                        <td> <?= $item["produto"] ?> </td>
                        <td> R$ <?= number_format($item["valor"], 2, ',', '.') ?> </td>
                        <td> <?= $item["quant"] ?> </td>
                        <td> R$ <?= number_format( $item["subtotal"], 2, ',', '.') ?> </td>
                        <td class="btn-action" onclick="editar(<?= $index ?>, <?= $item['quant'] ?>)">
                            ✏️
                        </td>
                        <td class="btn-action" onclick="excluir(<?= $index ?>, '<?= $item['produto'] ?>')">
                            🗑️
                        </td>
                    </tr>
                <?php
                $num++;
                endforeach;
            } else {
                echo "<tr><td colspan='7'>Seu carrinho está vazio.</td></tr>";
            }
            ?>

            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;"> Total: </td>
                <td colspan="3" style="font-weight: bold;"> R$ <?= number_format($total, 2, ',', '.') ?> </td>
            </tr>

        </tbody>
    </table>

    <div class="buttons-container">
        <button onclick="window.location.href='index.php'"> Continuar montando </button>
        <button onclick="window.location.href='cancela.php'"> Cancelar compra </button>
        <?php if (!empty($_SESSION["carrinho"])) : ?>
            <button class="botao-finalizar-compra" onclick="window.location.href='finalizar_pedido.php'">Finalizar Compra</button>
        <?php endif; ?>
    </div>

    <form action="edita.php" method="post" id="form-edita">
        <h3>Editar quantidade</h3>
        <input type="hidden" name="id" id="id">
        <input type="number" name="quant" id="quant" step="1" min="1" max="999" required>
        <input type="submit" value="feito">
    </form>

    <script>
        function excluir(id, prod){
            if( confirm("Deseja remover o item " + prod + " do carrinho?") ){
                window.location.href = "exclui.php?id=" + id;
            }
        }

        function editar(id, quant){
            document.getElementById("form-edita").style.display = "block";
            document.getElementById("id").value = id;
            document.getElementById("quant").value = quant;
        }
    </script>

</body>
</html>