<?php
/**
 * Fluxo HTTP: cadastro → login → alterar → CSV → excluir
 * Requer servidor em $base (php -S).
 */
$base = getenv('PIE6_BASE') ?: 'http://127.0.0.1:8080';
$cookie = tempnam(sys_get_temp_dir(), 'pie6cookie');
$email = 'httpflow_' . time() . '@pie6.local';
$senha = 'HttpTest1!';
$pass = 0;
$fail = 0;

function req(string $method, string $url, array $opts = []): array {
    global $cookie;
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_COOKIEJAR => $cookie,
        CURLOPT_COOKIEFILE => $cookie,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HEADER => true,
    ]);
    if (!empty($opts['post'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $opts['post']);
    }
    if (!empty($opts['json'])) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($opts['json']));
    }
    $raw = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);
    return [
        'code' => $code,
        'headers' => substr((string)$raw, 0, $headerSize),
        'body' => substr((string)$raw, $headerSize),
    ];
}

function check(bool $ok, string $label): void {
    global $pass, $fail;
    echo ($ok ? '[OK] ' : '[FAIL] ') . $label . "\n";
    $ok ? $pass++ : $fail++;
}

echo "=== HTTP flow @ $base ===\n";

$probe = @file_get_contents($base . '/index.php');
if ($probe === false) {
    fwrite(STDERR, "Servidor indisponível em $base\n");
    fwrite(STDERR, "Suba antes: php -S 127.0.0.1:8080 -t .\n");
    exit(2);
}

$home = req('GET', "$base/index.php");
check($home['code'] === 200, 'GET index.php');

$cad = req('POST', "$base/cadastro/salva_cadastro.php", [
    'post' => [
        'nome' => 'Fluxo HTTP',
        'nomesoc' => '',
        'dn' => '1992-02-02',
        'sexo' => '0',
        'email' => $email,
        'senha1' => $senha,
        'senha2' => $senha,
    ],
]);
check($cad['code'] === 302 || strpos($cad['headers'], 'Location: ../index.php?salvo=1') !== false, 'POST cadastro redireciona salvo');

$login = req('POST', "$base/acesso/acesso_cadastro.php", [
    'post' => ['email' => $email, 'senha' => $senha],
]);
check($login['code'] === 302 || strpos($login['headers'], 'telaprincuser.php') !== false, 'POST login redireciona hub');

$hub = req('GET', "$base/acesso/telaprincuser.php");
check($hub['code'] === 200 && strpos($hub['body'], 'Fluxo HTTP') !== false, 'GET hub autenticado');

$altPage = req('GET', "$base/acesso/alterar_cadastro.php");
check($altPage['code'] === 200 && strpos($altPage['body'], 'Alterar Cadastro') !== false, 'GET alterar cadastro');

$alt = req('POST', "$base/acesso/salvar_alteracao.php", [
    'post' => [
        'nome' => 'Fluxo HTTP Alt',
        'nomesoc' => 'Social',
        'dn' => '1992-02-02',
        'sexo' => '1',
        'email' => $email,
        'senha_atual' => $senha,
        'senha1' => '',
        'senha2' => '',
    ],
]);
check(strpos($alt['body'], 'atualizado') !== false || $alt['code'] === 200, 'POST alterar cadastro');

$csvSave = req('POST', "$base/acesso/salvar_csv.php", [
    'json' => [['2026-03-01', '09:00', '72', '118', '76']],
]);
check(strpos($csvSave['body'], 'sucesso') !== false, 'POST salvar CSV pressão');

$csvLoad = req('GET', "$base/acesso/carregar_csv.php");
check(strpos($csvLoad['body'], '118') !== false, 'GET carregar CSV pressão');

$pa = req('GET', "$base/acesso/controlepa.php");
check($pa['code'] === 200 && strpos($pa['body'], 'compartilharWhatsApp') !== false, 'GET controle PA com compartilhar');

$excPage = req('GET', "$base/acesso/excluir_cadastro.php");
check($excPage['code'] === 200, 'GET excluir cadastro');

$exc = req('POST', "$base/acesso/processar_exclusao.php", [
    'post' => ['senha' => $senha],
]);
check(strpos($exc['body'], 'excluído') !== false || strpos($exc['headers'], 'index.php') !== false, 'POST excluir cadastro');

$hub2 = req('GET', "$base/acesso/telaprincuser.php");
check($hub2['code'] === 302 || strpos($hub2['headers'], 'index.php') !== false || strpos($hub2['body'], 'Bem-vindo') === false, 'hub bloqueado após exclusão');

@unlink($cookie);
echo "\n=== HTTP: $pass ok, $fail falha(s) ===\n";
exit($fail > 0 ? 1 : 0);
