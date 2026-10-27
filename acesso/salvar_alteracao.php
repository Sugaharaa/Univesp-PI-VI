<?php
require_once 'conecta.php';
require_once 'auth.php';

$id = (int) $_SESSION['id'];

$nome       = trim($_POST['nome'] ?? '');
$nomesoc    = trim($_POST['nomesoc'] ?? '');
$dn         = $_POST['dn'] ?? '';
$email      = trim($_POST['email'] ?? '');
$sexo       = $_POST['sexo'] ?? '0';
$senhaAtual = $_POST['senha_atual'] ?? '';
$senha1     = $_POST['senha1'] ?? '';
$senha2     = $_POST['senha2'] ?? '';

function falha(string $msg): void {
    echo '<script>alert(' . json_encode($msg, JSON_UNESCAPED_UNICODE) . '); window.history.go(-1);</script>';
    exit;
}

if ($nome === '' || $dn === '' || $email === '' || $senhaAtual === '') {
    falha('Preencha os campos obrigatórios.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    falha('E-mail inválido.');
}

if (!in_array($sexo, ['0', '1'], true)) {
    falha('Sexo inválido.');
}

$stmt = $conn->prepare('SELECT senha FROM tab_usuario WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    falha('Usuário não encontrado.');
}

$hashAtual = $row['senha'];
$senhaOk = password_verify($senhaAtual, $hashAtual)
    || hash_equals((string) $hashAtual, (string) $senhaAtual);

if (!$senhaOk) {
    falha('Senha atual incorreta.');
}

$chk = $conn->prepare('SELECT id FROM tab_usuario WHERE email = ? AND id <> ?');
$chk->bind_param('si', $email, $id);
$chk->execute();
if ($chk->get_result()->num_rows > 0) {
    $chk->close();
    falha('Este e-mail já está em uso por outro usuário.');
}
$chk->close();

$sexoInt = (int) $sexo;

if ($senha1 !== '') {
    if ($senha1 !== $senha2) {
        falha('As novas senhas não coincidem.');
    }
    if (strlen($senha1) < 8 || strlen($senha1) > 12) {
        falha('A nova senha deve ter entre 8 e 12 caracteres.');
    }
    $novoHash = password_hash($senha1, PASSWORD_DEFAULT);
    $upd = $conn->prepare(
        'UPDATE tab_usuario SET nome=?, nomesoc=?, dn=?, sexo=?, email=?, senha=? WHERE id=?'
    );
    $upd->bind_param('sssissi', $nome, $nomesoc, $dn, $sexoInt, $email, $novoHash, $id);
} else {
    // Rehash se ainda estiver em texto puro
    if (!password_get_info($hashAtual)['algo']) {
        $hashAtual = password_hash($senhaAtual, PASSWORD_DEFAULT);
    }
    $upd = $conn->prepare(
        'UPDATE tab_usuario SET nome=?, nomesoc=?, dn=?, sexo=?, email=?, senha=? WHERE id=?'
    );
    $upd->bind_param('sssissi', $nome, $nomesoc, $dn, $sexoInt, $email, $hashAtual, $id);
}

if (!$upd->execute()) {
    $upd->close();
    falha('Não foi possível salvar as alterações.');
}
$upd->close();
$conn->close();

echo '<script>alert("Cadastro atualizado com sucesso!"); window.location.href="telaprincuser.php";</script>';
exit;
