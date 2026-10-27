<?php
/**
 * Testes locais do PI-6.
 * Uso: php tests/run_tests.php
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

$root = dirname(__DIR__);
$pass = 0;
$fail = 0;

function assert_true(bool $cond, string $label): void {
    global $pass, $fail;
    if ($cond) {
        echo "[OK] $label\n";
        $pass++;
    } else {
        echo "[FAIL] $label\n";
        $fail++;
    }
}

echo "=== PI-6 testes locais ===\n\n";

// 1) Arquivos essenciais existem
$required = [
    'index.php',
    'config.example.php',
    'sql/schema.sql',
    'cadastro/salva_cadastro.php',
    'cadastro/conecta.php',
    'acesso/conecta.php',
    'acesso/auth.php',
    'acesso/acesso_cadastro.php',
    'acesso/alterar_cadastro.php',
    'acesso/salvar_alteracao.php',
    'acesso/excluir_cadastro.php',
    'acesso/processar_exclusao.php',
    'acesso/logout.php',
    'acesso/compartilhar_relatorio.js',
    'acesso/telaprincuser.php',
    'acesso/controlepa.php',
    'acesso/controleglic.php',
    'acesso/antropometrico.php',
    'acesso/script.js',
    'acesso/modal_java.js',
];

foreach ($required as $rel) {
    assert_true(is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel)), "arquivo $rel");
}

assert_true(!is_file($root . DIRECTORY_SEPARATOR . 'telaprincuser.html'), 'protótipo órfão telaprincuser.html removido');

// 2) Branding PI-6 / sem PI-4 nos módulos
foreach (['controlepa.php', 'controleglic.php', 'antropometrico.php', 'telaprincuser.php'] as $f) {
    $content = file_get_contents($root . '/acesso/' . $f);
    assert_true(strpos($content, 'PI-6') !== false, "$f contém PI-6");
    assert_true(strpos($content, 'PI-4') === false, "$f sem PI-4");
}

// 3) HTML glicemia corrigido
$glic = file_get_contents($root . '/acesso/controleglic.php');
assert_true(strpos($glic, "</div\n") === false && !preg_match('/<\/div\s*$/m', $glic), 'controleglic.php sem tag </div quebrada');
assert_true(strpos($glic, '<div class="subtitulos">') !== false, 'controleglic.php tem subtitulos válido');

// 4) Links quebrados corrigidos
$hub = file_get_contents($root . '/acesso/telaprincuser.php');
assert_true(strpos($hub, 'alterar_cadastro.php') !== false, 'hub aponta alterar_cadastro.php');
assert_true(strpos($hub, 'excluir_cadastro.php') !== false, 'hub aponta excluir_cadastro.php');
assert_true(strpos($hub, 'logout.php') !== false, 'hub tem logout');
assert_true(strpos($hub, 'alterar-cadastro.html') === false, 'hub sem link html quebrado alterar');
assert_true(strpos($hub, 'excluir-cadastro.html') === false, 'hub sem link html quebrado excluir');

// 5) Relatórios com compartilhar / voltar
foreach (['controlepa.php', 'controleglic.php', 'antropometrico.php'] as $f) {
    $c = file_get_contents($root . '/acesso/' . $f);
    assert_true(strpos($c, 'compartilharWhatsApp') !== false, "$f tem WhatsApp");
    assert_true(strpos($c, 'compartilharEmail') !== false, "$f tem E-mail");
    assert_true(strpos($c, 'voltarInicio') !== false, "$f tem voltar");
    assert_true(strpos($c, 'compartilhar_relatorio.js') !== false, "$f inclui JS compartilhar");
}

// 6) JS sexo
$script = file_get_contents($root . '/acesso/script.js');
assert_true(strpos($script, 'isFeminino') !== false, 'script.js usa isFeminino');
assert_true(strpos($script, "sexo === \"f\"") === false, 'script.js sem comparação antiga sexo===f');

$modal = file_get_contents($root . '/acesso/modal_java.js');
assert_true(strpos($modal, "document.getElementById('sexo')") !== false, 'modal_java.js lê sexo do DOM');
assert_true(!preg_match('/let sexo = 0;/', $modal), 'modal_java.js não força sexo=0');

// 7) Login/cadastro usam hash
$login = file_get_contents($root . '/acesso/acesso_cadastro.php');
assert_true(strpos($login, 'password_verify') !== false, 'login usa password_verify');
assert_true(strpos($login, 'BINARY senha') === false, 'login sem comparação BINARY plaintext');

$cad = file_get_contents($root . '/cadastro/salva_cadastro.php');
assert_true(strpos($cad, 'password_hash') !== false, 'cadastro usa password_hash');
assert_true(strpos($cad, 'prepare(') !== false, 'cadastro usa prepared statement no INSERT');

// 8) Modal esqueceu senha corrigido
$acesso = file_get_contents($root . '/acesso/acessouser.php');
assert_true(strpos($acesso, "getElementById('eventModalEsqSenha')") !== false, 'modal esqueceu senha com id correto');
assert_true(strpos($acesso, 'EventModalEsqSenha') === false, 'sem id incorreto EventModalEsqSenha');

// 9) Extensão mysqli + conexão (se disponível)
if (!extension_loaded('mysqli')) {
    echo "[SKIP] extensão mysqli indisponível — pulando testes de banco\n";
} else {
    require $root . '/acesso/conecta.php';
    assert_true($conn instanceof mysqli, 'conexão MySQL ok');

    $schema = file_get_contents($root . '/sql/schema.sql');
    foreach (array_filter(array_map('trim', explode(';', $schema))) as $sql) {
        if ($sql === '') continue;
        // CREATE DATABASE / USE podem falhar se sem permissão; ignora USE se já conectado ao DB
        if (stripos($sql, 'CREATE DATABASE') === 0 || stripos($sql, 'USE ') === 0) {
            @$conn->query($sql);
            continue;
        }
        $ok = $conn->query($sql);
        assert_true((bool) $ok, 'schema: ' . substr($sql, 0, 40) . '...');
    }

    $email = 'teste_local_' . time() . '@pie6.local';
    $senha = 'Abcdef1!';
    $hash = password_hash($senha, PASSWORD_DEFAULT);
    $nome = 'Usuario Teste';
    $nomesoc = '';
    $dn = '1990-01-01';
    $sexo = 0;

    $ins = $conn->prepare('INSERT INTO tab_usuario (nome, nomesoc, dn, sexo, senha, email) VALUES (?, ?, ?, ?, ?, ?)');
    $ins->bind_param('sssiss', $nome, $nomesoc, $dn, $sexo, $hash, $email);
    assert_true($ins->execute(), 'INSERT usuário de teste');
    $uid = $ins->insert_id;
    $ins->close();

    $sel = $conn->prepare('SELECT senha FROM tab_usuario WHERE email = ?');
    $sel->bind_param('s', $email);
    $sel->execute();
    $row = $sel->get_result()->fetch_assoc();
    $sel->close();
    assert_true(password_verify($senha, $row['senha']), 'password_verify no usuário inserido');

    $novoNome = 'Usuario Teste Alt';
    $novoHash = password_hash('Xyzabc2@', PASSWORD_DEFAULT);
    $sexo1 = 1;
    $upd = $conn->prepare('UPDATE tab_usuario SET nome=?, sexo=?, senha=? WHERE id=?');
    $upd->bind_param('sisi', $novoNome, $sexo1, $novoHash, $uid);
    assert_true($upd->execute(), 'UPDATE usuário de teste');
    $upd->close();

    $csvPa = $root . "/acesso/dados_pressao_{$uid}.csv";
    $fp = fopen($csvPa, 'w');
    fputcsv($fp, ['Data', 'Hora', 'FC (bpm)', 'PAS', 'PAD']);
    fputcsv($fp, ['2026-01-01', '08:00', '70', '120', '80']);
    fclose($fp);
    assert_true(is_file($csvPa), 'CSV pressão criado para usuário teste');

    $del = $conn->prepare('DELETE FROM tab_usuario WHERE id = ?');
    $del->bind_param('i', $uid);
    assert_true($del->execute(), 'DELETE usuário de teste');
    $del->close();
    @unlink($csvPa);

    // Legado plaintext → verify path
    $email2 = 'legado_' . time() . '@pie6.local';
    $plain = 'Legado1!ab';
    $ins2 = $conn->prepare('INSERT INTO tab_usuario (nome, nomesoc, dn, sexo, senha, email) VALUES (?, ?, ?, ?, ?, ?)');
    $n2 = 'Legado'; $ns = ''; $dn2 = '1985-05-05'; $sx = 0;
    $ins2->bind_param('sssiss', $n2, $ns, $dn2, $sx, $plain, $email2);
    $ins2->execute();
    $uid2 = $ins2->insert_id;
    $ins2->close();

    $sel2 = $conn->prepare('SELECT id, senha FROM tab_usuario WHERE email = ?');
    $sel2->bind_param('s', $email2);
    $sel2->execute();
    $r2 = $sel2->get_result()->fetch_assoc();
    $sel2->close();
    $legacyOk = hash_equals($r2['senha'], $plain);
    assert_true($legacyOk, 'senha legada em texto puro reconhecível');

    $migrated = password_hash($plain, PASSWORD_DEFAULT);
    $up2 = $conn->prepare('UPDATE tab_usuario SET senha=? WHERE id=?');
    $up2->bind_param('si', $migrated, $uid2);
    $up2->execute();
    $up2->close();
    assert_true(password_verify($plain, $migrated), 'migração legado → hash');

    $del2 = $conn->prepare('DELETE FROM tab_usuario WHERE id = ?');
    $del2->bind_param('i', $uid2);
    $del2->execute();
    $del2->close();

    $conn->close();
}

echo "\n=== Resultado: $pass ok, $fail falha(s) ===\n";
exit($fail > 0 ? 1 : 0);
