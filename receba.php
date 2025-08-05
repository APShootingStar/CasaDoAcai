<?php

session_start();

// Garante que o carrinho exista na sessão
if (!isset($_SESSION["carrinho"])) {
    $_SESSION["carrinho"] = array();
}


$produtos = [
    
    "custom" => [
        "nome" => "Açaí Customizável",
        "complementos_opcionais" => [
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
            "Manga" => 2.95,
            "Abacaxi" => 2.00,
            "Mel" => 3.00
        ]
    ],
    // Produtos fixos, com complementos já inclusos no preço base
    1 => [
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
    2 => [
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
    3 => [
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

// Lógica para adicionar itens ao carrinho
if (isset($_GET['produtoId'])) {
    $produtoId = $_GET['produtoId'];
    $quantidade = isset($_GET['quantidade']) ? intval($_GET['quantidade']) : 1;
    if ($quantidade < 1) $quantidade = 1;

    // Lógica para adicionar o Açaí Customizável (copo + complementos)
    if ($produtoId == 'custom') {
        if (!isset($_GET['tamanhoCopo'])) {
            header("Location: index.php");
            exit();
        }

        // Pega as informações do copo
        list($tamanhoCopo, $precoCopo) = explode('*', $_GET['tamanhoCopo']);
        $precoCopo = floatval($precoCopo);

        // O subtotal unitário começa com o preço do copo
        $precoTotalUnitario = $precoCopo;
        $complementosSelecionados = [];

        // Adiciona os preços dos complementos selecionados e monta a lista de nomes
        if (isset($_GET['complementos']) && is_array($_GET['complementos']) && !empty($_GET['complementos'])) {
            foreach ($_GET['complementos'] as $complementoSelecionado) {
                list($nomeComplemento, $precoComplemento) = explode('*', $complementoSelecionado);
                $precoComplemento = floatval($precoComplemento);
                
                $precoTotalUnitario += $precoComplemento;
                $complementosSelecionados[] = $nomeComplemento;
            }
        }

        // Monta a descrição do produto para exibição no carrinho
        $descricaoProduto = "Açaí " . $tamanhoCopo;
        if (!empty($complementosSelecionados)) {
            $descricaoProduto .= " (Com: " . implode(', ', $complementosSelecionados) . ")";
        }
        $_SESSION["carrinho"][] = [
            "tipo" => "custom",
            "produto" => $descricaoProduto,
            "valor" => $precoTotalUnitario, 
            "quant" => $quantidade,
            "subtotal" => $precoTotalUnitario * $quantidade
        ];

    } else {
        $produtoId = intval($produtoId);
        if (!isset($produtos[$produtoId])) {
            header("Location: index.php");
            exit();
        }

        $produto = $produtos[$produtoId];
        $precoBase = $produto['preco'];
        $subtotal = $precoBase * $quantidade;

        
        $_SESSION["carrinho"][] = [
            "tipo" => "produto_fixo", 
            "produto" => $produto['nome'] . " (" . $produto['tamanho'] . ")",
            "valor" => $precoBase,
            "quant" => $quantidade,
            "subtotal" => $subtotal
        ];
    }
    
    header("Location: receba.php");
    exit();
}

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
    <link rel="stylesheet" href="style.css"> 
    <style>
        /* Estilos globais para o corpo da página */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        /* Estilo para o contêiner do carrinho, com a borda branca e cantos arredondados */
        .cart-container {
            background-color: #fff;
            border: 2px solid #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 800px;
            box-sizing: border-box;
            text-align: center;
        }

        /* Estilo para os botões */
        .buttons-container button {
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 8px; /* Cantos arredondados */
            font-size: 1em;
            cursor: pointer;
            margin: 0 5px;
            transition: background-color 0.3s ease;
        }

        /* Cor do botão "Continuar montando" (Roxo) */
        .button-continue {
            background-color: #8e1399ff;
        }

        /* Cor do botão "Cancelar compra" (Vermelho) */
        .button-cancel {
            background-color: #dc3545;
        }
        
        /* Cor do botão "Finalizar Compra" (Verde) */
        .button-finish {
            background-color: #28a745;
        }
        
        .buttons-container button:hover {
            opacity: 0.8; /* Escurece um pouco no hover */
        }
        
        /* Estilos para o restante da página */
        h3 {
            text-align: center;
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead th {
            background-color: #f2f2f2;
            color: #555;
            font-weight: bold;
            text-align: left;
            padding: 12px;
        }

        table tbody td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        table tbody tr:last-child td {
            border-bottom: none;
        }

        table tfoot td {
            font-size: 1.2em;
            font-weight: bold;
            text-align: right;
            padding: 12px;
        }
        
        .buttons-container {
            margin-top: 20px;
            text-align: center;
        }
        
        .btn-action {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <h3>Carrinho de Compras</h3>

        <table>
            <thead>
                <tr>
                    <th>NUM.</th>
                    <th>PRODUTO</th>
                    <th>VALOR UN.</th>
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
                            <td> <?= htmlspecialchars($item["produto"]) ?> </td>
                            <td> R$ <?= number_format($item["valor"], 2, ',', '.') ?> </td>
                            <td> <?= $item["quant"] ?> </td>
                            <td> R$ <?= number_format( $item["subtotal"], 2, ',', '.') ?> </td>
                            <td class="btn-action" onclick="editar(<?= $index ?>, <?= $item['quant'] ?>)">
                                ✏️
                            </td>
                            <td class="btn-action" onclick="excluir(<?= $index ?>, '<?= addslashes($item['produto']) ?>')">
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
            <button class="button-continue" onclick="window.location.href='index.php'"> Continuar montando </button>
            <button class="button-cancel" onclick="window.location.href='cancela.php'"> Cancelar compra </button>
            <?php if (!empty($_SESSION["carrinho"])) : ?>
                <button class="button-finish" onclick="window.location.href='finalizar_pedido.php'">Finalizar Compra</button>
            <?php endif; ?>
        </div>

        <form action="edita.php" method="post" id="form-edita" style="display:none; margin-top:20px;">
            <h3>Editar quantidade</h3>
            <input type="hidden" name="id" id="id">
            <input type="number" name="quant" id="quant" step="1" min="1" max="999" required>
            <input type="submit" value="feito">
        </form>
    </div>

    <script>
    function excluir(id, prod){
        // Usando um modal personalizado em vez de alert() e confirm()
        showModal("Deseja remover o item " + prod + " do carrinho?", () => {
            window.location.href = "exclui.php?id=" + id;
        });
    }

    function editar(id, quant){
        document.getElementById("form-edita").style.display = "block";
        document.getElementById("id").value = id;
        document.getElementById("quant").value = quant;
    }
    
    // Funções para o modal personalizado
    function showModal(message, onConfirm) {
        let modal = document.createElement('div');
        modal.style.position = 'fixed';
        modal.style.top = '50%';
        modal.style.left = '50%';
        modal.style.transform = 'translate(-50%, -50%)';
        modal.style.backgroundColor = 'white';
        modal.style.padding = '20px';
        modal.style.borderRadius = '10px';
        modal.style.boxShadow = '0 5px 15px rgba(0,0,0,0.3)';
        modal.style.zIndex = '1000';
        modal.style.textAlign = 'center';
        
        let overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.backgroundColor = 'rgba(0,0,0,0.5)';
        overlay.style.zIndex = '999';
        
        modal.innerHTML = `
            <p>${message}</p>
            <button onclick="confirmAction()">Sim</button>
            <button onclick="closeModal()">Não</button>
        `;
        
        document.body.appendChild(overlay);
        document.body.appendChild(modal);
        
        window.confirmAction = () => {
            onConfirm();
            closeModal();
        };
        
        window.closeModal = () => {
            document.body.removeChild(modal);
            document.body.removeChild(overlay);
        };
    }
    </script>
</body>
</html>
