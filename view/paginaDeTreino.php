<?php
/**
 * Página responsável por carregar os dados iniciais para a criação de um plano de treino
 * com base na solicitação de um aluno específico.
 * 
 * Esta página:
 * - Verifica a existência de um ID de aluno via GET;
 * - Recupera informações da solicitação de treino do aluno;
 * - Obtém dados como nome, experiência, objetivo e grupos musculares disponíveis;
 * - Prepara os dados para exibição e envio via formulário;
 * - Lança exceções em caso de ausência de dados críticos.
 * 
 * @package GYGA FIT
 * @author [Samuel/heitor]
 * @version 1.0
 */

session_start();

require_once __DIR__ . '/../models//Usuarios.class.php';
require_once __DIR__ . '/../models//SolicitacaoTreino.class.php';
require_once __DIR__ . '/../models//Treino.class.php';

$id_aluno = $_GET['id_aluno'] ?? null;

if ($id_aluno != null) {

    /**
     * Retorna o nome do aluno com base no ID.
     *
     * @param int $id_aluno
     * @return string
     */
    function getNomeAluno($id_aluno) {
        $users = new Users();
        $aluno = $users->getNomeAluno($id_aluno);
        return $aluno['username'] ?? 'Aluno não encontrado';
    }

    /**
     * Retorna a quantidade ou grupos de treino solicitados.
     *
     * @param int $id_aluno
     * @return mixed
     */
    function getTreinos($id_aluno) {
        $solicitacao = new SolicitacaoTreino();
        $formulario = $solicitacao->getFormularioForCriacaoDeTreino($id_aluno);
        return $formulario['treinos'] ?? 'Solicitação não encontrada';
    }

    /**
     * Retorna o nível de experiência do aluno.
     *
     * @param int $id_aluno
     * @return string
     */
    function getExperiencia($id_aluno) {
        $solicitacao = new SolicitacaoTreino();
        $formulario = $solicitacao->getFormularioForCriacaoDeTreino($id_aluno);
        return $formulario['experiencia'] ?? 'experiencia não encontrada';
    }

    /**
     * Retorna o objetivo de treino do aluno.
     *
     * @param int $id_aluno
     * @return string
     */
    function getObjetivo($id_aluno) {
        $solicitacao = new SolicitacaoTreino();
        $formulario = $solicitacao->getFormularioForCriacaoDeTreino($id_aluno);
        return $formulario['objetivo'] ?? 'objetivo não encontrado';
    }

    /**
     * Retorna o ID da solicitação de treino.
     *
     * @param int $id_aluno
     * @return int|string
     */
    function getIdformulario($id_aluno) {
        $solicitacao = new SolicitacaoTreino();
        $formulario = $solicitacao->getFormularioForCriacaoDeTreino($id_aluno);
        return $formulario['id'] ?? 'id não encontrado';
    }

    // Coleta e organiza os dados do aluno e da solicitação de treino
    $valorQtdTreinos = (int) (getTreinos($id_aluno) ?? 0);
    $grupoTreino = (string) getTreinos($id_aluno) ?? 'Grupos de treino não encontrado';
    $objetivo = (string) getObjetivo($id_aluno) ?? 'Objetivo não encontrado';
    $experiencia = (string) getExperiencia($id_aluno) ?? 'Experiência não encontrada';
    $id_solicitacao = (int) getIdformulario($id_aluno) ?? 0;

    // Carrega os dados de apoio para montar o plano de treino
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
    <h2 style="color: red;">Criar Plano de Treino</h2>
    <form id="formPlano" action="./../controllers//processarNovoTreino.php" method="POST">
      <div class="card-aluno">
        <div class="card-info">
            <h3 style="color: black;" > <?= htmlspecialchars(getNomeAluno($id_aluno)) ?></h3>
            <h4 style="color: red;">
              Dias de treino: <span style="color: black;"><?= htmlspecialchars($grupoTreino) ?></span>
            </h4>
            <h4 style="color: red;">
              Objetivo:<span  style="color: black;">  <?= htmlspecialchars($objetivo)?></span>
            </h4>
            <h4 style="color: red;">
              Experiência: <span style="color: black;">  <?= htmlspecialchars($experiencia  )?> </span>
            </h4>
            <input type="hidden" name="id_solicitacao" value="<?= $id_solicitacao?>">
          </div>
      </div>

      <!-- Campo oculto para o ID do aluno -->
      <input type="hidden" name="id_aluno" value="<?= htmlspecialchars($id_aluno) ?>">

      <!-- Campo oculto para a quantidade de treinos -->
      

    

      <div id="treinosContainer"></div>
      <button type="button" class="botao-mais-treino" onclick="adicionarTreino()">+ Treino</button>
      
     
      <button type="submit" name="submit_plano" class="botao-progresso">Enviar dados de treino</button>
    </form>
  </div>

  <script>
    let treinoIndex = 0;
    const letras = ["A", "B", "C", "D", "E", "F", "G"];
    const contadorExercicios = {};

    function gerarTreinoHTML(letra) {
      return `
        <div class="treino-box" id="treino${letra}">
            <h3 style="color: red;">Treino ${letra}</h3>
            <div id="exercicios${letra}">
                ${gerarExercicioHTML(letra, 1)}
            </div>
            <button type="button" class="botao-mais" onclick="adicionarExercicio('${letra}')">+ Exercício</button>
            <div class="observacao">
                <label for="obs${letra}">Observações:</label>
                <textarea name="obs[${letra}]" placeholder="Alguma instrução ou detalhe do treino ${letra}"></textarea>
            </div>
            <button type="button" class="botao-menos-treino" onclick="removerTreino('${letra}')">
              <span class="icone-remover">&times;</span>
            </button>
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
      <div class="exercicio-box">
        <strong>Exercício ${numero}</strong>
        <div class="exercicio-group">
          <input type="hidden" name="dados[${letra}][${numero}][num_exercicio]" value="${numero}">
          <select name="dados[${letra}][${numero}][grupo_muscular]" class="grupo-muscular-dropdown"  data-letra="${letra}" data-numero="${numero}" required>
            <option value="" disabled selected>Selecione o grupo muscular</option>
            ${grupoOptions}
          </select>
          <select name="dados[${letra}][${numero}][nome_exercicio]" class="exercicio-dropdown" required>
            <option value="" disabled selected>Selecione o exercício</option>
            ${exercicioSelectOptions}
          </select>
          <input type="number" name="dados[${letra}][${numero}][series_exercicio]" placeholder="Séries" required>
          <input type="text" name="dados[${letra}][${numero}][repeticoes_exercicio]" placeholder="Repetições" required>
           <button type="button" class="botao-menos" onclick="removerExercicio('${letra}', ${contadorExercicios[letra]})">
            <span class="icone-remover">&times;</span>
          </button>
        </div>
      </div>
      `;

    }

    function adicionarExercicio(letra) {
      const container = document.getElementById(`exercicios${letra}`);
      contadorExercicios[letra]++;
      container.insertAdjacentHTML('beforeend', gerarExercicioHTML(letra, contadorExercicios[letra]));
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
    }

  

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('grupo-muscular-dropdown')) {
          const grupoSelecionado = e.target.value; // Grupo muscular selecionado
          const letra = e.target.getAttribute('data-letra');
          const numero = e.target.getAttribute('data-numero');

          // Encontre o dropdown de exercícios correspondente
          const exercicioDropdown = document.querySelector(
            `select[name="dados[${letra}][${numero}][nome_exercicio]"]`
          );

          // Filtrar os exercícios com base no grupo muscular selecionado
          const exercicioOptions = <?= json_encode($exercicios, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
          const exerciciosFiltrados = exercicioOptions
            .filter(ex => ex.grupo_muscular === grupoSelecionado)
            .map(ex => `<option value="${ex.id}">${ex.nome_exercicio}</option>`)
            .join('');

          // Atualizar o dropdown de exercícios
          exercicioDropdown.innerHTML = `<option value="">Selecione</option>${exerciciosFiltrados}`;
        }
      });

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
            const inputNumero = ex.querySelector(`input[name="dados[${letra}][${index + 1}][num_exercicio]"]`);
            if (inputNumero) {
              inputNumero.value = index + 1; // Atualiza o valor do input "numero"
        }
          });
        }
    }
    function removerTreino(letra) {
        const container = document.getElementById("treinosContainer");
        const treino = document.getElementById(`treino${letra}`);

        if (treino) {
            treino.remove(); // Remove a div do treino correspondente

            // Atualiza o índice dos treinos restantes
            const treinosRestantes = container.querySelectorAll('.treino-box');
            treinosRestantes.forEach((treino, index) => {
                const letraAtual = String.fromCharCode(65 + index); // Gera a letra (A, B, C, ...)
                treino.id = `treino${letraAtual}`; // Atualiza o ID do treino
                const titulo = treino.querySelector('h3');
                titulo.textContent = `Treino ${letraAtual}`; // Atualiza o título do treino

                // Atualiza os atributos dos exercícios dentro do treino
                const exercicios = treino.querySelectorAll('.exercicio-box');
                exercicios.forEach((exercicio, exIndex) => {
                    const strong = exercicio.querySelector('strong');
                    strong.textContent = `Exercício ${exIndex + 1}`; // Atualiza o número do exercício

                    const inputNumero = exercicio.querySelector(`input[name^="dados[${letra}][${exIndex + 1}]"]`);
                    if (inputNumero) {
                        inputNumero.name = `dados[${letraAtual}][${exIndex + 1}][num_exercicio]`; // Atualiza o nome do input
                    }
                });
            });

            // Atualiza o índice global de treinos
            treinoIndex--;
        }
    }
    
    document.addEventListener("DOMContentLoaded", function () {
      adicionarTreino(); // Inicializa com o primeiro treino
    });

  </script>

</body>
</html>
