<?php

/**
 * Página HTML: Editar Perfil - Instrutor | Gyga Fit
 *
 * Esta página exibe um formulário para que o instrutor atualize suas informações pessoais
 * dentro do sistema Gyga Fit. Os dados são enviados via POST para o script de backend
 * responsável por processar e armazenar as alterações no perfil.
 *
 * Estrutura do documento:
 * - Cabeçalho:
 *   - Ícone do usuário
 *   - Logotipo da academia
 *   - Ícone de menu (hambúrguer)
 *
 * - Seção principal:
 *   - Visualização da foto de perfil
 *   - Botão para troca de imagem (ainda sem funcionalidade JS)
 *   - Informações pessoais organizadas em formulário
 *
 * - Formulário:
 *   - Método: POST
 *   - Ação: ./../controllers/processarCadastroInstrutor.php
 *   - Campos:
 *     - campo1: Nome (input text)
 *     - campo2: Idade (input number)
 *     - campo3: E-mail (input email)
 *     - campo4: Celular (input tel)
 *     - campo5: CPF (input text)
 *     - campo6: Unidade (select - Centro, Norte, Sul)
 *     - campo7: Serviço (select - Personal Trainer, Nutricionista, Fisioterapeuta)
 *
 * - Rodapé:
 *   - Informações institucionais da empresa
 *   - Links úteis: Fale Conosco, Política de Privacidade
 *   - Redes sociais: Facebook, Pinterest, Instagram, YouTube
 *
 * Requisitos e tecnologias:
 * - CSS externo: style/styleCadasto.css
 * - Ícones: Font Awesome (versão 6.4.0)
 * - Compatível com dispositivos móveis (viewport configurado)
 *
 * Observações:
 * - A foto de perfil atualmente é estática (sem upload implementado).
 * - Este arquivo é um front-end puro e precisa ser conectado a um back-end funcional.
 *
 * @package GygaFit
 * @subpackage Views
 * @version 1.0
 */
?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Academia | Gyga Fit</title>
    <link rel="stylesheet" href="./style/styleCadasto.css">
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

    <main>
        <div class="profile-container">
            <h1>Academia</h1>

            <div class="profile-photo-section">
                <div class="profile-photo">
                    <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-XKMyXzE7X0tDSQqThi9Jb78agdvqK1.png" alt="Foto de perfil">
                </div>
                <div class="photo-actions">
                    <button class="photo-btn">Escolha nova foto</button>
                    <p class="photo-info">Tamanho recomendado: 800x800 px<br>JPG ou PNG</p>
                </div>
            </div>

            <hr>
            <div class="academias-info">
                <h2>INFORMAÇÕES</h2>
                <form method="POST" action="./../controllers/AcademiaController.php?acao=cadastrar">
                    <div class="question-container">
                        <div class="form-group">
                            <label for="nome">Nome da Unidade</label>
                            <div class="input-with-icon">
                                <input type="text" id="nome" name="campo1">
                            </div>
                        </div>
                    </div>

                    <div class="question-container">
                        <div class="form-group">
                            <label for="idade">Cep</label>
                            <div class="input-with-icon">
                                <input type="text" id="cep" name="campo2">
                            </div>
                        </div>
                    </div>

                    <div class="question-container">
                        <div class="form-group">
                            <label for="email">E-mail da academia</label>
                            <div class="input-with-icon">
                                <input type="email" id="email" name="campo3">

                            </div>
                        </div>
                    </div>

                    <div class="question-container">

                        <div class="form-group">
                            <label for="cnpj">cnpj</label>
                            <div class="input-with-icon">
                                <input type="text" id="cnpj" name="campo4">

                            </div>
                        </div>
                    </div>

                    <div class="question-container">
                        <div class="form-group">
                            <label for="capacidade">Capacidade</label>
                            <div class="input-with-icon">
                                <input type="text" id="capacidade" name="campo5">

                            </div>
                        </div>
                    </div>

                    <div class="question-container">
                        <div class="option-card" data-value="Centro">
                            <input type="radio" id="centro" name="campo6" value="Centro">
                            <div class="option-icon">
                                <i class="faz fa-seedling"></i>
                            </div>
                            <div class="option-text">
                                <div class="option-label">Centro</div>
                                <div class="option-description">Unidade localizada no centro da cidade, com fácil acesso e diversas opções de serviços.</div>
                            </div>
                        </div>

                        <div class="option-card" data-value="Norte">
                            <input type="radio" id="norte" name="campo6" value="Norte">
                            <div class="option-icon">
                                <i class="faz fa-seedling"></i>
                            </div>
                            <div class="option-text">
                                <div class="option-label">Norte</div>
                                <div class="option-description">Unidade localizada na região norte, oferecendo uma ampla gama de atividades e serviços.</div>
                            </div>
                        </div>

                        <div class="option-card" data-value="Sul">
                            <input type="radio" id="sul" name="campo6" value="Sul">
                            <div class="option-icon">
                                <i class="faz fa-seedling"></i>
                            </div>
                            <div class="option-text">
                                <div class="option-label">Sul</div>
                                <div class="option-description">Unidade situada na região sul, com infraestrutura moderna e equipe qualificada.</div>
                            </div>
                        </div>
                    </div>

                    <input class="cadastro-btn" type="submit" value="Cadastrar" name="submit">
                </form>

                <hr>

                <div class="footer-info">
                    <div class="company-info">
                        <img src="./img/logo.png" alt="Gyga Fit Logo" class="footer-logo">
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