<?php
session_start();
require_once __DIR__ . '/../config/database.class.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'instrutor') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_aluno'], $_POST['status'])) {
    $id_aluno = $_POST['id_aluno'];
    $status = $_POST['status'];

    $db = new Database();
    $link = $db->getConexao();

    $stmt = $link->prepare("UPDATE usuario_instrutor SET status = :status WHERE id_Aluno = :id_aluno AND id_instrutor = :id_instrutor");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id_aluno', $id_aluno);
    $stmt->bindParam(':id_instrutor', $_SESSION['usuario']['id']);

    if ($stmt->execute()) {
        header('Location: perfilInstrutor.php');
        exit;
    } else {
        echo "Erro ao atualizar status.";
    }
} else {
    echo "Requisição inválida.";
}
