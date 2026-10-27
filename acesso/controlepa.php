<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilo_css.css">
    <link rel="stylesheet" href="popup_rel.css">
    <title>Avaliação da Pressão Arterial</title>
    <script src="script.js" defer></script>
    <script src="controlepa.js" defer></script>
    <script src="compartilhar_relatorio.js" defer></script>
    <script src="relatorio_pa.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<?php
require_once 'conecta.php';
require_once 'auth.php';
$id = (int) $_SESSION['id'];

    $stmt = $conn->prepare("SELECT * FROM tab_usuario WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $array_usuario = $result->fetch_assoc();

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

<br>
<br>

<form id="form1">
<br>
<label 
    style="
      font-size: 18px;
      margin-top: 20px;
      text-align: left;
      color: black;
      font-weight: bold;
    ">
	Bem-vindo: <?php echo htmlspecialchars($array_usuario['nome']); ?></label>
    <div class="titulos">
        <h3>Controle de Pressão Arterial</h3>
		<img src="img/relatorio.png" alt="Ícone Relatório" class="icone" onclick="abrirPopup()" title="Emitir Relatório">
    </div>
    <div id="titulo">
	  <img src="img/controlepa.jpg" alt="Logo">
	  <h2>Atenção: sintomas associados a hipertensão arterial podem indicar doenças graves, portanto devem ser avaliados por profissional médico!</h2>
    </div>
<hr>
      <div class="subtitulos">
		<h3>Adicionar Valores</h3>
	</div>
      <div class="campo">

			<table class="tabela-saude">
  			<thead>
    				<tr>
      				<th>*Data</th>
      				<th>*Hora</th>
      				<th>FC (bpm)</th>
      				<th>*PAS (sistólica)</th>
      				<th>*PAD (diastólica)</th>
      				<th>Opções</th>
    				</tr>
  			</thead>
  			<tbody>
    				<tr>
      				<td><input id="data" type="date" style="width: 80px;" required></td>
      				<td><input id="hora" type="time" style="width:60px;" required></td>
      				<td><input id="fc" type="number" value="0" style="width: 50px;" min="0" max="200" step="1"></td>
      				<td><input id="pas" type="number" style="width: 50px;" min="0" max="200" step="1" required></td>
      				<td><input id="pad" type="number" style="width: 50px;" min="0" max="200" step="1" required></td>
      				<td><button id="capturarBtn" class="btn-incluir" type="button">Incluir</button></td>
    				</tr>
  			</tbody>
			</table>
			<h2>(*) valores obrigatórios, para informações como obter os valores verifique o manual do seu aparelho ou solicite ajuda de um profissional de saúde</h2>
	</div>

<hr>
      <div class="subtitulos">
		<h3>Valores Cadastrados</h3>
	</div>
	<div class="bp-legend" aria-label="Classificação de pressão arterial">
  		<span class="label2">Classificação da HAS:</span>
  		<span class="square normal" title="Normal" aria-label="Normal"></span>
  		<span class="square pre-has" title="Pré-hipertensão" aria-label="Pré-hipertensão"></span>
  		<span class="square has-e1" title="Estágio 1" aria-label="Hipertensão Arterial Sistêmica Estágio 1"></span>
  		<span class="square has-e2" title="Estágio 2" aria-label="Hipertensão Arterial Sistêmica Estágio 2"></span>
  		<span class="square has-e3" title="Estágio 3" aria-label="Hipertensão Arterial Sistêmica Estágio 3"></span>
  		<span class="square hsi" title="Hipertensão Sistólica Isolada" aria-label="Hipertensão Sistólica Isolada"></span>
	</div>
	<div class="campo">

		<table id="tabela-valores" class="tabela-saude">
  		<thead>
    			<tr>
      			<th style="width:100px;">Data</th>
      			<th>Hora</th>
      			<th>FC (bpm)</th>
      			<th>PAS (sistólica)</th>
      			<th>PAD (diastólica)</th>
      			<th style="width:100px;">Opções</th>
    			</tr>
  		</thead>
  		<tbody id="corpo-tabela">
    			<!-- Linhas serão inseridas aqui -->
  		</tbody>
		</table>
	</div>

<hr>
	<div style="text-align: center;">
    		<div class="campo">
        		<button type="button" id="salvar">Salvar</button>
            <button type="button" onclick="voltarInicio()">Voltar ao início</button>
    		</div>
	</div>


</form>
<div id="popup" class="popup">
    <div id="popup-header">
      <h2 style="font-size:14px;">Relatório de Pressão Arterial de <?php echo htmlspecialchars($array_usuario['nome']); ?></h2>
      <div class="buttons">
        <button class="btn" onclick="window.print()">Imprimir</button>
        <button class="btn" onclick="compartilharWhatsApp('Relatório de Pressão Arterial - PI-6')">WhatsApp</button>
        <button class="btn" onclick="compartilharEmail('Relatório de Pressão Arterial - PI-6')">E-mail</button>
        <button class="btn" onclick="voltarInicio()">Início</button>
        <button class="btn" onclick="fecharPopup()">Fechar</button>
      </div>
    </div>
  <div id="relatorio-tabela"></div>
  <canvas id="grafico-relatorio" width="400" height="100"></canvas>
  <span class="fechar" onclick="fecharPopup()">&times;</span>
</div>
</body>
<script>

window.addEventListener("DOMContentLoaded", () => {
  fetch("carregar_csv.php")
    .then(response => response.json())
    .then(dados => {
      // Limpa a matriz antes de preencher
      matrizDados.length = 0;

      dados.forEach(linha => {
        const [data, hora, fc, pas, pad] = linha;

        // Cria timestamp para ordenação
        const timestamp = new Date(`${data}T${hora}`);

        // Adiciona à matrizDados
        matrizDados.push({ data, hora, fc, pas, pad, timestamp });
      });

      // Ordena e limita a 20 registros
      matrizDados.sort((a, b) => a.timestamp - b.timestamp);
      if (matrizDados.length > 20) {
        matrizDados.splice(0, matrizDados.length - 20);
      }

      // Atualiza a tabela visual
      preencherTabelaPA();
    })
    .catch(err => console.error("Erro ao carregar CSV:", err));
});

document.getElementById("salvar").addEventListener("click", function () {
  const linhas = document.querySelectorAll("#corpo-tabela tr");
  const dados = [];

  linhas.forEach(linha => {
    const colunas = linha.querySelectorAll("td");
    const linhaDados = [];
    colunas.forEach((coluna, index) => {
      // Ignora a coluna de opções (botões)
      if (index < 5) {
        linhaDados.push(coluna.textContent.trim());
      }
    });
    dados.push(linhaDados);
  });

  // Envia os dados via POST para PHP
  fetch("salvar_csv.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(dados)
  })
  .then(response => response.text())
  .then(msg => alert(msg))
  .catch(err => alert("Erro ao salvar: " + err));
});

</script>
</html>