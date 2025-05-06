<?php
session_start();
require_once __DIR__ . '/../models//Usuarios.class.php';
require_once __DIR__ . '/../models//SolicitacaoTreino.class.php';
require_once __DIR__ . '/../models//Treino.class.php';
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
    
    
    $valorQtdTreinos = (int) (getTreinos($id_aluno) ?? 0);
    $grupoTreino = (string)getTreinos($id_aluno) ?? 'Grupos de treino não encontrado';
    $objetivo = (string)getObjetivo($id_aluno) ?? 'Objetivo não encontrado';
    $experiencia = (string)getExperiencia($id_aluno) ?? 'Experiência não encontrada';

    $classTreino = new Treino();
    $grupo_muscular = $classTreino->getGrupo_muscular();
    $exercicios = $classTreino->getExercicios();


    if (!is_array($grupo_muscular) || !is_array($exercicios)) {
      throw new Exception("Os dados de grupo muscular ou exercícios são inválidos.");
    }else{
      echo '<pre>';
      print_r($grupo_muscular);
      print_r($exercicios);
      echo '</pre>';
    }

     

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
  <style>
    .dropdown {
      margin-bottom: 20px;
    }

    select, input {
      width: 300px;
      padding: 8px;
      margin: 5px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
  </style>
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
      <input type="hidden" id="quantTreinos" value="<?= $valorQtdTreinos ?>" name="quantTreinos">


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

      const grupoMuscularOptions = <?= json_encode($grupo_muscular, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
      const exercicioOptions = <?= json_encode($exercicios, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

      console.log(grupoMuscularOptions);
      console.log(exercicioOptions);

      const grupoOptions = grupoMuscularOptions.map(grupo =>
         `<option value="${grupo.grupo_muscular}">${grupo.grupo_muscular}</option>`
      ).join('');

      const exercicioSelectOptions = exercicioOptions.map(ex =>
        `<option value="${ex.id}" data-grupo="${ex.grupo_muscular}">${ex.nome_exercicio}</option>`
      ).join('');

        return `
        <div class="exercicio-box">
          <strong>Exercício ${numero}</strong>
          <div class="exercicio-group">
            <input type="hidden" name="dados[${letra}][${numero}][num_exercicio]" value="${numero}">
            
            <div class="dropdown">
              <label for = "grupoMuscular">Selecione o grupo muscular:</label>
              <select name="dados[${letra}][${numero}][grupo_muscular]" required>
                <option value="">Selecione</option>
                ${grupoOptions}
              </select>
            </div>

            <div class="dropdown">
              <label for = "buscaExercicio">Exercício:</label>
              <input type="text" name="dados[${letra}][${numero}][nome_exercicio]" placeholder="Nome do exercício" required>
            </div>

            <div class="dropdown">
              <label for = "exercicios ">Selecione o exercício:</label>
              <select name="dados[${letra}][${numero}][nome_exercicio]" required>
                <option value="">Selecione</option>
                ${exercicioSelectOptions}
              </select>
            </div>

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
    const grupoMuscularDropdown = document.getElementById('grupoMuscular');
    const buscaExercicioInput = document.getElementById('buscaExercicio');
    const exerciciosDropdown = document.getElementById('exercicios');

    // Filtrar exercícios por grupo muscular
    grupoMuscularDropdown.addEventListener('change', () => {
      const grupoSelecionado = grupoMuscularDropdown.value.toLowerCase();
      const opcoes = exerciciosDropdown.querySelectorAll('option');

      opcoes.forEach(opcao => {
        const grupo = opcao.getAttribute('data-grupo').toLowerCase();
        opcao.style.display = grupoSelecionado === '' || grupo === grupoSelecionado ? '' : 'none';
      });
    });

    // Filtrar exercícios pelo campo de busca
    buscaExercicioInput.addEventListener('input', () => {
      const busca = buscaExercicioInput.value.toLowerCase();
      const opcoes = exerciciosDropdown.querySelectorAll('option');

      opcoes.forEach(opcao => {
        const texto = opcao.textContent.toLowerCase();
        opcao.style.display = texto.includes(busca) ? '' : 'none';
      });
    });

    window.addEventListener("DOMContentLoaded", gerarTreinos);

  </script>

</body>
</html>
