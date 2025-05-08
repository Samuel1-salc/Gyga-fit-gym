<?php
/**
 * Página principal do aluno no sistema GYGA FIT.
 * Exibe informações do aluno logado, incluindo plano, tempo restante, instrutor responsável e unidade.
 * Permite ao aluno visualizar o cronograma de treinos, solicitar novo treino e acessar funcionalidades do menu lateral.
 *
 * Funcionalidades:
 * - Exibe nome, plano, tempo restante do plano, nome do instrutor e unidade do aluno.
 * - Mostra cronograma de treinos com botões para alternar entre Treino A, B e C.
 * - Permite solicitar um novo treino através de botão dedicado.
 * - Oferece menu lateral com opções de alterar perfil, configurações e menu da academia.
 * - Exibe informações institucionais e links de contato no rodapé.
 *
 * Dependências:
 * - Usuarios.class.php: Classe para operações gerais de usuários.
 *
 * Fluxo:
 * 1. Inicia a sessão e carrega os dados do usuário logado.
 * 2. Define funções auxiliares para exibir nome do plano, calcular tempo restante e buscar nome do instrutor.
 * 3. Renderiza informações do aluno e cronograma de treinos.
 * 4. Disponibiliza botões para solicitar novo treino e concluir ações.
 * 5. Exibe rodapé com informações da empresa e links sociais.
 *
 * Observações:
 * - O acesso a esta página pressupõe que o usuário esteja autenticado e com dados válidos na sessão.
 * - O layout utiliza CSS externo e fontes do Google Fonts.
 * - Funções JavaScript são usadas para manipular a sidebar.
 *
 * @package view
 * @author
 * @version 1.0
 */
session_start();
require_once __DIR__ . '/../models/Usuarios.class.php';
$usuarios = new Users();
function plano($plano){
    if ($plano == 1) {
        return "Mensal";
    } elseif($plano == 2){
        return "semestral";
    } elseif($plano == 3){
        return "Anual";
    }
 }

 function diffData($dataInicio, $dataTermino) {
    $data_inicio = $dataInicio;
    $data_termino = $dataTermino;

    $inicio = new DateTime($data_inicio);
    $fim = new DateTime($data_termino);

    $intervalo = $inicio->diff($fim);

    // Verifica se há meses na diferença
    if ($intervalo->m > 0 || $intervalo->y > 0) {
        // Calcula o total de meses, incluindo os anos convertidos em meses
        $meses = ($intervalo->y * 12) + $intervalo->m;
        return" {$meses} meses";
        
        if ($intervalo->d > 0) {
            return " e {$intervalo->d} dias";
        }
    } else {
        return " {$intervalo->d} dias";
    }
 }

 function nomeInstrutor($id_aluno){
    $usuarios = new Users();
    $nomeInstrutor = $usuarios->getNomePersonalByAluno($id_aluno);
    return $nomeInstrutor['nome_instrutor'] ?? 'Não disponível';
 }
?>
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
               
            </div>
            <div class="logo">
                <img src="./img/logo.png" alt="Gyga Fit Logo" class="logo-img">
            </div>
            <button class="header-button" onclick="toggleSidebar()">☰</button>
                
            </div>
        </div>
    </header>
    <div class="sidebar" id="sidebar">
        <button class="close-button" onclick="toggleSidebar()">X</button>
        <h3>Menu</h3>
        <a href="./alterarPerfil.php">Alterar Perfil</a>
        <a href="./configuracoes.php">Configurações</a>
        <a href="./menuAcademia.php">Menu da Academia</a>
    </div>
    <main>
        <div class="info-container">
            <div class="aluno-info">
                
                <h1>Informações do Aluno</h1>
                <p><strong>Nome: </strong><?= htmlspecialchars($_SESSION['usuario']['username'])  ?? 'Usuário não autenticado'; ?></p>
                <p><strong>Plano: </strong><?= htmlspecialchars(plano($_SESSION['usuario']['plano']))  ?? 'Não disponível'; ?></p>
                <p><strong>Tempo restante: </strong><?= htmlspecialchars(diffData($_SESSION['usuario']['data_inicio'],$_SESSION['usuario']['data_termino']))  ?? 'Não disponível'; ?></p>
                <p><strong>instrutor: </strong><?= htmlspecialchars((string)nomeInstrutor($_SESSION['usuario']['id'])) ?? 'Não disponível'; ?></p>
                <p><strong>Unidade: </strong><?= htmlspecialchars($_SESSION['usuario']['unidade']) ?></p>
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
                <button class="botao-novo-treino" id="botao-novo-treino" onclick="window.location.href='./paginaFormulario.php'">Solicitar Novo Treino</button>
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
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>

</html>