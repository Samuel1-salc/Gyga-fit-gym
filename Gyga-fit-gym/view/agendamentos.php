<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamentos - GYGA FIT</title>
    <link rel="stylesheet" href="./view/style/agendamentos.css?v=<?= time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="./public/js/calendario.js" defer></script>
</head>

<body>
    <header>
        <h1>Agendamentos de Consultas</h1>
    </header>

    <main>
        <section class="calendario">
            <h2>Selecione uma Data</h2>
            <div id="calendario"></div>
        </section>

        <section class="agendamentos">
            <h2>Agendamentos</h2>
            <div id="lista-agendamentos">
                <!-- Lista de agendamentos será carregada aqui via JavaScript -->
            </div>
        </section>

        <section class="form-agendamento">
            <h2>Criar Agendamento</h2>
            <form id="form-agendamento" method="POST" action="./controllers/AgendamentoController.php">
                <label for="aluno">Selecionar Aluno:</label>
                <select id="aluno" name="id_aluno" required>
                    <!-- Opções de alunos serão carregadas aqui via JavaScript -->
                </select>

                <input type="hidden" id="data-agendamento" name="data" required>

                <button type="submit">Agendar Consulta</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> GYGA FIT. Todos os direitos reservados.</p>
    </footer>
</body>

</html>