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
$solicitacaoTreino = new SolicitacaoTreino();
$alunoOriginal = $alunoInstrutor->getAlunosByIdInstrutor($instrutor['id']); // Dados originais
$aluno = $alunoOriginal; // Cópia para filtros


if (!empty($alunoOriginal)) {

    $alunosUnicos = [];
    $contadorSemId = 0;
    // Contar apenas alunos únicos, não formulários - usando dados originais
    foreach ($alunoOriginal as $item) {
        if (!empty($item['id_aluno'])) {
            $alunosUnicos[$item['id_aluno']] = true;
        } else {
            // Para alunos sem ID, usa nome como chave única
            $chaveUnica = 'sem_id_' . $contadorSemId . '_' . $item['nome_aluno'];
            $alunosUnicos[$chaveUnica] = true;
            $contadorSemId++;
        }
    }
    $countAlunos = count($alunosUnicos);
    $data_saida = $instrutor['data_saida'] ?? null;
    $disponibilidade = ($data_saida && $data_saida != '0000-00-00') ? "indisponível" : "disponível";





    /**
     * Conta quantas solicitações de treino um aluno possui
     *
     * @param int $id_aluno ID do aluno
     * @return int Número de solicitações
     */    function countSolicitacaoTreino($id_aluno)
    {
        global $aluno;
        $countSolicitacoes = 0;
        foreach ($aluno as $item) {
            if (!empty($id_aluno) && $item['id_aluno'] == $id_aluno && !empty($item['data_created'])) {
                $countSolicitacoes++;
            }
        }

        return $countSolicitacoes;
    }
    function getStatus($id_aluno)
    {
        global $aluno;
        foreach ($aluno as $item) {
            if (!empty($id_aluno) && $item['id_aluno'] == $id_aluno) {
                return $item['status'];
            }
        }
        return null;
    }
    /**
     * Extrai apenas informações únicas dos alunos (da tabela aluno_instrutor)
     * removendo duplicatas causadas pelos múltiplos formulários
     *
     * @param array $dadosAlunos Array de dados dos alunos (opcional, usa global se não fornecido)
     * @return array Array com informações únicas dos alunos
     */
    function extrairAlunosUnicos($dadosAlunos = null)
    {
        global $alunoOriginal;

        // Se não foram fornecidos dados específicos, usa os dados originais
        $dados = $dadosAlunos ?? $alunoOriginal;

        $alunosUnicos = [];
        $idsProcessados = [];
        $contadorSemId = 0; // Para alunos sem ID

        foreach ($dados as $item) {
            $idAluno = $item['id_aluno'];

            // Se o ID está vazio ou nulo, cria um identificador único
            if (empty($idAluno)) {
                $chaveUnica = 'sem_id_' . $contadorSemId . '_' . $item['nome_aluno'];
                $contadorSemId++;
            } else {
                $chaveUnica = $idAluno;
            }

            // Se ainda não processamos este aluno, adiciona às informações únicas
            if (!in_array($chaveUnica, $idsProcessados)) {
                $alunosUnicos[] = [
                    'id_aluno' => $item['id_aluno'],
                    'nome_aluno' => $item['nome_aluno'],
                    'data_solicitacao' => $item['data_solicitacao'],
                    'contato_aluno' => $item['contato_aluno'],
                    'processo' => $item['processo']
                ];
                $idsProcessados[] = $chaveUnica;
            }
        }

        return $alunosUnicos;
    }
    /**
     * Conta o total de solicitações de treino com status "em andamento"
     *
     * @return int Número de solicitações pendentes
     */
    function countPendentes()
    {
        global $alunoOriginal;
        $status = 'em andamento';
        $countPendentes = 0;
        foreach ($alunoOriginal as $item) {
            if ($item['status'] == $status) {
                $countPendentes++;
            }
        }
        return $countPendentes;
    }
    function getFormulariosByAluno($id_aluno)
    {
        global $aluno;
        $formularios = [];
        foreach ($aluno as $item) {
            // Se o ID está vazio, não há formulários (apenas dados da tabela aluno_instrutor)
            if (!empty($id_aluno) && $item['id_aluno'] == $id_aluno && !empty($item['data_created'])) {
                $formularios[] = [
                    'id_aluno' => $item['id_aluno'],
                    'nome_aluno' => $item['nome_aluno'],
                    'data_created' => $item['data_created'],
                    'experiencia' => $item['experiencia'],
                    'objetivo' => $item['objetivo'],
                    'treinos' => $item['treinos'],
                    'sexo' => $item['sexo'],
                    'peso' => $item['peso'],
                    'altura' => $item['altura'],
                    'status' => $item['status'],

                ];
            }
        }
        if (empty($formularios)) {
            return null;
        } else {
            return $formularios;
        }
    }
    function veryFyStatus($solicitacoes)
    {
        $veryFystatus = 'em andamento';
        foreach ($solicitacoes as $sol) {
            if ($sol['status'] == $veryFystatus) {
                return true;
            }
        }
        return false;
    }
    function forSearch($aluno, $search)
    {
        $result = [];

        // Verifica se os parâmetros são válidos
        if (empty($aluno) || !is_array($aluno) || empty($search)) {
            return $aluno; // Retorna os dados originais se não há busca válida
        }

        // Remove espaços extras e converte para minúsculas para busca mais eficiente
        $search = trim($search);

        foreach ($aluno as $item) {
            // Verifica se o item tem a chave 'nome_aluno' e se não está vazia
            if (isset($item['nome_aluno']) && !empty($item['nome_aluno'])) {
                // Busca case-insensitive no nome do aluno
                if (stripos($item['nome_aluno'], $search) !== false) {
                    $result[] = $item;
                }
            }
        }
        return $result;
    } // Filtro por status (usando dados já carregados)
    $mensagemFiltro = '';
    $temFiltroAtivo = false;

    if (isset($_GET['status']) && !empty($_GET['status'])) {
        $filtro = strtolower($_GET['status']);
        $temFiltroAtivo = true;

        if ($filtro !== 'todos') {
            // Filtrar apenas registros com o status específico
            $alunoFiltrado = [];
            $encontrouStatus = false;

            foreach ($aluno as $item) {
                // Só inclui registros que têm formulário e o status corresponde
                if (!empty($item['status']) && strtolower($item['status']) === $filtro) {
                    $alunoFiltrado[] = $item;
                    $encontrouStatus = true;
                }
            }

            $aluno = $alunoFiltrado;

            // Prepara mensagem se não encontrou alunos com o status específico
            if (!$encontrouStatus && $filtro === 'em andamento') {
                $mensagemFiltro = '<div style="padding: 15px; margin: 10px 0; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;"><strong>Nenhum aluno pendente encontrado.</strong></div>';
            } elseif (!$encontrouStatus && $filtro === 'atendido') {
                $mensagemFiltro = '<div style="padding: 15px; margin: 10px 0; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;"><strong>Nenhum aluno atendido encontrado.</strong></div>';
            }
        }
        // Se $filtro === 'todos', mantém $aluno como está (todos os dados)
    }    // Aplicar pesquisa sempre que houver termo de busca
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = trim($_GET['search']);
        if (!empty($search)) {
            $alunoAntesPesquisa = $aluno; // Salva o estado anterior

            // Conta alunos únicos antes da pesquisa
            $alunosUnicosAntes = extrairAlunosUnicos($alunoAntesPesquisa);
            $totalAntes = count($alunosUnicosAntes);

            $aluno = forSearch($aluno, $search);

            // Conta alunos únicos depois da pesquisa
            $alunosUnicosDepois = extrairAlunosUnicos($aluno);
            $totalDepois = count($alunosUnicosDepois);

            // Mensagens de resultado da pesquisa
            if (!empty($aluno)) {
                // Pesquisa encontrou resultados
                if ($temFiltroAtivo) {
                    $filtroNome = (isset($_GET['status']) && $_GET['status'] === 'em andamento') ? 'pendentes' : 'atendidos';
                    if ($totalDepois == 1) {
                        $mensagemFiltro = '<div style="padding: 15px; margin: 10px 0; background-color: #d1edff; color: #0c5460; border: 1px solid #b8daff; border-radius: 4px;"><strong>1 aluno ' . $filtroNome . ' encontrado para a pesquisa "' . htmlspecialchars($search) . '".</strong></div>';
                    } else {
                        $mensagemFiltro = '<div style="padding: 15px; margin: 10px 0; background-color: #d1edff; color: #0c5460; border: 1px solid #b8daff; border-radius: 4px;"><strong>' . $totalDepois . ' alunos ' . $filtroNome . ' encontrados para a pesquisa "' . htmlspecialchars($search) . '".</strong></div>';
                    }
                } else {
                    if ($totalDepois == 1) {
                        $mensagemFiltro = '<div style="padding: 15px; margin: 10px 0; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px;"><strong>1 aluno encontrado para a pesquisa "' . htmlspecialchars($search) . '".</strong></div>';
                    } else {
                        $mensagemFiltro = '<div style="padding: 15px; margin: 10px 0; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px;"><strong>' . $totalDepois . ' alunos encontrados para a pesquisa "' . htmlspecialchars($search) . '".</strong></div>';
                    }
                }
            } elseif (!empty($alunoAntesPesquisa)) {
                // Pesquisa não encontrou resultados
                if ($temFiltroAtivo) {
                    // Mensagem específica quando pesquisa com filtro ativo
                    $filtroNome = (isset($_GET['status']) && $_GET['status'] === 'em andamento') ? 'pendentes' : 'atendidos';
                    $mensagemFiltro = '<div style="padding: 15px; margin: 10px 0; background-color: #fff3cd; color: #856404; border: 1px solid #ffeaa7; border-radius: 4px;"><strong>Nenhum aluno ' . $filtroNome . ' encontrado para a pesquisa "' . htmlspecialchars($search) . '".</strong><br><small>Pesquisa realizada em ' . $totalAntes . ' aluno(s) com o filtro ativo.</small></div>';
                } else {
                    // Mensagem para pesquisa sem filtro
                    $mensagemFiltro = '<div style="padding: 15px; margin: 10px 0; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;"><strong>Nenhum aluno encontrado para a pesquisa "' . htmlspecialchars($search) . '".</strong><br><small>Pesquisa realizada em ' . $totalAntes . ' aluno(s) total.</small></div>';
                }
            }
        }
    }
} else {
    // Se não houver alunos, define variáveis vazias para evitar erros
    $countAlunos = 0;
    $mensagemFiltro = '<div><p style="color: red"><strong>Nenhum aluno encontrado.</strong></p></div>';
    $alunoOriginal = [];
    $aluno = [];
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

                // Mostra informação do filtro aplicado se não for "todos"
                $infoFiltro = '';
                if (!empty($statusSelecionado) && $statusSelecionado !== 'todos') {
                    $nomeStatus = $statusSelecionado === 'em andamento' ? 'Pendente' : 'Atendido';
                    $infoFiltro = " - Filtro: $nomeStatus ativo";
                }
                ?>
                <input type="text" name="search" placeholder="Pesquisar aluno..."
                    value="<?= htmlspecialchars($search) ?>">

                <!-- Campo hidden para manter o filtro ativo quando pesquisar -->
                <?php if (!empty($statusSelecionado)): ?>
                    <input type="hidden" name="status" value="<?= htmlspecialchars($statusSelecionado) ?>">
                <?php endif; ?>

                <button type="submit">Pesquisar</button>

                <select name="status_filter" id="status_filter" onchange="applyFilter(this.value)">
                    <option value="">Selecione um filtro...</option>
                    <option value="em andamento">Pendente</option>
                    <option value="atendido">Atendido</option>
                </select>

                <?php if (!empty($statusSelecionado) && $statusSelecionado !== 'todos'): ?>
                    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn-limpar-filtro" style="margin-left: 10px; padding: 8px 12px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;">
                        Limpar Filtros
                    </a>
                <?php endif; ?>

                <?php if (!empty($infoFiltro)): ?>
                    <span style="margin-left: 10px; color: #007bff; font-weight: bold;">
                        <?= $infoFiltro ?>
                    </span>
                <?php endif; ?>
            </form>

            <div class="secao-solicitacoes">
                <h3>Solicitações de Treino</h3>

                <?php
                // Exibe mensagem de filtro/pesquisa se houver
                if (!empty($mensagemFiltro)) {
                    echo $mensagemFiltro;
                }
                ?>

                <?php $alunosInfo = extrairAlunosUnicos($aluno); ?>

                <?php if (!empty($alunosInfo)): ?>
                    <?php foreach ($alunosInfo as $alunoAtual): ?>

                        <div class="card-aluno">
                            <?php $solicitacoes = getFormulariosByAluno($alunoAtual['id_aluno']); ?>
                            <div class="card-info">
                                <div>
                                    <?php $status = getStatus($alunoAtual['id_aluno']); ?>
                                    <?php if (!empty($status) && $status === 'em andamento'): ?>
                                        <div class="icon-notificacao"> </div>
                                    <?php endif; ?>
                                    <p><strong><?= htmlspecialchars($alunoAtual['nome_aluno']) ?></strong></p>
                                    <p><?= htmlspecialchars($alunoAtual['data_solicitacao']) ?></p>

                                    <p>Solicitações: <?= htmlspecialchars(countSolicitacaoTreino($alunoAtual['id_aluno'])) ?></p> <!-- Container oculto da solicitação -->
                                    <?php if (!empty($solicitacoes)): ?>
                                        <div id="solicitacao-<?= $alunoAtual['id_aluno'] ?>" class="solicitacao-content"
                                            style="display: none; margin-top: 10px;">
                                            <?php
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
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-botoes">

                                <?php if (!empty($solicitacoes)): ?>
                                    <button class="btn-visualizar"
                                        onclick="toggleSolicitacao('<?= $alunoAtual['id_aluno'] ?>')">Visualizar</button>
                                    <?php if (veryFyStatus($solicitacoes)): ?>
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
                <?php endif; // Fecha o if (!empty($alunosInfo)) 
                ?>
            </div>
        </div>
</body>
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

    function applyFilter(filterValue) {
        if (filterValue) {
            // Quando aplicar filtro, remover pesquisa e aplicar apenas o filtro
            window.location.href = window.location.pathname + '?status=' + encodeURIComponent(filterValue);
        }
    }
</script>

<footer>
    <img src="img/logo.png" alt="Logo GYGA FIT">
    <p>&copy; <?php echo date('Y'); ?> GYGA FIT. Todos os direitos reservados.</p>
</footer>

</html>