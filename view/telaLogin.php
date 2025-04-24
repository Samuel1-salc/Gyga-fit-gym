<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>GYGA FIT cadastro</title>
  <link rel="stylesheet" href="../view/style/Login-Cadastro.css"/>

  <style>
    /* Estilos para o vídeo de introdução */
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

    /* Animação fade-out */
    .fade-out {
      animation: fadeOut 2s forwards;
    }

    @keyframes fadeOut {
      to {
        opacity: 0;
        visibility: hidden;
      }
    }

    /* Estilos do conteúdo de login */
    .container {
      opacity: 0;
      transition: opacity 1s ease-in;
    }

    .show-content {
      opacity: 1;
    }

    /* Estilos gerais */
    .logo {
      text-align: center;
      margin-top: 20px;
    }

    .login-box {
      margin: 20px auto;
      padding: 20px;
      max-width: 400px;
      background: #fff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }

    .login-box h1 {
      text-align: center;
      margin-bottom: 15px;
    }

    .input-group {
      margin-bottom: 15px;
    }

    .input-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    .input-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 16px;
    }

    button {
      width: 100%;
      padding: 12px;
      border: none;
      background-color: #4CAF50;
      color: white;
      font-size: 16px;
      cursor: pointer;
      border-radius: 4px;
    }

    button:hover {
      background-color: #45a049;
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

<!-- INTRODUÇÃO COM VÍDEO -->
<div id="video-intro">
  <video autoplay muted>
    <source src="../view/video/intro.mp4" type="video/mp4" />
    Seu navegador não suporta vídeo HTML5.
  </video>
</div>

<!-- CONTEÚDO PRINCIPAL -->
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
        <input type="text" name="cpf" required />

        <?php
        session_start();
        if (isset($_SESSION['error']) && strpos($_SESSION['error'], "CPF") !== false): ?>
          <div class="error-message"><?php echo $_SESSION['error']; ?></div>
        <?php endif; ?>
      </div>
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

  // Aguarda o vídeo terminar
  const video = videoIntro.querySelector("video");
  video.addEventListener("ended", () => {
    videoIntro.classList.add("fade-out");
    conteudoLogin.classList.add("show-content");
  });
</script>

</body>
</html>
