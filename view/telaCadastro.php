
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
            <img src="img/logo.png" alt="GYGA FIT">
        </div>
        <div class="login-box">
            <h1>GYGA FIT</h1>
            <p>Entre com suas credenciais para cadastrar</p>
            <form action="/../../api/processarCadastro.php" method="post">
                <div class="input-group">
                    <label for="email">Email</label>
                     <input type="text" name = 'campo1'  placeholder="seu@email.com" required>
                </div>
                <div class = "input-group">
                    <label for="username">Usuário</label>
                     <input type="text" name = 'campo2' required>
                </div>
                <div class = "input-group">
                    <label for="cpf">cpf</label>
                    <input type="text" name = 'campo3' required>
                </div>
                <div class="input-group">
                    <label for="password">Senha</label>
                    <input type="password" name = 'campo4'  required>
                </div>
                <div class = "input-group">
                    <label for="confirmarSenha">Confirme a senha</label>
                    <input type="password" name = 'campo5' required>
                </div>
                <button type="submit">cadastrar</button>
            </form>
        </div>
        <div class="footer">
            © 2025 
        </div>
    </div>
   
</body>

</html>