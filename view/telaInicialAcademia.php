<?php
/**
 * Página HTML: Página Inicial - Gyga Fit
 *
 * Esta é a página de apresentação institucional da academia Gyga Fit.
 * Apresenta seções sobre a academia, unidades, equipe de professores, aulas oferecidas,
 * horários de funcionamento e endereços com mapas integrados via Google Maps.
 *
 * Estrutura do Documento:
 * - Cabeçalho:
 *   - Logotipo da academia
 *   - Menu de navegação com âncoras para as seções da página
 *   - Botão para redirecionar à tela de login (telaLogin.php)
 *
 * - Seções:
 *   1. Sobre:
 *      - Breve descrição institucional
 *      - Galeria de imagens da academia
 *
 *   2. Unidades:
 *      - Texto informativo sobre as localizações da academia
 *
 *   3. Professores:
 *      - Apresentação da equipe com galeria de imagens
 *
 *   4. Aulas:
 *      - Modalidades de atividades físicas oferecidas
 *
 *   5. Horários:
 *      - Funcionamento semanal da academia
 *
 *   6. Endereço:
 *      - Endereços das três unidades (Centro, Norte, Sul)
 *      - Iframes do Google Maps com localização geográfica
 *
 * - Rodapé:
 *   - Direitos autorais da academia
 *
 * Tecnologias Utilizadas:
 * - HTML5, CSS3
 * - Layout responsivo via meta viewport
 * - CSS externo localizado em: /Gyga-fit-gym/view/style/inicial.css
 * - Iframes com Google Maps (via embed)
 *
 * Observações:
 * - Esta página não possui backend (apenas visual).
 * - Pode ser usada como página inicial pública da academia antes do login.
 * - O botão de login direciona para telaLogin.php (onde o sistema pode autenticar usuários).
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
    <title>Gyga Fit - Academia</title>
    <link rel="stylesheet" href="/Gyga-fit-gym/view/style/inicial.css">
</head>

<body>
    <header>
        <div class="logo">GYGA FIT
        </div>
        <nav>
            <ul>
                <li><a href="#sobre">Sobre</a></li>
                <li><a href="#unidades">Unidades</a></li>
                <li><a href="#professores">Professores</a></li>
                <li><a href="#aulas">Aulas</a></li>
                <li><a href="#horarios">Horários</a></li>
                <li><a href="#endereco">Endereço</a></li>
            </ul>
        </nav>
        <a href="telaLogin.php" class="login-button">Login</a>
    </header>

    <section id="sobre">
        <h2>Sobre a Gyga Fit</h2>
        <div class="descricao-sobre">
            <p>A Gyga Fit é uma academia moderna com foco em bem-estar, performance e saúde. Unidades equipadas e
                profissionais qualificados.</p>
        </div>

        <div class="imagens-sobre">
            <img src="img/ACAD1.jpg" alt="Foto da academia Gyga Fit 1" class="imagem-sobre">
            <img src="img/ACAD2.jpg" alt="Foto da academia Gyga Fit 2" class="imagem-sobre">
            <img src="img/ACAD3.jpg" alt="Foto da academia Gyga Fit 3" class="imagem-sobre">
            <img src="img/ACAD4.jpg" alt="Foto da academia Gyga Fit 4" class="imagem-sobre">
        </div>
    </section>


    <section id="unidades">
        <h2>Nossas Unidades</h2>
        <p>Estamos presentes na Capital do Tocantins, Palmas. Contamos com unidades no Centro, na Norte e na Sul.</p>
        <!-- Lista de unidades com imagens pode ser inserida aqui -->
    </section>

    <section id="professores">
        <h2>Professores</h2>
        <p>Conheça nossa equipe de profissionais certificados.</p>

        <div class="imagens-professores">
            <img src="img/PROF1.jpg" alt="Foto do PROFESSOR Gyga Fit 1" class="imagem-professores">
            <img src="img/PROF2.jpg" alt="Foto do PROFESSOR Gyga Fit 2" class="imagem-professores">
            <img src="img/PROF3.jpg" alt="Foto do PROFESSOR Gyga Fit 3" class="imagem-professores">
            <img src="img/PROF4.jpg" alt="Foto do PROFESSOR Gyga Fit 4" class="imagem-professores">
        </div>
    </section>

    <section id="aulas">
        <h2>Aulas</h2>
        <p>Oferecemos musculação, funcional, yoga, dança, entre outras modalidades.</p>
    </section>

    <section id="horarios">
        <h2>Horários de Funcionamento</h2>
        <p>Seg a Sex: 5h às 23h | Sáb: 8h às 18h | Dom: 8h às 12h</p>
    </section>

    <section id="endereco">
        <h2>Endereços</h2>

        <h3>Unidade Centro</h3>
        <p>Rua Exemplo, 123 - Centro, Palmas - TO</p>
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1167.5024856952741!2d-48.32626484679314!3d-10.181091615858987!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt-BR!2sbr!4v1746114217432!5m2!1spt-BR!2sbr"
            width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>

        <h3>Unidade Norte</h3>
        <p>Rua Exemplo, 321 - Norte, Palmas - TO</p>
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1651.2260457792313!2d-48.31817641334221!3d-10.156290412893734!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt-BR!2sbr!4v1746114125588!5m2!1spt-BR!2sbr"
            width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>

        <h3>Unidade Sul</h3>
        <p>Rua Exemplo, 312 - Sul, Palmas - TO</p>
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1388.2774718646194!2d-48.32628008628961!3d-10.209727443672357!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt-BR!2sbr!4v1746114248952!5m2!1spt-BR!2sbr"
            width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>

    <footer>
        <p>&copy; 2025 Gyga Fit - Todos os direitos reservados.</p>
    </footer>

    <a href="https://api.whatsapp.com/send/?phone=%2B5561999426618&text=Ol%C3%A1%2C+Tenho+interesse+em+entrar+na+Gyga+Fit.+Me+passe+mais+informa%C3%A7%C3%B5es%21&type=phone_number&app_absent=0"
        class="whatsapp-button" target="_blank" title="Fale conosco pelo WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" width="24" height="24"
            style="vertical-align: middle; margin-right: 8px;">
            <path
                d="M20.52 3.48A11.867 11.867 0 0 0 12 0C5.373 0 0 5.373 0 12c0 2.113.553 4.178 1.6 6.017L0 24l6.154-1.578A11.952 11.952 0 0 0 12 24c6.627 0 12-5.373 12-12 0-3.19-1.24-6.188-3.48-8.52zM12 21.6a9.556 9.556 0 0 1-5.017-1.422l-.36-.214-3.654.936.973-3.56-.234-.37A9.514 9.514 0 0 1 2.4 12C2.4 6.93 6.93 2.4 12 2.4S21.6 6.93 21.6 12 17.07 21.6 12 21.6zm5.25-6.72c-.288-.144-1.71-.843-1.976-.94-.266-.097-.46-.144-.655.144-.195.288-.75.94-.92 1.134-.17.195-.34.22-.627.073a7.637 7.637 0 0 1-2.242-1.381 8.48 8.48 0 0 1-1.575-1.932c-.17-.288-.018-.444.127-.585.13-.13.288-.34.432-.51.14-.17.18-.29.27-.48.088-.195.044-.366-.022-.51-.066-.144-.655-1.577-.9-2.16-.237-.57-.48-.493-.66-.503l-.56-.01c-.192 0-.505.073-.77.366-.264.293-1.01.99-1.01 2.418 0 1.428 1.034 2.806 1.178 2.998.144.195 2.035 3.11 4.938 4.368.69.297 1.23.475 1.65.608.693.22 1.32.19 1.818.116.555-.083 1.71-.697 1.954-1.37.24-.673.24-1.25.168-1.37-.07-.12-.26-.195-.548-.34z" />
        </svg>
        Fale Conosco
    </a>
    
</body>

</html>