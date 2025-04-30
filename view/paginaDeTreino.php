<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Criar Plano de Treino - GYGA FIT</title>
  <link rel="stylesheet" href="./style/stylePaginaDeTreino.css">
  <style>
    .treino-box {
      border: 2px solid #ccc;
      padding: 15px;
      margin: 20px 0;
      border-radius: 8px;
    }

    .exercicio-box {
      border: 1px solid #aaa;
      padding: 10px;
      margin-top: 10px;
      border-radius: 6px;
      background-color: #f9f9f9;
    }

    .exercicio-box strong {
      color: #000;
    }

    .exercicio-group {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 5px;
    }

    .exercicio-group input {
      flex: 1 1 30%;
    }

    .observacao {
      margin-top: 15px;
      display: flex;
      flex-direction: column;
    }

    .observacao textarea {
      width: 100%;
      height: 80px;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #aaa;
      background-color: #fff;
      resize: vertical;
    }

    .botao-mais {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 6px 12px;
      font-size: 16px;
      border-radius: 4px;
      cursor: pointer;
      margin-top: 10px;
    }

    .mensagem-sucesso {
      display: none;
      margin-top: 15px;
      color: green;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <header>
    <h1>GYGA FIT</h1>
  </header>

  <div class="container">
    <h2>Criar Plano de Treino</h2>
    <form id="formPlano">

      <label for="nomeAluno">Nome do Aluno:</label>
      <input type="text" id="nomeAluno" name="nomeAluno" placeholder="Digite aqui o nome do aluno" required>

      <label for="quantTreinos">Quantos dias na semana o aluno irá treinar?</label>
      <select id="quantTreinos" name="quantTreinos" onchange="gerarTreinos()" required>
        <option value="" disabled selected>Selecione</option>
        <option value="1">1 dia</option>
        <option value="2">2 dias</option>
        <option value="3">3 dias</option>
        <option value="4">4 dias</option>
        <option value="5">5 dias</option>
        <option value="6">6 dias</option>
        <option value="7">7 dias</option>
      </select>

      <div id="treinosContainer"></div>

      <button type="submit" class="botao-progresso">Enviar dados de treino</button>
      <div id="mensagemSucesso" class="mensagem-sucesso">
        Seus dados foram enviados para seu gráfico de evolução mensal!
      </div>
    </form>
  </div>

  <script>
    function gerarTreinos() {
      const container = document.getElementById("treinosContainer");
      container.innerHTML = "";
      const letras = ["A", "B", "C", "D", "E", "F", "G"];
      const quant = parseInt(document.getElementById("quantTreinos").value);

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
            <textarea name="obs${letra}" placeholder="Alguma instrução ou detalhe do treino ${letra}"></textarea>
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
            <input type="text" name="exercicio_${letra}[]" placeholder="Nome do exercício" required>
            <input type="number" name="series_${letra}[]" placeholder="Séries" required>
            <input type="text" name="reps_${letra}[]" placeholder="Repetições" required>
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
      e.preventDefault();
      document.getElementById("mensagemSucesso").style.display = "block";
      setTimeout(() => {
        document.getElementById("mensagemSucesso").style.display = "none";
        document.getElementById("formPlano").reset();
        document.getElementById("treinosContainer").innerHTML = "";
      }, 3000);
    });
  </script>

</body>
</html>
