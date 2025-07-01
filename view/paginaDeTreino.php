<?php

/**
 * Página responsável por carregar os dados iniciais para a criação de um plano de treino
 * com base na solicitação de um aluno específico.
 */

session_start();

require_once __DIR__ . '/../models//Usuarios.class.php';
require_once __DIR__ . '/../models//SolicitacaoTreino.class.php';
require_once __DIR__ . '/../models//Treino.class.php';

$id_aluno = $_GET['id_aluno'] ?? null;

if ($id_aluno != null) {
  function getNomeAluno($id_aluno)
  {
    $users = new Users();
    $aluno = $users->getNomeAluno($id_aluno);
    return $aluno['username'] ?? 'Aluno não encontrado';
  }

  function getTreinos($id_aluno)
  {
    $solicitacao = new SolicitacaoTreino();
    $formulario = $solicitacao->getFormularioForCriacaoDeTreino($id_aluno);
    return $formulario['treinos'] ?? 'Solicitação não encontrada';
  }

  function getExperiencia($id_aluno)
  {
    $solicitacao = new SolicitacaoTreino();
    $formulario = $solicitacao->getFormularioForCriacaoDeTreino($id_aluno);
    return $formulario['experiencia'] ?? 'experiencia não encontrada';
  }

  function getObjetivo($id_aluno)
  {
    $solicitacao = new SolicitacaoTreino();
    $formulario = $solicitacao->getFormularioForCriacaoDeTreino($id_aluno);
    return $formulario['objetivo'] ?? 'objetivo não encontrado';
  }

  function getIdformulario($id_aluno)
  {
    $solicitacao = new SolicitacaoTreino();
    $formulario = $solicitacao->getFormularioForCriacaoDeTreino($id_aluno);
    return $formulario['id'] ?? 'id não encontrado';
  }

  $valorQtdTreinos = (int) (getTreinos($id_aluno) ?? 0);
  $grupoTreino = (string) getTreinos($id_aluno) ?? 'Grupos de treino não encontrado';
  $objetivo = (string) getObjetivo($id_aluno) ?? 'Objetivo não encontrado';
  $experiencia = (string) getExperiencia($id_aluno) ?? 'Experiência não encontrada';
  $id_solicitacao = (int) getIdformulario($id_aluno) ?? 0;

  $classTreino = new Treino();
  $grupo_muscular = $classTreino->getGrupo_muscular();
  $exercicios = $classTreino->getExercicios();

  if (!is_array($grupo_muscular) || !is_array($exercicios)) {
    throw new Exception("Os dados de grupo muscular ou exercícios são inválidos.");
  }
} else {
  throw new Exception("ID do aluno não foi fornecido.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Criar Plano de Treino - GYGA FIT</title>
  <link rel="stylesheet" href="./style/stylePaginaDeTreino.css?v=<?= time(); ?>" />
  <!-- Adicionando ícones Lucide via CDN -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  <style>
    /* Estilos adicionais para ícones e melhorias */
    .icon {
      width: 20px;
      height: 20px;
      display: inline-block;
      vertical-align: middle;
    }

    .header-icon {
      width: 24px;
      height: 24px;
      margin-right: 8px;
    }

    .metric-icon {
      width: 32px;
      height: 32px;
    }

    .btn-icon {
      width: 16px;
      height: 16px;
      margin-right: 8px;
    }
  </style>
</head>

<body>
  <!-- Header Profissional -->
  <header>
    <div class="header-logo">
      <div class="header-icon-container">
        <i data-lucide="dumbbell" class="header-icon"></i>
      </div>
      <div>
        <h1>GYGA FIT</h1>
        <p class="header-subtitle">Sistema de Treinamento</p>
      </div>
    </div>
    <div class="header-actions">
      <button type="button" class="btn-header">
        <i data-lucide="eye" class="btn-icon"></i>
        Preview
      </button>
      <button type="button" class="btn-header">
        <i data-lucide="save" class="btn-icon"></i>
        Salvar Rascunho
      </button>
    </div>
  </header>

  <div class="container">
    <!-- Título da Página -->
    <div class="page-title">
      <h2>Criar Plano de Treino</h2>
      <p class="page-subtitle">Desenvolva um programa personalizado para seu aluno</p>
    </div>

    <form id="formPlano" action="./../controllers//processarNovoTreino.php" method="POST">
      <!-- Card de Informações do Aluno Melhorado -->
      <div class="card-aluno">
        <div class="aluno-header">
          <div class="aluno-avatar">
            <div class="avatar-circle">
              <?= strtoupper(substr(getNomeAluno($id_aluno), 0, 1)) ?>
            </div>
          </div>
          <div class="aluno-info-header">
            <h3 class="aluno-nome"><?= htmlspecialchars(getNomeAluno($id_aluno)) ?></h3>
            <div class="aluno-badges">
              <span class="badge badge-primary">Aluno Ativo</span>
            </div>
          </div>
        </div>

        <div class="aluno-metricas">
          <div class="metrica-card dias">
            <div class="metrica-icon">
              <i data-lucide="calendar" class="metric-icon"></i>
            </div>
            <div class="metrica-content">
              <div class="metrica-numero"><?= htmlspecialchars($grupoTreino) ?></div>
              <div class="metrica-label">Dias por Semana</div>
            </div>
          </div>

          <div class="metrica-card objetivo">
            <div class="metrica-icon">
              <i data-lucide="target" class="metric-icon"></i>
            </div>
            <div class="metrica-content">
              <div class="metrica-numero"><?= htmlspecialchars($objetivo) ?></div>
              <div class="metrica-label">Objetivo</div>
            </div>
          </div>

          <div class="metrica-card experiencia">
            <div class="metrica-icon">
              <i data-lucide="trending-up" class="metric-icon"></i>
            </div>
            <div class="metrica-content">
              <div class="metrica-numero"><?= htmlspecialchars($experiencia) ?></div>
              <div class="metrica-label">Experiência</div>
            </div>
          </div>
        </div>

        <input type="hidden" name="id_solicitacao" value="<?= $id_solicitacao ?>">
      </div>

      <!-- Campos ocultos -->
      <input type="hidden" name="id_aluno" value="<?= htmlspecialchars($id_aluno) ?>">

      <!-- Container de Treinos -->
      <div id="treinosContainer" class="treinos-container"></div>

      <!-- Botões de Ação -->
      <div class="action-buttons">
        <button type="button" class="botao-mais-treino" onclick="adicionarTreino()">
          <i data-lucide="plus" class="btn-icon"></i>
          Adicionar Novo Treino
        </button>

        <button type="submit" name="submit_plano" class="botao-progresso">
          <i data-lucide="check-circle" class="btn-icon"></i>
          Finalizar e Enviar Plano
        </button>
      </div>

      <!-- Dicas Rápidas -->
      <div class="dicas-card">
        <h3 class="dicas-title">
          <i data-lucide="info" class="icon"></i>
          Dicas para um Plano Eficaz
        </h3>
        <div class="dicas-grid">
          <div class="dica-item">
            <i data-lucide="check-circle" class="dica-icon"></i>
            <span>Varie os exercícios para evitar adaptação</span>
          </div>
          <div class="dica-item">
            <i data-lucide="check-circle" class="dica-icon"></i>
            <span>Considere o nível de experiência do aluno</span>
          </div>
          <div class="dica-item">
            <i data-lucide="check-circle" class="dica-icon"></i>
            <span>Inclua tempo de descanso nas observações</span>
          </div>
          <div class="dica-item">
            <i data-lucide="check-circle" class="dica-icon"></i>
            <span>Ajuste a intensidade conforme o objetivo</span>
          </div>
        </div>
      </div>
    </form>

    <!-- Mensagem de Sucesso -->
    <div id="mensagemSucesso" class="mensagem-sucesso">
      <i data-lucide="check-circle" class="icon"></i>
      Plano de treino criado com sucesso!
    </div>
  </div>

  <script>
    let treinoIndex = 0;
    const letras = ["A", "B", "C", "D", "E", "F", "G"];
    const contadorExercicios = {};

    function gerarTreinoHTML(letra) {
      return `
                <div class="treino-box" id="treino${letra}">
                    <div class="treino-header">
                        <h3 class="treino-title">
                            <i data-lucide="dumbbell" class="icon"></i>
                            Treino ${letra}
                        </h3>
                        <button type="button" class="botao-menos-treino" onclick="removerTreino('${letra}')">
                            <i data-lucide="x" class="icon"></i>
                        </button>
                    </div>
                    
                    <div class="treino-content">
                        <div id="exercicios${letra}" class="exercicios-list">
                            ${gerarExercicioHTML(letra, 1)}
                        </div>
                        
                        <button type="button" class="botao-mais" onclick="adicionarExercicio('${letra}')">
                            <i data-lucide="plus" class="btn-icon"></i>
                            Adicionar Exercício
                        </button>
                        
                        <div class="observacao">
                            <label for="obs${letra}" class="observacao-label">
                                <i data-lucide="info" class="icon"></i>
                                Observações do Treino
                            </label>
                            <textarea name="obs[${letra}]" placeholder="Adicione instruções específicas, dicas de execução, tempo de descanso, etc..." class="observacao-textarea"></textarea>
                        </div>
                    </div>
                </div>
            `;
    }

    function gerarExercicioHTML(letra, numero) {
      if (!contadorExercicios[letra]) contadorExercicios[letra] = 1;

      const grupoMuscularOptions = <?= json_encode($grupo_muscular, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
      const exercicioOptions = <?= json_encode($exercicios, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

      const grupoOptions = grupoMuscularOptions.map(grupo =>
        `<option value="${grupo.grupo_muscular.replace(/"/g, '&quot;')}">${grupo.grupo_muscular}</option>`
      ).join('');

      const exercicioSelectOptions = exercicioOptions.map(ex =>
        `<option value="${ex.id}" data-grupo="${ex.grupo_muscular}">${ex.nome_exercicio}</option>`
      ).join('');

      return `
                <div class="exercicio-card">
                    <div class="exercicio-header">
                        <h4 class="exercicio-title">
                            <div class="exercicio-numero">${numero}</div>
                            Exercício ${numero}
                        </h4>
                        <button type="button" class="botao-menos" onclick="removerExercicio('${letra}', ${contadorExercicios[letra]})">
                            <i data-lucide="x" class="icon"></i>
                        </button>
                    </div>
                    
                    <div class="exercicio-form">
                        <input type="hidden" name="dados[${letra}][${numero}][num_exercicio]" value="${numero}">
                        
                        <div class="form-group">
                            <label class="form-label required">Grupo Muscular</label>
                            <select name="dados[${letra}][${numero}][grupo_muscular]" class="form-select grupo-muscular-dropdown" data-letra="${letra}" data-numero="${numero}" required>
                                <option value="" disabled selected>Selecione o grupo</option>
                                ${grupoOptions}
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Exercício</label>
                            <select name="dados[${letra}][${numero}][nome_exercicio]" class="form-select exercicio-dropdown" required>
                                <option value="" disabled selected>Selecione o exercício</option>
                                ${exercicioSelectOptions}
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Séries</label>
                            <input type="number" name="dados[${letra}][${numero}][series_exercicio]" placeholder="Ex: 3" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Repetições</label>
                            <input type="text" name="dados[${letra}][${numero}][repeticoes_exercicio]" placeholder="Ex: 12-15" class="form-input" required>
                        </div>
                    </div>
                </div>
            `;
    }

    function adicionarExercicio(letra) {
      const container = document.getElementById(`exercicios${letra}`);
      contadorExercicios[letra]++;
      container.insertAdjacentHTML('beforeend', gerarExercicioHTML(letra, contadorExercicios[letra]));
      lucide.createIcons(); // Reinicializa os ícones
    }

    function adicionarTreino() {
      const container = document.getElementById("treinosContainer");
      if (treinoIndex >= letras.length) {
        alert("Limite máximo de treinos atingido.");
        return;
      }

      const letra = letras[treinoIndex];
      container.insertAdjacentHTML('beforeend', gerarTreinoHTML(letra));
      treinoIndex++;
      lucide.createIcons(); // Reinicializa os ícones
    }

    document.addEventListener('change', function(e) {
      if (e.target.classList.contains('grupo-muscular-dropdown')) {
        const grupoSelecionado = e.target.value;
        const letra = e.target.getAttribute('data-letra');
        const numero = e.target.getAttribute('data-numero');

        const exercicioDropdown = document.querySelector(
          `select[name="dados[${letra}][${numero}][nome_exercicio]"]`
        );

        const exercicioOptions = <?= json_encode($exercicios, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        const exerciciosFiltrados = exercicioOptions
          .filter(ex => ex.grupo_muscular === grupoSelecionado)
          .map(ex => `<option value="${ex.id}">${ex.nome_exercicio}</option>`)
          .join('');

        exercicioDropdown.innerHTML = `<option value="">Selecione</option>${exerciciosFiltrados}`;
      }
    });

    document.getElementById("formPlano").addEventListener("submit", function(e) {
      document.getElementById("mensagemSucesso").style.display = "block";
      setTimeout(() => {
        document.getElementById("mensagemSucesso").style.display = "none";
      }, 3000);
    });

    function removerExercicio(letra, numero) {
      const container = document.getElementById(`exercicios${letra}`);
      const exercicio = document.querySelector(`#exercicios${letra} .exercicio-card:nth-child(${numero})`);

      if (exercicio) {
        exercicio.remove();
        contadorExercicios[letra]--;

        const exerciciosRestantes = container.querySelectorAll('.exercicio-card');
        exerciciosRestantes.forEach((ex, index) => {
          const titulo = ex.querySelector('.exercicio-title');
          titulo.innerHTML = `
                        <div class="exercicio-numero">${index + 1}</div>
                        Exercício ${index + 1}
                    `;
          const inputNumero = ex.querySelector(`input[name*="num_exercicio"]`);
          if (inputNumero) {
            inputNumero.value = index + 1;
          }
        });
      }
    }

    function removerTreino(letra) {
      const container = document.getElementById("treinosContainer");
      const treino = document.getElementById(`treino${letra}`);

      if (treino) {
        treino.remove();
        treinoIndex--;
      }
    }

    document.addEventListener("DOMContentLoaded", function() {
      adicionarTreino(); // Inicializa com o primeiro treino
      lucide.createIcons(); // Inicializa os ícones
    });
  </script>
</body>

</html>