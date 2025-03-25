<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYGA FIT</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="./style/Tela-Principal.css">

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
        <h3>Informações do Aluno</h3>
        <p><strong>Nome:</strong> </p>
        <p><strong>Altura:</strong>  </p>
        <p><strong>Peso:</strong> </p>
        <p><strong>Sexo:</strong> </p>
    </div>

    <div class="dias-semana">
        <button onclick="mostrarTreino('segunda')">Segunda</button>
        <button onclick="mostrarTreino('terca')">Terça</button>
        <button onclick="mostrarTreino('quarta')">Quarta</button>
        <button onclick="mostrarTreino('quinta')">Quinta</button>
        <button onclick="mostrarTreino('sexta')">Sexta</button>
    </div>
    <div id="treino" class="treino">
        <h3>Treino do Dia</h3>
        <p>Selecione um dia para ver o treino.</p>
    </div>

    <div class="treino">
        <h3>Progresso de Treino</h3>
        <div class="progresso">
            <div class="progresso-barra" id="barra-progresso"></div>
        </div>
        <p id="texto-progresso">Progresso: 0%</p>
        <button class="botao-progresso" id="botao-progresso" onclick="atualizarProgresso()">Treino Finalizado</button>
        <button class="botao-novo-treino" id="botao-novo-treino" onclick="solicitarNovoTreino()">Solicitar Novo Treino</button>
    </div>

</div>

<script>
    let progresso = 0;
    let treinoSelecionado = false;

    const treinos = {
        segunda: [
            { nome: "Supino reto", series: 4, repeticoes: "8-12", musculos: "Peito, Tríceps" },
            { nome: "Cadeira extensora", series: 4, repeticoes: "10-15", musculos: "Quadríceps" },
            { nome: "Tríceps pulley", series: 3, repeticoes: "10-12", musculos: "Tríceps" }
        ],
        terca: [
            { nome: "Puxada frontal", series: 4, repeticoes: "8-12", musculos: "Costas, Bíceps" },
            { nome: "Remada curvada", series: 4, repeticoes: "10-12", musculos: "Costas, Bíceps" },
            { nome: "Rosca direta", series: 3, repeticoes: "10-12", musculos: "Bíceps" }
        ],
        quarta: [
            { nome: "Agachamento", series: 4, repeticoes: "8-12", musculos: "Pernas, Glúteos" },
            { nome: "Leg press", series: 4, repeticoes: "10-15", musculos: "Pernas" },
            { nome: "Stiff", series: 3, repeticoes: "10-12", musculos: "Posterior de coxa, Glúteos" }
        ],
        quinta: [
            { nome: "Desenvolvimento", series: 4, repeticoes: "8-12", musculos: "Ombros" },
            { nome: "Elevação lateral", series: 3, repeticoes: "10-12", musculos: "Ombros" },
            { nome: "Encolhimento de ombros", series: 4, repeticoes: "10-12", musculos: "Trapézio" }
        ],
        sexta: [
            { nome: "Corrida", series: 1, repeticoes: "30 minutos", musculos: "Cardio, Perna" },
            { nome: "Prancha", series: 3, repeticoes: "30 segundos", musculos: "Core" },
            { nome: "Abdominal", series: 4, repeticoes: "15-20", musculos: "Abdômen" }
        ]
    };

    function mostrarTreino(dia) {
        const treino = treinos[dia];
        let treinoHTML = `<h3>Treino de ${dia.charAt(0).toUpperCase() + dia.slice(1)}</h3><ul>`;

        treino.forEach(exercicio => {
            treinoHTML += `
                <li class="exercicio">
                    <strong>${exercicio.nome}</strong><br>
                    Séries: ${exercicio.series} | Repetições: ${exercicio.repeticoes}<br>
                    <em>Músculos trabalhados: ${exercicio.musculos}</em>
                    <select  id ="peso">
                        <option value = "" disabled selected>Selecione</option>
                    </select>
                </li>
            `;
        });

        treinoHTML += "</ul>";
        document.getElementById('treino').innerHTML = treinoHTML;

        document.getElementById('botao-progresso').style.display = "block";
        treinoSelecionado = true;
    }

    function atualizarProgresso() {
        if (progresso < 100) {
            progresso += 2;
            document.getElementById('barra-progresso').style.width = progresso + "%";
            document.getElementById('texto-progresso').innerText = `Progresso: ${progresso}%`;

            if (progresso >= 100) {
                document.getElementById('botao-novo-treino').style.display = "block"; 
            }

            document.getElementById('botao-progresso').style.display = "none"; 
        }
    }

    function solicitarNovoTreino() {
        alert("Solicitação de novo treino enviada! Aguarde a atualização.");
    }

    function editarPerfil() {
        alert("Abrindo tela de edição de perfil...");
    }

    function abrirConfiguracoes() {
        alert("Abrindo configurações...");
    }
</script>

</body>
</html>