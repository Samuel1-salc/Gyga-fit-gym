<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gyga Fit - Painel Administrativo</title>
    <link rel="stylesheet" href="style/stylePainel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

    <main class="container">
        <div class="painel-header">
            <h1>Painel Administrativo</h1>
        </div>

        <div class="tabs">
            <button class="tab-btn active" data-tab="alunos">Alunos</button>
            <button class="tab-btn" data-tab="personais">Personais</button>
            <button class="tab-btn" data-tab="academia">Academia</button>
        </div>

        <div class="search-bar">
            <input type="text" placeholder="Buscar...">
            <button class="search-btn">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- Conteúdo da aba Alunos -->
        <div class="tab-content" id="alunos-content">
            <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>PAINEL DE ALUNOS</h2>
                <button class="btn-cadastrar">
                    <i class="fas fa-plus"></i>
                    <span>Cadastrar Aluno</span>
                </button>
            </div>

            <div class="aluno-card">
                <div class="aluno-avatar">
                    <img src="https://via.placeholder.com/100/333333/FFFFFF?text=J" alt="José">
                </div>
                <div class="aluno-info">
                    <h3>José</h3>
                    <p>Plano: anual</p>
                    <p>Tempo restante: 1 mês</p>
                </div>
                <div class="aluno-action">
                    <button class="btn-editar">
                        <i class="fas fa-cog"></i>
                        <span>Editar</span>
                    </button>
                </div>
            </div>

            <div class="aluno-card">
                <div class="aluno-avatar">
                    <img src="C:\xampp\htdocs\testeGyga\img\maria.jpg" alt="Maria">
                </div>
                <div class="aluno-info">
                    <h3>Maria</h3>
                    <p>Plano: anual</p>
                    <p>Tempo restante: 1 mês</p>
                </div>
                <div class="aluno-action">
                    <button class="btn-editar">
                        <i class="fas fa-cog"></i>
                        <span>Editar</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Conteúdo da aba Personais -->
        <div class="tab-content" id="personais-content" style="display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>PAINEL DE PERSONAIS</h2>
                <button class="btn-cadastrar">
                    <i class="fas fa-plus"></i>
                    <span>Cadastrar Personal</span>
                </button>
            </div>

            <div class="personal-card">
                <div class="personal-avatar">
                    <img src="https://via.placeholder.com/100/0066CC/FFFFFF?text=C" alt="Carlos">
                </div>
                <div class="personal-info">
                    <h3>Carlos</h3>
                    <p>Academia: ACADEMIA 2</p>
                    <p>Alunos ativos: 12</p>
                </div>
                <div class="personal-action">
                    <button class="btn-editar">
                        <i class="fas fa-cog"></i>
                        <span>Editar</span>
                    </button>
                </div>
            </div>

            <div class="personal-card">
                <div class="personal-avatar">
                    <img src="https://via.placeholder.com/100/CC0066/FFFFFF?text=A" alt="Ana">
                </div>
                <div class="personal-info">
                    <h3>Ana</h3>
                    <p>Academia: ACADEMIA 1</p>
                    <p>Alunos ativos: 8</p>
                </div>
                <div class="personal-action">
                    <button class="btn-editar">
                        <i class="fas fa-cog"></i>
                        <span>Editar</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Conteúdo da aba Academia -->
        <div class="tab-content" id="academia-content" style="display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>PAINEL DE ACADEMIAS</h2>
                <button class="btn-cadastrar">
                    <i class="fas fa-plus"></i>
                    <span>Cadastrar Academia</span>
                </button>
            </div>

            <div class="acad-card">
                <div class="acad-avatar">
                    <img src="https://via.placeholder.com/100/0066CC/FFFFFF?text=C" alt="Carlos">
                </div>
                <div class="acad-info">
                    <h3>UNIDADE 1</h3>
                    <p>Capacidade de alunos: 120</p>
                    <p>Alunos ativos: 120</p>
                    <p>Personais: 10</p>
                </div>

                <div class="acad-action">
                    <button class="btn-editar">
                        <i class="fas fa-cog"></i>
                        <span>Editar</span>
                    </button>
                </div>
            </div>

            <div class="acad-card">
                <div class="acad-avatar">
                    <img src="https://via.placeholder.com/100/CC0066/FFFFFF?text=A" alt="Ana">
                </div>
                <div class="acad-info">
                    <h3>UNIDADE 2</h3>
                    <p>Capacidade de alunos: 100</p>
                    <p>Alunos ativos: 90</p>
                    <p>Personais: 5</p>
                </div>

                <div class="acad-action">
                    <button class="btn-editar">
                        <i class="fas fa-cog"></i>
                        <span>Editar</span>
                    </button>
                </div>
            </div>
        </div>
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
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.getAttribute('data-tab');
                    
                    // Remove a classe 'active' de todos os botões
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Adiciona a classe 'active' ao botão clicado
                    this.classList.add('active');
                    
                    // Esconde todos os conteúdos de abas
                    tabContents.forEach(content => content.style.display = 'none');
                    
                    // Mostra o conteúdo da aba selecionada
                    document.getElementById(`${tabName}-content`).style.display = 'block';
                });
            });
        });
    </script>
</body>
</html>