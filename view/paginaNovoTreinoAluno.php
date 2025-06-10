<?php
/**
 * Página para aluno criar seu próprio plano de treino do zero
 * Preenche objetivo, experiência, e quantidade de treinos por semana manualmente.
 */

session_start();

require_once _DIR_ . '/../models/Treino.class.php';

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
  <title>Meu Plano de Treino - GYGA FIT</title>
  <link rel="stylesheet" href="./style/stylePaginaDeTreino.css?v=<?= time(); ?>" />
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
    <p class="page-subtitle">Personalize seu próprio plano de treino</p>
  </div>

  <form id="formPlano" action="./../controllers/processarNovoTreino.php" method="POST">
    <input type="hidden" name="id_aluno" value="<?= htmlspecialchars($id_aluno) ?>">

    <div class="form-group">
      <label>Quantos dias por semana você quer treinar?</label>
      <input type="number" name="frequencia" class="form-input" placeholder="Ex: 3" required>
    </div>

    <div class="form-group">
      <label>Qual é o seu objetivo?</label>
      <input type="text" name="objetivo" class="form-input" placeholder="Ex: Ganho de massa, definição..." required>
    </div>

    <div class="form-group">
      <label>Qual seu nível de experiência?</label>
      <input type="text" name="experiencia" class="form-input" placeholder="Ex: Iniciante, Intermediário..." required>
    </div>

    <div id="treinosContainer" class="treinos-container"></div>

    <div class="action-buttons">
      <button type="button" onclick="adicionarTreino()">Adicionar Novo Treino</button>
      <button type="submit" name="submit_plano">Salvar Treino</button>
    </div>
  </form>
</div>

<script>
let treinoIndex = 0;
const letras = ["A", "B", "C", "D", "E"];
const grupoMuscular = <?= json_encode($grupo_muscular) ?>;
const exercicios = <?= json_encode($exercicios) ?>;

function adicionarTreino() {
  if (treinoIndex >= letras.length) return alert("Limite de treinos atingido");
  const letra = letras[treinoIndex++];
  const container = document.getElementById("treinosContainer");
  const bloco = document.createElement("div");
  bloco.innerHTML = `
    <h3>Treino ${letra}</h3>
    <div>
      <label>Grupo Muscular:</label>
      <select name="treinos[${letra}][grupo_muscular]">${grupoMuscular.map(g => <option>${g.grupo_muscular}</option>)}</select>
    </div>
    <div>
      <label>Exercício:</label>
      <select name="treinos[${letra}][exercicio]">${exercicios.map(e => <option value="${e.id}">${e.nome_exercicio}</option>)}</select>
    </div>
    <div>
      <label>Séries:</label>
      <input type="number" name="treinos[${letra}][series]" required>
    </div>
    <div>
      <label>Repetições:</label>
      <input type="text" name="treinos[${letra}][repeticoes]" required>
    </div>
  `;
  container.appendChild(bloco);
}
</script>
<script>lucide.createIcons();</script>
</body>
</html>