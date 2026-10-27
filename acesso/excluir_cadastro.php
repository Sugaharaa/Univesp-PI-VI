<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Excluir Cadastro</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; padding-top: 90px; background: #f4f7fb; }
    .box {
      max-width: 520px; margin: 0 auto; background: #fff; padding: 24px;
      border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,.12);
    }
    h1 { color: #c0392b; text-align: center; }
    p { line-height: 1.5; color: #333; }
    label { display: block; margin: 12px 0 4px; font-weight: bold; }
    input { width: 100%; padding: 8px; box-sizing: border-box; }
    .acoes { display: flex; gap: 12px; margin-top: 20px; }
    button, .btn-voltar {
      flex: 1; padding: 12px; border: none; border-radius: 6px; cursor: pointer;
      text-align: center; text-decoration: none; font-weight: bold; color: #fff;
    }
    button { background: #c0392b; }
    .btn-voltar { background: #6c757d; }
  </style>
</head>
<?php
require_once 'conecta.php';
require_once 'auth.php';

$id = (int) $_SESSION['id'];
$stmt = $conn->prepare('SELECT nome, email FROM tab_usuario WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    header('Location: logout.php');
    exit;
}
?>
<body>
<div style="position:fixed;top:0;left:0;width:100%;background:#34495e;color:#fff;padding:15px;text-align:center;font-size:24px;font-weight:bold;z-index:1000;">
  PI-6 - Acompanhamento de Saúde Individual UNIVESP
</div>

<div class="box">
  <h1>Excluir Cadastro</h1>
  <p>Esta ação remove permanentemente o cadastro de
    <strong><?php echo htmlspecialchars($user['nome']); ?></strong>
    (<?php echo htmlspecialchars($user['email']); ?>)
    e os arquivos CSV de saúde associados.</p>
  <p>Para confirmar, digite sua senha:</p>

  <form method="POST" action="processar_exclusao.php" onsubmit="return confirm('Tem certeza que deseja excluir seu cadastro? Esta ação não pode ser desfeita.');">
    <label for="senha">Senha</label>
    <input type="password" name="senha" id="senha" required>

    <div class="acoes">
      <button type="submit">Excluir definitivamente</button>
      <a class="btn-voltar" href="telaprincuser.php">Cancelar</a>
    </div>
  </form>
</div>
</body>
</html>
