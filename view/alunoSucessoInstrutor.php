<!-- alunoSucessoInstrutor.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro Realizado</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="seu-arquivo.css"> <!-- Altere para o nome real do seu CSS -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            padding: 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        .sucesso-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 80vh;
            text-align: center;
        }

        .sucesso-container h2 {
            color: #28a745;
            margin-bottom: 20px;
        }

        .sucesso-container p {
            font-size: 20px;
            margin-bottom: 30px;
        }

        .btn-voltar {
            background-color: #ff2f00;
            color: #fff;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
        }

        .btn-voltar:hover {
            background-color: #e94e1f;
        }

        footer {
            background-color: #f0f0f0;
            text-align: center;
            padding: 15px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">Sistema de Treinamento</div>
        <nav>
            <ul>
                <li><a href="perfilInstrutor.php">Perfil</a></li>
                <!-- Adicione outros links conforme necessário -->
            </ul>
        </nav>
    </header>

    <main>
        <div class="sucesso-container">
            <h2>Aluno Cadastrado com Sucesso!</h2>
            <p>O aluno foi cadastrado ao instrutor com sucesso. Aperte "OK" para retornar ao seu perfil.</p>
            <a href="perfilInstrutor.php" class="btn-voltar">OK</a>
        </div>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> Sistema de Treinamento. Todos os direitos reservados.
    </footer>

</body>
</html>
