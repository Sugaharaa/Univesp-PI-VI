<?php
require_once 'auth.php';
$id = (int) $_SESSION['id'];
$arquivo = "dados_glicemia_{$id}.csv";

if (!file_exists($arquivo)) {
    echo json_encode([]);
    exit;
}

$dados = [];
if (($handle = fopen($arquivo, "r")) !== false) {
    fgetcsv($handle);
    while (($linha = fgetcsv($handle)) !== false) {
        $dados[] = $linha;
    }
    fclose($handle);
}

echo json_encode($dados);
