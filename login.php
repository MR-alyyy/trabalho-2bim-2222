<?php
session_start();
include 'conexao.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = mysqli_query($conexao, "SELECT * FROM cadastro WHERE email = '$email'");
    $dados = mysqli_fetch_assoc($sql);

    if ($dados && password_verify($senha, $dados['senha'])) {
        $_SESSION['usuario'] = $dados['email'];
        header("Location: menu.php");
        exit();
    } else {
        $erro = "<p class='erro'>Email ou senha inválidos!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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

        .logo span {
            font-size: 36px;
        }

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
    </style>
</head>
<body>
    <div class="card">
        <div class="logo"><span>🔥</span></div>
        <h2>Bem-vindo!</h2>
        <p class="subtitle">Entre com sua conta para continuar</p>

        <?php if (!empty($erro)) echo $erro; ?>

        < method="POST" action="login.php">
            <label>E-mail</label>
            <input name="email" type="text" placeholder="seu@email.com" autocomplete="off">

           <label>Senha</label>
<input name="senha" type="password" placeholder="••••••••">

<!-- ✅ Adicione essa linha -->
<div style="text-align: right; margin-top: -12px; margin-bottom: 18px;">
    <a href="recuperar_senha.php" style="font-size: 13px; color: #ff6a00; text-decoration: none; font-weight: 600;">
        Esqueceu a senha?
    </a>
</div>

<button type="submit" name="login" class="btn-primary">Entrar</button>
        </form>
        

        <button class="btn-secondary" onclick="window.location.href='cadastro.php'">Criar conta</button>
    </div>
</body>
</html>