<?php
    session_start();
    require_once __DIR__ . '/../models/usuarioInstrutor.class.php';
    require_once __DIR__ . '/../models/SolicitacaoTreino.class.php';


//888888888888888888888888888888888888888888888888888888//
// Verifica se o usuário está logado e é um instrutor

    $instrutor = $_SESSION['usuario'];
    $alunoInstrutor = new aluno_instrutor();

    $aluno = $alunoInstrutor->getAlunosByIdInstrutor($instrutor['id']);
    $countAlunos = $alunoInstrutor->quantidadeAlunosAtendidos($instrutor['id']);

    $data_saida = $instrutor['data_saida'] ?? null;

    if ($data_saida && $data_saida != '0000-00-00') {
        $disponibilidade = "indisponível";
    } else {
        $disponibilidade = "disponível";
    }

//888888888888888888888888888888888888888888888888888888//
// Objeto para verificar solicitações de treino


    function adcAlunoSolicitacao($id_aluno){
        
        $solicitacaoTreino = new SolicitacaoTreino();
        $relacaoAlunoInstrutor = new aluno_instrutor();

        if( $solicitacaoTreino->getSolicitacaoTreino($id_aluno)){
            $processo = 'em andamento';
            $data_solicitacao = $relacaoAlunoInstrutor->dataDeSolicitacao();
            //$relacaoAlunoInstrutor->adicionarAluno_Instrutor($id_aluno,$processo,$data_solicitacao);
            return "solicitEnviada";
        }else{
            return "solicitNaoEnviada";
        }
    }
    

    //888888888888888888888888888888888888888888888888888888//
    //contar solicitações de treino
    function countSolicitacaoTreino($id_aluno){
        $solicitacaoTreino = new SolicitacaoTreino();
        return $solicitacaoTreino->contarSolicitacoesTreino($id_aluno);
    }




?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Instrutor</title>
    <link rel="stylesheet" href="./style/perfilInstrutor.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<style>
   
* {
    box-sizing: border-box;
}

body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    background-color: #f9f9f9; /* Fundo claro */
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

