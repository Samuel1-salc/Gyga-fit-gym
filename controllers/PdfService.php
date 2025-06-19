<?php
// controllers/PdfService.php

// 1) Inclui o autoloader gerado pelo Composer
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

class PdfService
{
    /**
     * Gera e envia o PDF para download no navegador.
     *
     * @param array $dados Estrutura retornada por Treino::getPlanoParaPdf()
     */
    public function render(array $dados): void
    {
        extract($dados);

        // 1) Renderiza o HTML do template
        ob_start();
        include __DIR__ . '/../templates/treino.php';
        $html = ob_get_clean();

        // 2) Converte para PDF via Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // 3) Força o download do arquivo PDF
        header('Content-Type: application/pdf');
        header(
            'Content-Disposition: attachment; filename="treino_'
            . ($aluno['id'] ?? 'x')
            . '.pdf"'
        );
        echo $dompdf->output();
        exit;
    }
}
