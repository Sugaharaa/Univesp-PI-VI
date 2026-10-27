<?php
require_once 'conecta.php';

$nome    = trim($_POST['nome'] ?? '');
$nomesoc = trim($_POST['nomesoc'] ?? '');
$dn      = $_POST['dn'] ?? '';
$sexo    = $_POST['sexo'] ?? '0';
$email   = trim($_POST['email'] ?? '');
$senha1  = $_POST['senha1'] ?? '';
$senha2  = $_POST['senha2'] ?? '';

function voltarComErro(string $msg): void {
    echo '<script>alert(' . json_encode($msg, JSON_UNESCAPED_UNICODE) . '); window.history.go(-1);</script>';
    exit;
}

if ($nome === '' || $dn === '' || $email === '' || $senha1 === '') {
    voltarComErro('Preencha todos os campos obrigatórios.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    voltarComErro('E-mail inválido.');
}

if ($senha1 !== $senha2) {
    voltarComErro('As senhas não coincidem.');
}

if (strlen($senha1) < 8 || strlen($senha1) > 12) {
    voltarComErro('A senha deve ter entre 8 e 12 caracteres.');
}

if (!preg_match('/[A-Z]/', $senha1) ||
    !preg_match('/[a-z]/', $senha1) ||
    !preg_match('/[0-9]/', $senha1) ||
    !preg_match('/["\'!@#%¨&_~<>,;:?]/u', $senha1)) {
    voltarComErro('A senha deve conter maiúscula, minúscula, número e caractere especial.');
}

if (!in_array($sexo, ['0', '1'], true)) {
    voltarComErro('Sexo inválido.');
}

$stmt = $conn->prepare('SELECT id FROM tab_usuario WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt->close();
    $conn->close();
    voltarComErro('Email já cadastrado!');
}
$stmt->close();

$hash = password_hash($senha1, PASSWORD_DEFAULT);
$sexoInt = (int) $sexo;

$stmt = $conn->prepare(
    'INSERT INTO tab_usuario (nome, nomesoc, dn, sexo, senha, email) VALUES (?, ?, ?, ?, ?, ?)'
);
$stmt->bind_param('sssiss', $nome, $nomesoc, $dn, $sexoInt, $hash, $email);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header('Location: ../index.php?salvo=1');
    exit;
}

$stmt->close();
$conn->close();
voltarComErro('Ocorreu um erro inesperado ao salvar o cadastro.');
