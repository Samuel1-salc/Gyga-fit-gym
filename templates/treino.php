<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Plano de Treino - <?= htmlspecialchars($aluno['nome']) ?></title>
  <style>
    body { font-family: sans-serif; margin: 20px; }
    .header { text-align: center; margin-bottom: 30px; }
    .header h1 { margin: 0; }
    .exercicio { margin-bottom: 15px; }
    .exercicio h4 { margin: 0 0 5px; }
    .observacoes { margin-top: 30px; font-style: italic; }
  </style>
</head>
<body>
  <div class="header">
    <h1>Plano de Treino de <?= htmlspecialchars($aluno['nome']) ?></h1>
    <p>Data: <?= htmlspecialchars($data_criacao) ?></p>
  </div>

  <?php foreach ($treinos as $t): ?>
    <div class="exercicio">
      <h4>
        <?= htmlspecialchars($t['nome_exercicio']) ?>
        (<?= htmlspecialchars($t['letra_treino']) . $t['num_exercicio'] ?>)
      </h4>
      <p>Séries: <?= $t['series'] ?> × Repetições: <?= htmlspecialchars($t['repeticoes']) ?></p>
      <?php if (!empty($t['observacao'])): ?>
        <p>Obs: <?= htmlspecialchars($t['observacao']) ?></p>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>

  <?php if (!empty($observacoes_gerais)): ?>
    <div class="observacoes">
      <strong>Observações gerais:</strong> <?= htmlspecialchars($observacoes_gerais) ?>
    </div>
  <?php endif; ?>
</body>
</html>
