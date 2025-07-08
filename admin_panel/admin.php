<?php
session_start(); 


// VERIFICAÇÃO DE SEGURANÇA: Se o admin NÃO estiver logado, manda ele para a página de login
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['produtos'])) {
    $_SESSION['produtos'] = [];
}

// Variáveis para mensagens de feedback
$mensagem_sucesso = "";
$mensagem_erro = "";
$produto_para_editar = null; // Se estivermos editando, guarda os dados do produto
$edit_index = -1; // Índice do produto sendo editado

// --- Adicionar ou editar produtos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = floatval(str_replace(',', '.', $_POST['preco']));
    $tipo = trim($_POST['tipo']);
    $id_produto_ou_indice = isset($_POST['id_produto']) ? $_POST['id_produto'] : ''; // Usamos o ID  aqui

    if (empty($nome) || empty($preco) || empty($tipo)) {
        $mensagem_erro = "Nome, Preço e Tipo são campos obrigatórios.";
    } elseif ($preco <= 0) {
        $mensagem_erro = "O preço deve ser um valor positivo.";
    } else {
        $novo_produto = [
            'id' => uniqid(), // Gera um ID únic para cada produto na sessão
            'nome' => $nome,
            'descricao' => $descricao,
            'preco' => $preco,
            'tipo' => $tipo
        ];

        if ($id_produto_ou_indice !== '') { // Se tem ID, logo edita o produto
            
            foreach ($_SESSION['produtos'] as $key => $prod) {
                if ($prod['id'] == $id_produto_ou_indice) {
                    $_SESSION['produtos'][$key] = $novo_produto; // Substitui o produto existente
                    $mensagem_sucesso = "Produto atualizado com sucesso!";
                    break;
                }
            }
            if (!$mensagem_sucesso) { 
                 $mensagem_erro = "Erro ao atualizar produto: ID não encontrado.";
            }
        } else { // Se não tem ID, adiciona
            $_SESSION['produtos'][] = $novo_produto; 
            $mensagem_sucesso = "Produto adicionado com sucesso!";
        }
    }
}


if (isset($_GET['acao']) && $_GET['acao'] == 'editar' && isset($_GET['id'])) {
    $id_para_editar = $_GET['id'];
    foreach ($_SESSION['produtos'] as $key => $prod) {
        if ($prod['id'] == $id_para_editar) {
            $produto_para_editar = $prod;
            $edit_index = $key; 
            break;
        }
    }
    if (!$produto_para_editar) {
        $mensagem_erro = "Produto não encontrado para edição.";
    }
}


if (isset($_SESSION['mensagem_sucesso'])) {
    $mensagem_sucesso = $_SESSION['mensagem_sucesso'];
    unset($_SESSION['mensagem_sucesso']);
}
if (isset($_SESSION['mensagem_erro'])) {
    $mensagem_erro = $_SESSION['mensagem_erro'];
    unset($_SESSION['mensagem_erro']);
}

// Ordena os produtos para exibição
usort($_SESSION['produtos'], function($a, $b) {
    if ($a['tipo'] == $b['tipo']) {
        return strcmp($a['nome'], $b['nome']); 
    }
    return strcmp($a['tipo'], $b['tipo']); 
});
$produtos = $_SESSION['produtos'];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Administrativa - Produtos</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 20px; color: #333; }
        .container { max-width: 900px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h1, h2 { color: #007bff; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .header a { background-color: #dc3545; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; }
        .header a:hover { background-color: #c82333; }
        .form-section { border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .form-section label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-section input[type="text"],
        .form-section input[type="number"],
        .form-section select,
        .form-section textarea { width: calc(100% - 22px); padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .form-section input[type="submit"] { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        .form-section input[type="submit"]:hover { background-color: #218838; }
        .form-section button.cancel-edit { background-color: #6c757d; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; margin-left: 10px;}
        .form-section button.cancel-edit:hover { background-color: #5a6268; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .actions a, .actions button { margin-right: 5px; padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 0.9em; }
        .actions .edit-btn { background-color: #ffc107; color: #333; }
        .actions .edit-btn:hover { background-color: #e0a800; }
        .actions .delete-btn { background-color: #dc3545; color: white; border: none; cursor: pointer; }
        .actions .delete-btn:hover { background-color: #c82333; }
        .success-message { color: green; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .error-message { color: red; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Painel Administrativo</h1>
            <a href="logout.php">Sair</a>
        </div>

        <?php if ($mensagem_sucesso): ?>
            <div class="success-message"><?= $mensagem_sucesso ?></div>
        <?php endif; ?>
        <?php if ($mensagem_erro): ?>
            <div class="error-message"><?= $mensagem_erro ?></div>
        <?php endif; ?>

        <div class="form-section">
            <h2><?= $produto_para_editar ? 'Editar Produto' : 'Adicionar Novo Produto' ?></h2>
            <form action="admin.php" method="POST">
                <input type="hidden" name="id_produto" value="<?= htmlspecialchars($produto_para_editar['id'] ?? '') ?>">

                <label for="nome">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto_para_editar['nome'] ?? '') ?>" required><br>

                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao"><?= htmlspecialchars($produto_para_editar['descricao'] ?? '') ?></textarea><br>

                <label for="preco">Preço:</label>
                <input type="number" id="preco" name="preco" step="0.01" value="<?= htmlspecialchars($produto_para_editar['preco'] ?? '') ?>" required><br>

                <label for="tipo">Tipo:</label>
                <select name="tipo" id="tipo" required>
                    <option value="">Selecione o tipo</option>
                    <option value="copo" <?= (isset($produto_para_editar['tipo']) && $produto_para_editar['tipo'] == 'copo') ? 'selected' : '' ?>>Copo</option>
                    <option value="complemento" <?= (isset($produto_para_editar['tipo']) && $produto_para_editar['tipo'] == 'complemento') ? 'selected' : '' ?>>Complemento</option>
                </select><br>

                <input type="submit" value="<?= $produto_para_editar ? 'Atualizar Produto' : 'Adicionar Produto' ?>">
                <?php if ($produto_para_editar): ?>
                    <button type="button" class="cancel-edit" onclick="window.location.href='admin.php'">Cancelar Edição</button>
                <?php endif; ?>
            </form>
        </div>

        <h2>Produtos Cadastrados</h2>
        <?php if (empty($produtos)): ?>
            <p>Nenhum produto cadastrado ainda.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID (Temp)</th> <th>Nome</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?= htmlspecialchars($produto['id']) ?></td>
                            <td><?= htmlspecialchars($produto['nome']) ?></td>
                            <td><?= htmlspecialchars($produto['descricao']) ?></td>
                            <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars(ucfirst($produto['tipo'])) ?></td>
                            <td class="actions">
                                <a href="admin.php?acao=editar&id=<?= htmlspecialchars($produto['id']) ?>" class="edit-btn">Editar</a>
                                <button class="delete-btn" onclick="confirmarDelecao('<?= htmlspecialchars(addslashes($produto['id'])) ?>', '<?= htmlspecialchars(addslashes($produto['nome'])) ?>')">Deletar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
        function confirmarDelecao(id, nome) {
            if (confirm("Tem certeza que deseja deletar o produto: '" + nome + "' (ID Temporário: " + id + ")? Esta ação é irreversível nesta sessão!")) {
                window.location.href = "deleta_produto.php?id=" + id;
            }
        }
    </script>
</body>
</html>