<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['usuario']['id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Usuário não autenticado']);
        exit;
    }

    require_once __DIR__ . '/../models/hidratacao.class.php';

    $userId = $_SESSION['usuario']['id'];
    $weight = filter_input(INPUT_POST, 'weight', FILTER_VALIDATE_FLOAT);

    if ($weight === false || $weight <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Peso inválido']);
        exit;
    }

    $waterTracking = new WaterTracking();
    $success = $waterTracking->updateWaterTracking($userId, $weight);

    if ($success) {
        echo json_encode(['message' => 'Meta diária de água atualizada com sucesso']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao atualizar meta diária de água']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
}
