<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitação de Treino - Sucesso</title>
    <link rel="stylesheet" href="./style/sucesso.css?v=<?=time()?>">
    <style>
        body {
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .sucesso-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            padding: 40px 32px;
            text-align: center;
            max-width: 400px;
        }
        .sucesso-icone {
            font-size: 48px;
            color: #10b981;
            margin-bottom: 16px;
        }
        .sucesso-msg {
            font-size: 20px;
            font-weight: 600;
            color: #047857;
            margin-bottom: 32px;
        }
        .btn-ok {
            background: #10b981;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 32px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-ok:hover {
            background: #059669;
        }
    </style>
</head>
<body>
    <div class="sucesso-container">
        <div class="sucesso-icone">&#10003;</div>
        <div class="sucesso-msg">Plano de treino finalizado e enviado com sucesso!</div>
        <button class="btn-ok" onclick="window.location.href='/Gyga-fit-gym/index.php?page=perfilInstrutor'">OK</button>
    </div>
</body>
</html>
