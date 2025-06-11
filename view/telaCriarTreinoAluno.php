<?php
/**
 * Página para o aluno criar seu próprio plano de treino com liberdade,
 * semelhante à tela do instrutor, mas com os dados preenchidos diretamente.
 */

require_once __DIR__ . '/../models/Treino.class.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['typeUser'] !== 'aluno') {
    header("Location: ../index.php?page=erro");
    exit();
}

$id_aluno = $_SESSION['usuario']['id'];

$classTreino = new Treino();
$grupo_muscular = $classTreino->getGrupo_muscular();
$exercicios = $classTreino->getExercicios();

if (!is_array($grupo_muscular) || !is_array($exercicios)) {
    throw new Exception("Os dados de grupo muscular ou exercícios são inválidos.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Montar Meu Treino - GYGA FIT</title>
  <link rel="stylesheet" href="view/style/stylePaginaDeTreino.css?v=<?= time(); ?>" />
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>

<body>
  <header>
    <div class="header-logo">
      <i data-lucide="dumbbell" class="header-icon"></i>
      <h1>GYGA FIT</h1>
      <p class="header-subtitle">Área do Aluno</p>
    </div>
  </header>

  <div class="container">
    <div class="page-title">
      <h2>Montar Meu Treino</h2>
      <p class="page-subtitle">Você pode montar seu próprio plano de treino do zero!</p>
    </div>

    <form id="formPlano" action="./../controllers/processarNovoTreino.php" method="POST">
      <input type="hidden" name="id_aluno" value="<?= htmlspecialchars($id_aluno) ?>">

      <div class="form-group">
        <label>Dias por Semana</label>
        <input type="number" name="frequencia" class="form-input" placeholder="Ex: 3" required>
      </div>

      <div class="form-group">
        <label>Objetivo</label>
        <input type="text" name="objetivo" class="form-input" placeholder="Ex: Ganho de massa" required>
      </div>

      <div class="form-group">
        <label>Experiência</label>
        <input type="text" name="experiencia" class="form-input" placeholder="Ex: Iniciante" required>
      </div>

      <div id="treinosContainer" class="treinos-container"></div>

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
    </form>

    <div id="mensagemSucesso" class="mensagem-sucesso">
      <i data-lucide="check-circle" class="icon"></i>
      Plano de treino criado com sucesso!
    </div>
  </div>

  <script>
    let treinoIndex = 0;
    const letras = ["A", "B", "C", "D", "E"];
    const contadorExercicios = {};
    const grupoMuscularOptions = <?= json_encode($grupo_muscular) ?>;
    const exercicioOptions = <?= json_encode($exercicios) ?>;

    function gerarTreinoHTML(letra) {
      return `
        <div class="treino-box" id="treino${letra}">
          <div class="treino-header">
            <h3 class="treino-title"><i data-lucide="dumbbell"></i> Treino ${letra}</h3>
            <button type="button" class="botao-menos-treino" onclick="removerTreino('${letra}')">
              <i data-lucide="x"></i>
            </button>
          </div>
          <div id="exercicios${letra}" class="exercicios-list">
            ${gerarExercicioHTML(letra, 1)}
          </div>
          <button type="button" onclick="adicionarExercicio('${letra}')">Adicionar Exercício</button>
          <div class="observacao">
            <label>Observações do Treino</label>
            <textarea name="obs[${letra}]" placeholder="Instruções, descanso..."></textarea>
          </div>
        </div>`;
    }

    function gerarExercicioHTML(letra, numero) {
      if (!contadorExercicios[letra]) contadorExercicios[letra] = 1;

      const grupoOptions = grupoMuscularOptions.map(g =>
        `<option value="${g.grupo_muscular}">${g.grupo_muscular}</option>`).join('');

      const exercicioSelectOptions = exercicioOptions.map(e =>
        `<option value="${e.id}" data-grupo="${e.grupo_muscular}">${e.nome_exercicio}</option>`).join('');

      return `
        <div class="exercicio-card">
          <input type="hidden" name="dados[${letra}][${numero}][num_exercicio]" value="${numero}">
          <label>Grupo Muscular</label>
          <select name="dados[${letra}][${numero}][grupo_muscular]" class="grupo-muscular-dropdown" data-letra="${letra}" data-numero="${numero}">
            <option value="" disabled selected>Selecione</option>
            ${grupoOptions}
          </select>

          <label>Exercício</label>
          <select name="dados[${letra}][${numero}][nome_exercicio]" class="exercicio-dropdown" required>
            <option value="" disabled selected>Selecione</option>
            ${exercicioSelectOptions}
          </select>

          <label>Séries</label>
          <input type="number" name="dados[${letra}][${numero}][series_exercicio]" required>

          <label>Repetições</label>
          <input type="text" name="dados[${letra}][${numero}][repeticoes_exercicio]" required>
        </div>`;
    }

    function adicionarExercicio(letra) {
      const container = document.getElementById("exercicios" + letra);
      contadorExercicios[letra]++;
      container.insertAdjacentHTML("beforeend", gerarExercicioHTML(letra, contadorExercicios[letra]));
      lucide.createIcons();
    }

    function adicionarTreino() {
      if (treinoIndex >= letras.length) {
        alert("Limite atingido");
        return;
      }
      const letra = letras[treinoIndex++];
      const container = document.getElementById("treinosContainer");
      container.insertAdjacentHTML("beforeend", gerarTreinoHTML(letra));
      lucide.createIcons();
    }

    function removerTreino(letra) {
      const treino = document.getElementById("treino" + letra);
      if (treino) treino.remove();
    }

    document.addEventListener("change", function (e) {
      if (e.target.classList.contains("grupo-muscular-dropdown")) {
        const grupoSelecionado = e.target.value;
        const letra = e.target.getAttribute("data-letra");
        const numero = e.target.getAttribute("data-numero");
        const exercicioDropdown = document.querySelector(`select[name="dados[${letra}][${numero}][nome_exercicio]"]`);

        const filtrados = exercicioOptions.filter(ex => ex.grupo_muscular === grupoSelecionado)
          .map(ex => `<option value="${ex.id}">${ex.nome_exercicio}</option>`).join('');
        exercicioDropdown.innerHTML = "<option>Selecione</option>" + filtrados;
      }
    });

    document.addEventListener("DOMContentLoaded", function () {
      adicionarTreino();
      lucide.createIcons();
    });
  </script>
</body>
</html>
