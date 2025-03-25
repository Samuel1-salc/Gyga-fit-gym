<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Requerimento de Planilha de Treino</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 500px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="radio"] {
            margin-right: 10px;
        }
        .form-group input[type="text"], .form-group input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-group button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Requerimento de Planilha de Treino</h1>
        <form id="formTreino">
            <div class="form-group">
                <label for="experiencia">1. Qual a sua experiência na academia?</label>
                <input type="radio" id="iniciante" name="experiencia" value="Iniciante">
                <label for="iniciante">Iniciante</label><br>
                <input type="radio" id="intermediario" name="experiencia" value="Intermediário">
                <label for="intermediario">Intermediário (pelo menos 2 anos)</label><br>
                <input type="radio" id="avancado" name="experiencia" value="Avançado">
                <label for="avancado">Avançado (pelo menos 5 anos)</label>
            </div>

            <div class="form-group">
                <label for="objetivo">2. Qual o seu objetivo na academia?</label>
                <input type="radio" id="hipertrofia" name="objetivo" value="Hipertrofia">
                <label for="hipertrofia">Hipertrofia (ganho de massa muscular)</label><br>
                <input type="radio" id="forca" name="objetivo" value="Desenvolvimento de força">
                <label for="forca">Desenvolvimento de força</label><br>
                <input type="radio" id="recuperacao" name="objetivo" value="Recuperação contra lesão">
                <label for="recuperacao">Treinos de recuperação contra lesão</label><br>
                <input type="radio" id="explosao" name="objetivo" value="Explosão e coordenação motora">
                <label for="explosao">Treinos de explosão e coordenação motora voltados a esportes</label><br>
                <input type="radio" id="manutencao" name="objetivo" value="Manutenção geral da saúde">
                <label for="manutencao">Manutenção geral da saúde</label>
            </div>

            <div class="form-group">
                <label for="treinos">3. Qual a média de treinos por semana pretendida?</label>
                <input type="radio" id="treinos2" name="treinos" value="2">
                <label for="treinos2">2</label><br>
                <input type="radio" id="treinos3" name="treinos" value="3">
                <label for="treinos3">3</label><br>
                <input type="radio" id="treinos4" name="treinos" value="4 ou mais">
                <label for="treinos4">4 ou mais</label>
            </div>

            <div class="form-group">
                <label for="peso">4. Qual o seu peso? (kg)</label>
                <input type="number" id="peso" name="peso" required>
            </div>

            <div class="form-group">
                <label for="altura">5. Qual a sua altura? (cm)</label>
                <input type="number" id="altura" name="altura" required>
            </div>

            <div class="form-group">
                <button type="button" onclick="enviarFormulario()">Enviar Formulário</button>
            </div>
        </form>
    </div>

    <script>
        function enviarFormulario() {
            // Exibe a mensagem de sucesso
            alert("Seu formulário foi enviado ao personal trainer e a sua planilha de treino será enviada em até 3 dias úteis na caixa de notificações do site.");
        }
    </script>
</body>
</html>