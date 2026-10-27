<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Alterar Cadastro</title>
  <script src="../js/validar_senha.js" defer></script>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; padding-top: 90px; background: #f4f7fb; }
    .box {
      max-width: 560px; margin: 0 auto; background: #fff; padding: 24px;
      border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,.12);
    }
    h1 { color: #007BFF; text-align: center; }
    label { display: block; margin: 12px 0 4px; font-weight: bold; }
    input, select { width: 100%; padding: 8px; box-sizing: border-box; }
    .acoes { display: flex; gap: 12px; margin-top: 20px; }
    button, .btn-voltar {
      flex: 1; padding: 12px; border: none; border-radius: 6px; cursor: pointer;
      text-align: center; text-decoration: none; font-weight: bold;
    }
    button { background: #007BFF; color: #fff; }
    .btn-voltar { background: #6c757d; color: #fff; }
    #senhaStatus { color: red; font-size: 13px; }
  </style>
</head>
<?php
require_once 'conecta.php';
require_once 'auth.php';

$id = (int) $_SESSION['id'];
$stmt = $conn->prepare('SELECT nome, nomesoc, dn, sexo, email FROM tab_usuario WHERE id = ?');
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
  <h1>Alterar Cadastro e Senha</h1>
  <form method="POST" action="salvar_alteracao.php" onsubmit="return validarAlteracao()">
    <label for="nome">Nome completo</label>
    <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" required>

    <label for="nomesoc">Nome social</label>
    <input type="text" name="nomesoc" id="nomesoc" value="<?php echo htmlspecialchars($user['nomesoc']); ?>">

    <label for="dn">Data de nascimento</label>
    <input type="date" name="dn" id="dn" value="<?php echo htmlspecialchars($user['dn']); ?>" required>

    <label for="email">E-mail</label>
    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

    <label for="sexo">Sexo genético</label>
    <select name="sexo" id="sexo">
      <option value="0" <?php echo (string)$user['sexo'] === '0' ? 'selected' : ''; ?>>Masculino</option>
      <option value="1" <?php echo (string)$user['sexo'] === '1' ? 'selected' : ''; ?>>Feminino</option>
    </select>

    <label for="senha_atual">Senha atual (obrigatória)</label>
    <input type="password" name="senha_atual" id="senha_atual" required>

    <label for="senha1">Nova senha (deixe em branco para manter)</label>
    <input type="password" name="senha1" id="senha1" oninput="if(this.value) validarsenha();">
    <div id="senhaStatus"></div>

    <label for="senha2">Repetir nova senha</label>
    <input type="password" name="senha2" id="senha2">

    <div class="acoes">
      <button type="submit">Salvar alterações</button>
      <a class="btn-voltar" href="telaprincuser.php">Voltar</a>
    </div>
  </form>
</div>

<script>
function validarAlteracao() {
  const s1 = document.getElementById('senha1').value;
  const s2 = document.getElementById('senha2').value;
  if (!s1 && !s2) return true;
  if (s1 !== s2) {
    alert('As novas senhas não coincidem.');
    return false;
  }
  if (!(validasenha(s1) && senhavalida(s1))) {
    alert('Nova senha inválida.');
    return false;
  }
  return true;
}
</script>
</body>
</html>
