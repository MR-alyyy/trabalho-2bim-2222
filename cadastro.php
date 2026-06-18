<?php
include "conexao.php";

$mensagem = "";

if (isset($_POST['inserir'])) {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $erro = false;

    $senhaForte = preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $senha);

    if (!$senhaForte) {
        $mensagem .= "<p class='erro'>A senha deve ter no mínimo 8 caracteres, com letra maiúscula, minúscula, número e símbolo.</p>";
        $erro = true;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem .= "<p class='erro'>Digite um e-mail válido.</p>";
        $erro = true;
    }

    if (!$erro) {
        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $conexao->prepare("INSERT INTO cadastro (email, senha) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $senhaCriptografada);
        if ($stmt->execute()) {
            $mensagem = "<p class='sucesso'>Cadastro realizado com sucesso!</p>";
        } else {
            $mensagem = "<p class='erro'>Erro ao cadastrar: ".$stmt->error."</p>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #ff4e00, #ff8c00, #ffb347);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 20px;
            padding: 45px 40px;
            width: 380px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }

        .logo {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo span { font-size: 36px; }

        h2 {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #222;
            margin-bottom: 6px;
        }

        .subtitle {
            text-align: center;
            font-size: 14px;
            color: #888;
            margin-bottom: 28px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            margin-bottom: 18px;
            transition: border 0.2s;
            outline: none;
            color: #222;
            background: #fafafa;
        }

        input:focus {
            border-color: #ff6a00;
            background: #fff;
        }

        .btn-primary {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #ff4e00, #ff8c00);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
            margin-bottom: 12px;
        }

        .btn-primary:hover { opacity: 0.92; }
        .btn-primary:active { transform: scale(0.98); }

        .btn-secondary {
            width: 100%;
            padding: 13px;
            background: transparent;
            color: #ff6a00;
            border: 1.5px solid #ff6a00;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-secondary:hover { background: #fff3e8; }

        .erro {
            color: #d32f2f;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 16px;
            background: #fdecea;
            padding: 10px;
            border-radius: 8px;
        }

        .sucesso {
            color: #2e7d32;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 16px;
            background: #e8f5e9;
            padding: 10px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo"><span>🔥</span></div>
        <h2>Criar conta</h2>
        <p class="subtitle">Preencha os dados para se cadastrar</p>

        <?php echo $mensagem; ?>

        <form method="POST" action="cadastro.php">
            <label>E-mail</label>
            <input name="email" type="text" placeholder="seu@email.com" autocomplete="off" required>

            <label>Senha</label>
            <input name="senha" type="password" placeholder="Mín. 8 caracteres" required>

            <button type="submit" name="inserir" class="btn-primary">Cadastrar</button>
        </form>

        <button class="btn-secondary" onclick="window.location.href='login.php'">Já tenho conta</button>
    </div>
</body>
</html>