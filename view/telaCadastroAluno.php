<?php
/**
 * Página HTML: Editar Perfil - Instrutor | Gyga Fit
 *
 * Esta página permite que o instrutor visualize e edite suas informações de perfil,
 * incluindo nome, idade, e-mail, celular, CPF, unidade e plano.
 *
 * O formulário envia os dados para o controlador PHP responsável por processar as alterações.
 *
 * Estrutura:
 * - Header: Ícone do usuário, logo da academia e ícone de menu.
 * - Seção principal: Foto de perfil e formulário de edição de dados.
 * - Footer: Informações da empresa e redes sociais.
 *
 * Formulário:
 * - Método: POST
 * - Ação: ./../controllers/processarCadastroAluno.php
 * - Campos:
 *   - campo1: Nome
 *   - campo2: Idade
 *   - campo3: E-mail
 *   - campo4: Celular
 *   - campo5: CPF
 *   - campo6: Unidade (Centro, Norte, Sul)
 *   - campo7: Plano (Mensal, Semestral, Anual)
 *
 * Requisitos de imagem de perfil:
 * - Formatos aceitos: JPG, PNG
 * - Tamanho recomendado: 800x800 px
 *
 * Observações:
 * - A página depende do arquivo CSS `styleCadasto.css` para estilo.
 * - Utiliza FontAwesome para os ícones visuais.
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
            <form method="POST" action="./../controllers/processarCadastroAluno.php">
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
                            <label for="Plano">Plano</label>
                            <div class="select-with-icon">
                                <select id="plano" name = "campo7">
                                    <option selected>Selecione...</option>
                                    <option value = 1>Plano Mensal</option>
                                    <option value = 2>Plano Semestral</option>
                                    <option value = 3>Plano Anual</option>
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