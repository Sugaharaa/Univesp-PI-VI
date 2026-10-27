<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilo_css.css">
    <link rel="stylesheet" href="popup_rel.css">
    <title>Avaliação de Glicemia</title>
    <script src="script.js" defer></script>
    <script src="controleglic.js" defer></script>
    <script src="compartilhar_relatorio.js" defer></script>
	<script src="relatorio_glic.js" defer></script>
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
        <h1>Controle de Glicemia</h1>
		<img src="img/relatorio.png" alt="Ícone Relatório" class="icone" onclick="abrirPopup()" title="Emitir Relatório">
    </div>
    <div id="titulo">
	  <img src="img/controlegli.jpg" alt="Logo">
	  <h2>Atenção: sintomas associados a glicemias elevadas ou baixas podem indicar doenças graves, portanto devem ser avaliados por profissional médico!</h2>
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
      				<th>*Valor Glicemia</th>
      				<th>*Tipo</th>
      				<th>Tempo do tipo</th>
      				<th>Opções</th>
    				</tr>
  			</thead>
  			<tbody>
    				<tr>
      				<td><input id="data" type="date" style="width: 90px;"></td>
      				<td><input id="hora" type="time" style="width: 55px;"></td>
      				<td><input id="glicemia" type="number" style="width: 50px;" min="0" max="800" step="1"></td>
      				<td>
						<select id="tipo" style="width: 100px;">
							<option></option>
							<option value="Em jejum">Em jejum</option>
							<option value="Pos Prandial">Pós-alimentar</option>
						</select>
					</td>
      				<td><input id="tempoex" type="time" style="width: 55px;"></td>
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
	<div class="bp-legend" aria-label="Classificação da Glicemia">
  		<span class="label2">Classificação das Glicemias</span>
  		<span class="square normal" title="Normal" aria-label="Normal"></span>
  		<span class="square has-e1" title="Glicemia de Jejum Alterada" aria-label="Glicemia de Jejum Alterada"></span>
  		<span class="square has-e3" title="Possível Diabetes" aria-label="Possível Diabetes"></span>
	</div>
	<div class="campo">

		<table id="tabela-valores" class="tabela-saude">
  		<thead>
    			<tr>
      			<th>Data</th>
      			<th>Hora</th>
      			<th>Valor Glicemia</th>
      			<th>Tipo</th>
      			<th>Tempo do tipo</th>
      			<th>Opções</th>
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
      <h2 style="font-size:14px;">Relatório de Glicemia de <?php echo htmlspecialchars($array_usuario['nome']); ?></h2>
      <div class="buttons">
        <button class="btn" onclick="window.print()">Imprimir</button>
        <button class="btn" onclick="compartilharWhatsApp('Relatório de Glicemia - PI-6')">WhatsApp</button>
        <button class="btn" onclick="compartilharEmail('Relatório de Glicemia - PI-6')">E-mail</button>
        <button class="btn" onclick="voltarInicio()">Início</button>
        <button class="btn" onclick="fecharPopup()">Fechar</button>
      </div>
    </div>
  <div id="relatorio-tabela"></div>
<!--  <canvas id="grafico-relatorio" width="400" height="100"></canvas> -->
<canvas id="grafico-jejum" width="300" height="50"></canvas>
<canvas id="grafico-pos" width="300" height="50"></canvas>

  <span class="fechar" onclick="fecharPopup()">&times;</span>
</div>

</body>
<script>

window.addEventListener("DOMContentLoaded", () => {
  fetch("carregar_csv2.php")
    .then(response => response.json())
    .then(dados => {
      // Limpa a matriz antes de preencher
      matrizDados.length = 0;

      dados.forEach(linha => {
        const [data, hora, glicemia, tipo, tempotipo] = linha;

        // Cria timestamp para ordenação
        const timestamp = new Date(`${data}T${hora}`);

        // Adiciona à matrizDados
        matrizDados.push({ data, hora, glicemia, tipo, tempotipo, timestamp });
      });

      // Ordena e limita a 90 registros
      matrizDados.sort((a, b) => a.timestamp - b.timestamp);
      if (matrizDados.length > 90) {
        matrizDados.splice(0, matrizDados.length - 90);
      }

      // Atualiza a tabela visual
   	preencherTabelaGlicemia();
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
  fetch("salvar_csv2.php", {
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