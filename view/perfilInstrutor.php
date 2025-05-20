<?php
/**
 * painelInstrutor.php
 *
 * Página de painel para instrutores, onde é possível visualizar informações
 * dos alunos, solicitações de treino, filtrar por status, pesquisar alunos
 * e iniciar a criação de treinos.
 *
 * @author [Seu Nome]
 * @version 1.0
 * @package painelInstrutor
 */

session_start();

require_once __DIR__ . '/../models/usuarioInstrutor.class.php';
require_once __DIR__ . '/../models/SolicitacaoTreino.class.php';
require_once __DIR__ . '/../models/Treino.class.php';

// Verifica se o usuário está logado e é um instrutor
$instrutor = $_SESSION['usuario'];
$alunoInstrutor = new aluno_instrutor();

$aluno = $alunoInstrutor->getAlunosByIdInstrutor($instrutor['id']);
$countAlunos = $alunoInstrutor->quantidadeAlunosAtendidos($instrutor['id']);

$data_saida = $instrutor['data_saida'] ?? null;
$disponibilidade = ($data_saida && $data_saida != '0000-00-00') ? "indisponível" : "disponível";

/**
 * Verifica se existe uma solicitação de treino para um aluno
 *
 * @param int $id_aluno ID do aluno
 * @return string Retorna "solicitEnviada" se já houver solicitação, "solicitNaoEnviada" caso contrário
 */
function adcAlunoSolicitacao($id_aluno)
{
    $solicitacaoTreino = new SolicitacaoTreino();
    $relacaoAlunoInstrutor = new aluno_instrutor();

    if ($solicitacaoTreino->getSolicitacaoTreino($id_aluno)) {
        return "solicitEnviada";
    } else {
        return "solicitNaoEnviada";
    }
}

/**
 * Conta quantas solicitações de treino um aluno possui
 *
 * @param int $id_aluno ID do aluno
 * @return int Número de solicitações
 */
function countSolicitacaoTreino($id_aluno)
{
    $solicitacaoTreino = new SolicitacaoTreino();
    return $solicitacaoTreino->contarSolicitacoesTreino($id_aluno);
}

/**
 * Conta o total de solicitações de treino com status "em andamento"
 *
 * @return int Número de solicitações pendentes
 */
function countPendentes()
{
    $status = 'em andamento';
    $solicitacaoTreino = new SolicitacaoTreino();
    return $solicitacaoTreino->contarPendentes($status);
}

// Pesquisa por nome de aluno
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $aluno = $alunoInstrutor->getNameAlunoForPainelInstrutor($search);
} else {
    $aluno = $alunoInstrutor->getAlunosByIdInstrutor($instrutor['id']);
}

