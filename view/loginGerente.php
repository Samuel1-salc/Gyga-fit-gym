<?php
session_start();
$erro = $_SESSION['erro'] ?? '';
unset($_SESSION['erro']);
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

    <form method="POST" action="../controllers/processarLoginGerente.php">
        <label>CPF:</label>
        <input type="text" name="cpf" required>
        <br><br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
