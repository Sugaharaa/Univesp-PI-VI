const matrizDados = [];

document.getElementById("capturarBtn").addEventListener("click", () => {

  const data = document.getElementById("data").value.trim();
  const hora = document.getElementById("hora").value.trim();
  const glicemia = document.getElementById("glicemia").value.trim();
  const tipo = document.getElementById("tipo").value.trim();
  let tempotipo = document.getElementById("tempoex").value.trim();

if (!data || !hora || !glicemia || !tipo) {
	alert("Necessário os valores de data, horário, glicemia e o tipo!");
	return;
}

if (tempotipo === ""){
	tempotipo = "00:00";
}

  const timestamp = new Date(`${data}T${hora}`);

  matrizDados.push({ data, hora, glicemia, tipo, tempotipo, timestamp });

  // Ordena por data/hora crescente
  matrizDados.sort((a, b) => a.timestamp - b.timestamp);

  // Mantém no máximo 90 registros
  if (matrizDados.length > 90) {
    matrizDados.splice(0, matrizDados.length - 90);
    alert("É possível cadastrar até 90 valores!");
  }

  preencherTabelaGlicemia();
});

function preencherTabelaGlicemia() {
  const corpoTabela = document.getElementById("corpo-tabela");
  corpoTabela.innerHTML = "";

  matrizDados.forEach((dado, index) => {
    let classificaCor = "";
	if (dado.tipo === "Em jejum"){
		if (dado.glicemia > 125){
        		classificaCor = "#e74c3c"; // vermelho
		}else if (dado.glicemia > 99){
			classificaCor = "#f1c40f"; // amarelo
		}else{
			classificaCor = "#2ecc71"; // verde
		}
	}

    const linha = document.createElement("tr");

    linha.innerHTML = `
      <td>${dado.data}</td>
      <td>${dado.hora}</td>
      <td style="color: ${classificaCor};">${dado.glicemia}</td>
      <td>${dado.tipo}</td>
      <td>${dado.tempotipo}</td>
      <td>
        <div class="container-botao">
          <button class="btn-excluir" data-index="${index}">x<span class="legenda">Excluir</span></button>
        </div>
      </td>
    `;

    corpoTabela.appendChild(linha);
  });

  // Adiciona os eventos de exclusão
  document.querySelectorAll(".btn-excluir").forEach(botao => {
    botao.addEventListener("click", (e) => {
      const indice = parseInt(e.currentTarget.getAttribute("data-index"));
      matrizDados.splice(indice, 1); // Remove da matriz
      preencherTabelaGlicemia(); // Atualiza a tabela
    });
  });
}