// File: /Gyga-fit-gym/Gyga-fit-gym/public/js/calendario.js

document.addEventListener("DOMContentLoaded", function() {
    const calendarElement = document.getElementById('calendar');
    const alunosSelect = document.getElementById('alunos');
    const agendarButton = document.getElementById('agendar-button');
    const agendamentosList = document.getElementById('agendamentos-list');

    // Initialize the calendar
    const calendar = new FullCalendar.Calendar(calendarElement, {
        initialView: 'dayGridMonth',
        dateClick: function(info) {
            const selectedDate = info.dateStr;
            const selectedAluno = alunosSelect.value;

            if (selectedAluno) {
                agendarButton.onclick = function() {
                    criarAgendamento(selectedAluno, selectedDate);
                };
            } else {
                alert("Por favor, selecione um aluno.");
            }
        },
        events: '/Gyga-fit-gym/controllers/AgendamentoController.php?action=listarAgendamentos',
    });

    calendar.render();

    function criarAgendamento(id_aluno, data) {
        fetch('/Gyga-fit-gym/controllers/AgendamentoController.php?action=criarAgendamento', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id_aluno: id_aluno, data: data }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Agendamento criado com sucesso!");
                calendar.refetchEvents();
            } else {
                alert("Erro ao criar agendamento: " + data.message);
            }
        })
        .catch(error => {
            console.error("Erro:", error);
        });
    }

    // Load alunos into the select element
    fetch('/Gyga-fit-gym/controllers/AgendamentoController.php?action=listarAlunos')
        .then(response => response.json())
        .then(data => {
            data.forEach(aluno => {
                const option = document.createElement('option');
                option.value = aluno.id;
                option.textContent = aluno.nome;
                alunosSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Erro ao carregar alunos:", error);
        });
});