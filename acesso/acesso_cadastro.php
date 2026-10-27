<?php
require_once 'conecta.php';

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($email === '' || $senha === '') {
    echo "<script>alert('Informe e-mail e senha.'); window.history.go(-1);</script>";
    exit;
}

$stmt = $conn->prepare('SELECT id, senha FROM tab_usuario WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    echo "<script>alert('Login, senha ou ID incorreto(s)!'); window.history.go(-1);</script>";
    exit;
}

$row = $result->fetch_assoc();
$stmt->close();

$hash = $row['senha'];
$ok = password_verify($senha, $hash);

// Compatibilidade com senhas antigas em texto puro
if (!$ok && hash_equals((string) $hash, (string) $senha)) {
    $ok = true;
    $novoHash = password_hash($senha, PASSWORD_DEFAULT);
    $upd = $conn->prepare('UPDATE tab_usuario SET senha = ? WHERE id = ?');
    $upd->bind_param('si', $novoHash, $row['id']);
    $upd->execute();
    $upd->close();
}

if (!$ok) {
    echo "<script>alert('Login, senha ou ID incorreto(s)!'); window.history.go(-1);</script>";
    exit;
}

session_start();
$_SESSION['id'] = (int) $row['id'];
header('Location: telaprincuser.php');
exit;
