<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Criar Plano de Treino - GYGA FIT</title>
    <link rel="stylesheet" href="./style//stylePaginaDeTreino.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet"/>
    
</head>
<body>

  <header>
    <button class="btn-lateral btn-usuario" onclick="editarPerfil()">
      <i class="fas fa-user"></i>
    </button>

    <h1>GYGA FIT</h1>

    <button class="btn-lateral btn-config" onclick="abrirConfiguracoes()">
      <i class="fas fa-cog"></i>
    </button>
  </header>

  <div class="container">
    <div class="aluno-info">
      <h3>Criar Plano de Treino</h3>
      <form id="formPlano" action = "./../controllers/processarNovoTreino.php" method = "POST">
        <label for="aluno">Nome do Aluno:</label>
        <input type="text" id="aluno" name="aluno" placeholder="Digite o nome do aluno" required>

        <label for="diaSemana">Dia da Semana:</label>
        <select name="diaSemana" id="diaSemana" required>
          <option value="" disabled selected>Selecione o dia</option>
          <option value="segunda">Segunda</option>
          <option value="terca">Terça</option>
          <option value="quarta">Quarta</option>
          <option value="quinta">Quinta</option>
          <option value="sexta">Sexta</option>
        </select>

        <label for="exercicio">Exercício:</label>
        <input type="text" id="exercicio" name="exercicio" placeholder="Nome do exercício" required>

        <label for="series">Séries:</label>
        <input type="number" id="series" name="series" min="1" required>

        <label for="repeticoes">Repetições:</label>
        <input type="text" id="repeticoes" name="repeticoes" placeholder="Ex: 10-12" required>

        <label for="observacoes">Observações:</label>
        <textarea id="observacoes" name="observacoes" placeholder="Instruções ou cuidados específicos"></textarea>

        <button type="submit" class="botao-progresso">Enviar Plano</button>
        <div id="mensagemSucesso" class="mensagem-sucesso">
          O plano foi enviado com sucesso para seu aluno!
        </div>
      </form>
    </div>
  </div>

  <script>
    function editarPerfil() {
      alert("Abrindo tela de edição de perfil...");
    }

    function abrirConfiguracoes() {
      alert("Abrindo configurações...");
    }

    function enviarPlano(event) {
      event.preventDefault();
      document.getElementById("mensagemSucesso").style.display = "block";
      setTimeout(() => {
        document.getElementById("mensagemSucesso").style.display = "none";
        document.getElementById("formPlano").reset();
      }, 3000);
    }
  </script>

</body>
</html>