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
    <link rel="stylesheet" href="./style//perfilInstrutor.css?v=<?= time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>

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
                                <input class="btn-status" value = 'Novo Treino'name="submit_NovoTreino" type="submit"></input>
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