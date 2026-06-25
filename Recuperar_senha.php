
<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
require 'vendor/autoload.php';
include 'conexao.php';
 
$mensagem = "";
 
if (isset($_POST['recuperar'])) {
    $email = trim($_POST['email']);
 
    $sql = mysqli_query($conexao, "SELECT * FROM cadastro WHERE email = '$email'");
    $dados = mysqli_fetch_assoc($sql);
 
    if ($dados) {
        $novaSenha = bin2hex(random_bytes(4));
        $senhaCriptografada = password_hash($novaSenha, PASSWORD_DEFAULT);
 
        $stmt = $conexao->prepare("UPDATE cadastro SET senha = ? WHERE email = ?");
        $stmt->bind_param("ss", $senhaCriptografada, $email);
        $stmt->execute();
        $stmt->close();
 
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'seuemail@gmail.com';     // ✅ Troque pelo seu Gmail
            $mail->Password   = 'senha_do_aplicativo';    // ✅ Troque pela senha de app
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
 
            $mail->setFrom('seuemail@gmail.com', 'sistema');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de senha';
            $mail->Body    = "
                <h1>Recuperação de senha</h1>
                <p>Olá! Sua nova senha é: <strong>$novaSenha</strong></p>
                <p>Acesse o sistema e altere sua senha após o login.</p>
            ";
 
            $mail->send();
            $mensagem = "<div class='sucesso'>✔ Nova senha enviada para o seu email!</div>";
        } catch (Exception $e) {
            $mensagem = "<div class='erro'>✖ Erro ao enviar email: {$mail->ErrorInfo}</div>";
        }
    } else {
        $mensagem = "<div class='erro'>✖ Email não encontrado no sistema.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
 
        body {
            min-height: 100vh;
            background: #ffd9a0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
 
        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 36px;
            width: 380px;
            border: 1px solid #f0f0f0;
        }
 
        .logo {
            text-align: center;
            margin-bottom: 24px;
            font-size: 32px;
        }
 
        h2 {
            text-align: center;
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }
 
        .subtitle {
            text-align: center;
            font-size: 13px;
            color: #999;
            margin-bottom: 26px;
        }
 
        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #555;
            margin-bottom: 6px;
        }
 
        input {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #e6e6e6;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 16px;
            outline: none;
            color: #333;
            background: #fafafa;
        }
 
        input:focus {
            border-color: #ffa552;
            background: #fff;
        }
 
        .btn-primary {
            width: 100%;
            padding: 12px;
            background: #ff9d4d;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            margin-bottom: 10px;
        }
 
        .btn-primary:hover { background: #ff8e33; }
        .btn-primary:active { background: #ff7f1a; }
 
        .btn-secondary {
            width: 100%;
            padding: 12px;
            background: transparent;
            color: #ff8e33;
            border: 1px solid #ffd9b3;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }
 
        .btn-secondary:hover { background: #fff6ee; }
 
        .sucesso {
            color: #2e7d32;
            font-size: 13px;
            font-weight: 500;
            background: #eef7ef;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 14px;
        }
 
        .erro {
            color: #c62828;
            font-size: 13px;
            font-weight: 500;
            background: #fdf0ef;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 14px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">🔑</div>
        <h2>Recuperar senha</h2>
        <p class="subtitle">Digite seu email e enviaremos uma nova senha</p>
 
        <?php if (!empty($mensagem)) echo $mensagem; ?>
 
        <form method="POST" action="recuperar_senha.php">
            <label>E-mail</label>
            <input name="email" type="text" placeholder="seu@email.com" autocomplete="off">
            <button type="submit" name="recuperar" class="btn-primary">Enviar nova senha</button>
        </form>
 
        <button class="btn-secondary" onclick="window.location.href='login.php'">Voltar ao login</button>
    </div>
</body>
</html>

