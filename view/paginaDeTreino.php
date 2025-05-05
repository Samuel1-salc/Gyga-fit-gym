<?php
session_start();
require_once __DIR__ . '/../models//Usuarios.class.php';
require_once __DIR__ . '/../models//SolicitacaoTreino.class.php';
$id_aluno = $_GET['id_aluno'] ?? null;

if($id_aluno != null){
    function getNomeAluno($id_aluno) {
        
        $users = new Users();
        $aluno = $users->getNomeAluno($id_aluno);
        return $aluno['username'] ?? 'Aluno não encontrado';
    }
    function getTreinos($id_aluno) {
        
        $solicitacao = new SolicitacaoTreino();
        $formulario = $solicitacao->getFormularioForCriacaoDeTreino($id_aluno);
        return $formulario['treinos'] ?? 'Solicitação não encontrada';
    }
    function getExperiencia($id_aluno) {
        
        $solicitacao = new SolicitacaoTreino();
        $formulario = $solicitacao->getFormularioForCriacaoDeTreino($id_aluno);
        return $formulario['experiencia'] ?? 'Solicitação não encontrada';
    }
    function getObjetivo($id_aluno) {
        
        $solicitacao = new SolicitacaoTreino();
        $formulario = $solicitacao->getFormularioForCriacaoDeTreino($id_aluno);
        return $formulario['objetivo'] ?? 'Solicitação não encontrada';
    }
    
    
    $valorQtdTreinos = (int)getTreinos($id_aluno)?? 0;
    $grupoTreino = (string)getTreinos($id_aluno) ?? 'Grupo de treino não encontrado';
    $objetivo = (string)getObjetivo($id_aluno) ?? 'Objetivo não encontrado';
    $experiencia = (string)getExperiencia($id_aluno) ?? 'Experiência não encontrada';
}else{
  throw new Exception("ID do aluno não foi fornecido.");
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Criar Plano de Treino - GYGA FIT</title>
  <link rel="stylesheet" href="./style/stylePaginaDeTreino.css?v=<?= time(); ?>" />
</head>
<body>

  <header>
    <h1>GYGA FIT</h1>
  </header>

  <div class="container">
    <h2>Criar Plano de Treino</h2>
    <form id="formPlano" action="./../controllers//processarNovoTreino.php" method="POST">

      <h4>Nome do Aluno: <?= htmlspecialchars(getNomeAluno($id_aluno)) ?> </h4>
      <h4>Grupos de treino: <?= htmlspecialchars($grupoTreino)?> </h4>
      <h4>Objetivo: <?= htmlspecialchars($objetivo)?> </h4>
      <h4>Experiência: <?= htmlspecialchars($experiencia  )?> </h4>

      <!-- Campo oculto para o ID do aluno -->
      <input type="hidden" name="id_aluno" value="<?= htmlspecialchars($id_aluno) ?>">

      <!-- Campo oculto para a quantidade de treinos -->
      <input type="hidden" id="quantTreinos" value="<?= $valorQtdTreinos ?>" name="quantTreinos" oninput="gerarTreinos()" required>

      <div id="treinosContainer"></div>
      
     
      <button type="submit" name="submit_plano" class="botao-progresso">Enviar dados de treino</button>
    </form>
  </div>

  <script>
    function gerarTreinos() {
      const container = document.getElementById("treinosContainer");
      container.innerHTML = "";
      const letras = ["A", "B", "C", "D", "E", "F", "G"];
      const quant = <?= $valorQtdTreinos ?>; // Quantidade de treinos a serem gerados
      
      

      for (let i = 0; i < quant; i++) {
        const letra = letras[i];
        const treinoDiv = document.createElement("div");
        treinoDiv.className = "treino-box";
        treinoDiv.id = `treino${letra}`;
        

        treinoDiv.innerHTML = `
            <h3>Treino ${letra}</h3>
            <div id="exercicios${letra}">
              ${gerarExercicioHTML(letra, 1)}
            </div>
            <button type="button" class="botao-mais" onclick="adicionarExercicio('${letra}')">+ Adicionar exercício</button>
            <div class="observacao">
              <label for="obs${letra}">Observações:</label>
              <textarea name="obs[${letra}]" placeholder="Alguma instrução ou detalhe do treino ${letra}"></textarea>
            </div>
          
        `;
        container.appendChild(treinoDiv);

      
      }
    }

    const contadorExercicios = {};

    function gerarExercicioHTML(letra, numero) {
      if (!contadorExercicios[letra]) contadorExercicios[letra] = 1;
      return `
      <div class="exercicio-box">
        <strong>Exercício ${numero}</strong>
        <div class="exercicio-group">
          <input type="hidden" name="dados[${letra}][${numero}][num_exercicio]" value="${numero}">
          <input type="text" name="dados[${letra}][${numero}][nome_exercicio]" placeholder="Nome do exercício" required>
          <input type="number" name="dados[${letra}][${numero}][series_exercicio]" placeholder="Séries" required>
          <input type="text" name="dados[${letra}][${numero}][repeticoes_exercicio]" placeholder="Repetições" required>
        </div>
      </div>
      `;
    }

    function adicionarExercicio(letra) {
      const container = document.getElementById(`exercicios${letra}`);
      contadorExercicios[letra]++;
      container.insertAdjacentHTML('beforeend', gerarExercicioHTML(letra, contadorExercicios[letra]));
    }

    document.getElementById("formPlano").addEventListener("submit", function (e) {
      //e.preventDefault();
      document.getElementById("mensagemSucesso").style.display = "block";
      setTimeout(() => {
        document.getElementById("mensagemSucesso").style.display = "none";
        document.getElementById("formPlano").reset();
        document.getElementById("treinosContainer").innerHTML = "";
      }, 3000);
    });
    function removerExercicio(letra, numero) {
        const container = document.getElementById(`exercicios${letra}`);
        const exercicio = document.querySelector(`#exercicios${letra} .exercicio-box:nth-child(${numero})`);

        if (exercicio) {
          exercicio.remove(); // Remove a div do exercício correspondente

          // Atualiza o contador de exercícios
          contadorExercicios[letra]--;

          // Reorganiza os números dos exercícios restantes
          const exerciciosRestantes = container.querySelectorAll('.exercicio-box');
          exerciciosRestantes.forEach((ex, index) => {
            const strong = ex.querySelector('strong');
            strong.textContent = `Exercício ${index + 1}`; // Atualiza o número do exercício
            const inputNumero = ex.querySelector('input[name="numero"]');
            inputNumero.value = index + 1; // Atualiza o valor do input "numero"
          });
        }
    }
    window.addEventListener("DOMContentLoaded", gerarTreinos);

  </script>

</body>
</html>
