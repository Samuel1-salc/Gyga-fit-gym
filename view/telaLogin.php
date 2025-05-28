<?php
session_start();

$mostrarSenha = $_SESSION['mostrarSenha'] ?? false;
$cpfDigitado = $_SESSION['cpfDigitado'] ?? '';
$erro = $_SESSION['error'] ?? '';

unset($_SESSION['mostrarSenha'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GYGA FIT cadastro</title>
  <style>
    /* Seu CSS aqui (sem alterações) */
    /* Estilo do vídeo de introdução */
    #video-intro {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: black;
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    #video-intro video {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .fade-out {
      animation: fadeOut 2s forwards;
    }

    @keyframes fadeOut {
      to {
        opacity: 0;
        visibility: hidden;
      }
    }

    /* Corpo da página */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Arial', sans-serif;
      height: 100vh;
      background-color: #000;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      opacity: 0;
      transition: opacity 1s ease-in;
    }

    .show-content {
      opacity: 1;
    }

    .logo {
      margin-bottom: 30px;
      text-align: center;
    }

    .logo img {
      width: 200px;
      height: auto;
    }

    .login-box {
      width: 100%;
      max-width: 360px;
      padding: 30px 20px;
      background-color: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
      text-align: center;
      box-sizing: border-box;
    }

    .login-box h1 {
      font-size: 24px;
      color: #000;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .login-box p {
      font-size: 14px;
      color: #333;
      margin-bottom: 20px;
    }

    .input-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .input-group label {
      display: block;
      margin-bottom: 5px;
      font-size: 13px;
      color: #444;
    }

    .input-group input {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 20px;
      background-color: #f1f1f1;
      font-size: 16px;
      box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
      box-sizing: border-box;
    }

    .input-group input:focus {
      outline: none;
      border: 2px solid #ff0000;
      background-color: #fff;
    }

    button {
      width: 100%;
      padding: 14px;
      background-color: #ff0000;
      color: white;
      border: none;
      border-radius: 20px;
      font-size: 18px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
      box-shadow: 0 4px 0 #b30000;
    }

    button:hover {
      background-color: #cc0000;
    }

    .footer {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
      color: #777;
    }

    .error-message {
      color: red;
      font-size: 14px;
    }
  </style>
</head>

<body>
  <div id="video-intro">
    <video autoplay muted>
      <source src="../view/video/intro.mp4" type="video/mp4" />
      Seu navegador não suporta vídeo HTML5.
    </video>
  </div>

  <div class="container" id="conteudo-login">
    <div class="logo">
      <img src="../view/img/logo.png" alt="GYGA FIT" />
    </div>
    <div class="login-box">
      <h1>GYGA FIT</h1>
      <p>Entre com suas credenciais para Entrar</p>
      <form action="./../controllers/processarLogin.php" method="post">
        <div class="input-group">
          <label for="cpf">CPF</label>
          <input type="text" name="cpf" required value="<?= htmlspecialchars($cpfDigitado) ?>" maxlength="11" />
          <?php if (strpos($erro, "CPF") !== false): ?>
            <div class="error-message"><?= htmlspecialchars($erro) ?></div>
          <?php endif; ?>
        </div>

        <?php if ($mostrarSenha): ?>
          <div class="input-group">
            <label for="senha">Senha</label>
            <input type="password" name="senha" required />
            <?php if (strpos($erro, "senha") !== false): ?>
              <div class="error-message"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <button type="submit">Entrar</button>
      </form>
    </div>
    <div class="footer">
      © 2025
    </div>
  </div>

  <script>
    const videoIntro = document.getElementById('video-intro');
    const conteudoLogin = document.getElementById('conteudo-login');
    const video = videoIntro.querySelector("video");

    video.addEventListener("ended", () => {
      videoIntro.classList.add("fade-out");
      conteudoLogin.classList.add("show-content");
      document.querySelector('input[name="cpf"]').focus();
    });
  </script>
</body>

</html>