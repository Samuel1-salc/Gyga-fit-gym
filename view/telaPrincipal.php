<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYGA FIT</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./style/Tela-Principal.css">
</head>

<body>
    <header>
        <div class="header-container">
            <div class="user-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="logo">
                <img src="./img/logo.png" alt="Gyga Fit Logo" class="logo-img">
            </div>
            <div class="menu-icon">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>
    
    <main>
        <div class="info-container">
            <div class="aluno-info">
                <h1>Informações do Aluno</h1>
                    <p><strong>Nome: </strong><?php echo $_SESSION['usuario']['username'] ?? 'Usuário não autenticado';?></p>
                    <p><strong>Altura: </strong><?php echo $_SESSION['usuario']['altura'] ?? 'Não disponível'; ?></p>
                    <p><strong>Peso: </strong><?php echo $_SESSION['usuario']['peso'] ?? 'Não disponível'; ?></p>
                    <p><strong>Sexo: </strong><?php echo $_SESSION['usuario']['sexo'] ?? 'Não disponível'; ?></p>
                </div>
                
                <div id="treino" class="treino">    
                    <h3>Cronograma de Treinos</h3>
                    <p>Confira seu cronograma de treinos!</p>
                </div>

                <div class="dias-semana button">
                    <button onclick="mostrarTreino('treinoA')">Treino A</button>
                    <button onclick="mostrarTreino('treinoB')">Treino B</button>
                    <button onclick="mostrarTreino('treinoC')">Treino C</button>
                </div>

                <div class="botao-container">
                    <button class="botao-novo-treino" id="botao-novo-treino" onclick="solicitarNovoTreino()">Solicitar Novo Treino</button>
                    <button class="botao-registrar" onclick="abrirRegist()">Concluído</button>
                </div>
                
                <div class="footer-info">
                    <div class="company-info">
                    <img src="./img/logo.png" alt="gyga Fit Logo" class="footer-logo">
                        <div class="company-text">
                            <h3>Gyga Fit</h3>
                            <p>Na Gyga Fit, acreditamos em tornar a academia acessível para todos. Nossa abordagem é simples e eficaz, ajudando você a atingir seus objetivos de saúde e bem-estar com facilidade.</p>
                        </div>
                    </div>
                    <div class="footer-links">
                        <a href="#">Fale Conosco</a>
                        <span class="divider">|</span>
                        <a href="#">Política de Privacidade</a>
                    </div>
                    <div class="social-links">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-pinterest-p"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
        </div>
    </main> 
</body>

</html>