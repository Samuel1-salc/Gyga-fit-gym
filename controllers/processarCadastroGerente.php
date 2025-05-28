<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once __DIR__ . '/../models/Usuarios.class.php';
    require_once __DIR__ . '/../models/UserGerente.class.php';

    $usuarios = new Users();
    $gerente = new UserGerente();
    $erro = '';

    // Recebe os dados do formulário
    $nome = trim($_POST['nome'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    // Validações
    if (empty($nome) || empty($cpf) || empty($senha)) {
        $erro = "Preencha todos os campos!";
    } elseif (strlen($cpf) != 11) {
        $erro = "CPF inválido!";
    } else {
        // Verifica se o gerente já existe
        if ($usuarios->getDataGerenteByCpf($cpf)) {
            $erro = "Gerente já cadastrado!";
        } else {
            // Cadastra o gerente
            if ($gerente->cadastrarGerente($nome, $cpf, password_hash($senha, PASSWORD_DEFAULT))) {
                $_SESSION['success'] = "Cadastro realizado com sucesso!";

                exit();
            } else {
                $erro = "Erro ao cadastrar gerente!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Login do Gerente</title>
</head>

<body>
    <h2>Login - Gerente</h2>

    <?php if (!empty($erro)): ?>
        <p style="color:red;"><?php echo $erro; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>nome:</label>.
        <input type="text" name="nome" required>
        <br><br>
        <label>CPF:</label>
        <input type="text" name="cpf" required>
        <br><br>
        <label>Senha:</label>
        <input type="password" name="senha" required>
        <br><br>
        <button type="submit">entrar</button>
    </form>
</body>

</html>