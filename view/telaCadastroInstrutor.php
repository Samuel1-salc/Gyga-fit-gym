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
    <title>Editar Perfil - Instrutor | Gyga Fit</title>
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
                <img src="./img/logo.png" alt="Oryx Fit Logo" class="logo-img">
            </div>
            <div class="menu-icon">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <main>
        <div class="profile-container">
            <h1>Editar Perfil</h1>

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

            <div class="personal-info">
                <h2>INFORMAÇÕES PESSOAIS</h2>
            <form method="POST" action="./../controllers/processarCadastroInstrutor.php">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <div class="input-with-icon">
                                <input type="text" id="nome" name = "campo1">
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="idade">Idade</label>
                            <div class="input-with-icon">
                                <input type="number" id="idade" name = "campo2" >
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <div class="input-with-icon">
                                <input type="email" id="email" name = "campo3">
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="celular">Celular</label>
                            <div class="input-with-icon">
                                <input type="tel" id="celular" name = "campo4">
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cpf">Cpf</label>
                            <div class="input-with-icon">
                                <input type="cpf" id="cpf" name = "campo5">
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="unidade">Unidade</label>
                            <div class="select-with-icon">
                                <select id="unidade" name = "campo6">
                                    <option selected>Selecione...</option>
                                    <option>Centro</option>
                                    <option>Norte</option>
                                    <option>Sul</option>
                                </select>
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="servico">Serviço</label>
                            <div class="select-with-icon">
                                <select id="servico" name = "campo7">
                                    <option selected>Selecione...</option>
                                    <option>Personal Trainer</option>
                                    <option>Nutricionista</option>
                                    <option>Fisioterapeuta</option>
                                </select>
                                
                            </div>
                        </div>
                        
                        <button type="submit">cadastrar</button>

                </form>
          
            <hr>

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