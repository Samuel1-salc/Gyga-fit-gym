<?php
require_once __DIR__ . '/../models/Treino.class.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['typeUser'] !== 'aluno') {
  header("Location: ../index.php?page=erro");
  exit();
}

$id_aluno = $_SESSION['usuario']['id'];

$classTreino = new Treino();
$grupo_muscular = $classTreino->getGrupo_muscular();
$exercicios = $classTreino->getExercicios();

if (!is_array($grupo_muscular) || empty($grupo_muscular)) {
  $grupo_muscular = [
    ['grupo_muscular' => 'Peito'],
    ['grupo_muscular' => 'Costas'],
    ['grupo_muscular' => 'Pernas'],
    ['grupo_muscular' => 'Ombros'],
    ['grupo_muscular' => 'Braços'],
    ['grupo_muscular' => 'Abdômen'],
  ];
}

if (!is_array($exercicios) || empty($exercicios)) {
  $exercicios = [
    ['id' => 1, 'nome_exercicio' => 'Supino Reto', 'grupo_muscular' => 'Peito'],
    ['id' => 2, 'nome_exercicio' => 'Supino Inclinado', 'grupo_muscular' => 'Peito'],
    ['id' => 3, 'nome_exercicio' => 'Puxada Frontal', 'grupo_muscular' => 'Costas'],
    ['id' => 4, 'nome_exercicio' => 'Remada Curvada', 'grupo_muscular' => 'Costas'],
    ['id' => 5, 'nome_exercicio' => 'Agachamento', 'grupo_muscular' => 'Pernas'],
    ['id' => 6, 'nome_exercicio' => 'Leg Press', 'grupo_muscular' => 'Pernas'],
    ['id' => 7, 'nome_exercicio' => 'Desenvolvimento', 'grupo_muscular' => 'Ombros'],
    ['id' => 8, 'nome_exercicio' => 'Elevação Lateral', 'grupo_muscular' => 'Ombros'],
    ['id' => 9, 'nome_exercicio' => 'Rosca Direta', 'grupo_muscular' => 'Braços'],
    ['id' => 10, 'nome_exercicio' => 'Tríceps Pulley', 'grupo_muscular' => 'Braços'],
    ['id' => 11, 'nome_exercicio' => 'Prancha', 'grupo_muscular' => 'Abdômen'],
    ['id' => 12, 'nome_exercicio' => 'Abdominal Supra', 'grupo_muscular' => 'Abdômen'],
  ];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Montar Meu Treino - GYGA FIT</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-red-500 min-h-screen">
  <header class="bg-black text-white p-4">
    <div class="flex items-center justify-center gap-3">
      <i data-lucide="dumbbell" class="w-8 h-8 text-red-500"></i>
      <div class="text-center">
        <h1 class="text-2xl font-bold">GYGA FIT</h1>
        <p class="text-sm text-gray-300">Área do Aluno</p>
      </div>
    </div>
  </header>

  <main class="max-w-md mx-auto bg-white rounded-t-3xl mt-4 p-6 min-h-screen">
    <div class="text-center mb-6">
      <h2 class="text-2xl font-bold text-gray-800 mb-2">Montar Meu Treino</h2>
      <p class="text-gray-600">Você pode montar seu próprio plano de treino do zero!</p>
    </div>

    <form id="formPlano" class="space-y-6" method="POST" action="/Gyga-fit-gym/controllers/processarNovoTreino.php">
      <input type="hidden" name="id_aluno" value="<?= htmlspecialchars($id_aluno) ?>">

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Dias por Semana</label>
          <input type="number" name="frequencia" required class="w-full border rounded-md p-2" placeholder="Ex: 3" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Objetivo</label>
          <input type="text" name="objetivo" required class="w-full border rounded-md p-2" placeholder="Ex: Ganho de massa" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Experiência</label>
          <input type="text" name="experiencia" required class="w-full border rounded-md p-2" placeholder="Ex: Iniciante" />
        </div>
      </div>

      <div id="treinosContainer" class="space-y-4"></div>

      <div class="space-y-3">
        <button type="button" onclick="adicionarTreino()" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
          <i data-lucide="plus" class="w-4 h-4"></i> Adicionar Novo Treino
        </button>

        <button type="submit" name="submit_plano" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
          <i data-lucide="check-circle" class="w-4 h-4"></i> Finalizar e Enviar Plano
        </button>
      </div>

      <div id="mensagemSucesso" class="hidden mt-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700 flex items-center gap-2">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        Plano de treino criado com sucesso!
      </div>
    </form>
  </main>

  <script>
    let treinoIndex = 0;
    const letras = ["A", "B", "C", "D", "E"];
    const contadorExercicios = {};
    const grupoMuscularOptions = <?= json_encode($grupo_muscular) ?>;
    const exercicioOptions = <?= json_encode($exercicios) ?>;

    function gerarTreinoHTML(letra) {
      return `
        <div class="border border-red-200 rounded-md p-4 space-y-3 bg-red-50" id="treino${letra}">
          <div class="flex items-center justify-between">
            <h3 class="flex items-center gap-2 text-lg font-semibold text-red-600">
              <i data-lucide="dumbbell" class="w-5 h-5"></i> Treino ${letra}
            </h3>
            <button type="button" onclick="removerTreino('${letra}')" class="text-red-500 hover:text-red-700">
              <i data-lucide="x" class="w-4 h-4"></i>
            </button>
          </div>

          <div id="exercicios${letra}" class="space-y-3">
            ${gerarExercicioHTML(letra, 1)}
          </div>

          <button type="button" onclick="adicionarExercicio('${letra}')" class="w-full border border-red-200 text-red-600 rounded-md py-2 hover:bg-red-100">
            <i data-lucide="plus" class="inline w-4 h-4 mr-1"></i> Adicionar Exercício
          </button>

          <div>
            <label class="text-sm font-medium text-gray-700">Observações do Treino</label>
            <textarea name="obs[${letra}]" placeholder="Instruções, descanso..." class="w-full mt-1 border rounded-md p-2 resize-none"></textarea>
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
        <div class="border rounded-md p-3 space-y-2 bg-white">
          <input type="hidden" name="dados[${letra}][${numero}][num_exercicio]" value="${numero}">
          <div>
            <label class="block text-sm text-gray-700">Grupo Muscular</label>
            <select name="dados[${letra}][${numero}][grupo_muscular]" required
              class="grupo-muscular-dropdown w-full border rounded-md p-2 mt-1"
              data-letra="${letra}" data-numero="${numero}">
              <option value="" disabled selected>Selecione</option>
              ${grupoOptions}
            </select>
          </div>
          <div>
            <label class="block text-sm text-gray-700">Exercício</label>
            <select name="dados[${letra}][${numero}][nome_exercicio]" required
              class="exercicio-dropdown w-full border rounded-md p-2 mt-1">
              <option value="" disabled selected>Selecione</option>
              ${exercicioSelectOptions}
            </select>
          </div>
          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="block text-sm text-gray-700">Séries</label>
              <input type="number" name="dados[${letra}][${numero}][series_exercicio]" required class="w-full border rounded-md p-2" />
            </div>
            <div>
              <label class="block text-sm text-gray-700">Repetições</label>
              <input type="text" name="dados[${letra}][${numero}][repeticoes_exercicio]" required class="w-full border rounded-md p-2" />
            </div>
          </div>
        </div>`;
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

    function adicionarExercicio(letra) {
      const container = document.getElementById("exercicios" + letra);
      contadorExercicios[letra]++;
      container.insertAdjacentHTML("beforeend", gerarExercicioHTML(letra, contadorExercicios[letra]));
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
        exercicioDropdown.innerHTML = "<option disabled selected>Selecione</option>" + filtrados;
      }
    });

    document.addEventListener("DOMContentLoaded", function () {
      adicionarTreino();
      lucide.createIcons();
    });
  </script>
</body>
</html>
