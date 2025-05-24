<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!-- Metadados e títulos -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requerimento de Planilha de Treino - GYGA FIT</title>

    <!-- Fontes e estilos externos -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style/tela-Form-moderno.css">
</head>

<body>
    <!-- Header Moderno -->
    <header class="header-moderno">
        <div class="header-container">
            <div class="header-left">
                <button class="btn-menu" onclick="toggleMenu()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <div class="logo-text">
                        <h1>GYGA FIT</h1>
                        <p>Solicitar Plano de Treino</p>
                    </div>
                </div>
            </div>
            <div class="header-right">
                <button class="btn-header" onclick="toggleNotifications()">
                    <i class="fas fa-bell"></i>
                </button>
                <button class="btn-header" onclick="abrirConfiguracoes()">
                    <i class="fas fa-cog"></i>
                </button>
                <div class="avatar">
                    <span>A3</span>
                </div>
            </div>
        </div>
    </header>

    <div class="main-container">
        <!-- Header do Formulário -->
        <div class="form-header-card">
            <div class="form-header-gradient"></div>
            <div class="form-header-content">
                <div class="form-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div class="form-title">
                    <h1>Requerimento de Planilha de Treino</h1>
                    <p>Preencha as informações para receber seu plano personalizado</p>
                </div>
            </div>
        </div>

        <!-- Formulário Principal -->
        <form action="../controllers/processarSolicitacaoDeTreino.php" method="post" class="form-moderno">

            <!-- Pergunta 1: Experiência -->
            <div class="question-card">
                <div class="question-header">
                    <div class="question-number blue">1</div>
                    <div class="question-title">
                        <h3>Qual a sua experiência na academia?</h3>
                        <p>Selecione o nível que melhor descreve sua experiência</p>
                    </div>
                </div>
                <div class="question-content">
                    <div class="options-grid">
                        <label class="option-card" data-value="Iniciante">
                            <input type="radio" name="experiencia" value="Iniciante" required>
                            <div class="option-icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <div class="option-text">
                                <div class="option-label">Iniciante</div>
                                <div class="option-desc">Começando agora</div>
                            </div>
                        </label>

                        <label class="option-card" data-value="Intermediário">
                            <input type="radio" name="experiencia" value="Intermediário">
                            <div class="option-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="option-text">
                                <div class="option-label">Intermediário</div>
                                <div class="option-desc">Pelo menos 2 anos</div>
                            </div>
                        </label>

                        <label class="option-card" data-value="Avançado">
                            <input type="radio" name="experiencia" value="Avançado">
                            <div class="option-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="option-text">
                                <div class="option-label">Avançado</div>
                                <div class="option-desc">Pelo menos 5 anos</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Pergunta 2: Objetivo -->
            <div class="question-card">
                <div class="question-header">
                    <div class="question-number green">2</div>
                    <div class="question-title">
                        <h3>Qual o seu objetivo na academia?</h3>
                        <p>Escolha o objetivo principal do seu treino</p>
                    </div>
                </div>
                <div class="question-content">
                    <div class="options-grid two-columns">
                        <label class="option-card" data-value="Hipertrofia">
                            <input type="radio" name="objetivo" value="Hipertrofia" required>
                            <div class="option-icon">
                                <i class="fas fa-dumbbell"></i>
                            </div>
                            <div class="option-text">
                                <div class="option-label">Hipertrofia</div>
                                <div class="option-desc">Ganho de massa muscular</div>
                            </div>
                        </label>

                        <label class="option-card" data-value="Força">
                            <input type="radio" name="objetivo" value="Força">
                            <div class="option-icon">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div class="option-text">
                                <div class="option-label">Força</div>
                                <div class="option-desc">Desenvolvimento de força</div>
                            </div>
                        </label>

                        <label class="option-card" data-value="Recuperação">
                            <input type="radio" name="objetivo" value="Recuperação">
                            <div class="option-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="option-text">
                                <div class="option-label">Recuperação</div>
                                <div class="option-desc">Recuperação contra lesão</div>
                            </div>
                        </label>

                        <label class="option-card" data-value="Explosão">
                            <input type="radio" name="objetivo" value="Explosão">
                            <div class="option-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="option-text">
                                <div class="option-label">Explosão</div>
                                <div class="option-desc">Explosão e coordenação motora</div>
                            </div>
                        </label>

                        <label class="option-card" data-value="Manutenção">
                            <input type="radio" name="objetivo" value="Manutenção">
                            <div class="option-icon">
                                <i class="fas fa-balance-scale"></i>
                            </div>
                            <div class="option-text">
                                <div class="option-label">Manutenção</div>
                                <div class="option-desc">Manutenção geral da saúde</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Pergunta 3: Frequência -->
            <div class="question-card">
                <div class="question-header">
                    <div class="question-number purple">3</div>
                    <div class="question-title">
                        <h3>Quantos dias por semana pretende treinar?</h3>
                        <p>Selecione a frequência ideal para sua rotina</p>
                    </div>
                </div>
                <div class="question-content">
                    <div class="frequency-grid">
                        <label class="frequency-card" data-value="3">
                            <input type="radio" name="treinos" value="3" required>
                            <div class="frequency-number">3</div>
                            <div class="frequency-label">dias</div>
                        </label>

                        <label class="frequency-card" data-value="4">
                            <input type="radio" name="treinos" value="4">
                            <div class="frequency-number">4</div>
                            <div class="frequency-label">dias</div>
                        </label>

                        <label class="frequency-card" data-value="5">
                            <input type="radio" name="treinos" value="5">
                            <div class="frequency-number">5</div>
                            <div class="frequency-label">dias</div>
                        </label>

                        <label class="frequency-card" data-value="6">
                            <input type="radio" name="treinos" value="6">
                            <div class="frequency-number">6</div>
                            <div class="frequency-label">dias</div>
                        </label>

                        <label class="frequency-card" data-value="7">
                            <input type="radio" name="treinos" value="7">
                            <div class="frequency-number">7</div>
                            <div class="frequency-label">dias</div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Pergunta 4: Sexo -->
            <div class="question-card">
                <div class="question-header">
                    <div class="question-number orange">4</div>
                    <div class="question-title">
                        <h3>Sexo</h3>
                        <p>Selecione uma opção</p>
                    </div>
                </div>
                <div class="question-content">
                    <div class="gender-grid">
                        <label class="gender-card" data-value="M">
                            <input type="radio" name="sexo" value="M">
                            <div class="gender-icon">♂</div>
                            <div class="gender-label">Masculino</div>
                        </label>

                        <label class="gender-card" data-value="F">
                            <input type="radio" name="sexo" value="F">
                            <div class="gender-icon">♀</div>
                            <div class="gender-label">Feminino</div>
                        </label>

                        <label class="gender-card" data-value="O">
                            <input type="radio" name="sexo" value="O">
                            <div class="gender-icon">⚧</div>
                            <div class="gender-label">Outro</div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Perguntas 5 e 6: Peso e Altura -->
            <div class="input-grid">
                <div class="question-card">
                    <div class="question-header">
                        <div class="question-number red">5</div>
                        <div class="question-title">
                            <h3>Qual o seu peso?</h3>
                            <p>Informe em quilogramas (kg)</p>
                        </div>
                    </div>
                    <div class="question-content">
                        <div class="input-container">
                            <i class="fas fa-weight input-icon"></i>
                            <input type="number" name="peso" placeholder="Ex: 70" required>
                            <span class="input-unit">kg</span>
                        </div>
                    </div>
                </div>

                <div class="question-card">
                    <div class="question-header">
                        <div class="question-number indigo">6</div>
                        <div class="question-title">
                            <h3>Qual a sua altura?</h3>
                            <p>Informe em centímetros (cm)</p>
                        </div>
                    </div>
                    <div class="question-content">
                        <div class="input-container">
                            <i class="fas fa-ruler-vertical input-icon"></i>
                            <input type="number" name="altura" placeholder="Ex: 175" required>
                            <span class="input-unit">cm</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botão de Envio -->
            <div class="submit-card">
                <button type="submit" class="submit-button">
                    <i class="fas fa-paper-plane"></i>
                    Enviar Formulário
                </button>
                <div class="submit-info">
                    <i class="fas fa-clock"></i>
                    <span>Seu plano será criado em até 24 horas</span>
                </div>
            </div>
        </form>

        <!-- Footer -->
        <div class="footer-card">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <div class="footer-icon">
                            <i class="fas fa-dumbbell"></i>
                        </div>
                        <div>
                            <h3>GYGA FIT</h3>
                            <p>Transformando vidas através do fitness</p>
                        </div>
                    </div>
                </div>

                <div class="footer-section">
                    <div class="footer-links">
                        <a href="#" onclick="faleConosco()">Fale Conosco</a>
                        <span>|</span>
                        <a href="#" onclick="politicaPrivacidade()">Política de Privacidade</a>
                    </div>
                </div>

                <div class="footer-section">
                    <p>Siga-nos nas redes sociais</p>
                    <div class="social-links">
                        <button onclick="abrirFacebook()"><i class="fab fa-facebook-f"></i></button>
                        <button onclick="abrirInstagram()"><i class="fab fa-instagram"></i></button>
                        <button onclick="abrirYoutube()"><i class="fab fa-youtube"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/formulario-moderno.js"></script>
</body>

</html>

<?php
/**
 * Processamento PHP (se necessário)
 */
session_start();
require_once __DIR__ . '/../controllers/processarSolicitacaoDeTreino.php';
?>