header {
    background-color: #333; /* Cor neutra para o cabeçalho */
    color: white;
    padding: 10px 15px; /* Reduz a largura do cabeçalho */
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.header-logo {
    display: flex;
    align-items: center;
    gap: 10px;
}

.header-logo img {
    height: 40px;
}

.header-button {
    background-color: transparent;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.header-button:hover {
    transform: scale(1.1);
}

.main-content {
    display: flex;
    flex-direction: column; /* Alinha os elementos verticalmente */
    gap: 20px;
    padding: 20px;
    box-sizing: border-box;
    width: 100%;
}

.perfil-instrutor {
    display: flex; /* Alinha o conteúdo do perfil horizontalmente */
    flex-direction: row; /* Define o layout horizontal */
    align-items: center; /* Centraliza verticalmente */
    gap: 20px; /* Espaçamento entre os elementos */
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.perfil-instrutor img {
    width: 120px; /* Ajusta o tamanho da imagem */
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ccc;
}

.perfil-instrutor .perfil-detalhes {
    flex: 1; /* Faz com que os detalhes ocupem o espaço restante */
}

.perfil-instrutor p {
    margin: 5px 0;
    font-size: 14px;
    color: #555;
}

.solicitacoes {
    flex: 1;
    background-color: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.card-aluno {
    display: flex;
    flex-direction: row; /* Alinha os elementos horizontalmente */
    align-items: flex-start;
    gap: 20px;
    padding: 15px;
    background-color: #fff;
    border-radius: 6px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 15px;
}

.card-info {
    flex: 2; /* Ocupa mais espaço */
}

.card-info p {
    margin: 0;
    font-size: 14px;
    color: #555;
}

.card-botoes {
    flex: 1; /* Ocupa menos espaço */
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: flex-end; /* Alinha os botões à direita */
}

.btn-status, .btn-visualizar {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-status {
    background-color: #ff4d4d;
    color: white;
}

.btn-status:hover {
    background-color: #d93636;
    transform: scale(1.05);
}

.btn-visualizar {
    background-color: #4caf50;
    color: white;
}

.btn-visualizar:hover {
    background-color: #388e3c;
    transform: scale(1.05);
}

.btn-add-aluno {
    padding: 6px 12px; /* Botão menor */
    font-size: 13px; /* Ajusta o tamanho da fonte */
    border-radius: 4px;
    background-color: #007bff; /* Azul para destaque */
    color: white;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s ease, transform 0.2s ease;
    display: block; /* Garante que o botão ocupe uma linha */
    margin: 15px auto 0; /* Centraliza o botão e adiciona margem superior */
    text-align: center;
}

.btn-add-aluno:hover {
    background-color: #0056b3; /* Azul mais escuro no hover */
    transform: scale(1.05); /* Leve aumento no hover */
}

.sidebar {
    position: fixed;
    top: 0;
    left: -250px; /* Sidebar fechado por padrão no lado esquerdo */
    width: 250px;
    height: 100%;
    background-color: #333;
    color: white;
    padding: 20px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2); /* Sombra ajustada para o lado esquerdo */
    transition: left 0.3s ease; /* Transição suave para abrir/fechar */
    z-index: 1000;
}

.sidebar.open {
    left: 0; /* Sidebar aberto */
}

.sidebar h3 {
    margin-top: 0;
    font-size: 18px;
    margin-bottom: 15px;
    color: white; /* Título em branco */
}

.sidebar a {
    display: block; /* Cada link ocupa uma linha */
    color: white; /* Texto branco */
    text-decoration: none; /* Remove o sublinhado */
    margin-bottom: 10px; /* Espaçamento entre os links */
    font-size: 14px; /* Tamanho da fonte ajustado */
    transition: color 0.2s ease;
}

.sidebar a:hover {
    color: #007bff; /* Azul no hover */
}

.sidebar .close-button {
    background-color: transparent;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    margin-bottom: 20px;
    transition: transform 0.2s ease;
}

.sidebar .close-button:hover {
    transform: scale(1.1);
}

/* Responsividade */
@media (max-width: 768px) {
    .main-content {
        flex-direction: column; /* Empilha os elementos verticalmente */
        gap: 15px;
    }

    .perfil-instrutor {
        flex-direction: column; /* Alinha o perfil verticalmente */
        align-items: center; /* Centraliza os elementos */
        text-align: center; /* Centraliza o texto */
    }

    .perfil-instrutor img {
        width: 100px; /* Reduz o tamanho da imagem */
        height: 100px;
    }

    .perfil-instrutor .perfil-detalhes {
        flex: none; /* Remove o comportamento de ocupar espaço restante */
        width: 100%; /* Garante que os detalhes ocupem toda a largura */
    }

    .solicitacoes {
        padding: 10px; /* Reduz o padding */
    }

    .card-aluno {
        flex-direction: column; /* Empilha os elementos do card */
        align-items: center; /* Centraliza os elementos */
        text-align: center; /* Centraliza o texto */
    }

    .card-info {
        flex: none; /* Remove o comportamento de ocupar espaço restante */
        width: 100%; /* Garante que as informações ocupem toda a largura */
    }

    .card-botoes {
        align-items: center; /* Centraliza os botões */
        width: 100%; /* Garante que os botões ocupem toda a largura */
    }

    .btn-status, .btn-visualizar {
        width: 100%; /* Botões ocupam toda a largura */
        text-align: center;
    }

    .sidebar {
        width: 200px; /* Reduz a largura do sidebar */
    }

    footer {
        padding: 10px; /* Reduz o padding */
        font-size: 12px; /* Reduz o tamanho da fonte */
    }
}

footer {
    
    color: white;
    text-align: center;
    padding: 10px 20px; /* Reduz o padding */
    margin-top: auto; /* Garante que o footer fique no final da página */
    max-width: 800px; /* Limita a largura máxima */
    margin-left: auto; /* Centraliza horizontalmente */
    margin-right: auto; /* Centraliza horizontalmente */
    border-radius: 8px; /* Bordas arredondadas */
}

footer img {
    height: 30px; /* Reduz o tamanho do logo */
    margin-bottom: 5px; /* Reduz o espaçamento abaixo do logo */
}

footer p {
    font-size: 12px; /* Fonte menor */
    margin: 5px 0;
    color: #d93636; /* Texto com cor mais suave */
}
</style>
<body>

        <header>
            <div class="header-logo">
                <button class="header-button" onclick="toggleSidebar()">☰</button>
                <img src="img/logo.png" alt="Logo GYGA FIT">
                <h1>GYGA FIT - Painel do Instrutor</h1>
            </div>
        </header>

        <div class="sidebar" id="sidebar">
            <button class="close-button" onclick="toggleSidebar()">X</button>
            <h3>Menu</h3>
            <a href="./alterarPerfil.php">Alterar Perfil</a>
            <a href="./configuracoes.php">Configurações</a>
            <a href="./menuAcademia.php">Menu da Academia</a>
        </div>

        <div class="main-content">
            <div class="perfil-instrutor">
                <img src="img/instrutor.jpg" alt="Foto do Instrutor">
                <div class="perfil-detalhes">
                    <p><strong>Nome:</strong> <?= htmlspecialchars($instrutor['username']) ?></p>
                    <p><strong>Especialidade:</strong> <?= htmlspecialchars($instrutor['servico'] ?? 'Não informado') ?></p>
                    <p><strong>Quantidade de Alunos Atendidos:</strong> <?= htmlspecialchars($countAlunos ?? 'Nenhum aluno encontrado') ?></p>
                    <p><strong>Disponibilidade:</strong> <?= htmlspecialchars($disponibilidade) ?></p>
                </div>
            </div>

            <div class="solicitacoes">
                <h3>Solicitações de Treino</h3>
                <?php foreach ($aluno as $aluno): ?>
                    
                    <div class="card-aluno">
                        <div class="card-info">
                            <div>
                                <p><strong><?= htmlspecialchars($aluno['nome_aluno']) ?></strong></p>
                                <p><?= htmlspecialchars($aluno['data_solicitacao']) ?></p>
                                <p>Status: <?= htmlspecialchars($aluno['processo']) ?></p>
                                <p>Solicitações: <?= htmlspecialchars(countSolicitacaoTreino($aluno['id_aluno'])) ?></p>

                                <!-- Container oculto da solicitação -->
                                <div id="solicitacao-<?= $aluno['id_aluno'] ?>" class="solicitacao-content" style="display: none; margin-top: 10px;">
                                    <?php
                                        $solicitacoes = (new SolicitacaoTreino())->getSolicitacaoTreino($aluno['id_aluno']);
                                        if ($solicitacoes):
                                            foreach ($solicitacoes as $sol):
                                    ?>
                                            <p><strong>Experiençia:</strong> <?= htmlspecialchars($sol['experiencia']) ?></p>
                                            <p><strong>Objetivo:</strong> <?= htmlspecialchars($sol['objetivo']) ?></p>
                                            <p><strong>Dias de treino:</strong> <?= htmlspecialchars($sol['treinos']) ?></p>
                                            <p><strong>Peso:</strong> <?= htmlspecialchars($sol['peso']) ?></p>
                                            <p><strong>Altura:</strong> <?= htmlspecialchars($sol['altura']) ?></p>
                                            <hr>
                                    <?php
                                            endforeach;
                                        else:
                                            echo "<p>Nenhuma solicitação encontrada.</p>";
                                        endif;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-botoes">
                            <button class="btn-visualizar" onclick="toggleSolicitacao('<?= $aluno['id_aluno'] ?>')">Visualizar</button>
                            <form action="../controllers/processarNovoTreino.php" method="POST">
                                <input type="hidden" name="id_alunoNovoTreino" value="<?= $aluno['id_aluno'] ?>">
                                <button class="btn-status" name="submit_NovoTreino" type="submit">Novo Treino</button>
                            </form>
                        </div>
                    </div>
                    
                <?php endforeach; ?>
                <button class="btn-add-aluno" onclick="window.location.href='./alunos.php'">Adicionar Aluno</button>
            </div>
        </div>

        <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        function toggleSolicitacao(id) {
            const element = document.getElementById('solicitacao-' + id);
            if (element.style.display === 'none' || element.style.display === '') {
                element.style.display = 'block';
            } else {
                element.style.display = 'none';
            }
        }
    </script>
            
    <footer>
        <img src="img/logo.png" alt="Logo GYGA FIT">
        <p>&copy; <?= date('Y') ?> GYGA FIT. Todos os direitos reservados.</p>
    </footer>
</body>


</html>