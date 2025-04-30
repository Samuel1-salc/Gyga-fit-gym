<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requerimento de Planilha de Treino</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
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
    <h1>Requerimento de Planilha de Treino</h1>
    <form action="./../controllers/SolicitarTreino.php" method="post">
        <div class="form-group pergunta">
            <label>1. Qual a sua experiência na academia?</label>
            
            <div class="radio-group">
                <input type="radio" id="iniciante" name="experiencia" value="Iniciante" required>
                <label for="iniciante">Iniciante</label>
                
                <input type="radio" id="intermediario" name="experiencia" value="Intermediário">
                <label for="intermediario">Intermediário (pelo menos 2 anos)</label>
                
                <input type="radio" id="avancado" name="experiencia" value="Avançado">
                <label for="avancado">Avançado (pelo menos 5 anos)</label>
            </div>
        </div>

        <div class="form-group pergunta">
            <label>2. Qual o seu objetivo na academia?</label>
            <div class="radio-group">
                <input type="radio" id="hipertrofia" name="objetivo" value="Hipertrofia" required>
                <label for="hipertrofia">Hipertrofia (ganho de massa muscular)</label>
                
                <input type="radio" id="forca" name="objetivo" value="Força">
                <label for="forca">Desenvolvimento de força</label>
                
                <input type="radio" id="recuperacao" name="objetivo" value="Recuperação">
                <label for="recuperacao">Recuperação contra lesão</label>
                
                <input type="radio" id="explosao" name="objetivo" value="Explosão">
                <label for="explosao">Explosão e coordenação motora</label>
                
                <input type="radio" id="manutencao" name="objetivo" value="Manutenção">
                <label for="manutencao">Manutenção geral da saúde</label>
            </div>
        </div>

        <div class="form-group pergunta">
            <label>3. Quantos dias por semana pretende treinar?</label>
            <div class="radio-group">
                <input type="radio" id="treinos2" name="treinos" value="2" required>
                <label for="treinos2">2</label>
                
                <input type="radio" id="treinos3" name="treinos" value="3">
                <label for="treinos3">3</label>
                
                <input type="radio" id="treinos4" name="treinos" value="4 ou mais">
                <label for="treinos4">4 ou mais</label>
            </div>
        </div>

        <div class="form-group pergunta">
            <label>4. Sexo:</label>
            <div class="radio-group">
                <input type="radio" id="masculino" name="sexo" value="M">
                <label for="masculino">Masculino</label>
                
                <input type="radio" id="feminino" name="sexo" value="F">
                <label for="feminino">Feminino</label>

                <input type="radio" id="Outro" name="sexo" value="O">
                <label for="outro">Outro</label>
            </div>
        </div>

        <div class="form-group pergunta">
            <label for="peso">5. Qual o seu peso? (kg)</label>
            <input type="number" id="peso" name="peso" required>
        </div>

        <div class="form-group pergunta">
            <label for="altura">6. Qual a sua altura? (cm)</label>
            <input type="number" id="altura" name="altura" required>
        </div>
        
        <div class="form-group pergunta">
            <button type="submit">Enviar Formulário</button>
        </div>
    </form>
</div>

</body>
</html>