<?php
// controllers/gerarTreinoPdf.php

require_once __DIR__ . '/../vendor/autoload.php';       // lê Dompdf
require_once __DIR__ . '/../config/database.class.php'; // lê Database
require_once __DIR__ . '/../models/Treino.class.php';   // lê seu model Treino
require_once __DIR__ . '/PdfService.php';               // lê o serviço que gera o PDF

session_start();

// 1) Parâmetro alunoId via GET
$alunoId = $_SESSION['usuario']['id'];
if (!$alunoId) {
    http_response_code(400);
    exit('Parâmetro alunoId inválido.');
}

// 2) Busca os dados do plano (ou fallback)
$model = new Treino();
$dados = $model->getPlanoParaPdf($alunoId);

// 3) Se não encontrou **nenhum** dado (array vazio), retorna 404
if (empty($dados)) {
    http_response_code(404);
    exit('Nenhum treino disponível para este aluno.');
}

// 4) Gera e envia o PDF para **download**
$pdfService = new PdfService();
$pdfService->render($dados);
