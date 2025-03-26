<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Treino</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style/tela-Form.css">
    
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
