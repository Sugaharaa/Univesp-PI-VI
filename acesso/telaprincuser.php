<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Tela Usuário</title>
  <style>
body {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 50vh;
  flex-direction: column;
  margin: 0;
  padding-top: 80px;
}
#menucentral {
  max-width: 800px;
  width: 100%;
}
h1 {
  margin-top: 40px;
  text-align: center;
  color:#007BFF;
}
#identifica {
  font-size: 24px;
  margin-top: 20px;
  text-align: center;
  color:#007BFF;
  font-weight: bold;
}
.servicos {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 30px;
  margin-top: 40px;
}
.servico {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-decoration: none;
  color: #333;
  transition: transform 0.2s;
}
.servico:hover { transform: scale(1.05); }
.servico img {
  width: 80px;
  height: 80px;
  object-fit: contain;
  margin-bottom: 10px;
}
.servico span { font-size: 14px; }
hr {
  border: none;
  height: 2px;
  background: linear-gradient(to right, #007BFF, #00c6ff);
  margin: 20px auto;
  width: 80%;
}
.sair {
  margin-top: 30px;
  text-align: center;
}
.sair a {
  display: inline-block;
  padding: 10px 20px;
  background: #6c757d;
  color: #fff;
  text-decoration: none;
  border-radius: 6px;
  font-weight: bold;
}
  </style>
</head>

<?php
require_once 'conecta.php';
require_once 'auth.php';

$id = (int) $_SESSION['id'];

$stmt = $conn->prepare('SELECT * FROM tab_usuario WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$array_usuario = $result->fetch_assoc();
$stmt->close();

if (!$array_usuario) {
    header('Location: logout.php');
    exit;
}
?>

<body>
<div style="
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  background-color: #34495e;
  color: white;
  padding: 15px;
  text-align: center;
  font-size: 24px;
  font-weight: bold;
  z-index: 1000;
">
  PI-6 - Acompanhamento de Saúde Individual
	UNIVESP
</div>
<label id="identifica">Bem-vindo: <?php echo htmlspecialchars($array_usuario['nome']); ?></label>
<hr>

  <div id="menucentral">
      <h1>Selecione o Serviço Desejado</h1>

      <div class="servicos">
        <a href="alterar_cadastro.php" class="servico">
          <img src="img/cadastro.png" alt="Alterar Cadastro">
          <span>Alterar Cadastro e Senhas</span>
        </a>

        <a href="antropometrico.php" class="servico">
          <img src="img/balanca.png" alt="Controle Antropométrico">
          <span>Controle Antropométrico</span>
        </a>

        <a href="controlepa.php" class="servico">
          <img src="img/pa.png" alt="Controle da Pressão">
          <span>Controle da Pressão</span>
        </a>

        <a href="controleglic.php" class="servico">
          <img src="img/glicemia.png" alt="Controle da Glicemia">
          <span>Controle da Glicemia</span>
        </a>

        <a href="excluir_cadastro.php" class="servico">
          <img src="img/excluir.png" alt="Solicitar Exclusão">
          <span>Solicitar Exclusão de Cadastro</span>
        </a>
      </div>

      <div class="sair">
        <a href="logout.php">Sair</a>
      </div>
  </div>
</body>
</html>
