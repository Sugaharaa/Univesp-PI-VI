<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilo_css.css">
    <link rel="stylesheet" href="modal_css.css">
    <link rel="stylesheet" href="popup_rel2.css">
    <title>Avaliação Antropometrica</title>
    <script src="script.js" defer></script>
    <script src="modal_java.js" defer></script>
    <script src="antropometrico.js" defer></script>
    <script src="compartilhar_relatorio.js" defer></script>
    <script src="relatorio_antro.js" defer></script>
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
	Bem-vindo: <?php echo htmlspecialchars($array_usuario['nome']); ?>
</label>
    <input id="sexo" type="hidden" value="<?php echo htmlspecialchars((string)$array_usuario['sexo']); ?>">
	<div class="titulos">
    		<h1>Avaliação Antropométrica</h1>
		<img src="img/relatorio.png" alt="Ícone Relatório" class="icone" onclick="abrirPopup()" title="Emitir Relatório">
	</div>
      <div id="titulo">
	      <img src="img/antop.jpg" alt="Logo">
      </div>

      <div class="subtitulos">
		<h3>IMC - Índice de Massa Corporal</h3>
	</div>
      <div class="campo">
      	<input type="number" name="massa" id="massa" placeholder="Massa em Kg" style="width: 120px;" min="0" max="300" step="0.1" oninput="calculaIMC();">&nbspkg&nbsp&nbsp
		<input type="number" name="estatura" id="estatura" placeholder="Estatura em cm" style="width: 120px;" min="0" max="300" step="0.1" oninput="calculaIMC();">&nbspcm
	</div>
	<div id="resultIMC"></div>
<hr>

      <div class="subtitulos">
		<h3>Circunferência abdominal</h3>
	</div>
	<div>
		<h2>A medição deve ser feita na altura do umbigo, envolvendo a fita métrica ao redor do abdômen, sem apertar muito, mas também sem deixar folgas</h2>
	</div>
	<div class="campo">
		<label>É de etnia oriental (sul-asiáticos, chineses e japoneses)?
		<select id="etnia">
			<option value="0" selected>Não</option>
			<option value="1">Sim</option>
		</select>
		</label>
	</div>
	<div class="campo">
      	<input type="number" name="cirabd" id="cirabd" placeholder="Valor em cm" style="width: 100px;" min="0" max="300" step="0.1" oninput="calculacirabd()">
	</div>

	<div id="resultcirabd"></div>

<hr>
      <div class="subtitulos">
		<h3>Relação cintura-quadril</h3>
	</div>
      <div>
		<h2>Meça a maior parte do seu quadril, passando a fita métrica ao redor dos glúteos e tomando cuidado para não apertar demais ou deixá-la muito frouxa.</h2>
	</div>
	<div class="campo">
      	<input type="number" name="cirqua" id="cirqua" placeholder="Valor em cm" style="width: 100px;" min="0" max="300" step="0.1">
	</div>
<hr>
      	<button type="button" id="capturarBtn1" class="btn-incluir2">Incluir</button>
<hr>
      <div class="subtitulos">
		<h3>Valores Cadastrados</h3>
	</div>
	<div class="bp-legend" aria-label="Classificação do IMC">
  		<span class="label2">Classificação do IMC:</span>
  		<span class="square normal" title="Normal" aria-label="Normal"></span>
  		<span class="square has-e1" title="Obesidade Grau 1" aria-label="Obesidade Grau 1"></span>
  		<span class="square has-e2" title="Obesidade Grau 2" aria-label="Obesidade Grau 2"></span>
  		<span class="square has-e3" title="Obesidade Grau 3" aria-label="Obesidade Grau 3"></span>
	</div>
	<div class="campo">

			<table class="tabela-saude">
  			<thead>
    				<tr>
      				<th>Data</th>
      				<th>Massa</th>
      				<th>Estatura</th>
      				<th>IMC</th>
      				<th>Circ. Abdominal</th>
      				<th>Cintura-Quadril</th>
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
<div id="modalRelatorio">
	<h3>Avaliação dos Valores:</h3>
	<hr>
	<table style="border-collapse: collapse; width: 100%;">
  		<tr style="border-bottom: 1px solid black;">
    			<td>Índice de Massa Corporal (IMC)</td>
    			<td><div id="resultaimc"></div></td>
  		</tr>
  		<tr style="border-bottom: 1px solid black;">
    			<td>Circunferência Abdominal</td>
    			<td><div id="resultcirabd2"></div></td>
  		</tr>
  		<tr style="border-bottom: 1px solid black;">
    			<td>Relação Abdome/Quadril</td>
    			<td><div id="resultabdqua"></div></td>
  		</tr>
	</table>
	<button id="btnFechar" type="button" onclick="fecharModal();">X</button>
</div>
</body>
<div id="popup" class="popup">
    <div id="popup-header">
      <h2 style="font-size:14px;">Relatório Antropométrico de <?php echo htmlspecialchars($array_usuario['nome']); ?></h2>
      <div class="buttons">
        <button class="btn" onclick="window.print()">Imprimir</button>
        <button class="btn" onclick="compartilharWhatsApp('Relatório Antropométrico - PI-6')">WhatsApp</button>
        <button class="btn" onclick="compartilharEmail('Relatório Antropométrico - PI-6')">E-mail</button>
        <button class="btn" onclick="voltarInicio()">Início</button>
        <button class="btn" onclick="fecharPopup()">Fechar</button>
      </div>
    </div>
    <div id="relatorio-tabela"></div>

    <canvas id="grafico-massa" width="200px" height="20px"></canvas>
    <canvas id="grafico-imc" width="200px" height="20px"></canvas>
  
    <canvas id="grafico-cirabd" width="200px" height="20px"></canvas>
    <canvas id="grafico-relacao" width="200px" height="20px"></canvas>

    <span class="fechar" onclick="fecharPopup()">&times;</span>
</div>

<script>

window.addEventListener("DOMContentLoaded", () => {
  fetch("carregar_csv3.php")
    .then(response => response.json())
    .then(dados => {
      // Limpa a matriz antes de preencher
      matrizDados.length = 0;

      dados.forEach(linha => {
        const [data, massa, estatura, imc, cirabd, relabdqua] = linha;

        // Cria timestamp para ordenação
        const [dia, mes, ano] = data.split("/");
        const timestamp = new Date(`${ano}-${mes}-${dia}`);

        // Adiciona à matrizDados
        matrizDados.push({ data, massa, estatura, imc, cirabd, relabdqua, timestamp });
      });

      // Ordena e limita a 20 registros
      matrizDados.sort((a, b) => a.timestamp - b.timestamp);

      if (matrizDados.length > 48) {
        matrizDados.splice(0, matrizDados.length - 48);
      }

      // Atualiza a tabela visual
   	preencherTabelaAntro();
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
      if (index < 6) {
        linhaDados.push(coluna.textContent.trim());
      }
    });
    dados.push(linhaDados);
  });

  // Envia os dados via POST para PHP
  fetch("salvar_csv3.php", {
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