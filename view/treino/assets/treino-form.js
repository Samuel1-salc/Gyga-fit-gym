/**
 * JavaScript para gerenciamento do formulário de treino
 * Separado da view para melhor organização
 */

let treinoIndex = 0;
const letras = ["A", "B", "C", "D", "E", "F", "G"];
const contadorExercicios = {};

function gerarTreinoHTML(letra) {
    return `
        <div class="treino-box" id="treino${letra}">
            <div class="treino-header">
                <h3 class="treino-title">
                    <i data-lucide="dumbbell" class="icon"></i>
                    Treino ${letra}
                </h3>
                <button type="button" class="botao-menos-treino" onclick="removerTreino('${letra}')">
                    <i data-lucide="x" class="icon"></i>
                </button>
            </div>
            
            <div class="treino-content">
                <div id="exercicios${letra}" class="exercicios-list">
                    ${gerarExercicioHTML(letra, 1)}
                </div>
                
                <button type="button" class="botao-mais" onclick="adicionarExercicio('${letra}')">
                    <i data-lucide="plus" class="btn-icon"></i>
                    Adicionar Exercício
                </button>
                
                <div class="observacao">
                    <label for="obs${letra}" class="observacao-label">
                        <i data-lucide="info" class="icon"></i>
                        Observações do Treino
                    </label>
                    <textarea name="obs[${letra}]" placeholder="Adicione instruções específicas, dicas de execução, tempo de descanso, etc..." class="observacao-textarea"></textarea>
                </div>
            </div>
        </div>
    `;
}

function gerarExercicioHTML(letra, numero) {
    if (!contadorExercicios[letra]) contadorExercicios[letra] = 1;

    const grupoOptions = dadosTreino.gruposMusculares.map(grupo =>
        `<option value="${grupo.grupo_muscular.replace(/"/g, '&quot;')}">${grupo.grupo_muscular}</option>`
    ).join('');

    const exercicioSelectOptions = dadosTreino.exercicios.map(ex =>
        `<option value="${ex.id}" data-grupo="${ex.grupo_muscular}">${ex.nome_exercicio}</option>`
    ).join('');

    return `
        <div class="exercicio-card">
            <div class="exercicio-header">
                <h4 class="exercicio-title">
                    <div class="exercicio-numero">${numero}</div>
                    Exercício ${numero}
                </h4>
                <button type="button" class="botao-menos" onclick="removerExercicio('${letra}', ${contadorExercicios[letra]})">
                    <i data-lucide="x" class="icon"></i>
                </button>
            </div>
            
            <div class="exercicio-form">
                <input type="hidden" name="dados[${letra}][${numero}][num_exercicio]" value="${numero}">
                
                <div class="form-group">
                    <label class="form-label required">Grupo Muscular</label>
                    <select name="dados[${letra}][${numero}][grupo_muscular]" class="form-select grupo-muscular-dropdown" data-letra="${letra}" data-numero="${numero}" required>
                        <option value="" disabled selected>Selecione o grupo</option>
                        ${grupoOptions}
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Exercício</label>
                    <select name="dados[${letra}][${numero}][nome_exercicio]" class="form-select exercicio-dropdown" required>
                        <option value="" disabled selected>Selecione o exercício</option>
                        ${exercicioSelectOptions}
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Séries</label>
                    <input type="number" name="dados[${letra}][${numero}][series_exercicio]" placeholder="Ex: 3" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Repetições</label>
                    <input type="text" name="dados[${letra}][${numero}][repeticoes_exercicio]" placeholder="Ex: 12-15" class="form-input" required>
                </div>
            </div>
        </div>
    `;
}

function adicionarExercicio(letra) {
    const container = document.getElementById(`exercicios${letra}`);
    contadorExercicios[letra]++;
    container.insertAdjacentHTML('beforeend', gerarExercicioHTML(letra, contadorExercicios[letra]));
    lucide.createIcons();
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
    lucide.createIcons();
}

function removerExercicio(letra, numero) {
    const container = document.getElementById(`exercicios${letra}`);
    const exercicio = document.querySelector(`#exercicios${letra} .exercicio-card:nth-child(${numero})`);

    if (exercicio) {
        exercicio.remove();
        contadorExercicios[letra]--;

        const exerciciosRestantes = container.querySelectorAll('.exercicio-card');
        exerciciosRestantes.forEach((ex, index) => {
            const titulo = ex.querySelector('.exercicio-title');
            titulo.innerHTML = `
                <div class="exercicio-numero">${index + 1}</div>
                Exercício ${index + 1}
            `;
            const inputNumero = ex.querySelector(`input[name*="num_exercicio"]`);
            if (inputNumero) {
                inputNumero.value = index + 1;
            }
        });
    }
}

function removerTreino(letra) {
    const container = document.getElementById("treinosContainer");
    const treino = document.getElementById(`treino${letra}`);

    if (treino) {
        treino.remove();
        treinoIndex--;
    }
}

// Event Listeners
document.addEventListener('change', function (e) {
    if (e.target.classList.contains('grupo-muscular-dropdown')) {
        const grupoSelecionado = e.target.value;
        const letra = e.target.getAttribute('data-letra');
        const numero = e.target.getAttribute('data-numero');

        const exercicioDropdown = document.querySelector(
            `select[name="dados[${letra}][${numero}][nome_exercicio]"]`
        );

        const exerciciosFiltrados = dadosTreino.exercicios
            .filter(ex => ex.grupo_muscular === grupoSelecionado)
            .map(ex => `<option value="${ex.id}">${ex.nome_exercicio}</option>`)
            .join('');

        exercicioDropdown.innerHTML = `<option value="">Selecione</option>${exerciciosFiltrados}`;
    }
});

document.getElementById("formPlano").addEventListener("submit", function (e) {
    document.getElementById("mensagemSucesso").style.display = "block";
    setTimeout(() => {
        document.getElementById("mensagemSucesso").style.display = "none";
    }, 3000);
});

document.addEventListener("DOMContentLoaded", function () {
    adicionarTreino(); // Inicializa com o primeiro treino
    lucide.createIcons(); // Inicializa os ícones
});
