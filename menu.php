<?php
session_start();
include("conexao.php");

// Bloqueia acesso se não estiver logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Botão sair
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Cadastrar
if (isset($_POST['inserir'])) {
    $nome = $_POST["nome"];
    $stmt = $conexao->prepare("INSERT INTO menu (nome) VALUES (?)");
    $stmt->bind_param("s", $nome);
    if ($stmt->execute()) {
        $mensagem = "<p class='sucesso'>Cadastro realizado com sucesso!</p>";
    } else {
        $mensagem = "<p class='erro'>Erro ao cadastrar: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Filtro de busca
$resultados = [];
if (isset($_POST['buscar'])) {
    $filtro = $_POST["filtro"];
    $stmt = $conexao->prepare("SELECT nome FROM menu WHERE nome LIKE ?");
    $like = "%" . $filtro . "%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $resultado = $stmt->get_result();
    while ($row = $resultado->fetch_assoc()) {
        $resultados[] = $row;
    }
    $stmt->close();
}

$conexao->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Menu</title>
    <style>
        .sucesso { color: green; font-weight: bold; }
        .erro { color: red; font-weight: bold; }
        table { border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #333; padding: 8px 16px; }
        th { background: #5a3b1c; color: white; }
    </style>
</head>
<body>
    <h2>Menu</h2>

    <a href="menu.php?logout=1">
        <button type="button">Sair</button>
    </a>
    <br><br>

    <?php if (!empty($mensagem)) echo $mensagem; ?>

    <!-- Cadastrar -->
    <form method="POST" action="menu.php">
        <label>Cadastrar nome:</label>
        <input name="nome" type="text" autocomplete="off">
        <button type="submit" name="inserir">Cadastrar</button>
    </form>

    <br><hr><br>

    <!-- Filtro de busca -->
    <form method="POST" action="menu.php">
        <label>Buscar nome:</label>
        <input name="filtro" type="text" autocomplete="off">
        <button type="submit" name="buscar">Buscar</button>
    </form>

    <!-- Resultados -->
    <?php if (isset($_POST['buscar'])): ?>
        <?php if (count($resultados) > 0): ?>
            <table>
                <tr><th>Nome</th></tr>
                <?php foreach ($resultados as $row): ?>
                    <tr><td><?php echo htmlspecialchars($row['nome']); ?></td></tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="erro">Nenhum resultado encontrado.</p>
        <?php endif; ?>
    <?php endif; ?>

</body>
</html>