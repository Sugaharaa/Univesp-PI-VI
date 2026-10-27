<?php
require_once 'auth.php';
$id = (int) $_SESSION['id'];
$dados = json_decode(file_get_contents("php://input"), true);

if (!is_array($dados)) {
    http_response_code(400);
    echo "Dados inválidos.";
    exit;
}

$arquivo = "dados_glicemia_{$id}.csv";
$fp = fopen($arquivo, "w");

if ($fp === false) {
    http_response_code(500);
    echo "Erro ao abrir o arquivo.";
    exit;
}

fputcsv($fp, ["Data", "Hora", "Glicemia", "Tipo", "TempoTipo"]);

foreach ($dados as $linha) {
    fputcsv($fp, $linha);
}

fclose($fp);
echo "Dados salvos com sucesso!";
