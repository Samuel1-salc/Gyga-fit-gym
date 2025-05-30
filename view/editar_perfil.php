<?php
session_start();
require_once('../config/database.class.php');

$db = Database::getInstance();
$pdo = $db->getConexao();

// Verifica se o usuário está logado e é um aluno
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['typeUser'] !== 'aluno') {
    header("Location: telaLogin.php");
    exit;
}

// Pega o ID do aluno logado da sessão
$id = $_SESSION['usuario']['id'];
$uploadDir = 'uploads/';

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $phone = $_POST['celular'];

    $foto_nome = null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        // Garante que a pasta 'uploads/' exista
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_nome = 'perfil_' . $id . '.' . strtolower($extensao);
        $caminho_completo = $uploadDir . $foto_nome;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_completo)) {
            $stmt = $pdo->prepare("UPDATE aluno SET username = ?, email = ?, phone = ?, foto = ? WHERE id = ?");
            $stmt->execute([$nome, $email, $phone, $foto_nome, $id]);
        } else {
            die('Erro ao salvar a imagem. Verifique as permissões da pasta uploads.');
        }
    } else {
        $stmt = $pdo->prepare("UPDATE aluno SET username = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->execute([$nome, $email, $phone, $id]);
    }

    header("Location: sucessoEditarPerfil.php");
    exit;
}

// Buscar dados do aluno
$stmt = $pdo->prepare("SELECT * FROM aluno WHERE id = ?");
$stmt->execute([$id]);
$aluno = $stmt->fetch();

// Foto
$foto = $aluno['foto'] ?? 'foto_padrao.jpg';
$fotoPath = file_exists("uploads/$foto") ? "uploads/$foto" : "foto_padrao.jpg";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - Gyga Fit</title>
    <link rel="stylesheet" href="style/editar.css">
</head>

<body>
    <div class="container">
        <h2>Editar Perfil</h2>

        <div class="profile-img">
            <img src="<?= $fotoPath ?>" alt="Foto de Perfil">
        </div>

        <form method="POST" enctype="multipart/form-data">
            <label>Escolha nova foto:</label>
            <input type="file" name="foto" accept="image/*">
            <small>Tamanho recomendado: 800x800 px - JPG ou PNG</small>

            <h3>INFORMAÇÕES PESSOAIS</h3>

            <label>Nome</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($aluno['username']) ?>" required>

            <label>Idade</label>
            <input type="number" name="idade" value="18" readonly>

            <label>E-mail</label>
            <input type="email" name="email" value="<?= htmlspecialchars($aluno['email']) ?>" required>

            <label>Celular</label>
            <input type="text" name="celular" value="<?= htmlspecialchars($aluno['phone']) ?>" required>

            <button type="submit">Salvar</button>
        </form>

        <footer>
            <p><strong>Gyga Fit</strong> - Tornando a academia acessível para todos.</p>
            <div class="social">
                <a href="#">Fale Conosco</a> |
                <a href="#">Política de Privacidade</a>
            </div>
        </footer>
    </div>
</body>

</html>