// Filtro por status
if (isset($_GET['status'])) {
    $filtro = strtolower($_GET['status']);

    if ($filtro === 'todos') {
        $aluno = $alunoInstrutor->getAlunosByIdInstrutor($instrutor['id']);
    } else {
        $formulario = new SolicitacaoTreino();
        $todosFormularios = $formulario->getTodosFormularios(); // este método deve existir
        $aluno = [];

        foreach ($todosFormularios as $a) {
            if (strtolower($a['status']) === $filtro) {
                $aluno[] = $alunoInstrutor->getAlunosByIdAlunosForPainelInstrutor($a['id_aluno']);
                if ($filtro === 'em andamento' && empty($aluno)) {
                    echo '<div><p style = "color: red"><strong>Nenhum aluno pendente encontrado.</strong></p></div>';
                }
            }
        }
    }
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
        <a href="./editar_perfil_instrutor.php">Alterar Perfil</a>
        <a href="./configuracoes.php">Configurações</a>
        <a href="./telaInicialAcademia.php">Menu da Academia</a>

    </div>

    <div class="main-content">
        <div class="perfil-instrutor">
            <?php if (!empty($instrutor['foto'])): ?>
                <img src="../view/uploads/<?php echo htmlspecialchars($instrutor['foto']); ?>" alt="Foto do Instrutor"
                    class="foto-instrutor">
            <?php else: ?>
                <img src="./img/user-placeholder.png" alt="Sem foto" class="foto-instrutor">
            <?php endif; ?>

            <div class="perfil-detalhes">
                <p><strong>Nome:</strong> <?= htmlspecialchars($instrutor['username']) ?></p>
                <p><strong>Especialidade:</strong> <?= htmlspecialchars($instrutor['servico'] ?? 'Não informado') ?></p>
                <p><strong>Quantidade de Alunos Atendidos:</strong>
                    <?= htmlspecialchars($countAlunos ?? 'Nenhum aluno encontrado') ?></p>
                <p><strong>Pendentes:</strong> <?= htmlspecialchars(countPendentes() ?? '0') ?></p>
                <p><strong>Disponibilidade:</strong> <?= htmlspecialchars($disponibilidade) ?></p>
            </div>
        </div>

        <div class="solicitacoes">
            <form method="GET" action="" class="search-bar">
                <?php
                $search = $_GET['search'] ?? '';
                $statusSelecionado = strtolower($_GET['status'] ?? '');
                ?>
                <input type="text" name="search" placeholder="Pesquisar aluno..."
                    value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Pesquisar</button>
                <select name="status" id="status" onchange="this.form.submit()">
                    <option value="em andamento" <?= $statusSelecionado === 'em andamento' ? 'selected' : '' ?>>Pendente
                    </option>
                    <option value="atendido" <?= $statusSelecionado === 'atendido' ? 'selected' : '' ?>>Atendido</option>
                    <option value="todos" <?= $statusSelecionado === 'todos' ? 'selected' : '' ?>>Todos</option>
                </select>


            </form>
            <h3>Solicitações de Treino</h3>
            <?php foreach ($aluno as $alunoAtual): ?>

                <div class="card-aluno">
                    <div class="card-info">
                        <div>
                            <?php $formulario = new SolicitacaoTreino();
                            $status = $formulario->getFormularioForCriacaoDeTreino($alunoAtual['id_aluno']); ?>
                            <?php if (!empty($status) && isset($status['status']) && $status['status'] === 'em andamento'): ?>
                                <div class="icon-notificacao"> </div>
                            <?php endif; ?>
                            <p><strong><?= htmlspecialchars($alunoAtual['nome_aluno']) ?></strong></p>
                            <p><?= htmlspecialchars($alunoAtual['data_solicitacao']) ?></p>
                            <p>Solicitações: <?= htmlspecialchars(countSolicitacaoTreino($alunoAtual['id_aluno'])) ?></p>




                            <!-- Container oculto da solicitação -->
                            <div id="solicitacao-<?= $alunoAtual['id_aluno'] ?>" class="solicitacao-content"
                                style="display: none; margin-top: 10px;">
                                <?php
                                $solicitacoes = (new SolicitacaoTreino())->getSolicitacaoTreino($alunoAtual['id_aluno']);
                                if ($solicitacoes):
                                    foreach ($solicitacoes as $sol):
                                        ?>
                                        <p><strong>Experiençia:</strong> <?= htmlspecialchars($sol['experiencia']) ?></p>
                                        <p><strong>Objetivo:</strong> <?= htmlspecialchars($sol['objetivo']) ?></p>
                                        <p><strong>Dias de treino:</strong> <?= htmlspecialchars($sol['treinos']) ?></p>
                                        <p><strong>Peso:</strong> <?= htmlspecialchars($sol['peso']) ?></p>
                                        <p><strong>Altura:</strong> <?= htmlspecialchars($sol['altura']) ?></p>

                                        <?php
                                        $status = strtolower($sol['status']);
                                        switch ($status) {
                                            case 'em andamento':
                                                $cor = 'red';

                                                break;
                                            case 'atendido':
                                                $cor = 'green';
                                                break;
                                            default:
                                                $cor = 'gray';
                                        }
                                        ?>
                                        <p style="color: <?= $cor ?>;">Solicitação: <?= htmlspecialchars($sol['status']) ?></p>



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
                        <?php $formulario = new SolicitacaoTreino(); ?>
                        <?php if ($formulario->getFormularioForCriacaoDeTreino($alunoAtual['id_aluno'])): ?>
                            <button class="btn-visualizar"
                                onclick="toggleSolicitacao('<?= $alunoAtual['id_aluno'] ?>')">Visualizar</button>
                            <?php $statusParaNovoTreino = $formulario->getFormularioForCriacaoDeTreino($alunoAtual['id_aluno']) ?>
                            <?php if ($statusParaNovoTreino['status'] == 'em andamento'): ?>
                                <form action="../controllers/processarNovoTreino.php" method="POST">
                                    <input type="hidden" name="id_alunoNovoTreino" value="<?= $alunoAtual['id_aluno'] ?>">
                                    <input class="btn-status" value='Novo Treino' name="submit_NovoTreino" type="submit"></input>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
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