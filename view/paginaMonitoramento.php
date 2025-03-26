<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Treino</title>
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
        .form-group select, .form-group input {
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
        <h1>Registro de Treino</h1>
        <form id="formTreino">
            <div class="form-group">
                <label for="diaTreino">Qual treino da semana?</label>
                <select id="diaTreino" name="diaTreino" required>
                    <option value="">Selecione</option>
                    <option value="segunda">Segunda</option>
                    <option value="terca">Terça</option>
                    <option value="quarta">Quarta</option>
                    <option value="quinta">Quinta</option>
                    <option value="sexta">Sexta</option>
                </select>
            </div>

            <div class="form-group">
                <label for="exercicio1">Quantos quilos e repetições você executou no primeiro exercício?</label>
                <input type="text" id="exercicio1" name="exercicio1" required>
            </div>

            <div class="form-group">
                <label for="exercicio2">Quantos quilos e repetições você executou no segundo exercício?</label>
                <input type="text" id="exercicio2" name="exercicio2" required>
            </div>

            <div class="form-group">
                <label for="exercicio3">Quantos quilos e repetições você executou no terceiro exercício?</label>
                <input type="text" id="exercicio3" name="exercicio3" required>
            </div>

            <div class="form-group">
                <button type="button" onclick="enviarDados()">Enviar Dados de Treino</button>
            </div>
        </form>
    </div>

    <script>
        function enviarDados() {
            alert("Seus dados foram enviados para seu gráfico de evolução mensal!");
        }
    </script>
</body>
</html>
