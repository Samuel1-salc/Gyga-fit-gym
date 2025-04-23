
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYGA FIT cadastro</title>
    <link rel="stylesheet" href="../view/style/Login-Cadastro.css">
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="../view/img/logo.png" alt="GYGA FIT">
        </div>
        <div class="login-box">
            <h1>GYGA FIT</h1>
            <p>Entre com suas credenciais para Entrar</p>
            <form action="./../controllers/processarLogin.php" method="post">
           
                <div class = "input-group">
                    <label for="CPF">CPF</label>
                    <input type="text" name = 'cpf' required>

                    <?php if (isset($_SESSION['error']) && strpos($_SESSION['error'], "CPF") !== false): ?>
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
   
</body>

</html>