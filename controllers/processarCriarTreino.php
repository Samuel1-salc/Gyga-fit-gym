<?php
// Conexão com o banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "giga_teste";

$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Recebe os dados do formulário
$nome_aluno = $_POST['nome_aluno'];
$exercicio1 = $_POST['exercicio1'];
$repeticoes1 = $_POST['repeticoes1'];
$exercicio2 = $_POST['exercicio2'];
$repeticoes2 = $_POST['repeticoes2'];
$exercicio3 = $_POST['exercicio3'];
$repeticoes3 = $_POST['repeticoes3'];
$observacoes = $_POST['observacoes'];

// Prepara o SQL para inserção
$sql = "INSERT INTO treinos (nome_aluno, exercicio1, repeticoes1, exercicio2, repeticoes2, exercicio3, repeticoes3, observacoes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $nome_aluno, $exercicio1, $repeticoes1, $exercicio2, $repeticoes2, $exercicio3, $repeticoes3, $observacoes);

// Executa e verifica o resultado
if ($stmt->execute()) {
    echo "Treino cadastrado com sucesso!";
} else {
    echo "Erro ao cadastrar treino: " . $stmt->error;
}

// Fecha a conexão
$stmt->close();
$conn->close();
?>
