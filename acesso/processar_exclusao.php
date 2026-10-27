<?php
require_once 'conecta.php';
require_once 'auth.php';

$id = (int) $_SESSION['id'];
$senha = $_POST['senha'] ?? '';

function falha(string $msg): void {
    echo '<script>alert(' . json_encode($msg, JSON_UNESCAPED_UNICODE) . '); window.history.go(-1);</script>';
    exit;
}

if ($senha === '') {
    falha('Informe a senha para confirmar a exclusão.');
}

$stmt = $conn->prepare('SELECT senha FROM tab_usuario WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    falha('Usuário não encontrado.');
}

$hash = $row['senha'];
$ok = password_verify($senha, $hash) || hash_equals((string) $hash, (string) $senha);

if (!$ok) {
    falha('Senha incorreta.');
}

$del = $conn->prepare('DELETE FROM tab_usuario WHERE id = ?');
$del->bind_param('i', $id);
$del->execute();
$del->close();
$conn->close();

foreach ([
    "dados_pressao_{$id}.csv",
    "dados_glicemia_{$id}.csv",
    "dados_antro_{$id}.csv",
] as $arquivo) {
    $path = __DIR__ . DIRECTORY_SEPARATOR . $arquivo;
    if (is_file($path)) {
        @unlink($path);
    }
}

$_SESSION = [];
session_destroy();

echo '<script>alert("Cadastro excluído com sucesso."); window.location.href="../index.php";</script>';
exit;
